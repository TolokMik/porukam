<?php

class Madhouse_Messenger_Controllers_Ajax extends WebSecBaseModel
{
    function __construct()
    {
        parent::__construct();
        $this->ajax = true;

        // DAOs.
        $this->threadsDAO = Madhouse_Messenger_Models_Threads::newInstance();
        $this->messagesDAO = Madhouse_Messenger_Models_Messages::newInstance();
		        // Create a Madhouse_User object for the logged user.
        $this->loggedUser = Madhouse_Utils_Models::findUserByPrimaryKey(osc_logged_user_id());
		$this->recipientDAO = Madhouse_Messenger_Models_Recipients::newInstance();
				
    }

    function doModel()
    {
        parent::doModel();
        switch (Params::getParam("do")) {
            /**
             * do=more
             * Load {n} previous messages in the thread.
             *    It returns a list of seralized messages as JSON string.
             *    Takes thread {id} and optional page {p} and number {n} to paginate.
             */
            case "more":
				// Thread requested.
				$threadId = Params::getParam("tid");
				// Page number.
				$p = Params::getParam("p");
				// Number of messages per page.
				$n = Params::getParam("n");
				if(!isset($n) || empty($n)) {
				    $n = 10;
				}

                try {
                    $thread = $this->threadsDAO->findByPrimaryKey($threadId);
                } catch (Madhouse_AuthorizationException $e) {
                mdh_handle_error(
                    __("The requested message / conversation does not exists.", mdh_current_plugin_name()),
                    mdh_messenger_inbox_url()
                );
                } catch (Madhouse_QueryFailedException $e) {
                    mdh_handle_error(
                        __("An error occured while performing the task.", mdh_current_plugin_name()),
                        mdh_messenger_inbox_url()
                    );
                }


                $viewer = $this->loggedUser;

				// Returns a JSON encoded array of all the messages.
				echo json_encode(array(
					"data" => array_map(
					    function($m) use($viewer) {
					        $mv = new Madhouse_Messenger_Views_Message(
					            $viewer,
					            $m
					        );
					        return $mv->toArray();
					    },
					    $this->messagesDAO->findByThread($thread, $p, $n)
					),
					"hasMore" => $thread->hasMoreMessages($p, $n)
				));
			break;
            /**
             * do=widget
             * List the last 5 threads in which the logged user belongs to.
             * 		It returns this list serialized as a JSON string.
             * 		Takes no parameters at all, just using osc_logged_user_id().
             */
            case "widget":
                // Get the current logged user.
                $user = $this->loggedUser;
                try {
                    // Get all threads in inbox.
                    $threads = Madhouse_Messenger_Actions::search(array("user" => $user, "label" => "inbox"), 1, 5);

                    // Return
                    echo json_encode(array(
                        "threads" => array_map(function($t) use($user) {
                            $ts = new Madhouse_Messenger_Views_ThreadSummary(
                                $user,
                                $t,
                                Madhouse_Messenger_Models_Messages::newInstance()->countByUser($user, array("thread" => $t, "unread" => true))
                            );
                            return $ts->toArray();
                        }, $threads),
                        "nbUnread" => (count($threads) > 0) ? $this->messagesDAO->countByUser($user, array("label" => Madhouse_Messenger_Models_Labels::newInstance()->findByName("inbox"), "unread" => true)) : 0

                    ));
                } catch(Madhouse_QueryFailedException $e) {
                    echo json_encode(array(
                        "error" => __("Something went wrong performing the task!", mdh_current_plugin_name())
                    ));
                }
            break;
			/*
			read message from AJAX
			*/
			case "read":
				$threadId = Params::getParam("tid");
				$user = $this->loggedUser;
				try {
                    $thread = $this->threadsDAO->findByPrimaryKey($threadId);
					if(Params::getParam("do") == "read")
						Madhouse_Messenger_ThreadActions::read($thread, $user);
                } catch (Madhouse_AuthorizationException $e) {
                mdh_handle_error(
                    __("The requested message / conversation does not exists.", mdh_current_plugin_name()),
                    mdh_messenger_inbox_url()
                );
                } catch (Madhouse_QueryFailedException $e) {
                    mdh_handle_error(
                        __("An error occured while performing the task.", mdh_current_plugin_name()),
                        mdh_messenger_inbox_url()
                    );
                }
				  
			break;
            default:
                echo json_encode(array('error' => __('No action defined', mdh_current_plugin_name())));
            break;
        }
    }

}

?>