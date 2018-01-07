<?php

	/**
	 * Messenger base URL. Returns the base URL for frontend (web) calls.
	 * @return a string.
	 * @since 1.20 - Now using osclass routes.
	 *        1.01 - This function was added.
	 */
	function mdh_messenger_ajax_url() {
		return osc_route_ajax_url(mdh_current_plugin_name() . "_ajax");
	}

	/**
     * Returns the messenger inbox URL.
     * @param $filters an array of filters (can contain 'filter', 'label').
	 * @param $page the page number (int) to display
     * @param $num the number of item per page (int) to display
	 * @return a string.
	 * @since 1.01
	 */
	function mdh_messenger_inbox_url($filters=null, $page=null, $num=null) {
	    $params = array();

        if(isset($filters["filter"]) && $filters["filter"]) {
           $params["filter"] = $filters["filter"];
        }

        if(isset($filters["label"]) && $filters["label"]) {
            $params["label"] = $filters["label"];
        }

        if(! is_null($page)) {
            $params["p"] = $page;
        }

        if(! is_null($num)) {
            $params["n"] = $num;
        }

		return osc_route_url(mdh_current_plugin_name() . "_inbox", $params);
	}

    function mdh_messenger_current_inbox_url()
    {
        return mdh_messenger_inbox_url(
            array(
                "filter" => Params::getParam("filter"),
                "label" => Params::getParam("label")
            ),
            Params::getParam("p"),
            Params::getParam("n")
        );
    }

	/**
	 * Messenger URL for a particular thread.
	 * @param $threadId the id of the thread to point to.
	 * @return a string.
	 * @since 1.20
	 */
	function mdh_messenger_thread_url($threadId=null)
	{
	    //return mdh_messenger_ajax_url() . "&do=message&id=" . $threadId;
	    return osc_route_url(mdh_current_plugin_name() . "_thread", array("id" => $threadId));
	}

	/**
	 * Messenger send URL. Returns the URL to call to send a message.
	 * @return a string.
	 * @since 1.01
	 */
	function mdh_messenger_send_url() {
		//return mdh_messenger_ajax_url() . "&do=send";
		return osc_route_url(mdh_current_plugin_name() . "_send");
	}

    /**
     * Mark a thread as unread
     * @return a string.
     * @since 1.50
     */
    function mdh_messenger_thread_mark_unread_url($threadId=null) {
        return osc_route_url(mdh_current_plugin_name() . "_thread_mark_unread", array("id" => $threadId));
    }

    /**
     * Messenger change thread label URL.
     * @return a string.
     * @since 1.33
     */
    function mdh_messenger_thread_label_add_url($threadId, $labelId)
    {
        //return mdh_messenger_ajax_url() . "&do=send";
        return osc_route_url(
            mdh_current_plugin_name() . "_thread_label_add",
            array(
                "id" => $threadId,
                "label" => $labelId
            )
        );
    }

    /**
     * Messenger change thread label URL.
     * @return a string.
     * @since 1.40
     */
    function mdh_messenger_thread_label_remove_url($threadId, $labelId)
    {
        return osc_route_url(
            mdh_current_plugin_name() . "_thread_label_remove",
            array(
                "id" => $threadId,
                "label" => $labelId
            )
        );
    }

    /**
     * Messenger archive URL.
     * @return a string.
     * @since 1.33
     */
    function mdh_messenger_thread_archive_url($threadId) {
        return mdh_messenger_thread_label_add_url(
            $threadId,
            Madhouse_Messenger_Models_Labels::newInstance()->findByName("archive")->getId()
        );
    }

    /**
     * Messenger UNarchive URL.
     * @return a string.
     * @since 1.33
     */
    function mdh_messenger_thread_unarchive_url($threadId) {
        return mdh_messenger_thread_label_add_url(
            $threadId,
            Madhouse_Messenger_Models_Labels::newInstance()->findByName("inbox")->getId()
        );
    }

    /**
     * Messenger block user URL.
     * @return a string.
     * @since 1.50.0
     */
    function mdh_messenger_user_block_user_url($userId) {
        return osc_route_url(
            mdh_current_plugin_name() . "_user_block_user",
            array(
                "id" => $userId
            )
        );
    }

    /**
     * Messenger unblock user URL.
     * @return a string.
     * @since 1.50.0
     */
    function mdh_messenger_user_unblock_user_url($userId) {
        return osc_route_url(
            mdh_current_plugin_name() . "_user_unblock_user",
            array(
                "id" => $userId
            )
        );
    }

	/**
	 * Includes the messenger widget.
	 *
	 * The messenger widget is a component generally located in the header navigation
	 * bar which consists in :
	 *     - Giving a quick-access link to the messenger inbox.
	 *     - Displaying the number of unread messages of the current user.
	 *     - Giving a quick-access to the last threads of the current user.
	 *
	 * Makes AJAX call to get those informations.
	 *
	 * Think of it as a copycat of the Facebook widget that you all know.
	 *
	 * @return void
	 * @see Madhouse_Messenger_Controllers_Web::doModel - case "widget".
	 * @since 1.20 Since then, a default widget is loaded if none is found in the theme.
	 *        1.00
	 */
	function mdh_messenger_widget() {
	    Madhouse_Utils_Controllers::doViewPart("widget.php");
	}

    osc_add_hook('mdh_messenger_widget', 'mdh_messenger_widget');

	/**
	 * Includes the messenger contact form.
	 *
	 * That's the form you need when contacting someone from an item (see item.php
	 * or item-contact.php). Loads the default contact form if none is found in the
	 * theme.
	 *
	 * @return void
	 * @see Madhouse_Messenger_Controllers_Web::doModel - case "send".
	 * @since 1.20
	 */
	function mdh_messenger_contact_form() {
    	Madhouse_Utils_Controllers::doViewPart("contact-form.php");
	}

    osc_add_hook('mdh_messenger_contact_form', 'mdh_messenger_contact_form');

	/**
	 * Tells if the current user or item has already been contacted. Exports the thread if it exists.
	 * @return true if a thread already exists, false otherwise.
	 * @since 1.20
	 */
    function mdh_messenger_is_contacted() {
        if(is_null(osc_item_id())) {
            // Is user contacted ?
            $e = Madhouse_Messenger_Models_Threads::newInstance()->findByUsers(osc_logged_user_id(), osc_item_user_id());
            if($e) {
    	        View::newInstance()->_exportVariableToView("mdh_thread", $e);
    	        return true;
    	    }
    	    return false;
        } else {
            // Is item contacted ?
            if(! osc_item_user_id()) {
                return false;
            }

            $e = Madhouse_Messenger_Models_Threads::newInstance()->findByUsers(osc_logged_user_id(), osc_item_user_id(), osc_item_id());
            if($e) {
                View::newInstance()->_exportVariableToView("mdh_thread", $e);
                return true;
            }
            return false;
        }
    }

    function mdh_messenger_message_template()
    {
        if (!mdh_get_preference("enable_message_template")) {
            return "";
        }

        try {
            $template = Madhouse_Messenger_Actions::findTemplate(osc_logged_user_id());
        } catch(Madhouse_NoResultsException $e) {
            // No template, probably the first message of the user.
            $template = "";
        }

        // Split some things, to get only the firstname.
        $nameTokens = explode(" ", osc_item_contact_name());

        // Return the template filled with the name of the current item owner.
        return str_replace(
            "{RECIPIENT_NAME}",
            $nameTokens[0],
            $template
        );
    }

    /**
     * Are the status enabled ?
     * @return true/false.
     * @since 1.20
     */
    function mdh_messenger_status_enabled()
    {
        return osc_get_preference("enable_status", mdh_current_preferences_section());
    }

    /**
     * Returns the default status id.
     * @return int.
     * @since 1.20
     */
    function mdh_messenger_default_status()
    {
        return osc_get_preference("default_status", mdh_current_preferences_section());
    }

    /**
     * Is the status only editable for item owners ?
     * @return true/false.
     * @since 1.20
     */
    function mdh_messenger_status_for_owner()
    {
        return osc_get_preference("status_for_owner_only", mdh_current_preferences_section());
    }

	// HELPERS for Inbox.

    /**
     * Returns true if the current page is a messenger page.
     * @return true/false
     * @since 1.20
     */
    function mdh_is_messenger()
    {
        return mdh_messenger_is_inbox();
    }

    /**
     * Returns true if the current page is a messenger page.
     * @return true/false
     * @since 1.00
     * @deprecated use mdh_is_messenger() instead.
     */
	function mdh_messenger_is_inbox() {
	    $rewrite = Rewrite::newInstance();
	    if(
	        ($rewrite->get_location() == 'ajax' && Params::getParam("ajaxfile") == mdh_current_plugin_name() . "/main.php")
	        ||
            preg_match('/^' . mdh_current_plugin_name() . '_.*$/', Params::getParam("route"))
	    ) {
	        return true;
	    }
		return false;
	}

    /**
     * Returns true if the current page is a the inbox page (with label=inbox).
     * @return true/false
     * @since 1.33
     */
    function mdh_messenger_is_inbox_page()
    {
        return (mdh_is_messenger() && Params::getParam("label") === "inbox");
    }

    /**
     * Returns true if the current page is a the archive page (with label=archive).
     * @return true/false
     * @since 1.33
     */
    function mdh_messenger_is_archive_page()
    {
        return (mdh_is_messenger() && Params::getParam("label") === "archive");
    }

    /*
     * ==========================================================================
     *  VARIOUS HELPERS
     * ==========================================================================
     */

    function mdh_messenger_create_status($name, $title, $text = null)
    {
        // Set basic infos.
        $status = new Madhouse_Messenger_Status();
        $status
            ->setName($name)
            ->setTitle($title);

        if(!is_null($text)) {
            $status->setText($text);
        }

        // Create the event.
        return Madhouse_Messenger_Actions::createStatus($status);
    }

    function mdh_messenger_create_event($name, $excerpt, $text)
    {
        // Set basic infos.
        $event = new Madhouse_Messenger_Event();
        $event
            ->setName($name)
            ->setExcerpt($excerpt)
            ->setText($text);

        // Create the event.
        return Madhouse_Messenger_Actions::createEvent($event);
    }

    function mdh_messenger_create_label($name, $title, $user = null, $system = false, $parent = null)
    {
        // Set basic infos.
        $label = new Madhouse_Messenger_Label();
        $label
            ->setName($name)
            ->setTitle($title)
            ->setSystem($system);

        // Set user (owner of this label), if needed.
        if(!is_null($user)) {
            $label->setUser($user);
        }

        // Set parent label, if needed.
        if(!is_null($parent)) {
            $label->setUser($parent);
        }

        // Create the label.
        return Madhouse_Messenger_Actions::createLabel($label);
    }
