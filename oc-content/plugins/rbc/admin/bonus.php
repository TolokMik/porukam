<?php if ( (!defined('ABS_PATH')) ) exit('ABS_PATH is not loaded. Direct access is not allowed.'); ?>
<?php if ( !OC_ADMIN ) exit('User access is not allowed.'); ?>

<?php
/*
 * Copyright 2015 osclass-pro.com
 *
 * You shall not distribute this plugin and any its files (except third-party libraries) to third parties.
 * Rental, leasing, sale and any other form of distribution are not allowed and are strictly forbidden.
 */

    $mp = ModelRbc::newInstance();

    if(Params::getParam('plugin_action') == 'done') {
        $useremail = Params::getParam("user_email");
		$userid = $mp->getUser_rbc($useremail);
		$hashTotal = Params::getParam("bonus");
        $concept = sprintf(__('Add bonus to user (%s)', 'rbc'), $userid);
		$itemId = "0";
		$pro2co = "Bonus";
		$ist1 = "Bonus";
		$currency = osc_get_preference('currency', 'rbc');
		
        $mp->addWallet_rbc($userid, $hashTotal);
        $mp->saveLog_rbc($concept, $currency, $hashTotal, $userid, $itemId, $ist1, $pro2co); 		
        // HACK : This will make possible use of the flash messages ;)
        ob_get_clean();
        osc_add_flash_ok_message(__('Congratulations, the Bonus is now added', 'rbc'), 'admin');
		osc_redirect_to( osc_admin_base_url(true) . '?page=plugins&action=renderplugin&route=rbc-admin-conf#bonus');
    }
?>
    <div id="bonus" style="padding: 20px;">
	<h2 class="render-title"><b><i class="fa fa-hand-o-down"></i> <?php _e('Add Bonus', 'rbc'); ?><b></h2>
            <fieldset>
                <div class="form-horizontal">
                    <form name="rbc_form" id="bonus" action="<?php echo osc_admin_base_url(true);?>" method="POST" enctype="multipart/form-data" >
                        <input type="hidden" name="page" value="plugins" />
                        <input type="hidden" name="action" value="renderplugin" />
                        <input type="hidden" name="route" value="rbc-admin-bonus" />
                        <input type="hidden" name="plugin_action" value="done" />
                      <div class="form-row">
                        <div class="form-label"><i class="fa fa-user"></i> <?php _e('User e-mail', 'rbc'); ?></div>
                        <div class="form-controls">
                            <input type="text" class="xlarge" name="user_email" value="" />
                        </div>
                    </div>
					<div class="form-row">
                        <div class="form-label"><i class="fa fa-money"></i> <?php _e('Add Bonus', 'rbc'); ?></div>
                        <div class="form-controls">
                            <input type="text" class="xlarge" name="bonus" value="" />
                        </div>
                    </div>
                        <button type="submit" style="float: right;" class="btn btn-submit"><?php _e('Add', 'rbc'); ?></button>
                    </form>
                </div>
            </fieldset>

    </div>
	<div class="form-row">
	 <address class="osclasspro_address">
	<span>&copy;<?php echo date('Y') ?> <a target="_blank" title="osclass-pro.ru" href="https://osclass-pro.ru/">osclass-pro.ru</a>. All rights reserved.</span>
  </p>
  </address></div>
 <?php echo '<script src="' . osc_base_url() . 'oc-content/plugins/rbc/admin/js/jquery.admin.js"></script>'; ?>