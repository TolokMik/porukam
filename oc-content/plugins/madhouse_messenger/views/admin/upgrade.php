
<div class="space-in-lg bg-light lter b-b">
    <h3 class="h3 row-space-1 text-info">
        Upgrade to <?php echo __get("current_version"); ?>
    </h3>
    <p class="row-space-0">
        <span class="text-danger font-bold">
            <?php _e("Important!"); ?>&nbsp;
        </span>
        <?php _e("Please wait, depending on the size of your database, it may take a few minutes to update your messenger installation to the latest version."); ?>
    </p>
</div>
<div class="space-in-lg">
    <div id="updater"
        data-toggle="plugin-updater"
        data-url="<?php echo mdh_messenger_admin_ajax_url(); ?>"
        data-end="<?php echo mdh_count_threads(); ?>"
        data-version="<?php echo __get("current_version"); ?>"
        <?php echo (Params::existParam("n")) ? 'data-chunk="' . Params::getParam("n") . '"' : ""; ?>
        <?php echo (Params::existParam("p")) ? 'data-page="' . Params::getParam("p") . '"' : ""; ?>
    >
        <div class="js-show-idle">
            <div class="alert alert-success">
                <?php _e("Nothing to update, everything's good.", mdh_current_plugin_name()); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-1">
                <button type="submit" class="btn btn-primary js-run-updater">
                    <?php _e("Update", mdh_current_plugin_name()); ?>
                </button>
            </div>
            <div class="col-xs-11">
                <div class="space-in-xs text-lg">
                    <span class="font-bold">
                        <?php _e("Progress", mdh_current_plugin_name()); ?> :
                    </span>
                    <span class="js-eta">
                        0%
                    </span>
                </div>
            </div>
        </div>

        <div class="space-in-t-md space-in-b-md">
            <div class="progress space-out-b-o">
              <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="min-width: 1em;">
                <span class="sr-only js-eta" id="js-eta">0%</span>
              </div>
            </div>
        </div>
        <div class="js-show-success space-in-b-md">
            <div class="alert alert-success">
                <p>
                    <span class="js-count-processed">X</span>&nbsp;
                    <?php _e("thread(s) were successfully updated.", mdh_current_plugin_name()); ?>&nbsp;
                    [<?php _e("Total time:"); ?>&nbsp;<span class="js-total-timer"></span>]
                </p>
            </div>
            <a class="btn btn-default font-bold" href="<?php echo mdh_messenger_admin_dashboard_url(); ?>">
                <?php echo _e("Back to Messenger dashboard", mdh_current_plugin_name()); ?>
            </a>
        </div>
        <div class="js-show-error">
            <div class="alert alert-danger">
                <?php _e("An error occured, please try again or contact us on the market.", mdh_current_plugin_name()); ?>
            </div>
        </div>
        <div class="js-show-eta space-in-b-md" style="font-family: Menlo, Monaco, monospace;">
        </div>
    </div>
</div>
