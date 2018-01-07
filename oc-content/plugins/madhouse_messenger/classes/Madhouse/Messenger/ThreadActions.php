<?php

class Madhouse_Messenger_ThreadActions
{
    /**
     * Start a new thread.
     * @param  $nm a new Madhouse_Messenger_Message to send.
     * @param  $item an item to link to the new thread.
     * @throws Madhouse_NotValidItemException / Item is not valid (expired, disabled, blocked, marked as spam).
     * @throws Madhouse_QueryFailedException  / Message has not been sent.
     * @since  1.22
     */
    public static function create($nm, $item=null)
    {
        $content = $nm->getContent();
        if(!isset($content) || empty($content)) {
            throw new Madhouse_Messenger_EmptyMessageException();
        }

        if(! is_null($item)) {
            if(! View::newInstance()->_exists("item")) {
                // Export item $item to access helpers.
                View::newInstance()->_exportVariableToView("item", $item);
            }

            if(! osc_item_is_enabled() || ! osc_item_is_active() || osc_item_is_spam() || osc_item_is_expired()) {
                // Can't contact a "not valid" item.
                throw new Madhouse_NoValidItemException();
            }
        }

        // Check if sender of the first message is valid.
        if(! $nm->getSender() instanceof Madhouse_User) {
            throw new InvalidArgumentException();
        }

        // Check if recipients are valid.
        $fRecipients = array_filter(
            $nm->getRecipients(),
            function($u) {
                return ($u instanceof Madhouse_User && ! $u->isFake());
            }
        );
        if(count($fRecipients) === 0) {
            throw new Madhouse_Messenger_NoValidRecipientsException();
        }

        // Just before sending the message. Hook on this.
        osc_run_hook('mdh_messenger_pre_send', $nm, null);

    	$thread = Madhouse_Messenger_Models_Threads::newInstance()->create(
    		$nm,
    		osc_item_id()
    	);

    	// Message is finally sent (first of thread). Hook on this.
    	osc_run_hook('mdh_messenger_post_send', $thread->getLastMessage(), $thread, false);

    	// Thread is created. Hook on this.
    	osc_run_hook('mdh_messenger_thread_created', $thread);

        return $thread;
    }

    /**
     * Adds the message $nm to thread $thread.
     * @param $nm the new message to add to thread.
     * @param $thread the thread to add the message to.
     * @throws Madhouse_QueryFailedException, if the query has failed.
     * @since 1.22
     */
    public static function add($nm, $thread)
    {
        // Check if sender of the first message is valid.
        if(! $nm->getSender() instanceof Madhouse_User) {
            throw new InvalidArgumentException();
        }

        // Check if recipients are valid.
        $fRecipients = array_filter(
            $nm->getRecipients(),
            function($u) {
                return ($u instanceof Madhouse_User && ! $u->isFake());
            }
        );
        if(count($fRecipients) === 0) {
            throw new Madhouse_Messenger_NoValidRecipientsException();
        }

        osc_run_hook('mdh_messenger_pre_send', $nm, $thread);

    	// Send the message.
    	$message = Madhouse_Messenger_Models_Messages::newInstance()->create(
            $nm,
            $thread
    	);

        osc_run_hook('mdh_messenger_post_send', $message, $thread, true);

        return $message;
    }

    /**
     * Mark a thread $thread as read by the user $user.
     * @param $message Madhouse_Messenger_Thread to mark as read.
     * @param $user Madhouse_User that wants to mark the message as read.
     * @return void
     * @since 1.22
     */
    public static function read($thread, $user)
    {
        if(! Madhouse_Messenger_Actions::canDo($user, $thread)) {
            throw new Madhouse_AuthorizationException();
        }

        // Gets all unread messages of the thread $thread.
        $ums = Madhouse_Messenger_Models_Messages::newInstance()->findUnreadByThread($thread, $user);
        if(! empty($ums)) {
            // Just mark them as read, if there's any.
            Madhouse_Messenger_Models_Recipients::newInstance()->read($ums, $user);
        }
    }

    public static function changeStatus($thread, $user, $newStatus)
    {
        if(! Madhouse_Messenger_Actions::canDo($user, $thread) || ! mdh_messenger_status_enabled() ||
            ($thread->hasItem() && mdh_messenger_status_for_owner() && ($user->getId() != $thread->getItem()->getUserId()))
        ) {
            throw new Madhouse_AuthorizationException();
        }

        $frecipients = array_filter(
            Madhouse_Utils_Collections::filterById($thread->getUsers(), $user->getId()),
            function($u) {
                return ($u instanceof Madhouse_User && ! $u->isFake());
            }
        );
        if(count($frecipients) === 0) {
            throw new Madhouse_Messenger_NoValidRecipientsException();
        }

        // Apply status change on the thread.
    	$updated = Madhouse_Messenger_Models_Threads::newInstance()->updateStatus($thread, $newStatus);

        // Create an auto-message to notify the status change.
    	$nm = new Madhouse_Messenger_Message(
    	    "",
    	    $user,
    	    $frecipients
    	);
    	$nm->withStatus($newStatus);
    	$nm->withEvent(
            Madhouse_Messenger_Models_Events::newInstance()->findByName("status_change")
        );

    	// Add it to the thread.
    	self::add(
    	    $nm,
    	    $thread
    	);
    }

    public static function addLabel($thread, $user, $newLabel)
    {
        if(!Madhouse_Messenger_Actions::canDo($user, $thread)) {
            throw new Madhouse_AuthorizationException();
        }

        $inbox = Madhouse_Messenger_Models_Labels::newInstance()->findByName("inbox");
        $archive = Madhouse_Messenger_Models_Labels::newInstance()->findByName("archive");

        if($thread->hasLabel($inbox, $user) && $newLabel->getName() === "archive") {
            // Archiving the thread.
            Madhouse_Messenger_Models_MessageLabels::newInstance()->move($inbox, $archive, $thread, $user);
        } elseif($thread->hasLabel($archive, $user) && $newLabel->getName() === "inbox") {
            // Unarchiving the thread.
            Madhouse_Messenger_Models_MessageLabels::newInstance()->move($archive, $inbox, $thread, $user);
        } else {
            if ($thread->hasLabel($newLabel, $user)) {
                throw new Madhouse_Messenger_ForbiddenOperationException(
                    "Thread is already marked with this label"
                );
            }

            // Apply label change on the thread.
            Madhouse_Messenger_Models_MessageLabels::newInstance()->add($newLabel, $thread->getId(), $user);
        }

        // Update labels.
        $thread->setLabels(Madhouse_Messenger_Models_Labels::newInstance()->findByThread($thread, true));

        return $thread;
    }

    public static function removeLabel($thread, $label, $user)
    {
        if (!Madhouse_Messenger_Actions::canDo($user, $thread)) {
            throw new Madhouse_AuthorizationException();
        }

        if (!$thread->hasLabel($label, $user)) {
                throw new Madhouse_Messenger_ForbiddenOperationException(
                    "Thread is not marked with this label"
                );
            }

        // Remove the label.
        Madhouse_Messenger_Models_MessageLabels::newInstance()->remove(
            $label,
            $thread,
            $user
        );

        // Update labels.
        $thread->setLabels(Madhouse_Messenger_Models_Labels::newInstance()->findByThread($thread, true));

        return $thread;
    }

    /**
     * When a thread is exported, exports the related data.
     *
     * For helpers to work as intended, some datas about the thread needs to be
     * exported, especially datas we'll loop around. This function exports those
     * datas.
     *
     * @return void
     * @see Madhouse_Utils_Plugins::doHelperLoop();
     * @since 1.30
     */
    public static function extendExport() {
        $view = View::newInstance();

        // Get the thread.
        $t = $view->_get("mdh_thread");

        // Export the thread users.
        $view->_exportVariableToView("mdh_thread_members", $t->getUsers());

        // Export the item.
        if($t->hasItem()) {
            $view->_exportVariableToView("item", osc_apply_filter("mdh_thread_item", osc_apply_filter("pre_show_item", $t->getItem()->toArray())));
        }
    }

    /**
     * Mark a thread as unread
     * @return void
     * @since 1.50
     */
    public static function markAsUnread($thread, $user) {
        if(! mdh_get_preference("enable_mark_as_unread")) {
            throw new Madhouse_AuthorizationException();
        }

        if(! Madhouse_Messenger_Actions::canDo($user, $thread)) {
            throw new Madhouse_AuthorizationException();
        }

        // Gets all unread messages of the thread $thread.
        $message = Madhouse_Messenger_Models_Messages::newInstance()->findRecievedMessages($thread, $user , 1, 1);
        if(! empty($message)) {
            // Just mark them as read, if there's any.
            Madhouse_Messenger_Models_Recipients::newInstance()->unread($message, $user);
        }
    }

}
