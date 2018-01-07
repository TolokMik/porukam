<?php

if(!defined('OC_ADMIN')):
    exit('Direct access is not allowed.');
endif;

?>
<?php require __DIR__ . "/nav.php"; ?>
<div class="dashboard bg-light">
    <div class="container-fluid">
        <?php if(! mdh_get_preference("version")): ?>
            <div class="space-in">
                <h2 class="h4 space-in-sm row-space-2">
                    <?php _e("Upgrade", mdh_current_plugin_name()); ?>
                </h2>
                <div class="alert alert-warning text-center">
                    <?php _e("Your messenger plugin needs to be upgraded.", mdh_current_plugin_name()); ?>&nbsp;
                    <a class="btn btn-info" href="<?php echo mdh_messenger_admin_upgrade_url(); ?>">
                        <?php _e("Upgrade now!", mdh_current_plugin_name()); ?>
                    </a>
                </div>
            </div>
        <?php endif; ?>
        <div class="space-in">
            <div class="row">
                <div class="col-xs-4">
                    <div class="panel bg-light">
                        <div class="lter panel-heading space-in-md">
                            <h3 class="h4 row-space-0"><?php _e("Daily", mdh_current_plugin_name()); ?></h3>
                        </div>
                        <div class="panel-body bg-info space-in-md lt">
                            <div class="">
                                <h4 class="h5 row-space-3"><?php _e("Today (so far)", mdh_current_plugin_name()); ?>&nbsp;:</h4>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <span class="h1 font-bold text-white row-space-0">
                                            <?php echo mdh_messenger_today_threads_count(); ?>
                                        </span>
                                        <span class="text-muted">
                                            <?php _e("threads", mdh_current_plugin_name()); ?>
                                        </span>
                                    </div>
                                    <div class="col-xs-6">
                                        <span class="h1 font-bold text-white row-space-0">
                                            <?php echo mdh_messenger_today_messages_count(); ?>
                                        </span>
                                        <span class="text-muted">
                                            <?php _e("messages", mdh_current_plugin_name()); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body bg-info dk space-in-md">
                            <div class="">
                                <h4 class="h5 row-space-3"><?php _e("Yesterday", mdh_current_plugin_name()); ?>&nbsp;:</h4>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <span class="h1 font-bold text-white row-space-0">
                                            <?php echo mdh_messenger_yesterday_threads_count(); ?>
                                        </span>
                                        <span class="text-muted">
                                            <?php _e("threads", mdh_current_plugin_name()); ?>
                                        </span>
                                    </div>
                                    <div class="col-xs-6">
                                        <span class="h1 font-bold text-white row-space-0">
                                            <?php echo mdh_messenger_yesterday_messages_count(); ?>
                                        </span>
                                        <span class="text-muted">
                                            <?php _e("messages", mdh_current_plugin_name()); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="panel bg-light">
                        <div class="lt panel-heading space-in-md">
                            <h3 class="h4 row-space-0"><?php _e("Weekly", mdh_current_plugin_name()); ?></h3>
                        </div>
                        <div class="panel-body space-in-md">
                            <div class="">
                                <h4 class="h5 row-space-3"><?php _e("This week (so far)", mdh_current_plugin_name()); ?>&nbsp;:</h4>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <span class="h1 font-bold row-space-0">
                                            <?php echo mdh_messenger_this_week_threads_count(); ?>
                                        </span>
                                        <span class="text-muted">
                                            <?php _e("threads", mdh_current_plugin_name()); ?>
                                        </span>
                                    </div>
                                    <div class="col-xs-6">
                                        <span class="h1 font-bold row-space-0">
                                            <?php echo mdh_messenger_this_week_messages_count(); ?>
                                        </span>
                                        <span class="text-muted">
                                            <?php _e("messages", mdh_current_plugin_name()); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body space-in-md dk">
                            <div class="">
                                <h4 class="h5 row-space-3"><?php _e("Last week", mdh_current_plugin_name()); ?>&nbsp;:</h4>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <span class="h1 font-bold row-space-0">
                                            <?php echo mdh_messenger_last_week_threads_count(); ?>
                                        </span>
                                        <span class="text-muted">
                                            <?php _e("threads", mdh_current_plugin_name()); ?>
                                        </span>
                                    </div>
                                    <div class="col-xs-6">
                                        <span class="h1 font-bold row-space-0">
                                            <?php echo mdh_messenger_last_week_messages_count(); ?>
                                        </span>
                                        <span class="text-muted">
                                            <?php _e("messages", mdh_current_plugin_name()); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="panel bg-light">
                        <div class="lt panel-heading space-in-md">
                            <h3 class="h4 row-space-0"><?php _e("Monthly", mdh_current_plugin_name()); ?></h3>
                        </div>
                        <div class="panel-body space-in-md">
                            <div class="">
                                <h4 class="h5 row-space-3"><?php _e("This month (so far)", mdh_current_plugin_name()); ?>&nbsp;:</h4>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <span class="h1 font-bold row-space-0">
                                            <?php echo mdh_messenger_this_month_threads_count(); ?>
                                        </span>
                                        <span class="text-muted">
                                            <?php _e("threads", mdh_current_plugin_name()); ?>
                                        </span>
                                    </div>
                                    <div class="col-xs-6">
                                        <span class="h1 font-bold row-space-0">
                                            <?php echo mdh_messenger_this_month_messages_count(); ?>
                                        </span>
                                        <span class="text-muted">
                                            <?php _e("messages", mdh_current_plugin_name()); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body space-in-md dk">
                            <div class="">
                                <h4 class="h5 row-space-3"><?php _e("Last month", mdh_current_plugin_name()); ?>&nbsp;:</h4>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <span class="h1 font-bold row-space-0">
                                            <?php echo mdh_messenger_last_month_threads_count(); ?>
                                        </span>
                                        <span class="text-muted">
                                            <?php _e("threads", mdh_current_plugin_name()); ?>
                                        </span>
                                    </div>
                                    <div class="col-xs-6">
                                        <span class="h1 font-bold row-space-0">
                                            <?php echo mdh_messenger_last_month_messages_count(); ?>
                                        </span>
                                        <span class="text-muted">
                                            <?php _e("messages", mdh_current_plugin_name()); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="bg-light">
                        <div class="panel-heading dker space-in-md b-0">
                            <h3 class="h4 row-space-0"><?php _e("Global", mdh_current_plugin_name()); ?></h3>
                        </div>
                        <div class="panel-body dk space-in-md">
                            <h4 class="h5 row-space-3"><?php _e("Since the beginning", mdh_current_plugin_name()); ?>&nbsp;:</h4>
                            <div class="row">
                                <div class="col-xs-6">
                                    <span class="h1 font-bold text-info row-space-0">
                                        <?php echo mdh_messenger_threads_count(); ?>
                                    </span>
                                    <span class="text-muted">
                                        <?php _e("threads", mdh_current_plugin_name()); ?>
                                    </span>
                                </div>
                                <div class="col-xs-6">
                                    <span class="h1 font-bold text-info row-space-0">
                                        <?php echo mdh_messenger_messages_count(); ?>
                                    </span>
                                    <span class="text-muted">
                                        <?php _e("messages", mdh_current_plugin_name()); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>