<?php

if(!defined('OC_ADMIN')):
    exit('Direct access is not allowed.');
endif;

?>
<?php require __DIR__ . "/nav.php"; ?>
<div class="container-fluid ">

    <div class="space-in">
        <h2 class="h3 text-info row-space-2 space-in-xs">
            <i class="glyphicon glyphicon-bookmark space-out-r-sm"></i>
            <?php echo osc_apply_filter("custom_listing_title", __("Create a label", mdh_current_plugin_name())); ?>
        </h2>
        <div class="alert alert-info">
            <?php _e("Labels are used by users to sort their conversations. As on gmail, you can create labels like 'favorits' for users. Therefore they will have the ability to add threads to this label and so organise their inbox.", mdh_current_plugin_name()); ?>
        </div>
        <div class="space-in bg-white row-space-3">
            <form class="form-horizontal" action="<?php echo mdh_messenger_admin_labels_add_url() ?>" method="post">
                <div class="form-group">
                    <!-- base_url -->
                    <label class="control-label col-xs-2">
                        <?php _e("Label title", mdh_current_plugin_name()) ?>
                    </label>
                    <div class="col-xs-10">
                        <div class="row">
                            <div class="col-xs-6">
                                <input class="form-control" type="text" name="title" value="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-offset-2 col-xs-6">
                        <input type="submit" value="<?php echo osc_esc_html( __('Add label') ); ?>" class="btn btn-primary" />
                    </div>
                </div>
            </form>
        </div>

        <h2 class="h3 text-info row-space-2 space-in-xs">
            <i class="glyphicon glyphicon-bookmark space-out-r-sm"></i>
            <?php echo osc_apply_filter("custom_listing_title", __("Manage labels", mdh_current_plugin_name())); ?>
        </h2>
        <form class="form-horizontal" action="<?php echo mdh_messenger_admin_labels_post_url() ?>" method="post">
            <?php while(mdh_has_thread_labels()): ?>
                <div>
                        <div class="tab-container row-space-3 clearfix">
                            <ul class="nav nav-tabs" role="tablist">
                                <?php $first = true ?>
                                <?php while (osc_has_web_enabled_locales()): ?>
                                    <li role="presentation" class="<?php echo ($first)?"active": "" ?>">
                                        <a  href="#<?php echo mdh_thread_label_id().osc_locale_code() ?>" aria-controls="settings" role="tab" data-toggle="tab">
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
                                    <h3><?php printf(__("Label: %s", mdh_current_plugin_name()), mdh_thread_label_title()) ?><?php if(!mdh_thread_label()->isSystem()): ?>
                                         -
                                        <a class="text-warning" onclick="return delete_dialog('<?php echo mdh_messenger_admin_labels_remove_url(mdh_thread_label_id()) ?>');" href="#"><?php _e("Remove this label", mdh_current_plugin_name()) ?></a>
                                    <?php endif; ?></h3>
                                </div>
                                <?php while (osc_has_web_enabled_locales()): ?>
                                    <div class="<?php echo ($first)?"active": "" ?> tab-pane fade in"  role="tabpanel" class="tab-pane active" id="<?php echo mdh_thread_label_id().osc_locale_code() ?>">
                                        <div class="form-group row-space-0">
                                            <!-- base_url -->
                                            <label class="control-label col-xs-3">
                                                <?php printf(__("Label title in %s", mdh_current_plugin_name()), osc_locale_name()) ?>&nbsp;
                                            </label>
                                            <div class="col-xs-9">
                                                <div class="row">
                                                    <div class="col-xs-6">
                                                        <input class="form-control" type="text" name="labels[<?php echo mdh_thread_label_id() ?>][<?php echo osc_locale_code() ?>]" value="<?php echo mdh_thread_label()->getTitle(osc_locale_code()) ?>">
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
            <div class="">
                <input type="submit" value="<?php echo osc_esc_html( __('Update all labels') ); ?>" class="btn btn-primary" />
            </div>
        </form>
    </div>
</div>
<div id="dialog-delete">
    <div class="form-horizontal">
        <div class="bg-white">
            <div class="row-space-3">
                <?php _e("Are you sure you want to delete this label for all users? This can't be undone.", mdh_current_plugin_name()); ?>
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