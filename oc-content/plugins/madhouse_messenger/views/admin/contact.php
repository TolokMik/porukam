<div class="bg-light dker">
    <form action="<?php echo mdh_messenger_admin_contact_url(); ?>" method="post">
        <input type="hidden" name="recipients" value="<?php echo osc_user_id(); ?>" />
        <div class="space-in-lg">
            <div class="form-group vpadder">
                <label class="form-label">
                    <?php _e("Starting a conversation with", mdh_current_plugin_name()); ?>&nbsp;:
                </label>
                <div class="form-controls">
                    <span class="l-h-input"><?php printf("%s (#%d)", osc_user_name(), osc_user_id()); ?></span>
                    <ul class="list-unstyled list-inline row-space-0">
                        <li>
                            <a href="<?php echo osc_admin_base_url(true) . '?page=users&action=edit&amp;id=' . osc_user_id(); ?>" target="_blank">
                                <?php _e('Edit') ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo osc_user_public_profile_url(); ?>" target="_blank">
                                <?php _e("Public profile", mdh_current_plugin_name()); ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label space-in">
                    <?php _e("Message", mdh_current_plugin_name()); ?>
                </label>
                <div class="form-controls clearfix">
                    <textarea class="col-xs-12 space-in" name="message" rows="10" placeholder="<?php _e("Write something...", mdh_current_plugin_name())?>"></textarea>
                </div>
            </div>
            <div class="hpadder-lg">
                <input type="submit" id="save_changes" value="<?php echo _e("Send message", mdh_current_plugin_name()); ?>" class="btn btn-primary btn-block" />
            </div>
        </div>
    </form>
</div>