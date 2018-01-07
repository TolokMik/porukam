<?php

class Madhouse_Messenger_Actions
{
    public static function canDo($user, $thread)
    {
        if(! method_exists($thread, "getUsers")) {
            $thread = Madhouse_Messenger_Models_Threads::newInstance()->findByPrimaryKey($thread->getId());
        }

        if(! in_array($user->getId(), Madhouse_Utils_Collections::getIdsFromList($thread->getUsers()))) {
            return false;
        }
        return true;
    }

    /**
     * Search and returns threads for a user and some filters.
     * @param  Madhouse_User $user                  The user for whom we're searching
     * @param  String $filter                       One of: "all" for all threads, "unread" for only unread threads
     * @param  Int $page                            Pagination (page number)
     * @param  Int $num                             Pagination (number of threads per cairo_pattern_get_extend(pattern))
     * @return Array<Madhouse_Messenger_Threads>    The list of found threads.
     */
    public static function search($filters, $page, $num)
    {
        // Find by user.
        $user = $filters["user"];
        if(!$user instanceof Madhouse_User) {
            throw new InvalidArgumentException();
        }

        try {
            // First try within persistent labels (such as inbox, archive, spam...).
            $label = Madhouse_Messenger_Models_Labels::newInstance()->findByName($filters["label"]);
        } catch(Madhouse_NoResultsException $e) {
            // Try within user's labels and let the exception handling to controller.
            try {
                $label = Madhouse_Messenger_Models_Labels::newInstance()->findByName($filters["label"], $user);
            } catch (Madhouse_NoResultsException $e) {
                $label = null;
            }
        }

        $unread = (isset($filters["unread"]) && $filters["unread"] === true) ? true : false;

        // Get threads for $user.
        $results = Madhouse_Messenger_Models_Threads::newInstance()->findByUser($user, array("label" => $label, "unread" => $unread), $page, $num);

        // Transform Madhouse_Messenger_Threads to Views objects.
        $fresults = array_map(
            function($t) use ($user) {
                $thread = new Madhouse_Messenger_Views_ThreadSummary(
                    $user,
                    $t,
                    Madhouse_Messenger_Models_Messages::newInstance()->countByUser($user, array("thread" => $t, "unread" => true))
                );
                $thread->setBlockedUsers(Madhouse_Messenger_Models_BlockedUsers::newInstance()->findByUserAndThread($user, $thread));
                return $thread;
            },
            $results
        );

        return $fresults;
    }

    /**
     * Contact between users.
     *      User $sender is sending a message $content to each user in $recipients (opt. about an item $item).
     * @param $content content of the message to send.
     * @param $sender user sending the message (Madhouse_User).
     * @param $recipients list of the recipients of the message (array of Madhouse_User objects).
     * @param $item item to link to the message/thread.
     * @return void.
     * @since 1.22
     */
    public static function contact($content, $sender, $recipients, $item=null)
    {
        if(!is_null($item)) {
            if(! is_array($item)) {
                // $item is not null (something was passed) but $item is not an array.
                throw new Madhouse_NoValidItemException();
            }
            View::newInstance()->_exportVariableToView('item', $item);
        }

        if(!isset($content) || empty($content)) {
            throw new Madhouse_Messenger_EmptyMessageException();
        }

        if(! $sender instanceof Madhouse_User) {
            throw new InvalidArgumentException();
        }

        $frecipients = array_filter(
            $recipients,
            function($u) {
                return ($u instanceof Madhouse_User && ! $u->isFake());
            }
        );
        if(count($frecipients) === 0) {
            throw new Madhouse_Messenger_NoValidRecipientsException();
        }

        // Hook before a contact form has been filled.
        osc_run_hook("mdh_messenger_pre_contact");

        if(count($frecipients) === 1) {
            /**
             * REGULAR ONE-TO-ONE MESSAGE.
             * IF:   a thread already exists, append a new message to it.
             * ELSE: a thread is created with $content as first message.
             */



            $thread = Madhouse_Messenger_Models_Threads::newInstance()->findByUsers($sender->getId(), $frecipients[0]->getId(), osc_item_id());

            // Check if the recipient blocked the sender
            if (Madhouse_Messenger_Actions::isBlocked($frecipients[0], $sender->getId())) {
                throw new Madhouse_Messenger_ForbiddenOperationException(
                    "This user blocked you"
                );
            }

            if(isset($thread) && !empty($thread)) {

                // Reply to existing thread.
                Madhouse_Messenger_ThreadActions::add(
                    new Madhouse_Messenger_Message($content, $sender, $frecipients),
                    $thread
                );

                // Re-get the thread since it has been modified.
                $thread = Madhouse_Messenger_Models_Threads::newInstance()->findByPrimaryKey($thread->getId());

                // Hook after a contact form has been filled again.
                osc_run_hook("mdh_messenger_contacted_again", $thread);
            } else {
                // Create a new thread.
                $thread = Madhouse_Messenger_ThreadActions::create(
                    new Madhouse_Messenger_Message(
                        $content,
                        $sender,
                        $frecipients
                    ),
                    $item
                );

                // Hook after a contact form has been filled for the first time.
                osc_run_hook("mdh_messenger_post_contact_first", $thread);
            }
        } else {
            /**
             * GROUP MESSAGE.
             * Just create a new thread with $content as first message.
             * (no check for existing thread)
             */
            $message = new Madhouse_Messenger_Message(
                $content,
                $sender,
                $frecipients
            );

            $thread = Madhouse_Messenger_ThreadActions::create(
                $message,
                $item
            );
        }

        // Hook after an item has been contacted.
        osc_run_hook("mdh_messenger_post_contact", $thread);

        return $thread;
    }

    /**
     * Reply to a thread.
     * @param $content content of the message to send.
     * @param $sender user sending the message (Madhouse_User).
     * @param $recipients list of the recipients of the message (array of Madhouse_User objects).
     * @param $thread the thread to send the message in (Madhouse_Messenger_Thread).
     * @return void.
     * @since 1.22
     */
    public static function reply($content, $sender, $recipients, $thread)
    {
        if(! self::canDo($sender, $thread)) {
            throw new Madhouse_AuthorizationException();
        }

        if(!isset($content) || empty($content)) {
            throw new Madhouse_Messenger_EmptyMessageException();
        }

        if(! $sender instanceof Madhouse_User) {
            throw new InvalidArgumentException();
        }

        $frecipients = array_filter(
            $recipients,
            function($u) {
                return ($u instanceof Madhouse_User && ! $u->isFake());
            }
        );
        if(count($frecipients) === 0) {
            throw new Madhouse_Messenger_NoValidRecipientsException();
        }

        if(! $thread instanceof Madhouse_Messenger_ThreadSkeleton) {
            throw new InvalidArgumentException();
        }

        // Check if the recipient blocked the sender
        if (Madhouse_Messenger_Actions::isBlocked(reset($frecipients), $sender->getId())) {
            throw new Madhouse_Messenger_ForbiddenOperationException(
                "This user blocked you"
            );
        }

        // Create the new message.
        $nm = new Madhouse_Messenger_Message(
            $content,
            $sender,
            $frecipients
        );

        osc_run_hook("mdh_messenger_pre_reply", $nm, $thread);

        // Actually reply to thread $thread.
        Madhouse_Messenger_ThreadActions::add(
            $nm,
            $thread
        );

        $archiveLabel = Madhouse_Messenger_Models_Labels::newInstance()->findByName("archive");
        $inboxLabel = Madhouse_Messenger_Models_Labels::newInstance()->findByName("inbox");

        foreach ($thread->getUsers() as $user) {
            if($thread->hasLabel($archiveLabel, $user)) {
                Madhouse_Messenger_ThreadActions::addLabel($thread, $user, $inboxLabel);
            }
        }

        // Re-get the thread since it has been modified.
        $thread = Madhouse_Messenger_Models_Threads::newInstance()->findByPrimaryKey($thread->getId());

        osc_run_hook("mdh_messenger_post_reply", $nm, $thread);

        return $thread;
    }

    /**
     * [broadcast description]
     * @param  [type] $itemOrUser [description]
     * @param  [type] $sender     [description]
     * @param  [type] $content    [description]
     * @param  [type] $event      [description]
     * @return [type]             [description]
     */
    public static function broadcast($itemOrUser, $content = null, $event = null, $onlyUnanswered = false)
    {
        if(is_null($event) && (!isset($content) || empty($content))) {
            throw new Madhouse_Messenger_EmptyMessageException();
        }

        if($itemOrUser instanceof Madhouse_User) {
            $threads = Madhouse_Messenger_Models_Threads::newInstance()->findByUser($itemOrUser);
            $sender = $itemOrUser;
        } else if($itemOrUser instanceof Madhouse_Item) {
            $threads = Madhouse_Messenger_Models_Threads::newInstance()->findByItem($itemOrUser);
            $sender = Madhouse_Utils_Models::findUserByPrimaryKey($itemOrUser->getUserId());
        } else {
            throw new InvalidArgumentException();
        }

        foreach ($threads as $t) {
            if($onlyUnanswered === false
                || (
                    $onlyUnanswered === true
                    && $itemOrUser instanceof Madhouse_Item
                    && $itemOrUser->getUserId() != $t->getLastMessage()->getSender()->getId()
                )
            ) {
                // Maximum number of days allowed for a thread.
                $delta = mdh_get_preference("automessage_newer_days");

                // Oldest date allowed.
                $sent = new \DateTime($t->getLastMessage()->getSentDate());
                $max = new \DateTime("today midnight");
                $max->sub(new \DateInterval(sprintf("P%dD", $delta)));

                if(empty($delta) || $delta === 0 || $max < $sent) {
                    $frecipients = array_filter(
                        Madhouse_Utils_Collections::filterById($t->getUsers(), $sender->getId()),
                        function($u) {
                            return ($u instanceof Madhouse_User && ! $u->isFake());
                        }
                    );

                    if(count($frecipients) !== 0) {
                        $nm = new Madhouse_Messenger_Message(
                            $content,
                            $sender,
                            $frecipients
                        );
                        if(! is_null($event)) {
                            $nm->withEvent($event);
                        }

                        Madhouse_Messenger_ThreadActions::add(
                            $nm,
                            $t
                        );
                    }
                }
            }
        }
    }

    /**
     * Block a user
     * @param  Madhouse_User $user
     * @param  String or Madhouse_User The blocked user (can be an id or an email)
     * @return String or Madhouse_User
     */
    public static function blockUser($user, $blockedUser)
    {
        if(! mdh_get_preference("enable_block_user")) {
            throw new Madhouse_AuthorizationException();
        }

        if (self::isBlocked($user, $blockedUser->getId())) {
            throw new Madhouse_Messenger_ForbiddenOperationException(
                "This user is already blocked"
            );
        }

        // Insert @ database
        Madhouse_Messenger_Models_BlockedUsers::newInstance()->create($user, $blockedUser->getId());

        if (mdh_get_preference("email_admin_on_block_user")) {
            $now = new \DateTime();
            Madhouse_Utils_Emails::send(
                "email_admin_on_blocked_user",
                array(
                    "{USER_NAME}" => $user->getName(),
                    "{BLOCKED_USER_NAME}" => $blockedUser->getName(),
                    "{ADMIN_USER_URL}" => osc_admin_base_url(true) . "?page=users&amp;userId=" . $blockedUser->getId(). "&amp;user=". $blockedUser->getName(),
                    "{CREATION_DATE}" => $now->format(osc_date_format()),
                ),
                array(),
                array()
            );
        }

        return $blockedUser;
    }

    /**
     * Unblock a user.
     *
     * @param  Madhouse_User $user
     * @param  Madhouse_Thread
     *
     * @return String or Madhouse_User
     */
    public static function unblockUser($user, $blockedUser)
    {
        // If not blocked, throw an exception.
        if (!self::isBlocked($user, $blockedUser->getId())) {
            throw new Madhouse_Messenger_ForbiddenOperationException(
                "This user isn't blocked"
            );
        }

        // Unblock user and return.
        return Madhouse_Messenger_Models_BlockedUsers::newInstance()->removeBlockedUser($user, $blockedUser->getId());
    }

    /**
     * Is $user has blocked $blockedUser ?
     *
     * @param  Madhouse_User  $user
     * @param  Madhouse_User  $blockedId
     *
     * @return Boolean
     */
    public static function isBLocked($user, $blockedId) {
        // Check if the Recipients have blocked the sender.
        $isAlreadyBlocked = Madhouse_Messenger_Models_BlockedUsers::newInstance()->findByUserAndBlockedUser($user, $blockedId);

        if (count($isAlreadyBlocked) > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Block all messages of a given $user user.
     * @param  Madhouse_User $user          The user for whom we want to block his messages.
     * @return void
     * @throws InvalidArgumentException     If the user is not a Madhouse_User object.
     * @since  1.30
     */
    public static function disableUser($user)
    {
        if(! $user instanceof Madhouse_User) {
            throw new InvalidArgumentException();
        }

        Madhouse_Messenger_Models_Messages::newInstance()->setBlockByUser($user, true);
    }

    /**
     * Unblock all messages of a given $user user.
     * @param  Madhouse_User $user          The user for whom we want to block his messages.
     * @return void
     * @throws InvalidArgumentException     If the user is not a Madhouse_User object.
     * @since  1.30
     */
    public static function enableUser($user)
    {
        if(! $user instanceof Madhouse_User) {
            throw new InvalidArgumentException();
        }

        Madhouse_Messenger_Models_Messages::newInstance()->setBlockByUser($user, false);
    }

    /**
     * Find the template and clean it up.
     * @param  Int $userId [description]
     * @return String
     */
    public static function findTemplate($userId)
    {
        // Retrieve the template message.
        $message = Madhouse_Messenger_Models_Messages::newInstance()->findTemplateByUser($userId);
        $template = $message->getContent();

        // Clean it up for each recipient.
        foreach($message->getRecipients() as $recipient) {
            // Search token array
            $search = array_merge(
                array($recipient->getName()),
                explode(" ", $recipient->getName())
            );

            // Replace by a token.
            $template = str_ireplace(
                $search,
                array_fill(0, count($search), "{RECIPIENT_NAME}"),
                $template
            );
        }

        return $template;
    }

    public static function createStatus($status)
    {
        // Just create the status @ database.
        return Madhouse_Messenger_Models_Status::newInstance()->create($status);
    }

    public static function createEvent($event)
    {
        // Just create the event @ database.
        return Madhouse_Messenger_Models_Events::newInstance()->create($event);
    }

    public static function createLabel($label)
    {
        // Just create the label @ database.
        return Madhouse_Messenger_Models_Labels::newInstance()->create($label);
    }

    /**
     * Notify users that they have some messages waiting in the inbox.
     * @param  Madhouse_Messenger_Message $message the new message to notify its recipients about.
     * @return void
     * @since  1.25
     */
    public static function notify($message, $thread, $reply=false)
    {
        if(osc_get_preference('enable_notifications', mdh_current_preferences_section())) {
            // For each recipient in the recipients list.
            foreach ($message->getRecipients() as $recipient) {
                // Number of unread messages for this recipient (user).
                $nbUnread = Madhouse_Messenger_Models_Messages::newInstance()->countByUser(
                    $recipient,
                    array(
                        "label" => Madhouse_Messenger_Models_Labels::newInstance()->findByName("inbox"),
                        "unread" => true
                    )
                );

                // Do we send a notification for each message ? Have we sent enough notifications already.
                if(osc_get_preference('notify_everytime', mdh_current_preferences_section()) &&
                    $nbUnread < osc_get_preference('stop_notify_after', mdh_current_preferences_section())) {

                    // Find the name of the correct email.
                    if($reply === true) {
                        $emailName = "email_mmessenger_reply_user";
                    } else {
                        $emailName = "email_mmessenger_contact_user";
                    }

                    // Notification for you.
                    Madhouse_Utils_Emails::send(
                        $emailName,
                        array(
                            "{SENDER_NAME}" => $message->getSender()->getName(),
                            "{SENDER_URL}" => $message->getSender()->getURL(),
                            "{SENDER_AVATAR}" => $message->getSender()->getAvatar(),
                            "{MESSAGE_EXCERPT}" => $message->getExcerpt(osc_get_preference("email_excerpt_length", mdh_current_preferences_section()), osc_get_preference("email_excerpt_oneline", mdh_current_preferences_section())),
                            "{MESSAGE_CONTENT}" => $message->getText(),
                            "{MESSAGE_DATE}" => $message->getFormattedSentDate(),
                            "{THREAD_URL}" => $message->getThread()->getURL(),
                            "{ITEM_TITLE}" =>
                                (
                                    ($message->getThread()->hasItem()) ?
                                        $message->getThread()->getItem()->getTitle()
                                    :
                                    __("No related item", mdh_current_plugin_name())
                                ), // if has an item, get the URL. If not : "No related item"
                            "{ITEM_URL}" =>
                                (
                                    ($message->getThread()->hasItem()) ?
                                        $message->getThread()->getItem()->getURL()
                                    :
                                    "#"
                                ) // if has an item, get the URL. If not : "#"
                        ),
                        array($recipient),
                        array($message)
                    );
                } else if($nbUnread % osc_get_preference('reminder_every', mdh_current_preferences_section()) == 0) {
                    // Reminder for you.
                    Madhouse_Utils_Emails::send(
                        "email_alert_mmessenger_messages",
                        array(
                            "{NB_UNREAD_MESSAGES}" => $nbUnread
                        ),
                        array($recipient),
                        array($message)
                    );
                }
            }
        }
    }

    /**
     * Sends reminders to users with unread messages (each day).
     * @return void
     */
    public static function notifyDaily()
    {
        if(osc_get_preference('enable_notifications', mdh_current_preferences_section()) && osc_get_preference('enable_reminders', mdh_current_preferences_section())) {
            $report = Madhouse_Messenger_Models_Messages::newInstance()->reportDaily();
            foreach($report as $r) {
                // This is today midnight.
                $tday = new \DateTime("today midnight");

                // This is the date of the last sent message.
                $start = new DateTime($r["date"]->format("Y-m-d"));

                // This is the date when we stop sending reminders.
                $end = new DateTime($start->format("Y-m-d"));
                $end->add(new \DateInterval(sprintf("P%dD", osc_get_preference("stop_reminder_after", mdh_current_preferences_section()))));

                /*
                 * Make sure the last message is older than today.
                 * If not, we do not send a reminder today, the user already got an
                 * email today.
                 */
                if($start < $tday && $tday < $end) {
                    // Compute the delta in days.
                    $delta = $tday->diff($start)->format("%a");
                    if($delta % osc_get_preference("reminder_every_days", mdh_current_preferences_section()) == 0) {
                        // Actually send the reminder.
                        Madhouse_Utils_Emails::send(
                            "email_alert_mmessenger_messages",
                            array(
                                "{NB_UNREAD_MESSAGES}" => $r["count"]
                            ),
                            array($r["user"])
                        );
                    }
                }
            }
        }
    }
}

?>