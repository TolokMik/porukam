<?php

if(!defined('OC_ADMIN')):
    exit('Direct access is not allowed.');
endif;

?>
<?php require __DIR__ . "/nav.php"; ?>
<div class="container-fluid space-in">
    <h2 class="h3 text-info row-space-2 space-in-xs">
        <i class="glyphicon glyphicon-bookmark space-out-r-sm"></i>
        <?php echo osc_apply_filter("custom_listing_title", __("Create a status", mdh_current_plugin_name())); ?>
    </h2>
    <div class="alert alert-info">
        <?php _e("Status can be used by users to mark a conversation. It is displayed for everybody in the thread. It help users to organize their threads.", mdh_current_plugin_name()); ?>
    </div>
    <div class="space-in bg-white row-space-3">
        <form class="form-horizontal" action="<?php echo mdh_messenger_admin_status_add_url() ?>" method="post">
            <div class="form-group">
                <!-- base_url -->
                <label class="control-label col-xs-2">
                    <?php _e("Title", mdh_current_plugin_name()) ?>
                </label>
                <div class="col-xs-10">
                    <div class="row">
                        <div class="col-xs-6">
                            <input class="form-control" type="text" name="title" value="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <!-- base_url -->
                <label class="control-label col-xs-2">
                    <?php _e("Label text", mdh_current_plugin_name()) ?>
                </label>
                <div class="col-xs-10">
                    <div class="row">
                        <div class="col-xs-6">
                            <textarea rows="4" class="form-control" name="text"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-offset-2 col-xs-6">
                    <input type="submit" value="<?php echo osc_esc_html( __('Add status') ); ?>" class="btn btn-primary" />
                </div>
            </div>
        </form>
    </div>
    <div class="">
        <h2 class="h3 text-info row-space-2 space-in-xs">
            <i class="glyphicon glyphicon-bookmark space-out-r-sm"></i>
            <?php echo osc_apply_filter("custom_listing_title", __("Manage status", mdh_current_plugin_name())); ?>
        </h2>
        <form class="form-horizontal" action="<?php echo mdh_messenger_admin_status_post_url() ?>" method="post">
            <?php if(true): ?>
            <?php while(mdh_has_status()): ?>
                <div>
                    <div class="tab-container row-space-3 clearfix">
                        <ul class="nav nav-tabs" role="tablist">
                            <?php $first = true ?>
                            <?php while (osc_has_web_enabled_locales()): ?>
                                <li role="presentation" class="<?php echo ($first)?"active": "" ?>">
                                    <a  href="#<?php echo mdh_status()->getId().osc_locale_code() ?>" aria-controls="settings" role="tab" data-toggle="tab">
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
                                <h3 class="text-bold"><?php printf(__("Status: %s", mdh_current_plugin_name()), mdh_status()->getName()); ?>
                                 -
                                        <a class="text-warning" onclick="return delete_dialog('<?php echo mdh_messenger_admin_status_remove_url(mdh_status()->getId()) ?>');" href="#"><?php _e("Remove this status", mdh_current_plugin_name()) ?></a>
                                </h3>
                            </div>
                            <?php while (osc_has_web_enabled_locales()): ?>
                                <div class="<?php echo ($first)?"active": "" ?> tab-pane fade in"  role="tabpanel" class="tab-pane active" id="<?php echo mdh_status()->getId().osc_locale_code() ?>">
                                    <div class="form-group">
                                        <!-- base_url -->
                                        <label class="control-label col-xs-3">
                                            <?php printf(__("Title in %s", mdh_current_plugin_name()), osc_locale_name()) ?>&nbsp;
                                        </label>
                                        <div class="col-xs-9">
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <input class="form-control" type="text" name="status[<?php echo mdh_status()->getId() ?>][<?php echo osc_locale_code() ?>][title]" value="<?php echo mdh_status()->getTitle(osc_locale_code()) ?>">
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
                                                    <textarea rows="7" class="form-control" name="status[<?php echo mdh_status()->getId() ?>][<?php echo osc_locale_code() ?>][text]" ><?php echo mdh_status()->getText(osc_locale_code()) ?></textarea>
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
            <?php endwhile; ?>
            <?php endif; ?>
            <div class="space-in-md">
                <input type="submit" value="<?php echo osc_esc_html( __('Save titles') ); ?>" class="btn btn-primary btn-block" />
            </div>
        </form>
    </div>
</div>
<div id="dialog-delete">
    <div class="form-horizontal">
        <div class="bg-white">
            <div class="row-space-3">
                <?php _e("Are you sure you want to delete this status? This can't be undone.", mdh_current_plugin_name()); ?>
            </div>
        </div>
        <div>
            <div class="pull-left">
                <a class="btn btn-danger" href="javascript:void(0);" onclick="$('#dialog-delete').dialog('close');"><?php _e('Cancel', mdh_current_plugin_name()); ?></a>
            </div>
            <div class="pull-right">
                <a id="js-delete-link" class="btn btn-default" href="#" ><?php _e('Delete', mdh_current_plugin_name()); ?></a>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function delete_dialog(href) {
            $("#dialog-delete").dialog('open');
            $("#js-delete-link").attr("href", href)
            return false;
        }
    $(document).ready(function(){
        // dialog delete
        $("#dialog-delete").dialog({
            autoOpen: false,
            modal: true,
            title: '<?php echo osc_esc_js( __('Delete') ); ?>'
        });

        // dialog delete function
    });
</script>