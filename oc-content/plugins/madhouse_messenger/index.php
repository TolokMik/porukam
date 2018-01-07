<?php
/*
Plugin Name: Madhouse Messenger
Short Name: madhouse_messenger
Description: A personal messaging plugin for OSClass, designed by Madhouse.
Plugin URI: http://wearemadhouse.wordpress.com/portfolio/madhouse-messenger/
Plugin update URI: madhouse-messenger
Version: 1.50.0
Author: Madhouse
Author URI: http://wearemadhouse.wordpress.com/
Support URI: http://market.osclass.org/item/contact/175/
*/

/*
 * ==========================================================================
 *  LOADING
 * ==========================================================================
 */

require __DIR__ . "/vendor/composer_components/madhouse/autoloader/autoload.php";

/**
 * Makes this plugin the first to be loaded.
 * - Bumps this plugin at the top of the active_plugins stack.
 */
function mdh_messenger_bump_me()
{
    if(OC_ADMIN) {
        // @legacy : ALWAYS remove this if active.
        if(osc_plugin_is_enabled("madhouse_utils/index.php")) {
            Plugins::deactivate("madhouse_utils/index.php");
        }

        // Sanitize & get the {PLUGIN_NAME}/index.php.
        $path = str_replace(osc_plugins_path(), '', osc_plugin_path(__FILE__));

        if(osc_plugin_is_installed($path)) {
            // Get the active plugins.
            $plugins_list = unserialize(osc_active_plugins());
            if(!is_array($plugins_list)) {
                return false;
            }

            // Remove $path from the active plugins list
            foreach($plugins_list as $k => $v) {
                if($v == $path) {
                    unset($plugins_list[$k]);
                }
            }

            // Re-add the $path at the beginning of the active plugins.
            array_unshift($plugins_list, $path);

            // Serialize the new active_plugins list.
            osc_set_preference('active_plugins', serialize($plugins_list));

            if(Params::getParam("page") === "plugins" && Params::getParam("action") === "enable" && Params::getParam("plugin") === $path) {
                //osc_redirect_to(osc_admin_base_url(true) . "?page=plugins");
            } else {
                osc_redirect_to(osc_admin_base_url(true) . "?" . http_build_query(Params::getParamsAsArray("get")));
            }
        }
    }
}

if(!function_exists("mdh_utils") || (function_exists("mdh_utils") && (mdh_utils() === true || version_compare(mdh_utils(), Madhouse_Messenger_Plugin::UTILS_VERSION) < 0))) {
    mdh_messenger_bump_me();
} else {
    /*
     * ==========================================================================
     *  INSTALL / UNINSTALL
     * ==========================================================================
     */

    /**
     * (hook: install) Make installation operations
     * 		It creates the database schema and sets some preferences.
     */
    function mdh_messenger_install() {
    	Madhouse_Messenger_Plugin::install();
    }
    osc_register_plugin(osc_plugin_path(__FILE__), 'mdh_messenger_install');

    /**
     * (hook: uninstall) Make un-installation operations
     * 		It destroys the database schema and unsets some preferences.
     */
    function mdh_messenger_uninstall() {
    	Madhouse_Messenger_Plugin::uninstall();
    }
    osc_add_hook(osc_plugin_path(__FILE__) . '_uninstall', 'mdh_messenger_uninstall');

    /*
     * ==========================================================================
     *  INIT: install if bump, upgrade if necessary.
     * ==========================================================================
     */
    Madhouse_Messenger_Plugin::init();

    /*
     * ==========================================================================
     *  ROUTES
     * ==========================================================================
     */

    function mdh_messenger_controller()
    {
        if(mdh_is_messenger()) {
            $do = new Madhouse_Messenger_Controllers_Web();
            $do->doModel();
        }
    }
    osc_add_hook("custom_controller", "mdh_messenger_controller");

    function mdh_messenger_admin_controller()
    {
        if(mdh_is_messenger()) {
            // Enqueue style for admin only.
            osc_add_hook("admin_header", function() {
                osc_remove_script("fancybox");
                osc_enqueue_script(mdh_current_plugin_name() . "_admin");
                osc_enqueue_style(mdh_current_plugin_name() . "_admin", mdh_current_plugin_url("assets/css/admin.css"));
            });

            $filter = function($string) {
                return __("Madhouse Messenger", mdh_current_plugin_name());
            };

            // Page title (in <head />)
            osc_add_filter("admin_title", $filter, 10);

            // Page is madhouse-made.
            osc_add_filter("admin_body_class", function($classes) {
                array_push($classes, "madhouse");
                return $classes;
            });

            // Page title (in <h1 />)
            osc_add_filter("custom_plugin_title", $filter);

            // Add a .row-offset to wrapping <div /> element.
            osc_add_filter("render-wrapper", function($string) {
                return "row-offset";
            });

            $do = new Madhouse_Messenger_Controllers_Admin();
            $do->doModel();
        }
    }
    osc_add_hook("renderplugin_controller", "mdh_messenger_admin_controller");

    osc_add_route(
        mdh_current_plugin_name() . "_inbox",
        mdh_get_preference("base_url") . '/inbox/?([0-9]*)/?',
        mdh_get_preference("base_url") . '/inbox/{p}',
        mdh_current_plugin_name() . '/views/web/inbox.php',
        true,
        "user",
        "custom",
        __("Inbox", mdh_current_plugin_name())
    );

    osc_add_route(
        mdh_current_plugin_name() . "_thread",
        mdh_get_preference("base_url") . '/thread/([0-9]+)/?',
        mdh_get_preference("base_url") . '/thread/{id}/',
        mdh_current_plugin_name() . '/views/web/thread.php',
        true,
        "user",
        "custom",
        __("Thread", mdh_current_plugin_name())
    );

    osc_add_route(
        mdh_current_plugin_name() . "_thread_status",
        mdh_get_preference("base_url") . '/thread/status/([0-9]+)/([0-9]+)/?',
        mdh_get_preference("base_url") . '/thread/status/{id}/{status}/',
        mdh_current_plugin_name() . '/views/web/thread.php',
        true
    );

    osc_add_route(
        mdh_current_plugin_name() . "_thread_label_add",
        mdh_get_preference("base_url") . '/thread/label/add_post/([0-9]+)/([0-9]+)/?',
        mdh_get_preference("base_url") . '/thread/label/add_post/{id}/{label}',
        mdh_current_plugin_name() . '/views/web/thread.php',
        true
    );

    osc_add_route(
        mdh_current_plugin_name() . "_thread_label_remove",
        mdh_get_preference("base_url") . '/thread/label/remove_post/([0-9]+)/([0-9]+)/?',
        mdh_get_preference("base_url") . '/thread/label/remove_post/{id}/{label}',
        mdh_current_plugin_name() . '/views/web/thread.php',
        true
    );

    osc_add_route(
        mdh_current_plugin_name() . "_user_block_user",
        mdh_get_preference("base_url") . '/thread/block/([0-9]+)/?',
        mdh_get_preference("base_url") . '/thread/block/{id}',
        mdh_current_plugin_name() . '/views/web/thread.php',
        true
    );

    osc_add_route(
        mdh_current_plugin_name() . "_user_unblock_user",
        mdh_get_preference("base_url") . '/thread/unblock/([0-9]+)/?',
        mdh_get_preference("base_url") . '/thread/unblock/{id}',
        mdh_current_plugin_name() . '/views/web/thread.php',
        true
    );

    osc_add_route(
        mdh_current_plugin_name() . "_message_delete",
        mdh_get_preference("base_url") . '/message/delete/([0-9]+)/?',
        mdh_get_preference("base_url") . '/message/delete/{id}',
        mdh_current_plugin_name() . '/views/web/thread.php',
        true
    );

    osc_add_route(
        mdh_current_plugin_name() . "_send",
        mdh_get_preference("base_url") . '/send/?',
        mdh_get_preference("base_url") . '/send/',
        mdh_current_plugin_name() . '/views/web/thread.php',
        true
    );

    osc_add_route(
        mdh_current_plugin_name() . "_thread_mark_unread",
        mdh_get_preference("base_url") . '/thread/mark/unread/([0-9]+)/?',
        mdh_get_preference("base_url") . '/thread/mark/unread/{id}',
        mdh_current_plugin_name() . '/views/web/thread.php',
        true
    );

    osc_add_route(
        mdh_current_plugin_name() . "_ajax",
        mdh_get_preference("base_url") . '/ajax/?',
        mdh_get_preference("base_url") . '/ajax/',
        mdh_current_plugin_name() . '/ajax.php',
        true
    );

    osc_add_route(
        mdh_current_plugin_name() . "_admin_ajax",
        mdh_get_preference("base_url") . '/admin/ajax/?',
        mdh_get_preference("base_url") . '/admin/ajax/',
        mdh_current_plugin_name() . '/ajax-admin.php',
        true
    );

    osc_add_route(
        mdh_current_plugin_name() . "_dashboard",
        mdh_get_preference("base_url") . '/dashboard/?',
        mdh_get_preference("base_url") . '/dashboard/',
        mdh_current_plugin_name() . '/views/admin/main.php'
    );

    osc_add_route(
        mdh_current_plugin_name() . "_listing",
        mdh_get_preference("base_url") . '/listing/?',
        mdh_get_preference("base_url") . '/listing/',
        mdh_current_plugin_name() . '/views/admin/listing.php'
    );

    osc_add_route(
        mdh_current_plugin_name() . "_message_block",
        mdh_get_preference("base_url") . '/message/block/?',
        mdh_get_preference("base_url") . '/message/block/',
        mdh_current_plugin_name() . '/views/admin/listing.php'
    );

    osc_add_route(
        mdh_current_plugin_name() . "_message_unblock",
        mdh_get_preference("base_url") . '/message/unblock/?',
        mdh_get_preference("base_url") . '/message/unblock/',
        mdh_current_plugin_name() . '/views/admin/listing.php'
    );

    osc_add_route(
        mdh_current_plugin_name() . "_settings",
        mdh_get_preference("base_url") . '/settings/?',
        mdh_get_preference("base_url") . '/settings/',
        mdh_current_plugin_name() . '/views/admin/settings.php'
    );

    osc_add_route(
        mdh_current_plugin_name() . "_settings_post",
        mdh_get_preference("base_url") . '/settings/post/?',
        mdh_get_preference("base_url") . '/settings/post',
        mdh_current_plugin_name() . '/views/admin/settings.php'
    );

    osc_add_route(
        mdh_current_plugin_name() . "_contact",
        mdh_get_preference("base_url") . '/contact/?',
        mdh_get_preference("base_url") . '/contact/',
        mdh_current_plugin_name() . '/views/admin/contact.php'
    );

    osc_add_route(
        mdh_current_plugin_name() . "_upgrade",
        mdh_get_preference("base_url") . '/upgrade/?',
        mdh_get_preference("base_url") . '/upgrade/',
        mdh_current_plugin_name() . '/views/admin/upgrade.php'
    );

    osc_add_route(
        mdh_current_plugin_name() . "_labels",
        mdh_get_preference("base_url") . '/labels/?',
        mdh_get_preference("base_url") . '/labels/',
        mdh_current_plugin_name() . '/views/admin/labels.php'
    );

    osc_add_route(
        mdh_current_plugin_name() . "_labels_post",
        mdh_get_preference("base_url") . '/labels/post/?',
        mdh_get_preference("base_url") . '/labels/post',
        mdh_current_plugin_name() . '/views/admin/labels.php'
    );

    osc_add_route(
        mdh_current_plugin_name() . "_label_add",
        mdh_get_preference("base_url") . '/label/add/?',
        mdh_get_preference("base_url") . '/label/add',
        mdh_current_plugin_name() . '/views/admin/labels.php'
    );

    osc_add_route(
        mdh_current_plugin_name() . "_label_remove",
        mdh_get_preference("base_url") . '/label/remove/([0-9]+)/?',
        mdh_get_preference("base_url") . '/label/remove/{id}',
        mdh_current_plugin_name() . '/views/admin/labels.php'
    );

    osc_add_route(
        mdh_current_plugin_name() . "_events",
        mdh_get_preference("base_url") . '/events/?',
        mdh_get_preference("base_url") . '/events/',
        mdh_current_plugin_name() . '/views/admin/events.php'
    );

    osc_add_route(
        mdh_current_plugin_name() . "_events_post",
        mdh_get_preference("base_url") . '/events/post/?',
        mdh_get_preference("base_url") . '/events/post',
        mdh_current_plugin_name() . '/views/admin/labels.php'
    );

    osc_add_route(
        mdh_current_plugin_name() . "_status",
        mdh_get_preference("base_url") . '/status/?',
        mdh_get_preference("base_url") . '/status/',
        mdh_current_plugin_name() . '/views/admin/status.php'
    );

    osc_add_route(
        mdh_current_plugin_name() . "_status_post",
        mdh_get_preference("base_url") . '/status/post/?',
        mdh_get_preference("base_url") . '/status/post',
        mdh_current_plugin_name() . '/views/admin/status.php'
    );

    osc_add_route(
        mdh_current_plugin_name() . "_status_add",
        mdh_get_preference("base_url") . '/status/add/?',
        mdh_get_preference("base_url") . '/status/add',
        mdh_current_plugin_name() . '/views/admin/status.php'
    );

    osc_add_route(
        mdh_current_plugin_name() . "_status_remove",
        mdh_get_preference("base_url") . '/status/remove/([0-9]+)/?',
        mdh_get_preference("base_url") . '/status/remove/{id}',
        mdh_current_plugin_name() . '/views/admin/status.php'
    );

    osc_add_route(
        mdh_current_plugin_name() . "_blocked_users",
        mdh_get_preference("base_url") . '/blocked-users/?',
        mdh_get_preference("base_url") . '/blocked-users/',
        mdh_current_plugin_name() . '/views/admin/blocked-users.php'
    );

    osc_add_route(
        mdh_current_plugin_name() . "_unblock_user",
        mdh_get_preference("base_url") . '/unblock-user/([0-9]+)/([0-9]+)/?$',
        mdh_get_preference("base_url") . '/unblock-user/{userId}/{blockedUserId}',
        mdh_current_plugin_name() . '/views/admin/blocked-users.php'

    );

    /*
     * ==========================================================================
     *  REGISTER & ENQUEUE
     * ==========================================================================
     */
    if(OC_ADMIN && preg_match('/^' . mdh_current_plugin_name() . '.*$/', Params::getParam("route"))) {
        osc_register_script("jquery", mdh_current_plugin_url("vendor/bower_components/jquery/dist/jquery.js"));
    }

    osc_register_script(mdh_current_plugin_name() . "_admin", mdh_current_plugin_url("assets/js/admin.min.js"));

    osc_register_script(mdh_current_plugin_name() . "_autolinker", mdh_current_plugin_url("vendor/bower_components/Autolinker.js/dist/Autolinker.min.js"));

    /*
     * ==========================================================================
     *  FILTERS
     * ==========================================================================
     */
    osc_add_filter("meta_title_filter", function($v) {
        if(mdh_is_messenger()) {
            return __("Messages", mdh_current_plugin_name()) . " - " . osc_page_title();
        }
        return $v;
    });

    osc_add_filter("mdh_messenger_event_text", function($text, $message) {
        if($message->hasEvent("status_change")) {
            $statusTitle = (is_null($message->getStatus()))? __("Unknown status", mdh_current_plugin_name())  : $message->getStatus()->getTitle();

            return str_ireplace(array("{STATUS_NAME}"), array($statusTitle), $text);
        }
        return $text;
    });

    function mdh_messenger_common_words($desc, $recipients, $context) {
        return osc_mailBeauty(
            $desc,
            array(
                array("{INBOX_URL}"),
                array(mdh_messenger_inbox_url())
            )
        );
    }
    osc_add_filter("email_mmessenger_contact_user_description_after", "mdh_messenger_common_words");
    osc_add_filter("email_alert_mmessenger_messages_description_after", "mdh_messenger_common_words");

    /*
     * ==========================================================================
     *  HOOKS
     * ==========================================================================
     */

    // Add flashmessage if upgrade is required.
    osc_add_hook("before_admin_html", array("Madhouse_Messenger_Plugin", "notifyUpgrade"));

    /**
     * (hook: mdh_messenger_post_send) Sends a notification to user(s).
     */
    osc_add_hook("mdh_messenger_post_send", "Madhouse_Messenger_Actions::notify");

    /**
     * (hook: disable_user) Disable the user & all his messages.
     */
    osc_add_hook("disable_user", function($user) {
        $mu = Madhouse_Utils_Models::findUserByPrimaryKey($user["pk_i_id"]);
        try {
            Madhouse_Messenger_Actions::disableUser($mu);
        } catch(Exception $e) {
            // Do nothing.
        }
    });

    /**
     * (hook: enable_user) Enable the user & all his messages.
     */
    osc_add_hook("enable_user", function($user) {
        $mu = Madhouse_Utils_Models::findUserByPrimaryKey($user["pk_i_id"]);
        try {
            Madhouse_Messenger_Actions::enableUser($mu);
        } catch(Exception $e) {
            // Do nothing.
        }
    });

    /**
     * (hook: mdh_thread_exported) Exports the data related to the thread exported.
     */
    osc_add_hook("mdh_thread_exported", "Madhouse_Messenger_ThreadActions::extendExport");

    if(mdh_get_preference("automessage_item_deleted")) {
        /**
         * (hook: after_archive_item) Sends an auto-message on archive
         */
        osc_add_hook("after_archive_item", function($item) {
            $event = null;
            $message = Params::getParam("mdhMessengerBroadcast");
            if(empty($message)) {
                $event = Madhouse_Messenger_Models_Events::newInstance()->findByName("item_deleted");
            }

            Madhouse_Messenger_Actions::broadcast(
                new Madhouse_Item(
                    $item,
                    ItemResource::newInstance()->getResource($item["pk_i_id"])
                ),
                $message,
                $event
            );
        });

        /**
         * (hook: after_delete_item) Sends an auto-message on delete
         */
        osc_add_hook("after_delete_item", function($itemId, $item) {
            $event = null;
            $message = Params::getParam("mdhMessengerBroadcast");
            if(empty($message)) {
                $event = Madhouse_Messenger_Models_Events::newInstance()->findByName("item_deleted");
            }
            Madhouse_Messenger_Actions::broadcast(
                new Madhouse_Item(
                    $item,
                    null
                ),
                $message,
                $event
            );
        });
    }

    // On delete user clean blocked_user Table
    // Since 1.50.0
    osc_add_hook('delete_user', function ($user_id) {
        Madhouse_Messenger_Models_BlockedUsers::newInstance()->removeByUserId($user_id);
    });

    /**
     * (hook: item_spam_on) Sends an auto-message on mark as spam
     */
    if(mdh_get_preference("automessage_item_spammed")) {
        osc_add_hook("item_spam_on", function($itemId) {
            Madhouse_Messenger_Actions::broadcast(
                new Madhouse_Item(
                    Item::newInstance()->findByPrimaryKey($itemId),
                    ItemResource::newInstance()->getResource($itemId)
                ),
                null,
                Madhouse_Messenger_Models_Events::newInstance()->findByName("item_spammed")
            );
        });
    }

    /**
     * (hook: cron_daily) Sends reminders to users that have old unread messages.
     */
    osc_add_hook("cron_daily", function() {
        // This cron is sending a reminder if the user have missed messages.
        Madhouse_Messenger_Actions::notifyDaily();
    });

    /*
     * ==========================================================================
     *  USER AND ADMIN MENU
     * ==========================================================================
     */

    /**
     * Adds a menu link in the user_menu if the settings requires it.
     * (hook: user_menu)
     */
    function mdh_messenger_usermenu() {
        if(osc_get_preference('display_usermenu_link', mdh_current_preferences_section())):
            ?>
                <li class="opt_messenger <?php echo (mdh_is_messenger()) ? "active" : "" ?>">
                    <a href="<?php echo mdh_messenger_inbox_url(); ?>"><?php _e("Messages", mdh_current_plugin_name()); ?></a>
                </li>
            <?php
        endif;
    }
    osc_add_hook('user_menu', 'mdh_messenger_usermenu') ;

    /**
     * Adds a submenu to the Madhouse main admin menu.
     * (hook: admin_menu_init)
     */
    function mdh_messenger_admin_init() {
        osc_add_admin_submenu_divider('madhouse', __("Messenger", mdh_current_plugin_name()), mdh_current_plugin_name(), 'administrator');
        osc_add_admin_submenu_page('madhouse', __('Dashboard', mdh_current_plugin_name()), mdh_messenger_admin_dashboard_url(), mdh_current_plugin_name() . '_dashboard', 'moderator');

        osc_add_admin_submenu_page('madhouse', __('Manage messages', mdh_current_plugin_name()), mdh_messenger_admin_messages_url(), mdh_current_plugin_name() . '_listing', 'administrator');
        osc_add_admin_submenu_page('madhouse', __('Blocked users', mdh_current_plugin_name()), mdh_messenger_admin_blocked_users_url(),
            mdh_current_plugin_name() . '_blocked_users', 'administrator');
        osc_add_admin_submenu_page('madhouse', __('Labels', mdh_current_plugin_name()), mdh_messenger_admin_labels_url(), mdh_current_plugin_name() . '_labels', 'administrator');

        osc_add_admin_submenu_page('madhouse', __('Settings', mdh_current_plugin_name()), mdh_messenger_admin_settings_url(), mdh_current_plugin_name() . '_settings', 'administrator');
    }
    osc_add_hook('admin_menu_init', 'mdh_messenger_admin_init');

    /*
     * Adds a link to the users admin datatable.
     * (hook: more_actions_manage_users)
     */
    function mdh_messenger_actions_manage_users($options_more, $aRow) {
        array_push(
            $options_more,
            sprintf('<a href="%s">%s</a>', mdh_messenger_admin_contact_url($aRow['pk_i_id']), __('Contact', mdh_current_plugin_name()))
        );
    	return $options_more;
    }
    osc_add_filter('more_actions_manage_users', 'mdh_messenger_actions_manage_users');

}

?>
