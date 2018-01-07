<?php

if(!defined('OC_ADMIN')):
    exit('Direct access is not allowed.');
endif;

?>
<?php require __DIR__ . "/nav.php"; ?>
<div class="container-fluid messages">

    <div class="space-in">
        <h2 class="h3 text-info row-space-2 space-in-xs">
            <i class="glyphicon glyphicon-exclamation-sign space-out-r-sm"></i>
            <?php echo osc_apply_filter("custom_listing_title", __("Auto-messages", mdh_current_plugin_name())); ?>
        </h2>
        <div class="alert alert-info">
            <?php _e("Auto-messages are messages automatically send when you're doing actions on osclass. Ie: Change the satus of a conversation, mark as spam an item or delete an item.", mdh_current_plugin_name()); ?>
        </div>
        <form class="form-horizontal" action="<?php echo mdh_messenger_admin_events_post_url() ?>" method="post">
            <?php if(true): ?>
            <?php while(mdh_has_thread_events()): ?>
                <?php if(mdh_thread_event()->getName() != "contact_details_sent"): ?>
                <div>
                    <div class="tab-container row-space-3 clearfix">
                        <ul class="nav nav-tabs" role="tablist">
                            <?php $first = true ?>
                            <?php while (osc_has_web_enabled_locales()): ?>
                                <li role="presentation" class="<?php echo ($first)?"active": "" ?>">
                                    <a  href="#<?php echo mdh_thread_event()->getId().osc_locale_code() ?>" aria-controls="settings" role="tab" data-toggle="tab">
                                        <?php echo osc_locale_name() ?>
                                    </a>
                                </li>
                                <?php $first = false ?>
                            <?php endwhile; ?>
                            <?php osc_goto_first_locale() ?>
                        </ul>


                        <div class="tab-content clearfix">
                            <?php $first = true ?>
                            <div class="row-space-1">
                                <h4 class="text-bold"><?php printf(__("Event: %s", mdh_current_plugin_name()), mdh_thread_event()->getName()); ?></h4>
                            </div>
                            <?php while (osc_has_web_enabled_locales()): ?>
                                <div class="<?php echo ($first)?"active": "" ?> tab-pane fade in"  role="tabpanel" class="tab-pane active" id="<?php echo mdh_thread_event()->getId().osc_locale_code() ?>">
                                    <div class="form-group">
                                        <!-- base_url -->
                                        <label class="control-label col-xs-3">
                                            <?php printf(__("Excerpt in %s", mdh_current_plugin_name()), osc_locale_name()) ?>&nbsp;
                                        </label>
                                        <div class="col-xs-9">
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <input class="form-control" type="text" name="events[<?php echo mdh_thread_event()->getId() ?>][<?php echo osc_locale_code() ?>][excerpt]" value="<?php echo mdh_thread_event()->getExcerpt(osc_locale_code()) ?>">
                                                    <span class="help-block">
                                                        <?php _e("Excerpt is displayed on inbox when this event is the last message received. If empty the text is displayed.", mdh_current_plugin_name()) ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <!-- base_url -->
                                        <label class="control-label col-xs-3">
                                            <?php printf(__("Text in %s", mdh_current_plugin_name()), osc_locale_name()) ?>&nbsp;
                                        </label>
                                        <div class="col-xs-9">
                                            <div class="row">
                                                <div class="col-xs-10">
                                                    <textarea rows="8" class="form-control" name="events[<?php echo mdh_thread_event()->getId() ?>][<?php echo osc_locale_code() ?>][text]" ><?php echo mdh_thread_event()->getText(osc_locale_code()) ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php $first = false ?>
                            <?php endwhile; ?>
                            <?php osc_goto_first_locale() ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            <?php endwhile; ?>
            <?php endif; ?>
            <div class="space-in-md">
                <input type="submit" value="<?php echo osc_esc_html( __('Save titles') ); ?>" class="btn btn-primary btn-block" />
            </div>
        </form>
    </div>
</div>