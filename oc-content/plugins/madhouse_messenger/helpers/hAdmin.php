<?php

function mdh_messenger_admin_url($params)
{
    return osc_route_admin_ajax_url(mdh_current_plugin_name(), $params);
}

function mdh_messenger_admin_dashboard_url()
{
    return osc_route_admin_url(mdh_current_plugin_name() . "_dashboard");
}

function mdh_messenger_admin_contact_url($userId=null)
{
    $params = array();
    if(! is_null($userId)) {
        $params["recipients"] = $userId;
    }

    return osc_route_admin_url(mdh_current_plugin_name() . "_contact", $params);
}

function mdh_messenger_admin_messages_url($filterType=null)
{
    $params = array();
    if(! is_null($filterType)) {
        $params["filter-type"] = $filterType;
        if($filterType === "oThread") {
            $params["threadId"] = mdh_message()->getThread()->getId();
        }
        if($filterType === "oUser") {
            $params["userId"] = mdh_message()->getSender()->getId();
        }
        if($filterType === "oItem") {
            if(mdh_message()->getThread()->hasItem()) {
                $params["itemId"] = mdh_message()->getThread()->getItem()->getId();
            }
        }
    }
    return osc_route_admin_url(mdh_current_plugin_name() . "_listing", $params);
}

function mdh_messenger_admin_blocked_users_url($filterType=null) {
    $params = array();
    return osc_route_admin_url(mdh_current_plugin_name() . "_blocked_users", $params);
}

function mdh_messenger_admin_unblock_user_url($userId, $blockedUserId) {
    $params = array(
        'userId' => $userId,
        'blockedUserId' => $blockedUserId
    );
    return osc_route_admin_url(mdh_current_plugin_name() . "_unblock_user", $params);
}

function mdh_messenger_admin_settings_url()
{
    return osc_route_admin_url(mdh_current_plugin_name() . "_settings");
}

function mdh_messenger_admin_block_url($messageId)
{
    return osc_route_admin_url(mdh_current_plugin_name() . "_message_block", array("id[]" => $messageId));
}

function mdh_messenger_admin_unblock_url($messageId)
{
    return osc_route_admin_url(mdh_current_plugin_name() . "_message_unblock", array("id[]" => $messageId));
}

function mdh_messenger_admin_settings_post_url()
{
    return osc_route_admin_url(mdh_current_plugin_name() . "_settings_post");
}

/**
 * @return Route to admin labels
 * @since 1.50
 */
function mdh_messenger_admin_labels_url()
{
    return osc_route_admin_url(mdh_current_plugin_name() . "_labels");
}

/**
 * @return Route to admin labels post
 * @since 1.50
 */
function mdh_messenger_admin_labels_post_url()
{
    return osc_route_admin_url(mdh_current_plugin_name() . "_labels_post");
}

/**
 * @return Route to admin labels add
 * @since 1.50
 */
function mdh_messenger_admin_labels_add_url()
{
    return osc_route_admin_url(mdh_current_plugin_name() . "_label_add");
}

/**
 * @return Route to admin labels add
 * @since 1.50
 */
function mdh_messenger_admin_labels_remove_url($labelId)
{
    $params['id'] = $labelId;
    return osc_route_admin_url(mdh_current_plugin_name() . "_label_remove", $params);
}

/**
 * @return Route to admin events
 * @since 1.50
 */
function mdh_messenger_admin_events_url()
{
    return osc_route_admin_url(mdh_current_plugin_name() . "_events");
}

/**
 * @return Route to admin events post
 * @since 1.50
 */
function mdh_messenger_admin_events_post_url()
{
    return osc_route_admin_url(mdh_current_plugin_name() . "_events_post");
}

/**
 * @return Route to admin status
 * @since 1.50
 */
function mdh_messenger_admin_status_url()
{
    return osc_route_admin_url(mdh_current_plugin_name() . "_status");
}

/**
 * @return Route to admin status post
 * @since 1.50
 */
function mdh_messenger_admin_status_post_url()
{
    return osc_route_admin_url(mdh_current_plugin_name() . "_status_post");
}

/**
 * @return Route to admin status add
 * @since 1.50
 */
function mdh_messenger_admin_status_add_url()
{
    return osc_route_admin_url(mdh_current_plugin_name() . "_status_add");
}

/**
 * @return Route to admin status add
 * @since 1.50
 */
function mdh_messenger_admin_status_remove_url($statusId)
{
    $params['id'] = $statusId;
    return osc_route_admin_url(mdh_current_plugin_name() . "_status_remove", $params);
}

function mdh_messenger_admin_ajax_url()
{
    return osc_route_admin_ajax_url(mdh_current_plugin_name() . "_admin_ajax");
}

    /**
     * Messenger upgrade URL.
     * @return the URL for the upgrade.
     * @since  1.30
     */
    function mdh_messenger_admin_upgrade_url() {
        return osc_route_admin_url(mdh_current_plugin_name() . "_upgrade");
    }

// GLOBAL

function mdh_messenger_messages_count() {
    return View::newInstance()->_get("messages_count");
}

function mdh_messenger_threads_count() {
    return View::newInstance()->_get("threads_count");
}

// DAY

function mdh_messenger_today_messages_count() {
    return View::newInstance()->_get("messages_count_today");
}

function mdh_messenger_today_threads_count() {
    return View::newInstance()->_get("threads_count_today");
}

function mdh_messenger_yesterday_messages_count() {
    return View::newInstance()->_get("messages_count_yesterday");
}

function mdh_messenger_yesterday_threads_count() {
    return View::newInstance()->_get("threads_count_yesterday");
}

// WEEK

function mdh_messenger_this_week_messages_count() {
    return View::newInstance()->_get("messages_count_this_week");
}

function mdh_messenger_last_week_messages_count() {
    return View::newInstance()->_get("messages_count_last_week");
}

function mdh_messenger_this_week_threads_count() {
    return View::newInstance()->_get("threads_count_this_week");
}

function mdh_messenger_last_week_threads_count() {
    return View::newInstance()->_get("threads_count_last_week");
}

// MONTH

function mdh_messenger_this_month_messages_count() {
    return View::newInstance()->_get("messages_count_this_month");
}

function mdh_messenger_last_month_messages_count() {
    return View::newInstance()->_get("messages_count_last_month");
}

function mdh_messenger_this_month_threads_count() {
    return View::newInstance()->_get("threads_count_this_month");
}

function mdh_messenger_last_month_threads_count() {
    return View::newInstance()->_get("threads_count_last_month");
}

// --------------------------------------------------------------------------------
// -- EVENTS HELPERS --------------------------------------------------------------
// --------------------------------------------------------------------------------

/**
 * Iterate over (exported) status and exports each one to View if it exists.
 * @return true if there's remaining labels to process.
 * @since 1.50
 */
function mdh_has_thread_events()
{
    return mdh_helpers_loop("mdh_thread_events", "mdh_thread_event");
}

/**
 * Return the current label in the thread labels loop.
 * @return Madhouse_Messenger_Label Object.
 * @since 1.33
 */
function mdh_thread_event()
{
    $e = View::newInstance()->_get("mdh_thread_event");
    return $e;
}