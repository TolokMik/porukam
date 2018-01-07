<?php

// --------------------------------------------------------------------------------
// -- STATUS HELPERS --------------------------------------------------------------
// --------------------------------------------------------------------------------

/**
 * Iterate over (exported) status and exports each one to View if it exists.
 * @return true if there's remaining status in the loop, false otherwise.
 * @since 1.20
 */
function mdh_has_status()
{
    return mdh_helpers_loop("mdh_statuss", "mdh_status");
}

/**
 * Returns the current status in the status loop.
 * @return a Madhouse_Messenger_Status object.
 * @throws Exception if there's no current status.
 * @since 1.20
 */
function mdh_status()
{
    $e = View::newInstance()->_get("mdh_status");
    if(! $e) {
        throw new Exception("No current status (mdh_status === null)");
    }
    return $e;
}

/**
 * Returns the id of the current status.
 * @return an int.
 * @since 1.20
 */
function mdh_status_id()
{
    return mdh_status()->getId();
}

/**
 * Returns the name (slug) of the current status.
 * @return a string.
 * @since 1.20
 */
function mdh_status_name()
{
    return mdh_status()->getName();
}

/**
 * Returns the text (translated/localized) of the current status.
 * @return a string.
 * @since 1.20
 */
function mdh_status_title()
{
    return mdh_status()->getTitle();
}

/**
 * Returns the text (translated/localized) of the current status.
 * @return a string.
 * @since 1.20
 */
function mdh_status_text()
{
    return mdh_status()->getText();
}

/**
 * Returns the change URL of the current status.
 * @return a string.
 * @since 1.20
 */
function mdh_status_url()
{
    //return mdh_messenger_ajax_url() . "&do=status_change&thread_id=" . mdh_thread_id() . "&new_status=" . mdh_status_id();
    return osc_route_url(mdh_current_plugin_name() . "_thread_status", array("id" => mdh_thread_id(), "status" => mdh_status_id()));
}

// --------------------------------------------------------------------------------
// -- LABELS HELPERS --------------------------------------------------------------
// --------------------------------------------------------------------------------

/**
 * Iterate over (exported) status and exports each one to View if it exists.
 * @return true if there's remaining labels to process.
 * @since 1.33
 */
function mdh_has_thread_labels()
{
    return mdh_helpers_loop("mdh_thread_labels", "mdh_thread_label");
}

/**
 * Thread labels count.
 * @return an int
 * @since 1.33
 */
function mdh_thread_labels_count()
{
    return View::newInstance()->_get("mdh_thread_labels_count");
}

/**
 * Return the current label in the thread labels loop.
 * @return Madhouse_Messenger_Label Object.
 * @since 1.33
 */
function mdh_thread_label()
{
    $e = View::newInstance()->_get("mdh_thread_label");
    if(! $e) {
        throw new Exception("No current label (mdh_thread_label === null)");
    }
    return $e;
}

/**
 * Returns the id of the current label.
 * @return an int.
 * @since 1.33
 */
function mdh_thread_label_id()
{
    return mdh_thread_label()->getId();
}

/**
 * Returns the name of the current label.
 * @return a string.
 * @since 1.33
 */
function mdh_thread_label_name()
{
    return mdh_thread_label()->getName();
}

/**
 * Returns the title (translated) of the current label.
 * @return a string.
 * @since 1.33
 */
function mdh_thread_label_title()
{
    return mdh_thread_label()->getTitle();
}

/**
 * Returns true if the label is everlasting.
 * @return boolean
 * @since 1.40
 */
function mdh_thread_label_is_system()
{
    return mdh_thread_label()->isSystem();
}


// --------------------------------------------------------------------------------
// -- THREADS HELPERS -------------------------------------------------------------
// --------------------------------------------------------------------------------

/**
 * Returns the current thread.
 * @return a Madhouse_Messenger_Views_ThreadSummary object
 * @throws Exception if no current thread is found.
 * @since 1.20
 */
function mdh_thread()
{
    $e = View::newInstance()->_get("mdh_thread");
    if(! $e) {
        throw new Exception("No current thread (mdh_thread === null)");
    }
    return $e;
}

/**
 * Iterates through threads and exports each one to View at each call.
 * @return true if there's remaining threads in the loop, false otherwise.
 * @since 1.20
 */
function mdh_has_threads()
{
    return mdh_helpers_loop("mdh_threads", "mdh_thread");
}

/**
 * Returns the total number of threads a user has.
 * @return int.
 * @since 1.20
 * @deprecated see mdh_threads_count()
 */
function mdh_count_threads() {
	return mdh_threads_count();
}

/**
 * Returns the total number of threads a user has.
 * @return int.
 * @since 1.30
 */
function mdh_threads_count() {
    return View::newInstance()->_get("mdh_threads_count");
}

/**
 * Resets the loop of threads and erase current variables in View.
 * @return void
 * @since 1.20
 */
function mdh_reset_threads()
{
    return mdh_helpers_reset("mdh_threads");
}

/**
 * Returns all the exported threads.
 * @return an array of Madhouse_Messenger_Views_ThreadSummary objects.
 * @since 1.20
 */
function mdh_threads()
{
    return View::newInstance()->_get("mdh_threads");
}

/**
 * Returns the id of the current thread.
 * @return an int.
 * @since 1.20
 */
function mdh_thread_id()
{
    return mdh_thread()->getId();
}

/**
 * Tells if the thread is linked to an item.
 * @return true if the thread has an enabled (not blocked, spam, disabled nor deleted) item.
 * @since 1.20
 * @see Madhouse_Messenger_ThreadSkeleton::hasItem()
 */
function mdh_thread_has_item()
{
    return (mdh_thread()->hasItem());
}

/**
 * Tells if the thread has been linked to an item but is not anymore.
 * @return true if the thread had an item (item is disabled/deleted), false otherwise.
 * @since 1.20
 * @see Madhouse_Messenger_ThreadSkeleton::hadItem()
 */
function mdh_thread_had_item()
{
    return (mdh_thread()->hadItem());
}

/**
 * Tells if the viewer of the current thread is the owner of the linked item.
 * @return true if the current thread has an item and the viewer is its owner.
 * @since 1.20
 */
function mdh_is_thread_item_owner()
{
    return (mdh_thread_has_item() && mdh_thread()->isItemOwner());
}

/**
 * Returns true if the current message is from the viewer.
 * @return boolean
 * @since 1.33
 */
function mdh_message_is_from_viewer()
{
    return mdh_message()->isFromViewer();
}

/**
 * Returns true if the current thread is marked with label $label
 * @param  Madhouse_Messenger_Label $label
 * @return boolean
 * @since 1.40
 */
function mdh_thread_in_label($label)
{
    return mdh_thread()->hasLabel($label);
}

/**
 * Tells if the current thread has a status.
 * @return true if there's a status linked to the current thread.
 * @since 1.20
 */
function mdh_thread_has_status()
{
    return mdh_thread()->hasStatus();
}

/**
 * Returns the id of the status of the current thread.
 * @return an int.
 * @since 1.20
 */
function mdh_thread_status_id()
{
    if(mdh_thread_has_status()) {
        return mdh_thread()->getStatus()->getId();
    }
}

/**
 * Returns the name (slug) of the status of the current thread.
 * @return an int.
 * @since 1.20
 */
function mdh_thread_status_name()
{
    if(mdh_thread_has_status()) {
        return mdh_thread()->getStatus()->getName();
    } else {
        return "none";
    }
}

/**
 * Returns the text (localized) of the status of the current thread.
 * @return String
 * @since 1.20
 * @deprecated see mdh_thread_status_title();
 */
function mdh_thread_status_text($default=null) {
    return mdh_thread_status_title($default);
}

/**
 * Returns the text (localized) of the status of the current thread.
 * @return String
 * @since  1.30
 */
function mdh_thread_status_title($default=null)
{
    if(mdh_thread_has_status()) {
        return mdh_thread()->getStatus()->getTitle();
    } else {
        if(! is_null($default)) {
            return $default;
        }
        return "-";
    }
}

/**
 * Returns the title of the current thread.
 *
 * By default, the title of a thread is composed by the name of the users
 * that are recipients of the thread (all other users but you, think Facebook
 * here again).
 *
 * @return a string.
 * @since 1.20
 */
function mdh_thread_title()
{
    return mdh_thread()->getTitle();
}

/**
 * Return the default title
 *
 * Return the thread title if it isn't empty
 * If not return the name of the member of the conversation
 *
 * @param  string  $before Text before the title
 * @param  boolean $link   If true add a link to the public profile on the user name
 * @return string          The title
 */
function mdh_thread_title_default ($before ="", $link = false) {
    $title =  $before;
    if(mdh_thread_title()):
        $title .= mdh_thread_title();
    else:
        $i = 1;
        $m = mdh_thread_users_count();
        while(mdh_thread_has_users()):
            if (mdh_thread_user_id() != osc_logged_user_id()):
                if($i < $m && $i != 1):
                    echo ", ";
                endif;

                ++$i;

                $title .= ($link)?'<a class="link-reset" href="' . mdh_thread_user_url().'">':'';
                $title .= mdh_thread_user_name();
                $title .= ($link)?'</a>':'';
            endif;
        endwhile;
    endif;
    return $title;
}

/**
 * Returns the excerpt of the last message of the current thread.
 * @return a string.
 * @since 1.20
 */
function mdh_thread_excerpt()
{
    return mdh_thread()->getExcerpt();
}

/**
 * Returns the (total) number of messages in the current thread.
 * @return an int.
 * @since 1.20
 */
function mdh_thread_count_messages()
{
    return mdh_thread()->getNumberOfMessages();
}

/**
 * Has the current thread anymore messages ?
 * @return true if the current thread as at least at least one more message, false otherwise.
 * @since 1.20
 */
function mdh_thread_has_more_messages()
{
    // 'p' is the current page and 'n' the number of messages per page.
    return mdh_thread()->hasMoreMessages(Params::getParam("p"), Params::getParam("n"));
}


/**
 * Returns the number of unread messages in the current thread.
 * @return an int.
 * @since 1.20
 */
function mdh_thread_count_unread()
{
    return mdh_thread()->countUnreadMessages();
}

/**
 * Has the current thread any unread messages ?
 * @return true if the current thread as at least one unread message, false otherwise.
 * @since 1.20
 */
function mdh_thread_has_unread()
{
    return (mdh_thread_count_unread());
}

/**
 * Returns the date of last activity of the current thread.
 * @return the raw date/time of the last event/message in the thread.
 * @since 1.20
 */
function mdh_thread_last_activity()
{
    return mdh_thread()->getLastActivity();
}

/**
 * Returns the formatted (like Facebook) date of last activity of the current thread.
 * @return the "facebookized" date/time of the last event/message in the thread.
 * @since 1.20
 */
function mdh_thread_formatted_last_activity()
{
    return Madhouse_Utils_Dates::smartDate(mdh_thread()->getLastActivity());
}

/**
 * Tells whether the viewer is the last contributor of the current thread.
 *
 * Is the viewer the last user to have done something in the thread ? Is he the author
 * of the last message ? Is he the last one to modify the status ? etc. It's useful if
 * you want display an icon if the user as answered or not for example.
 *
 * @return true if the viewer is the last user to have done something in the thread, false otherwise.
 * @since 1.20
 */
function mdh_is_thread_last_sender()
{
    return mdh_thread()->isLastSender();
}

/**
 * Returns the URL of the current thread.
 * @return a string.
 * @since 1.20
 */
function mdh_thread_url()
{
    return mdh_thread()->getURL();
}

function mdh_thread_users()
{
    return View::newInstance()->_get("mdh_thread_members");
}

function mdh_thread_user()
{
    $e = View::newInstance()->_get("mdh_thread_member");
    if(! $e) {
        throw new Exception("No current user (mdh_thread_member === null)");
    }
    return $e;
}

function mdh_thread_has_users()
{
    return mdh_helpers_loop("mdh_thread_members", "mdh_thread_member");
}

function mdh_thread_user_id()
{
    return mdh_thread_user()->getId();
}

function mdh_thread_user_name()
{
    return mdh_thread_user()->getName();
}

function mdh_thread_user_url()
{
    return mdh_thread_user()->getURL();
}

function mdh_thread_users_count() {
    return mdh_helpers_count("mdh_thread_members");
}

// --------------------------------------------------------------------------------
// -- MESSAGES HELPERS ------------------------------------------------------------
// --------------------------------------------------------------------------------

/**
 * Iterates through messages and exports each one to View at each call.
 * @return true if there's remaining threads in the loop, false otherwise.
 * @since 1.20
 */
function mdh_has_messages()
{
    return mdh_helpers_loop("mdh_messages", "mdh_message");
}

/**
 * Returns the collection of the exported messages.
 * @return an array of Madhouse_Messenger_Views_Message objects.
 * @since 1.20
 */
function mdh_messages()
{
    return View::newInstance()->_get("mdh_messages");
}

/**
 * Returns the current message.
 * @return a Madhouse_Messenger_Views_Message object
 * @throws Exception if no current message is found.
 * @since 1.20
 */
function mdh_message()
{
    $e = View::newInstance()->_get("mdh_message");
    if(! $e) {
        throw new Exception("No current message (mdh_message === null)");
    }
    return $e;
}

/**
 * Returns the id of the current message.
 * @return an int.
 * @since 1.30
 */
function mdh_message_id()
{
    return mdh_message()->getId();
}

/**
 * Returns the content of the current message.
 * @return a string.
 * @since 1.20
 */
function mdh_message_text()
{
    return nl2br(htmlspecialchars(mdh_message()->getText()));
}

/**
 * Returns the sent date (raw) of the current message.
 * @return a string.
 * @since 1.20
 */
function mdh_message_sent_date()
{
    return mdh_message()->getSentDate();
}

/**
 * Returns the Facebook formatted sent date of the current message.
 * @return a string.
 * @since 1.20
 */
function mdh_message_formatted_sent_date()
{
    return Madhouse_Utils_Dates::smartDate(mdh_message()->getSentDate());
}

/**
 * Returns the Facebook formatted read date of the current message.
 * @return a string.
 * @since  1.30
 */
function mdh_message_formatted_read_date()
{
    return Madhouse_Utils_Dates::smartDate(mdh_message()->getReadDate());
}


/**
 * Tells if the current message has been read by its recipient.
 * @return true if the message is read, false otherwise.
 * @since  1.30
 */
function mdh_message_is_read()
{
    return mdh_message()->isRead();
}

/**
 * Tells if the current message is an automatic message.
 *
 * An automatic message is a special message that notfies user that the
 * status has changed, that a user has been added or removed of a thread
 * or that the title of the thread has changed, etc. As of version 1.20,
 * only changing status and sending contact details generates an automatic
 * message. It is useful to make the auto-messages look differently.
 *
 * @return true if the message is an automatic message, false otherwise.
 * @since 1.20
 */
function mdh_message_is_auto()
{
    return mdh_message()->isAuto();
}

/**
 * Tells if the current message has bee hidden (deleted).
 *
 * In Madhouse Messenger a message can be deleted but it only hides it. Every other
 * user that received the message still can see it. It works like e-mails or Facebook
 * in this case.
 *
 * @return true if the message is hidden (deleted), false otherwise.
 * @since 1.20
 */
function mdh_message_is_hidden()
{
    return mdh_message()->isHidden();
}

/**
 * Tells if the current message has been blocked by an admin.
 *
 * Administrator can block a message if they consider it spam, harmful, etc.
 *
 * @return true if the current message is blocked, false otherwise.
 * @since 1.20
 */
function mdh_message_is_blocked()
{
    return mdh_message()->isBlocked();
}

/**
 * Returns the delete URL of the current message.
 *
 * Once again, a message is never really deleted. It is simply
 * hid to the users but still readable by all the other users
 * of the thread.
 *
 * @return a string.
 * @since 1.20
 */
function mdh_message_delete_url()
{
    return mdh_message()->getDeleteURL();
}

/**
 * Is the sender of the current message a fake user ?
 *
 * When a user deletes is account, all his message still exists. To keep
 * them correctly displayed, when we detect that the user does not exist
 * anymore, we create a fake user "'Dead' User". You can't reply to a fake
 * user but you can still see messages that he sent you.
 *
 * @return true if the sender is "fake", false if it really exists.
 * @since 1.20
 */
function mdh_is_message_fake_sender()
{
    return mdh_message()->getSender()->isFake();
}

/**
 * Returns the id of the sender of the current message.
 * @return a string.
 * @since 1.20
 */
function mdh_message_sender_id()
{
    return mdh_message()->getSender()->getId();
}

/**
 * Returns the name of the sender of the current message.
 * @return a string.
 * @since 1.20
 */
function mdh_message_sender_name()
{
    return mdh_message()->getSender()->getName();
}

/**
 * Returns the URL of the public profile of the sender of the current message.
 * @return a string
 * @since 1.20
 */
function mdh_message_sender_url()
{
    return mdh_message()->getSender()->getURL();
}


// --------------------------------------------------------------------------------
// BLOCKED USERS HELPER
// --------------------------------------------------------------------------------

/**
 * Iterates through blocked users and exports each one to View at each call.
 * @return true if there's remaining threads in the loop, false otherwise.
 * @since 1.50
 */
function mdh_messenger_has_blocked_users()
{
    return mdh_helpers_loop("mdh_blocked_users", "mdh_blocked_user");
}

/**
 * Returns the collection of the exported blocked users.
 * @return an array of Madhouse_Messenger_BlockedUser objects.
 * @since 1.50
 */
function mdh_messenger_blocked_users()
{
    return View::newInstance()->_get("mdh_blocked_users");
}

/**
 * Returns the current message.
 * @return a Madhouse_Messenger_BlockedUser object
 * @throws Exception if no current blocked user is found.
 * @since 1.20
 */
function mdh_messenger_blocked_user()
{
    $e = View::newInstance()->_get("mdh_blocked_user");
    if(! $e) {
        return false;
    }
    return $e;
}

?>