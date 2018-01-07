<?php

/*
 * ========================================================================================
 *
 * TO CUSTOMIZE
 *
 * COPY THIS FILE TO YOUR THEME IN
 * oc-content/themes/{your_theme_name}/plugins/madhouse_messenger/contact-form.php
 *
 * FOR TRANSLATION, RENAME ALL "madhouse_messenger" in this file by "your_theme_name"
 * Then update your po and mo file of your theme
 *
 * ========================================================================================
 */

?>

<form action="<?php echo mdh_messenger_send_url(); ?>" method="post" name="contact_form">
    <?php if(osc_is_ad_page() || osc_is_item_contact_page()): ?>
        <?php ContactForm::primary_input_hidden(); ?>
        <input type="hidden" name="recipients[]" value="<?php echo osc_item_user_id(); ?>" />
    <?php else: ?>
        <input type="hidden" name="recipients[]" value="<?php echo osc_user_id(); ?>" />
    <?php endif; ?>

    <div class="control-group">
        <div class="controls">
            <?php if(mdh_messenger_is_contacted()): ?>
                <div class="wrapper-flash">
                    <div class="flashmessage flashmessage-info">
                        <?php _e("Already contacted : ", 'madhouse_messenger'); ?><a style="color: #fff;" href="<?php echo mdh_thread_url(); ?>"><?php _e('see thread', 'madhouse_messenger') ; ?></a>.
                    </div>
                </div>
            <?php endif; ?>
            <textarea name="message" rows="5" placeholder="<?php _e("Write something...", 'madhouse_messenger')?>" id="quickad"><?php echo mdh_messenger_message_template(); ?></textarea>
        </div>
    </div>
    <div class="control-group">
    	<div class="controls">
    		<button type="submit">
                <?php _e('Send message', 'madhouse_messenger') ; ?>
            </button>
    	</div>
    </div>
</form>