<?php

/**
 * Madhouse Messenger class.
 * @since  1.30
 */
class Madhouse_Messenger_Plugin
{
	/**
	 * Current version of this plugin.
	 *
	 * @since
	 */
	const CURRENT_VERSION 	= "1.50.0";
	const UTILS_VERSION 	= "1.21.0";

	public static function install()
	{
		mdh_import_sql(mdh_current_plugin_path("assets/model/install.sql", false));

    	// New thread email template.
        mdh_install_email(
            "email_mmessenger_contact_user",
            "{SENDER_NAME} has contacted you !",
            "{SENDER_NAME} sent you a message about {ITEM_TITLE} : {THREAD_URL} <br/><br/>{MESSAGE_CONTENT}"
        );
    	mdh_install_email(
            "email_alert_mmessenger_messages",
            "You have unread messages !",
            "You have {NB_UNREAD_MESSAGES} unread messages in you inbox : {INBOX_URL}"
        );

    	// General
    	osc_set_preference('display_usermenu_link', '1', mdh_current_preferences_section(), 'BOOLEAN');

    	// Notifications
    	osc_set_preference('enable_notifications', '1', mdh_current_preferences_section(), 'BOOLEAN');
        osc_set_preference('notify_everytime', '1', mdh_current_preferences_section(), 'BOOLEAN');
    	osc_set_preference('stop_notify_after', '10', mdh_current_preferences_section(), 'INTEGER');

    	// Reminders
    	osc_set_preference('enable_reminders', '1', mdh_current_preferences_section(), 'BOOLEAN');
    	osc_set_preference('reminder_every', '10', mdh_current_preferences_section(), 'INTEGER');

    	// Status
    	osc_set_preference('enable_status', '1', mdh_current_preferences_section(), 'BOOLEAN');
    	osc_set_preference('default_status', '0', mdh_current_preferences_section(), 'INTEGER');
    	osc_set_preference('status_for_owner_only', '1', mdh_current_preferences_section(), 'INTEGER');

        Madhouse_Messenger_Plugin::upgrade();
	}

	public static function uninstall()
	{
		mdh_import_sql(mdh_current_plugin_path("assets/model/uninstall.sql", false));

        mdh_uninstall_email("email_mmessenger_contact_user");
    	mdh_uninstall_email("email_mmessenger_reply_user");
    	mdh_uninstall_email("email_alert_mmessenger_messages");

        mdh_delete_preferences(mdh_current_preferences_section());
	}

	/**
	 * Upgrades the model & preferences to 1.30.
	 * @return void
	 * @since  1.30
	 */
	public static function upgrade130()
	{
		if(mdh_get_preference("version") === "") {
			// Imports new SQL model.
			mdh_import_sql(mdh_current_plugin_path("assets/model/upgrade-130.sql", false));

			// Insert status descriptions.
			$statusList = array(
				"processing" => array(
					"s_title" => "Waiting",
					"s_text" => "Your inquiry is getting processed"
				),
				"scheduled" => array(
					"s_title" => "Scheduled",
					"s_text" => "An appointment/shipment has been scheduled"
				),
				"accepted" => array(
					"s_title" => "Accepted",
					"s_text" => "Item owner has accepted your offer"
				),
				"refused" => array(
					"s_title" => "Refused",
					"s_text" => "Item owner is not interested"
				)
			);

			foreach($statusList as $statusName => $statusInfos) {
				try {
					$statusRaw = Madhouse_Messenger_Models_Status::newInstance()->findByName($statusName, true);

					$status = new Madhouse_Messenger_Status();
					$status
						->setId($statusRaw->getId())
						->setName($statusRaw->getName())
						->setTitle($statusInfos["s_title"])
						->setText($statusInfos["s_text"]);

					Madhouse_Messenger_Models_Status::newInstance()->insertDescription($status);
				} catch (Madhouse_NoResultsException $e) {
					// Skip this status, no description to insert.
				}
			}

			// Insert events descriptions.
			$eventsList = array(
				"status_change" => array(
					"s_text" => "Status of this thread has changed to {STATUS_NAME}"
				),
				"item_deleted" => array(
					"s_text" => "Item linked to this thread is not available anymore: advertiser might have found what he/she was looking for"
				),
				"item_spammed" => array(
					"s_text" => "Item linked to this thread has been marked as spam by an admin"
				)
			);

			foreach($eventsList as $eventName => $eventInfos) {
				$event = new Madhouse_Messenger_Event();
				$event
					->setName($eventName)
					->setText($eventInfos["s_text"]);

				// Deal with new and old events.
				try {
					$eventRaw = Madhouse_Messenger_Models_Events::newInstance()->findByName($eventName, true);

					$event->setId($eventRaw->getId());

					Madhouse_Messenger_Models_Events::newInstance()->insertDescription($event);

				} catch(Madhouse_NoResultsException $e) {
					Madhouse_Messenger_Models_Events::newInstance()->create($event);
				}
			}

			// Auto-messages.
			osc_set_preference('automessage_item_deleted', '1', mdh_current_preferences_section(), 'BOOLEAN');
			osc_set_preference('automessage_item_spammed', '1', mdh_current_preferences_section(), 'BOOLEAN');
			osc_set_preference('automessage_newer_days', '30', mdh_current_preferences_section(), 'INTEGER');

			// URL rewriting
		    osc_set_preference('base_url', 'messenger', mdh_current_preferences_section(), 'STRING');

			// New preferences for emails.
			osc_set_preference('email_excerpt_length', '45', mdh_current_preferences_section(), 'INTEGER');
	    	osc_set_preference('email_excerpt_oneline', '0', mdh_current_preferences_section(), 'BOOLEAN');

			// New preferences for reminders.
			osc_set_preference('reminder_every_days', '2', mdh_current_preferences_section(), 'INTEGER');
	    	osc_set_preference('stop_reminder_after', '8', mdh_current_preferences_section(), 'INTEGER');

	    	// We just upgraded to 1.30. Tag it.
			osc_set_preference("version", "1.30", mdh_current_preferences_section());
		}
	}

	/**
	 * Upgrades the model & preferences to 1.30.
	 * @return void
	 * @since  1.30
	 */
	public static function upgrade133()
	{
		$currentVersion = mdh_get_preference("version");
		if($currentVersion === "" || strnatcmp($currentVersion, "1.33") === -1) {
			// We just upgraded to a new version. Tag it.
			osc_set_preference("version", "1.33", mdh_current_preferences_section());
		}
	}

	/**
	 * Upgrades the model & preferences to 1.30.
	 * @return void
	 * @since  1.30
	 */
	public static function upgrade140()
	{
		$upgradeVersion = "1.40.0";
		$currentVersion = mdh_get_preference("version");

		if($currentVersion === "" || version_compare($currentVersion, $upgradeVersion) < 0) {
			// Imports new SQL model.
			mdh_import_sql(mdh_current_plugin_path("assets/model/upgrade-140-1.sql", false));

			// Create an inbox and archive label.
			mdh_messenger_create_label("inbox", "Inbox", null, true);
			mdh_messenger_create_label("archive", "Archive", null, true);

			// Gets the Page data-access object (an emails is a special static page)
			$pageDAO = Page::newInstance();
			$existingEmail = $pageDAO->findByInternalName("email_mmessenger_contact_user");

			// Duplicate the existing email.
			$isInserted = $pageDAO->insert(
				array(
					"s_internal_name" => "email_mmessenger_reply_user",
					"b_indelible" => true,
					"b_link" => true
				),
				array_map(
					function($v) {
						return array(
							"s_title" => "RE: " . $v["s_title"],
							"s_text" => $v["s_text"]
						);
					},
					$existingEmail["locale"]
				)
			);

			// We just upgraded to a new version. Tag it.
			osc_set_preference("version", $upgradeVersion, mdh_current_preferences_section());

			if (Madhouse_Messenger_Models_Threads::newInstance()->count() > 0) {
				// Register this version for more advanced upgrade (AJAX required).
				self::registerUpgrade($upgradeVersion);
			}
		}

		if($currentVersion === "" || strnatcmp($currentVersion, "1.40.2") === -1) {
			// We just upgraded to a new version. Tag it.
			osc_set_preference("version", "1.40.2", mdh_current_preferences_section());
		}

		if($currentVersion === "" || strnatcmp($currentVersion, "1.40.3") === -1) {

			osc_set_preference("enable_message_template", '1', mdh_current_preferences_section());

			// We just upgraded to a new version. Tag it.
			osc_set_preference("version", "1.40.3", mdh_current_preferences_section());
		}
	}

	public static function upgrade150() {
		$currentVersion = mdh_get_preference("version");
		$upgradeVersion = "1.50.0";

		if($currentVersion === "" || strnatcmp($currentVersion, $upgradeVersion) === -1) {

			mdh_import_sql(mdh_current_plugin_path("assets/model/upgrade-150-0.sql", false));

			osc_set_preference("enable_block_user", '1', mdh_current_preferences_section());
			osc_set_preference("email_admin_on_block_user", '1', mdh_current_preferences_section());

			osc_set_preference("enable_mark_as_unread", '1', mdh_current_preferences_section());

			osc_set_preference("enable_autolinker", '1', mdh_current_preferences_section());

			osc_set_preference("autolinker_new_window", 'true', mdh_current_preferences_section());
			osc_set_preference("autolinker_strip_prefix", 'false', mdh_current_preferences_section());
			osc_set_preference("autolinker_truncate_length", '30', mdh_current_preferences_section());

			mdh_install_email(
	            "email_admin_on_blocked_user",
	            "{USER_NAME} just block the user {BLOCKED_USER_NAME}",
	            'Hi admin,<br/><br/>{USER_NAME} just block the user {BLOCKED_USER_NAME}.<br/><br>Maybe you should review him. <a href="{ADMIN_USER_URL}">{ADMIN_USER_URL}</a> '
	        );

			// We just upgraded to a new version. Tag it.
			osc_set_preference("version", $upgradeVersion, mdh_current_preferences_section());
		}
	}

	/**
	 * Upgrades the model & preferences to last version.
	 * @return void
	 * @since  1.31
	 */
	public static function upgrade()
	{
		self::upgrade130();
        self::upgrade133();
        self::upgrade140();
        self::upgrade150();
	}

	/**
	 * Init the plugin.
	 *
	 * @return void
	 * @since 1.40
	 */
	public static function init()
	{
		if(
			osc_plugin_is_installed(mdh_current_plugin_name(true))
			&& osc_get_preference("display_usermenu_link", mdh_current_preferences_section()) === ""
		) {
			// We bumped the plugin, run ::install().
	        mdh_messenger_install();
	    } elseif(
	    	osc_plugin_is_enabled(mdh_current_plugin_name(true))
	    	&& strnatcmp(osc_get_preference("version", mdh_current_preferences_section()), self::CURRENT_VERSION) === -1
	    ) {
	    	// Upgrade if necessary.
	        self::upgrade();
	    }

	    // Do nothing;
	}

	public static function notifyUpgrade()
	{
		$upgradingVersions = self::getUpgradingVersions();
		if (
            (!defined("IS_AJAX") || (defined("IS_AJAX") && !IS_AJAX))
            && !empty($upgradingVersions)
            && Madhouse_Messenger_Models_Threads::newInstance()->count() > 0
        ) {
            // Some AJAX updating is required.
            osc_add_flash_warning_message(
                sprintf(
                    __("A new Madhouse Messenger version has been detected, head to the %s to complete the upgrade.", mdh_current_plugin_name()),
                    sprintf(
                        '<a class="alert-link font-bold text-black" href="%s">%s</a>',
                        mdh_messenger_admin_upgrade_url(),
                        __("upgrade page", mdh_current_plugin_name())
                    )
                ),
                "admin"
            );
        }
	}

	public static function getUpgradingVersions()
	{
		$upgradingVersions = osc_get_preference("upgrading_versions", mdh_current_preferences_section());
		if ($upgradingVersions != "") {
			return json_decode($upgradingVersions, true);
		}

		return array();
	}

	public static function registerUpgrade($candidate)
	{
		$upgradingVersions = self::getUpgradingVersions();

		// Push candidate at the end of the array.
		$upgradingVersions[] = $candidate;

		// Set preference @ database.
		osc_set_preference("upgrading_versions", json_encode($upgradingVersions), mdh_current_preferences_section());
	}

	public static function unregisterUpgrade($candidate)
	{
		$upgradingVersions = self::getUpgradingVersions();

		$upgradingVersions = array_filter(
			$upgradingVersions,
			function ($version) use ($candidate) {
				return ($version != $candidate);
			}
		);

		// Set preference @ database.
		osc_set_preference("upgrading_versions", json_encode($upgradingVersions), mdh_current_preferences_section());
	}
}

?>