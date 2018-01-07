<?php require __DIR__ . "/nav.php"; ?>
<div class="hbox">
    <div class="col bg-light b-r width-lg" id="navbar-exemple">
        <ul class="nav nav-stacked hpadder-md js-affix-sidenav" role="tablist">
            <li class="active">
                <a class="space-in-lg" href="#start">
                    <i class="glyphicon glyphicon-plane space-out-r-sm"></i><?php _e("Start", mdh_current_plugin_name()); ?>
                </a>
            </li>
            <li>
                <a class="space-in-lg" href="#general">
                    <i class="glyphicon glyphicon-cog space-out-r-sm"></i><?php _e("General", mdh_current_plugin_name()); ?>
                </a>
            </li>
            <li>
                <a class="space-in-lg" href="#auto-messages">
                    <i class="glyphicon glyphicon-exclamation-sign space-out-r-sm"></i><?php _e("Auto-messages", mdh_current_plugin_name()); ?>
                </a>
            </li>
            <li>
                <a class="space-in-lg" href="#links">
                    <i class="glyphicon glyphicon-link space-out-r-sm"></i><?php _e("Links", mdh_current_plugin_name()); ?>
                </a>
            </li>
            <li>
                <a class="space-in-lg" href="#emails">
                    <i class="glyphicon glyphicon-send space-out-r-sm"></i><?php _e("Notifications & E-mails", mdh_current_plugin_name()); ?>
                </a>
            </li>
            <li>
                <a class="space-in-lg" href="#status">
                    <i class="glyphicon glyphicon-bookmark space-out-r-sm"></i><?php _e("Status", mdh_current_plugin_name()); ?>
                </a>
            </li>
            <li>
                <a class="space-in-lg" href="#user-block">
                    <i class="glyphicon glyphicon-minus-sign space-out-r-sm"></i><?php _e("User block", mdh_current_plugin_name()); ?>
                </a>
            </li>
        </ul>
    </div>
    <div class="col">
        <div class="bg-light lter b-b">
            <form class="form-horizontal" action="<?php echo mdh_messenger_admin_settings_post_url(); ?>" method="post">
            <div class="anchor" id="start"></div>
            <div class="space-in-md b-b">
                <h2 class="h4 text-info row-space-2">
                    <i class="glyphicon glyphicon-plane space-out-r-sm"></i><?php _e("Start", mdh_current_plugin_name()); ?>
                </h2>
                <div class="form-group">
                    <div class="col-xs-12">
                        <p><?php _e("Add this helper in the header to display a link to the inbox and the number of unread messages.",mdh_current_plugin_name()) ?></p>
<pre>
&lt;?php
    osc_run_hook('mdh_messenger_widget'); // Includes the widget who will display : Message ({NUMBER UNREAD MESSAGE})
?&gt;
</pre>
<p><?php _e("Add this helper in item.php or user-public-profile.php to display the contact form.    ", mdh_current_plugin_name()) ?>
<pre>
&lt;?php
    osc_run_hook('mdh_messenger_contact_form'); // Include the contact form html code.
?&gt;
</pre>
                    </div>
                </div>
            </div>
            <div class="anchor" id="general"></div>
            <div class="space-in-md b-b">
                <h2 class="h4 text-info row-space-2">
                    <i class="glyphicon glyphicon-cog space-out-r-sm"></i><?php _e("General", mdh_current_plugin_name()); ?>
                </h2>

                <div class="form-group">
                    <div class="col-xs-10 col-xs-offset-2">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" <?php echo (osc_get_preference('display_usermenu_link', mdh_current_preferences_section()) ? 'checked="checked"' : '' ); ?> name="display_usermenu_link" value="1" />
                                <?php _e("Display a link in 'user_menu'", mdh_current_plugin_name()); ?>
                            </label>
                            <div class="help-block">
                                <?php _e("If checked, a link is added using 'user_menu' hook.", mdh_current_plugin_name()); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <!-- base_url -->
                    <label class="control-label col-xs-2">
                        <?php _e("Permalinks", mdh_current_plugin_name()); ?>
                    </label>
                    <div class="col-xs-10">
                        <div class="row">
                            <div class="col-xs-4">
                                <input class="form-control" name="base_url" type="text" value="<?php echo osc_get_preference("base_url", mdh_current_preferences_section()); ?>" />
                            </div>
                        </div>
                        <div class="help-block">
                            <p><?php _e("The prefix is used to create URLs for the plugin. By default, the prefix is 'messenger', therefore, the inbox URL will be 'http://yourwebsite.com/messenger/inbox'.", mdh_current_plugin_name()); ?></p>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-2"><?php _e("Moderator", mdh_current_plugin_name()); ?></label>
                    <div class="col-xs-10">
                        <!-- moderation_user -->
                        <div class="row">
                            <div class="col-xs-4">
                                <input id="fUser" type="text" class="fUser form-control" value=""  data-ajaxurl="<?php echo osc_admin_base_url(true); ?>?page=ajax&action=userajax"/>
                            </div>
                        </div>
                        <input id="fUserId" name="moderation_user" type="hidden" value="<?php echo osc_get_preference("moderation_user", mdh_current_preferences_section()); ?>" />

                            <div class="help-block">
                                <?php _e("Select a user to be able to interact with your users from the users listing (action contact on a particular user).", mdh_current_plugin_name()); ?>
                                <?php if(osc_user_id()): ?>
                                    <br />
                                    <strong>
                                        <?php
                                            printf(
                                                __("Current is '%s'", mdh_current_plugin_name()),
                                                osc_user_name()
                                            );
                                        ?>
                                        <a href="<?php echo osc_admin_base_url() . "index.php?page=users&userId=" . osc_user_id(); ?>"><?php _e("(manage user)", mdh_current_plugin_name()); ?></a>
                                    </strong>
                                <?php endif; ?>
                            </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-2"><?php _e("Contact form", mdh_current_plugin_name()); ?></label>
                    <div class="col-xs-10">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" <?php echo (mdh_get_preference('enable_message_template') ? 'checked="checked"' : '' ); ?> name="enable_message_template" value="1" />
                                <?php _e("Enable message template ", mdh_current_plugin_name()); ?>
                            </label>
                        </div>
                        <div class="help-block">
                            <?php _e("This feature display the last message send by a user when he try to contact someone avoiding copy and past.", mdh_current_plugin_name()); ?><br/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-2"><?php _e("Thread action", mdh_current_plugin_name()); ?></label>
                    <div class="col-xs-10">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" <?php echo (mdh_get_preference('enable_mark_as_unread') ? 'checked="checked"' : '' ); ?> name="enable_mark_as_unread" value="1" />
                                <?php _e("Enable mark as unread", mdh_current_plugin_name()); ?>
                            </label>
                            <div class="help-block">
                                <?php _e("Users are able to mark a conversation as unread when somebody send them a message.", mdh_current_plugin_name()); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="anchor" id="auto-messages"></div>
            <div class="space-in-md b-b">
                <h2 class="h4 text-info row-space-2">
                    <i class="glyphicon glyphicon-exclamation-sign space-out-r-sm"></i><?php _e("Auto-messages", mdh_current_plugin_name()); ?>
                </h2>
                <div class="form-group">
                    <label class="control-label col-xs-2"><?php _e("On item delete", mdh_current_plugin_name()); ?></label>
                    <div class="col-xs-10">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" <?php echo (osc_get_preference('automessage_item_deleted', mdh_current_preferences_section()) ? 'checked="checked"' : '' ); ?> name="automessage_item_deleted" value="1" />
                                <?php _e("Send an auto-message when deleting an item", mdh_current_plugin_name()); ?>
                            </label>
                        </div>
                        <div class="help-block">
                            <?php _e("Users that contacted the deleted item will get a message to notify them that it's not available anymore.", mdh_current_plugin_name()); ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-2"><?php _e("On item spam", mdh_current_plugin_name()); ?></label>
                    <div class="col-xs-10">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" <?php echo (osc_get_preference('automessage_item_spammed', mdh_current_preferences_section()) ? 'checked="checked"' : '' ); ?> name="automessage_item_spammed" value="1" />
                                <?php _e("Send an auto-message when the admin marks an item as spam", mdh_current_plugin_name()); ?>
                            </label>
                        </div>
                        <div class="help-block">
                            <?php _e("Users that contacted an item marked as spam will get a message to notify them to be careful.", mdh_current_plugin_name()); ?>
                        </div>
                        <div class="" ass="form-group">
                            <a class="btn btn-primary" href="<?php echo mdh_messenger_admin_events_url() ?>">
                                <?php _e("Customize auto-messages text", mdh_current_plugin_name()) ?>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-10 col-xs-offset-2">
                        <?php printf(__("Do not send auto-messages to threads inactive for more than %s days", mdh_current_plugin_name()),
                        '<div class="vpadder-xs width-sm d-ib"><input type="text" class="form-control" name="automessage_newer_days" value="' . osc_get_preference('automessage_newer_days', mdh_current_preferences_section()) . '" /></div>'); ?>
                        <div class="help-block">
                            <?php _e("This feature is useful not to notify users that contacted the item a very long time ago.", mdh_current_plugin_name()); ?><br />
                            <?php _e("Inactive threads means: no recent messages for X days.", mdh_current_plugin_name()); ?><br />
                            <?php _e("Leave empty to always send a message.", mdh_current_plugin_name()); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="anchor" id="links"></div>
            <div class="space-in-md b-b">
                <h2 class="h4 text-info row-space-2">
                    <i class="glyphicon glyphicon-link space-out-r-sm"></i><?php _e("Links", mdh_current_plugin_name()); ?>
                </h2>
                <div class="form-group">
                    <div class="col-xs-10 col-xs-offset-2">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" <?php echo (mdh_get_preference('enable_autolinker')) ? 'checked="checked"' : '' ; ?> name="enable_autolinker" value="1" />
                                &nbsp;<?php _e("Enable autolinker", mdh_current_plugin_name()); ?>
                            </label>
                        </div>
                        <div class="help-block">
                            <p><?php _e("Enable autolinker to replace links, emails, hashtag and @name in messages by clickable links.", mdh_current_plugin_name()); ?></p>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-2">
                        <?php _e("Open links", mdh_current_plugin_name()); ?>
                    </label>
                    <div class="col-xs-10">
                        <label class="radio-inline">
                            <input type="radio" <?php echo (mdh_get_preference('autolinker_new_window') == 'true') ? 'checked="checked"' : ''; ?> name="autolinker_new_window" value="true" />
                            &nbsp;<?php _e("in a new window", mdh_current_plugin_name()); ?>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" <?php echo (mdh_get_preference('autolinker_new_window') == 'false') ? 'checked="checked"' : ''; ?> name="autolinker_new_window" value="false" />
                                &nbsp;<?php _e("in the same window", mdh_current_plugin_name()); ?>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-2">
                        <?php _e("Prefix", mdh_current_plugin_name()); ?>
                    </label>
                    <div class="col-xs-10">
                        <label class="radio-inline">
                            <input type="radio" <?php echo (mdh_get_preference('autolinker_strip_prefix') == 'true') ? 'checked="checked"' : ''; ?> name="autolinker_strip_prefix" value="true" />
                            &nbsp;<?php _e("Hide", mdh_current_plugin_name()); ?>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" <?php echo (mdh_get_preference('autolinker_strip_prefix') == 'false') ? 'checked="checked"' : ''; ?> name="autolinker_strip_prefix" value="false" />
                            &nbsp;<?php _e("Show", mdh_current_plugin_name()); ?>
                        </label>
                        <div class="help-block">
                            <p><?php _e("Show or hide the http:// or https:// prefix", mdh_current_plugin_name()); ?></p>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-2">
                        <?php _e("Link Length", mdh_current_plugin_name()); ?>
                    </label>
                    <div class="col-xs-10">
                        <div class="row">
                            <div class="col-xs-4">
                                <input class="form-control" name="autolinker_truncate_length" type="text" value="<?php echo mdh_get_preference('autolinker_truncate_length'); ?>" />
                            </div>
                        </div>
                        <div class="help-block">
                            <p><?php _e("Truncate links to a specific length. Leave empty if you doesn't want to truncate links in messages.", mdh_current_plugin_name()); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="anchor" id="emails"></div>
            <div class="space-in-md b-b">
                <h2 class="h4 text-info row-space-2">
                    <i class="glyphicon glyphicon-send space-out-r-sm"></i><?php _e("Notifications & Reminders (e-mails)", mdh_current_plugin_name()); ?>
                </h2>
                <div class="form-group space-out-b-lg">
                    <!-- enable_notifications -->
                    <div class="col-xs-10 col-xs-offset-2">
                        <div class="checkbox">
                            <label class="control-label">
                                <input type="checkbox" <?php echo (osc_get_preference('enable_notifications', mdh_current_preferences_section()) ? 'checked="checked"' : '' ); ?> name="enable_notifications" value="1" />
                                &nbsp;<?php _e("Enable e-mails", mdh_current_plugin_name()); ?>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-2">
                        <?php _e("Formatting options", mdh_current_plugin_name()); ?>
                    </label>
                    <!-- email_excerpt_length -->
                    <div class="col-xs-10">
                        <div class="space-out-r-xs width-sm d-ib">
                            <input type="text" name="email_excerpt_length" class="form-control" value="<?php echo osc_get_preference('email_excerpt_length', mdh_current_preferences_section()); ?>" />
                        </div>
                        <?php _e("characters in e-mail excerpt.", mdh_current_plugin_name()); ?>
                    </div>
                    <div class="col-xs-10 col-xs-offset-2 checkbox">
                        <label class="form-label-checkbox">
                            <!-- email_excerpt_oneline -->
                            <input type="checkbox" <?php echo (osc_get_preference('email_excerpt_oneline', mdh_current_preferences_section()) ? 'checked="checked"' : '' ); ?> name="email_excerpt_oneline" value="1" />
                            &nbsp;<?php _e("Limit the e-mail excerpt to the first line.", mdh_current_plugin_name()); ?>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-2">
                        <?php _e("Notifications", mdh_current_plugin_name()); ?>
                    </label>
                    <div class="col-xs-10">
                        <div class="checkbox">
                            <label>
                                <!-- notify_everytime -->
                                <input type="checkbox" <?php echo (osc_get_preference('notify_everytime', mdh_current_preferences_section()) ? 'checked="checked"' : '' ); ?> name="notify_everytime" value="1" />
                                <?php _e("Send an e-mail everytime a user receive a message", mdh_current_plugin_name()); ?>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-10 col-xs-offset-2 l-h-input">
                        <!-- stop_notify_after -->
                        <?php printf(__("Stop if the user has more than %s unread message(s)", mdh_current_plugin_name()),
                        '<div class="vpadder-xs width-sm d-ib"><input type="text" class="form-control" name="stop_notify_after" value="' . osc_get_preference('stop_notify_after', mdh_current_preferences_section()) . '" /></div>'); ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-10 col-xs-offset-2">
                        <!-- reminder_every -->
                        <?php printf(__("After that, send a summary every %s new message(s)", mdh_current_plugin_name()),
                        '<div class="vpadder-xs width-sm d-ib"><input type="text" class="form-control" name="reminder_every" value="' . osc_get_preference('reminder_every', mdh_current_preferences_section()) . '" /></div>'); ?>
                        <div class="help-block">
                            <?php _e("It will send an e-mail every N unread messages.", mdh_current_plugin_name()); ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-2">
                        <?php _e("Daily reminders", mdh_current_plugin_name()); ?>
                    </label>
                    <div class="col-xs-10">
                        <div class="checkbox">
                            <label>
                                <!-- enable_reminders -->
                                <input type="checkbox" <?php echo (osc_get_preference('enable_reminders', mdh_current_preferences_section()) ? 'checked="checked"' : '' ); ?> name="enable_reminders" value="1" />
                                <?php _e("Enable reminders", mdh_current_plugin_name()); ?>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-10 col-xs-offset-2 ">
                        <!-- reminder_every -->
                        <?php printf(__("Send a reminder every %s day(s) for %s day(s)", mdh_current_plugin_name()),
                        '<div class="vpadder-xs width-sm d-ib"><input type="text" class="form-control" name="reminder_every_days" value="' . osc_get_preference('reminder_every_days', mdh_current_preferences_section()) . '" /></div>',
                        '<div class="vpadder-xs width-sm d-ib"><input type="text" class="form-control" name="stop_reminder_after" value="' . osc_get_preference('stop_reminder_after', mdh_current_preferences_section()) . '" /></div>'); ?>
                        <div class="help-block">
                            <?php _e("It will send an e-mail every X days for Y days to users with unread messages.", mdh_current_plugin_name()); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="anchor" id="status"></div>
            <div class="space-in b-b">
                <h2 class="h4 text-info row-space-2">
                    <i class="glyphicon glyphicon-bookmark space-out-r-sm"></i><?php _e("Status", mdh_current_plugin_name()); ?>
                </h2>
                <div class="form-group">
                    <div class="col-xs-10 col-xs-offset-2">
                        <div class="checkbox">
                            <label>
                                <!-- enable_status -->
                                <input type="checkbox" <?php echo (osc_get_preference('enable_status', mdh_current_preferences_section()) ? 'checked="checked"' : '' ); ?> name="enable_status" value="1" />
                                <?php _e("Enable status", mdh_current_plugin_name()); ?>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-10 col-xs-offset-2">
                        <a class="btn btn-primary" href="<?php echo mdh_messenger_admin_status_url() ?>">
                            <?php _e("Manage and customize thread status", mdh_current_plugin_name()) ?>
                        </a>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-2">
                        <?php _e("Default status", mdh_current_plugin_name()); ?>
                    </label>
                    <div class="col-xs-10">
                        <!-- default_status -->
                        <select name="default_status" class="select-box-input">
                            <option value="0">-</option>
                            <?php foreach(View::newInstance()->_get("statuses") as $s): ?>
                                <option <?php echo (osc_get_preference('default_status', mdh_current_preferences_section()) == $s->getId()) ? 'selected="selected"': ""; ?> value="<?php echo $s->getId(); ?>"><?php echo $s->getName() ?> </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-10 col-xs-offset-2">
                        <!-- status_for_owner_only -->
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" <?php echo (osc_get_preference('status_for_owner_only', mdh_current_preferences_section()) ? 'checked="checked"' : '' ); ?> name="status_for_owner_only" value="1" />
                                <?php _e("Only item owner can modify the status of a thread", mdh_current_plugin_name()); ?>
                                <div class="help-block">
                                    <?php _e("If this option is not checked anyone can modify it.", mdh_current_plugin_name()); ?>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="anchor" id="user-block"></div>
            <div class="space-in">
                <h2 class="h4 text-info row-space-2">
                    <i class="glyphicon glyphicon-minus-sign space-out-r-sm"></i><?php _e("User block", mdh_current_plugin_name()); ?>
                </h2>
                <div class="form-group">
                    <div class="col-xs-10 col-xs-offset-2">
                        <div class="checkbox">
                            <label>
                                <!-- enable_status -->
                                <input type="checkbox" <?php echo mdh_get_preference("enable_block_user")? 'checked="checked"' : ''; ?> name="enable_block_user" value="1" />
                                <?php _e("Allow users to block somebody", mdh_current_plugin_name()); ?>
                            </label>
                            <div class="help-block">
                                <?php _e("Users can't send messages to users who blocked him.", mdh_current_plugin_name()); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-10 col-xs-offset-2">
                        <div class="checkbox">
                            <label>
                                <!-- enable_status -->
                                <input type="checkbox" <?php echo mdh_get_preference("email_admin_on_block_user")? 'checked="checked"' : ''; ?> name="email_admin_on_block_user" value="1" />
                                <?php _e("Notify admin when somebody is blocked", mdh_current_plugin_name()); ?>
                            </label>
                            <div class="help-block">
                                <?php _e("Send an email to admin when somebody is blocked to manage him.", mdh_current_plugin_name()); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="space-in-md">
                <input type="submit" value="<?php echo osc_esc_html( __('Save changes') ); ?>" class="btn btn-primary btn-block" />
            </div>
            </form>
        </div>
    </div>
</div>