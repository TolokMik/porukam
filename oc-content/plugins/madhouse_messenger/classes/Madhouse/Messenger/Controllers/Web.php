<?php

class Madhouse_Messenger_Controllers_Web extends WebSecBaseModel {

	private $dao;
    private $user;

	public function __construct() {
        // Handling referers for inbox and thread page.
        if(!osc_get_http_referer()) {
            // No referer set, neither in session nor in $_SERVER.
            // Set one as the default.
            if(Params::getParam('route') == mdh_current_plugin_name() . "_thread") {
                Session::newInstance()->_setReferer(mdh_messenger_thread_url(Params::getParam("id")));
            } else {
                Session::newInstance()->_setReferer(mdh_messenger_inbox_url());
            }
        }

        parent::__construct();

        // Create a Madhouse_User object for the logged user.
        $this->user = Madhouse_Utils_Models::findUserByPrimaryKey(osc_logged_user_id());
		$this->threadsDAO = Madhouse_Messenger_Models_Threads::newInstance();
	}

    /**
     * Shows authentificate fail page with the proper message.
     *  (Don't empty the session to keep the referer)
     * @override SecBaseModel
     */
    function showAuthFailPage()
    {
        osc_add_flash_info_message(__("Please login to access to your messages", mdh_current_plugin_name()));
        parent::showAuthFailPage();
    }


    /**
     * Stub method.
     *  (Don't empty the session to keep the referer)
     * @override SecBaseModel
     */
    function logout() {
    }

	/**
	 * Business control for Madhouse Messenger Plugin for OSClass.
	 */
	public function doModel() {
        parent::doModel();

        // Export the viewer, just in case we need it.
        $this->_exportVariableToView("mdh_viewer", $this->user);
        $this->_exportVariableToView("user", User::newInstance()->findByPrimaryKey(osc_logged_user_id()));

		switch(Params::getParam("route")) {
			case mdh_current_plugin_name() . "_message_delete":
			    // Gets the message.
			    $message = self::findMessage(Params::getParam("id"));

			    try {
    				// Delete the message.
    				Madhouse_Messenger_MessageActions::delete(
    				    $message,
    				    $this->user
    				);
    			} catch (Madhouse_AuthorizationException $e) {
    			    // Pretend it does not exist.
    			    mdh_handle_error(
    			        __("The requested message / conversation does not exist", mdh_current_plugin_name()),
    			        mdh_messenger_inbox_url()
    			    );
    			} catch (Madhouse_QueryFailedException $e) {
    			    mdh_handle_error(
    			        __("An error occured while performing the task", mdh_current_plugin_name()),
    			        mdh_messenger_inbox_url()
    			    );
    			}

    			// Redirect to thread.
    			$this->redirectTo($message->getThread()->getURL());
			break;
			// INBOX
			case mdh_current_plugin_name() . "_inbox":

				// Page number.
				$page = Params::getParam("p");
				if(!isset($page) || empty($page)) {
				  $page = 1;
				  Params::setParam("p", $page);
				}

				// Number of thread displayed per page.
				$num = Params::getParam("n");
				if(!isset($num) || empty($num)) {
				  $num = 10;
				  Params::setParam("n", $num);
				}

                $label = Params::getParam("label");
                if(!isset($label) || empty($label)) {
                    $label = "inbox";
                    Params::setParam("label", $label);
                }

                $threads = Madhouse_Messenger_Actions::search(
                    array(
                        "user" => $this->user,
                        "label" => $label,
                        "unread" => (Params::getParam("filter") === "unread") ? true : false
                    ),
                    $page,
                    $num
                );

				// Load and send view object to the view.
				View::newInstance()->_exportVariableToView(
					"mdh_threads",
					$threads
				);

                try {
                    // First try within system labels (such as inbox, archive, spam...).
                    $olabel = Madhouse_Messenger_Models_Labels::newInstance()->findByName($label);
                } catch(Madhouse_NoResultsException $e) {
                    try {
                        // Try within user's labels and let the exception handling to controller.
                        $olabel = Madhouse_Messenger_Models_Labels::newInstance()->findByName($label, $this->user);
                    } catch (Madhouse_NoResultsException $e) {
                        mdh_handle_error(
                            __("The requested label does not exist", mdh_current_plugin_name()),
                            mdh_messenger_inbox_url()
                        );
                    }
                }

				// Exports the total number of threads to the view.
				View::newInstance()->_exportVariableToView(
					"mdh_threads_count",
					Madhouse_Messenger_Models_Threads::newInstance()->countByUser(
						$this->user,
						array(
                            "label" => $olabel,
                            "unread" => (Params::getParam("filter") === "unread") ? true : false
                        )
					)
				);

                View::newInstance()->_exportVariableToView(
                    "mdh_thread_labels",
                    Madhouse_Messenger_Models_Labels::newInstance()->findByUser($this->user)
                );

                osc_run_hook("mdh_show_inbox");
			break;
			// MESSAGE
			case mdh_current_plugin_name() . "_thread":
    			// Page number.
    			$page = Params::getParam("p");
    			if(!isset($page) || empty($page)) {
                    $page = 1;
                    Params::setParam("p", $page);
    			}

    			// Number of messages displayed per page.
    			$num = Params::getParam("n");
    			if(!isset($num) || empty($num)) {
                    $num = 10;
                    Params::setParam("n", $num);
    			}

			    $thread = self::findThread(Params::getParam("id"));
			    try {
    				// Mark messages as read.
    				Madhouse_Messenger_ThreadActions::read(
    				    $thread,
    				    $this->user
    				);
    			} catch (Madhouse_AuthorizationException $e) {
    			    mdh_handle_error(
    			        __("The requested message / conversation does not exist", mdh_current_plugin_name()),
    			        mdh_messenger_inbox_url()
    			    );
    			} catch (Madhouse_QueryFailedException $e) {
    			    mdh_handle_error(
    			        __("An error occured while performing the task", mdh_current_plugin_name()),
    			        mdh_messenger_inbox_url()
    			    );
    			}

                $viewer = $this->user;


                $thread = new Madhouse_Messenger_Views_ThreadSummary(
                    $viewer,
                    $thread,
                    0 // We've just marked the thread as read.
                );
                $thread->setBlockedUsers(Madhouse_Messenger_Models_BlockedUsers::newInstance()->findByUserAndThread($viewer, $thread));

                if (mdh_get_preference('autolinker_new_window')) {
                    osc_enqueue_script(mdh_current_plugin_name() . "_autolinker");
                }

				// Load and send view object to the view.
				View::newInstance()->_exportVariableToView("mdh_thread", $thread);

				// Export the messages to be able to loop over them (helper).
				View::newInstance()->_exportVariableToView(
                	"mdh_messages",
	                array_map(
	                    function($m) use ($viewer) {
	                        return new Madhouse_Messenger_Views_Message(
	                            $viewer,
	                            $m
	                        );
	                    },
	                    Madhouse_Messenger_Models_Messages::newInstance()->findByThread($thread, $page, $num)
	                )
	            );

	            // Load and send view object to the view.
				View::newInstance()->_exportVariableToView(
					"mdh_statuss",
					Madhouse_Messenger_Models_Status::newInstance()->findAll()
				);

				// Export all the other extra-datas related to this thread.
				Madhouse_Messenger_ThreadActions::extendExport();

				// Run a hook when showing a thread.
				osc_run_hook("mdh_show_thread");
			break;
			// SEND
			case mdh_current_plugin_name() . "_send":
			    osc_csrf_check();

				$threadId = Params::getParam("tid");
				if(isset($threadId) && !empty($threadId)) {
				    /*
				     * Sent from the thread itself.
				     *     Reply to thread & redirect to the thread.
				     */

                    try {
                        // Getting thread from database.
                        $thread = Madhouse_Messenger_Models_Threads::newInstance()->findByPrimaryKey($threadId);

                        // Reply to the thread.
                        Madhouse_Messenger_Actions::reply(
                            Params::getParam("message", false, false),
                            $this->user,
                            Madhouse_Utils_Collections::filterById($thread->getUsers(), $this->user->getId()),
                            $thread
                        );

                        // Redirect to the thread.
                        $to = mdh_messenger_thread_url($thread->getId());

                    } catch (Madhouse_AuthorizationException $e) {
                        mdh_handle_error(
                            __("Nice try, but you can't do that!", mdh_current_plugin_name()),
                            mdh_messenger_inbox_url()
                        );
                    } catch (Madhouse_NoResultsException $e) {
                        mdh_handle_error(
                            __("The requested message / conversation does not exist", mdh_current_plugin_name()),
                            mdh_messenger_inbox_url()
                        );
                    } catch (Madhouse_Messenger_EmptyMessageException $e) {
                        mdh_handle_error(
                            __("Can't send an empty message", mdh_current_plugin_name()),
                            mdh_messenger_thread_url($threadId)
                        );
                    } catch (Madhouse_Messenger_NoValidRecipientsException $e) {
                	    mdh_handle_error(
                	        __("Sorry, you can't send a message to a disabled/deleted user", mdh_current_plugin_name()),
                	        mdh_messenger_thread_url($thread->getId())
                	    );
                    }  catch (Madhouse_Messenger_ForbiddenOperationException $e) {
                        mdh_handle_error(
                            __("Sorry, you can't send a message to this user. He may have blocked you.", mdh_current_plugin_name()),
                            mdh_messenger_thread_url($thread->getId())
                        );
                    } catch (Madhouse_QueryFailedException $e) {
                        mdh_handle_error(
                            __("An error occured while performing the task", mdh_current_plugin_name()),
                            mdh_messenger_inbox_url()
                        );
                    }
				} else {
				    /*
				     * Sent from item contact form (without thread id).
				     *     EITHER; create a new thread;
				     *     OR; reply to an existing thread (between the two users and for this item)
				     */

				    // Get and retrieve the recipients from the database.
				    $recipients = Madhouse_Utils_Models::findUsersByPrimaryKey(Params::getParam("recipients"));

                    // Getting the item.
                    $item = Item::newInstance()->findByPrimaryKey(Params::getParam('id'));
                    if($item) {
                    	View::newInstance()->_exportVariableToView('item', $item);

                    	// Sets the destination page to item page.
                    	$to = osc_item_url();
                    } else {
                    	$item = null; // Re-setting to null, just to make sure.

                    	if(count($recipients) === 1) {
                    		$r = array_shift(array_values($recipients));
                    		$to = $r->getURL();
                    	} else {
                    		$to = mdh_messenger_inbox_url();
                    	}
                    }

                    try {
                        // Contact the item/user.
                        Madhouse_Messenger_Actions::contact(
                            Params::getParam("message", false, false),
                            $this->user,
                            $recipients,
                            $item
                        );
                    } catch (Madhouse_Messenger_EmptyMessageException $e) {
                        mdh_handle_error(
                            __("Can't send an empty message", mdh_current_plugin_name()),
                            $to
                        );
                    } catch (Madhouse_Messenger_NoValidRecipientsException $e) {
                        mdh_handle_error(
                            __("Sorry, you can't send a message to a disabled/deleted user", mdh_current_plugin_name()),
                            $to
                        );
                    } catch (Madhouse_NoValidItemException $e) {
                        mdh_handle_error(
                            __("Sorry, you can't send a message to a disabled/deleted item", mdh_current_plugin_name()),
                            $to
                        );
                    } catch (Madhouse_Messenger_ForbiddenOperationException $e) {
                        mdh_handle_error(
                            __("Sorry, you can't send a message to this user. He may have blocked you.", mdh_current_plugin_name()),
                            osc_item_url()
                        );
                    } catch (Madhouse_QueryFailedException $e) {
                        mdh_handle_error(
                            __("An error occured while performing the task", mdh_current_plugin_name()),
                            mdh_messenger_inbox_url()
                        );
                    }
		    	}

                // All is fine, just redirect to the $to page.
                mdh_handle_ok(__("We've successfully sent your message", mdh_current_plugin_name()), $to);
			break;
			// STATUS_CHANGE
			case mdh_current_plugin_name() . "_thread_status":
			    // Getting thread from database.
			    $thread = self::findThread(Params::getParam("id"));

				try {
    				// User $user sets status $status to thread $id.
                    Madhouse_Messenger_ThreadActions::changeStatus(
                        $thread,
                        $this->user,
                        self::findStatus(Params::getParam("status"))
                    );

                } catch(Madhouse_AuthorizationException $e) {
                    mdh_handle_error(
                        __("Nice try, but you can't do that!", mdh_current_plugin_name()),
                        mdh_messenger_inbox_url()
                    );
                } catch(Madhouse_Messenger_NoValidRecipientsException $e) {
                    mdh_handle_error(
                        __("Sorry, you can't send a message to a disabled/deleted user", mdh_current_plugin_name()),
                        $thread->getURL()
                    );
                } catch(Madhouse_QueryFailedException $e) {
                    mdh_handle_error(
                        __("An error occured while performing the task", mdh_current_plugin_name()),
                        mdh_messenger_inbox_url()
                    );
				}

				// Redirects to the thread itself.
				$this->redirectTo($thread->getURL());
			break;
            /**
             * do=thread_label
             * Archives a thread.
             * @param id id of the thread to archive.
             */
            case mdh_current_plugin_name() . "_thread_label_add":
                // Getting thread from database.
                $thread = self::findThread(Params::getParam("id"));
                $label = self::findLabel(Params::getParam("label"));

                try {
                    // User $user sets status $status to thread $id.
                    Madhouse_Messenger_ThreadActions::addLabel(
                        $thread,
                        $this->user,
                        $label
                    );

                    // Redirects to the thread itself.
                    mdh_handle_ok(
                        __(sprintf("Successfully moved thread to %s", $label->getTitle()), mdh_current_plugin_name()),
                        osc_get_http_referer()
                    );
                    $this->redirectTo(osc_get_http_referer());
                } catch(Madhouse_Messenger_ForbiddenOperationException $e) {
                    mdh_handle_warning(
                        sprintf(
                            __("Thread is already marked as %s", mdh_current_plugin_name()),
                            $label->getTitle()
                        ),
                        osc_get_http_referer()
                    );
                } catch(Madhouse_AuthorizationException $e) {
                    mdh_handle_error(
                        __("Nice try, but you can't do that!", mdh_current_plugin_name()),
                        osc_get_http_referer()
                    );
                } catch(Madhouse_QueryFailedException $e) {
                    mdh_handle_error(
                        __("An error occured while performing the task", mdh_current_plugin_name()),
                        osc_get_http_referer()
                    );
                }
            break;
            case mdh_current_plugin_name() . "_thread_label_remove":
                $thread = self::findThread(Params::getParam("id"));
                $label = self::findLabel(Params::getParam("label"));

                try {
                    // User $user sets status $status to thread $id.
                    Madhouse_Messenger_ThreadActions::removeLabel(
                        $thread,
                        $label,
                        $this->user
                    );

                    // Redirects to the thread itself.
                    mdh_handle_ok(
                        __(sprintf("Successfully removed thread of '%s'", $label->getTitle()), mdh_current_plugin_name()),
                        osc_get_http_referer()
                    );
                    $this->redirectTo(osc_get_http_referer());
                } catch(Madhouse_Messenger_ForbiddenOperationException $e) {
                    mdh_handle_warning(
                        sprintf(
                            __("Thread is not marked as %s", mdh_current_plugin_name()),
                            $label->getTitle()
                        ),
                        osc_get_http_referer()
                    );
                } catch(Madhouse_AuthorizationException $e) {
                    mdh_handle_error(
                        __("Nice try, but you can't do that!", mdh_current_plugin_name()),
                        osc_get_http_referer()
                    );
                } catch(Madhouse_QueryFailedException $e) {
                    mdh_handle_error(
                        __("An error occured while performing the task", mdh_current_plugin_name()),
                        osc_get_http_referer()
                    );
                }
            break;
            /**
             * do=thread_block_user
             * Block a user
             * @param id of the user to block.
             */
            case mdh_current_plugin_name() . "_user_block_user":
                // Find the user to block.
                $blockedUser = Madhouse_Utils_Models::findUserByPrimaryKey(Params::getParam("id"));

                if ($this->user->isFake()) {
                    // Redirects to the thread itself.
                    mdh_handle_error(
                        sprintf(__("User has deleted his account.", mdh_current_plugin_name()), $blockedUser->getName()),
                        osc_get_http_referer()
                    );
                }

                try {
                    // Block the user.
                    $blockedUser = Madhouse_Messenger_Actions::blockUser($this->user, $blockedUser);

                    // Redirects to the thread itself.
                    mdh_handle_ok(
                        sprintf(__("Successfully blocked user %s", mdh_current_plugin_name()), $blockedUser->getName()),
                        osc_get_http_referer()
                    );
                } catch(Madhouse_Messenger_ForbiddenOperationException $e) {
                    mdh_handle_warning(
                        sprintf(
                            __("This user is already blocked", mdh_current_plugin_name())
                        ),
                        osc_get_http_referer()
                    );
                } catch(Madhouse_AuthorizationException $e) {
                    mdh_handle_error(
                        __("Nice try, but you can't do that!", mdh_current_plugin_name()),
                        osc_get_http_referer()
                    );
                } catch(Madhouse_QueryFailedException $e) {
                    mdh_handle_error(
                        __("An error occured while performing the task", mdh_current_plugin_name()),
                        osc_get_http_referer()
                    );
                }
            break;
            /**
             * do=thread_block_user
             * Block a user
             * @param id of the user to unblock.
             */
            case mdh_current_plugin_name() . "_user_unblock_user":
                // Getting user to block from database.
                $blockedUser = Madhouse_Utils_Models::findUserByPrimaryKey(Params::getParam("id"));

                try {
                    // User $user sets status $status to thread $id.
                    Madhouse_Messenger_Actions::unblockUser($this->user, $blockedUser);

                    // Redirects to the thread itself.
                    mdh_handle_ok(
                        sprintf(__("Successfully unblocked user %s", mdh_current_plugin_name()), $blockedUser->getName()),
                        osc_get_http_referer()
                    );
                    $this->redirectTo(osc_get_http_referer());
                } catch(Madhouse_Messenger_ForbiddenOperationException $e) {
                    mdh_handle_warning(
                        sprintf(
                            __("This user is already unblocked", mdh_current_plugin_name())
                        ),
                        osc_get_http_referer()
                    );
                } catch(Madhouse_AuthorizationException $e) {
                    mdh_handle_error(
                        __("Nice try, but you can't do that!", mdh_current_plugin_name()),
                        osc_get_http_referer()
                    );
                } catch(Madhouse_QueryFailedException $e) {
                    mdh_handle_error(
                        __("An error occured while performing the task", mdh_current_plugin_name()),
                        osc_get_http_referer()
                    );
                }
            break;
            /**
             * do=thread_mark_unread
             * Mark a thread as unread
             * @param id of the thread to mark as unread.
             */
            case mdh_current_plugin_name() . "_thread_mark_unread":

                try {

                    // Find the thread
                    $thread = self::findThread(Params::getParam("id"));

                    // User $user sets status $status to thread $id.
                    Madhouse_Messenger_ThreadActions::markAsUnread($thread, $this->user);

                    // Redirects to the thread itself.
                    mdh_handle_ok(
                        sprintf(__("Successfully mark a thread as unread", mdh_current_plugin_name())),
                        osc_get_http_referer()
                    );
                    $this->redirectTo(osc_get_http_referer());
                } catch(Madhouse_AuthorizationException $e) {
                    mdh_handle_error(
                        __("Nice try, but you can't do that!", mdh_current_plugin_name()),
                        osc_get_http_referer()
                    );
                } catch(Madhouse_QueryFailedException $e) {
                    mdh_handle_error(
                        __("An error occured while performing the task", mdh_current_plugin_name()),
                        osc_get_http_referer()
                    );
                } catch(Madhouse_NoResultsException $e) {

                }
            break;
			// DEFAULT
			default:
				// Don't know what to do. Pretend not to exist.
				Madhouse_Utils_Controllers::handleError();
			break;
		}
	}

	public static function findThread($threadId)
	{
	    // Get the thread, throws exception and redirect if it does not exist.
	    try {
	    	return Madhouse_Messenger_Models_Threads::newInstance()->findByPrimaryKey($threadId);
	    } catch(Exception $e) {
	    	mdh_handle_error(
	    	    __("The requested message / conversation does not exist", mdh_current_plugin_name()),
	    	    mdh_messenger_inbox_url()
	    	);
	    }
	}

	public static function findMessage($messageId)
	{
	    try {
	    	return Madhouse_Messenger_Models_Messages::newInstance()->findByPrimaryKey($messageId);
	    } catch (Exception $e) {
	    	mdh_handle_error(
	    	    __("The requested message / conversation does not exist", mdh_current_plugin_name()),
	    	    mdh_messenger_inbox_url()
	    	);
	    }
	}

	public static function findStatus($statusId)
	{
	    try {
	    	return Madhouse_Messenger_Models_Status::newInstance()->findByPrimaryKey($statusId);
	    } catch(Exception $e) {
	    	mdh_handle_error(
	    	    __("The requested status does not exist", mdh_current_plugin_name()),
	    	    mdh_messenger_inbox_url()
	    	);
	    }
	}

    public static function findLabel($labelId)
    {
        try {
            return Madhouse_Messenger_Models_Labels::newInstance()->findByPrimaryKey($labelId);
        } catch(Exception $e) {
            mdh_handle_error(
                __("The requested label does not exist", mdh_current_plugin_name()),
                mdh_messenger_inbox_url()
            );
        }
    }
}
