<?php
		   /*
 * Copyright 2015 osclass-pro.com, osclass-pro.ru
 *
 * You shall not distribute this plugin and any its files (except third-party libraries) to third parties.
 * Rental, leasing, sale and any other form of distribution are not allowed and are strictly forbidden.
 */

    if(Params::getParam('plugin_action')=='done') {
        osc_set_preference('default_premium_cost', Params::getParam("default_premium_cost") ? Params::getParam("default_premium_cost") : '1.0', 'rbc', 'STRING');
        osc_set_preference('default_top_cost', Params::getParam("default_top_cost") ? Params::getParam("default_top_cost") : '1.0', 'rbc', 'STRING');
        osc_set_preference('default_color_cost', Params::getParam("default_color_cost") ? Params::getParam("default_color_cost") : '1.0', 'rbc', 'STRING');
		osc_set_preference('allow_premium', Params::getParam("allow_premium") ? Params::getParam("allow_premium") : '0', 'rbc', 'BOOLEAN');
		osc_set_preference('allow_move', Params::getParam("allow_move") ? Params::getParam("allow_move") : '0', 'rbc', 'BOOLEAN');
		osc_set_preference('allow_high', Params::getParam("allow_high") ? Params::getParam("allow_high") : '0', 'rbc', 'BOOLEAN');
		osc_set_preference('allow_after', Params::getParam("allow_after") ? Params::getParam("allow_after") : '0', 'rbc', 'BOOLEAN');
		osc_set_preference('default_publish_cost', Params::getParam("default_publish_cost") ? Params::getParam("default_publish_cost") : '1.0', 'rbc', 'STRING');
        osc_set_preference('pay_per_post', Params::getParam("pay_per_post") ? Params::getParam("pay_per_post") : '0', 'rbc', 'BOOLEAN');
        osc_set_preference('premium_days', Params::getParam("premium_days") ? Params::getParam("premium_days") : '7', 'rbc', 'INTEGER');
		osc_set_preference('color_days', Params::getParam("color_days") ? Params::getParam("color_days") : '7', 'rbc', 'INTEGER');
		osc_set_preference('currency', Params::getParam("currency") ? Params::getParam("currency") : 'UAH', 'rbc', 'STRING');
        osc_set_preference('but_premium', Params::getParam("but_premium") ? Params::getParam("but_premium") : '0', 'rbc', 'BOOLEAN');
		osc_set_preference('but_top', Params::getParam("but_top") ? Params::getParam("but_top") : '0', 'rbc', 'BOOLEAN');
		osc_set_preference('but_high', Params::getParam("but_high") ? Params::getParam("but_high") : '0', 'rbc', 'BOOLEAN');
        osc_set_preference('mrhlogin', Params::getParam("mrhlogin") ? Params::getParam("mrhlogin") : '', 'rbc', 'STRING');
        osc_set_preference('mrhpass1', Params::getParam("mrhpass1") ? Params::getParam("mrhpass1") : '', 'rbc', 'STRING');
        osc_set_preference('mrhpass2', Params::getParam("mrhpass2") ? Params::getParam("mrhpass2") : '', 'rbc', 'STRING');
		osc_set_preference('pack_price_1', Params::getParam("pack_price_1"), 'rbc', 'STRING');
        osc_set_preference('pack_price_2', Params::getParam("pack_price_2"), 'rbc', 'STRING');
        osc_set_preference('pack_price_3', Params::getParam("pack_price_3"), 'rbc', 'STRING');

        ob_get_clean();
        osc_add_flash_ok_message(__('Congratulations, the plugin is now configured', 'rbc'), 'admin');
        osc_redirect_to(osc_route_admin_url('rbc-admin-conf'));
    }
?>
<?php if ( (!defined('ABS_PATH')) ) exit('ABS_PATH is not loaded. Direct access is not allowed.'); ?>
<?php if ( !OC_ADMIN ) exit('User access is not allowed.'); ?>
<link rel="stylesheet" href="<?php echo osc_base_url();?>oc-content/plugins/rbc/admin/css/admin.css">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<div class="credit-osclasspro"> <a href="https://osclass-pro.ru/" target="_blank" class="pro_logo"><img src="<?php echo osc_base_url();?>oc-content/plugins/rbc/admin/img/logo.png" alt="Premium themes and plugins" title="Premium themes and plugins" /> </a>
  <div class="follow">
    <ul>
      <li><?php _e('Follow us', 'rbc'); ?><i class="fa fa-hand-o-right"></i></li>
      <li><a href="https://www.facebook.com/osclassproru" target="_blank" title="facebook"><img src="<?php echo osc_base_url();?>oc-content/plugins/rbc/admin/img/facebook.png" alt=""></a></li>
      <li><a href="https://twitter.com/osclass_pro_ru" target="_blank" title="twitter"><img src="<?php echo osc_base_url();?>oc-content/plugins/rbc/admin/img/twitter.png" alt=""></a></li>
      <li><a href="https://plus.google.com/112399491305671918542/posts" target="_blank" title="google plus"><img src="<?php echo osc_base_url();?>oc-content/plugins/rbc/admin/img/google.png" alt=""></a></li>
    </ul>
  </div>
</div>
<div class="clear"></div>
<div id="tabs" class="FinoTab">
<ul>
    <li><a href="#general"><?php _e('Robakassa settings', 'rbc'); ?></a></li>
	<li><a href="#category"><?php _e('Category prices', 'rbc'); ?></a></li>
	<li><a href="#log"><?php _e('Robakassa log', 'rbc'); ?></a></li>
	<li><a href="#bonus"><?php _e('Bonus', 'rbc'); ?></a></li>
	<li><a href="#help"><?php _e('Help', 'rbc'); ?></a></li>
 </ul>
    <div id="general">
        <h2 class="render-title"><?php _e('Robakassa settings', 'rbc'); ?></h2>
        <ul id="error_list"></ul>
        <form name="rbc_form" action="<?php echo osc_admin_base_url(true); ?>" method="post">
            <input type="hidden" name="page" value="plugins" />
            <input type="hidden" name="action" value="renderplugin" />
            <input type="hidden" name="route" value="rbc-admin-conf" />
            <input type="hidden" name="plugin_action" value="done" />
            <fieldset>
                <div class="form-horizontal">

                    <div class="form-row">
                        <div class="form-label"><?php _e('Premium ads', 'rbc'); ?></div>
                        <div class="form-controls">
                            <div class="form-label-checkbox">
                                <label>
                                    <input type="checkbox" <?php echo (osc_get_preference('allow_premium', 'rbc') ? 'checked="true"' : ''); ?> name="allow_premium" value="1" />
                                    <?php _e('Allow premium ads', 'rbc'); ?>
                                </label>
                            </div>
                        </div>
                    </div>
					 <div class="form-row">
                        <div class="form-label"><?php _e('Default premium cost', 'rbc'); ?></div>
                        <div class="form-controls">
                            <input type="text" class="xlarge" name="default_premium_cost" value="<?php echo osc_get_preference('default_premium_cost', 'rbc'); ?>" />
                        </div>
                    </div>
					<div class="form-row">
                        <div class="form-label"><?php _e('Premium days', 'rbc'); ?></div>
                        <div class="form-controls">
                            <input type="text" class="xlarge" name="premium_days" value="<?php echo osc_get_preference('premium_days', 'rbc'); ?>" />
                        </div>
                    </div>
					<div class="form-row">
                        <div class="form-label"><?php _e('Publish fee', 'rbc'); ?></div>
                        <div class="form-controls">
                            <div class="form-label-checkbox">
                                <label>
                                    <input type="checkbox" <?php echo (osc_get_preference('pay_per_post', 'rbc') ? 'checked="true"' : ''); ?> name="pay_per_post" value="1" />
                                    <?php _e('Pay per post ads', 'rbc'); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label"><?php _e('Default publish cost', 'rbc'); ?></div>
                        <div class="form-controls"><input type="text" class="xlarge" name="default_publish_cost" value="<?php echo osc_get_preference('default_publish_cost', 'rbc'); ?>" /></div>
                    </div>
                    <div class="form-row">
                        <div class="form-label"><?php _e('Move to Top', 'rbc'); ?></div>
                        <div class="form-controls">
                            <div class="form-label-checkbox">
                                <label>
                                    <input type="checkbox" <?php echo (osc_get_preference('allow_move', 'rbc') ? 'checked="true"' : ''); ?> name="allow_move" value="1" />
                                    <?php _e('Allow move to top', 'rbc'); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                   

                    <div class="form-row">
                        <div class="form-label"><?php _e('Default move to top', 'rbc'); ?></div>
                        <div class="form-controls">
                            <input type="text" class="xlarge" name="default_top_cost" value="<?php echo osc_get_preference('default_top_cost', 'rbc'); ?>" />
                        </div>
                    </div>
					<div class="form-row">
                        <div class="form-label"><?php _e('Highlight', 'rbc'); ?></div>
                        <div class="form-controls">
                            <div class="form-label-checkbox">
                                <label>
                                    <input type="checkbox" <?php echo (osc_get_preference('allow_high', 'rbc') ? 'checked="true"' : ''); ?> name="allow_high" value="1" />
                                    <?php _e('Allow highlighting', 'rbc'); ?>
                                </label>
                            </div>
                        </div>
                    </div>
					<div class="form-row">
                        <div class="form-label"><?php _e('Default highlighting', 'rbc'); ?></div>
                        <div class="form-controls">
                            <input type="text" class="xlarge" name="default_color_cost" value="<?php echo osc_get_preference('default_color_cost', 'rbc'); ?>" />
                        </div>
                    </div>
					<div class="form-row">
                        <div class="form-label"><?php _e('Highlighting days', 'rbc'); ?></div>
                        <div class="form-controls">
                            <input type="text" class="xlarge" name="color_days" value="<?php echo osc_get_preference('color_days', 'rbc'); ?>" />
                        </div>
                    </div>
					<p> <?php _e('Insert buttons for services in item page', 'rbc'); ?></p>
					<div class="form-row">
                        <div class="form-label"><?php _e('Item Premium Button', 'rbc'); ?></div>
                        <div class="form-controls">
                            <div class="form-label-checkbox">
                                <label>
                                    <input type="checkbox" <?php echo (osc_get_preference('but_premium', 'rbc') ? 'checked="true"' : ''); ?> name="but_premium" value="1" />
                                    <?php _e('Insert button', 'rbc'); ?>
                                </label>
                            </div>
                        </div>
                    </div>
					<div class="form-row">
                        <div class="form-label"><?php _e('Item Top Button', 'rbc'); ?></div>
                        <div class="form-controls">
                            <div class="form-label-checkbox">
                                <label>
                                    <input type="checkbox" <?php echo (osc_get_preference('but_top', 'rbc') ? 'checked="true"' : ''); ?> name="but_top" value="1" />
                                    <?php _e('Insert button', 'rbc'); ?>
                                </label>
                            </div>
                        </div>
                    </div>
					<div class="form-row">
                        <div class="form-label"><?php _e('Item Highlited Button', 'rbc'); ?></div>
                        <div class="form-controls">
                            <div class="form-label-checkbox">
                                <label>
                                    <input type="checkbox" <?php echo (osc_get_preference('but_high', 'rbc') ? 'checked="true"' : ''); ?> name="but_high" value="1" />
                                    <?php _e('Insert button', 'rbc'); ?>
                                </label>
                            </div>
                        </div>
                    </div>
					<p> <?php _e('After the published ad redirect to page immediately offered to pay Premium and Highlighting', 'rbc'); ?></p>
					<div class="form-row">
                        <div class="form-label"><?php _e('After publish', 'rbc'); ?></div>
                        <div class="form-controls">
                            <div class="form-label-checkbox">
                                <label>
                                    <input type="checkbox" <?php echo (osc_get_preference('allow_after', 'rbc') ? 'checked="true"' : ''); ?> name="allow_after" value="1" />
                                    <?php _e('Allow after publish', 'rbc'); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                       <p> <?php _e('Allow after work only if Pay per post disable', 'rbc'); ?></p>
					 
					  <div class="form-row">
                        <div class="form-label"><?php _e('Currency', 'rbc'); ?></div>
                        <div class="form-controls">
                            <select name="currency" id="currency">
                                <option value="EUR" <?php if(osc_get_preference('currency', 'rbc')=="EUR") { echo 'selected="selected"';}; ?> >EUR</option>
                                <option value="USD" <?php if(osc_get_preference('currency', 'rbc')=="USD") { echo 'selected="selected"';}; ?> >USD</option>
                                <option value="UAH" <?php if(osc_get_preference('currency', 'rbc')=="UAH") { echo 'selected="selected"';}; ?> >UAH</option>
                              </select>
                        </div>

                    </div>
					  <div class="form-row">
                        <span class="help-box">
                            <?php _e("You could specify up to 3 'packs' that users can buy, so they don't need to pay each time they publish an ad. The credit from the pack will be stored for later uses.",'rbc'); ?>
                        </span>
                    </div>
                    <div class="form-row">
                        <div class="form-label"><?php echo sprintf(__('Price of pack #%d', 'rbc'), '1'); ?></div>
                        <div class="form-controls"><input type="text" class="xlarge" name="pack_price_1" value="<?php echo osc_get_preference('pack_price_1', 'rbc'); ?>" /></div>
                    </div>
                    <div class="form-row">
                        <div class="form-label"><?php echo sprintf(__('Price of pack #%d', 'rbc'), '2'); ?></div>
                        <div class="form-controls"><input type="text" class="xlarge" name="pack_price_2" value="<?php echo osc_get_preference('pack_price_2', 'rbc'); ?>" /></div>
                    </div>
                    <div class="form-row">
                        <div class="form-label"><?php echo sprintf(__('Price of pack #%d', 'rbc'), '3'); ?></div>
                        <div class="form-controls"><input type="text" class="xlarge" name="pack_price_3" value="<?php echo osc_get_preference('pack_price_3', 'rbc'); ?>" /></div>
                    </div>
                   <div class="form-row">
                        <span class="help-box">
                            <?php _e("Robokassa account settings.",'rbc'); ?>
                        </span>
                    </div>
                    <div class="form-row">
                        <div class="form-label"><?php _e('ID shop', 'rbc'); ?></div>
                        <div class="form-controls">
                            <input type="text" class="xlarge" name="mrhlogin" value="<?php echo osc_get_preference('mrhlogin', 'rbc'); ?>" />
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-label"><?php _e('Password1', 'rbc'); ?></div>
                        <div class="form-controls">
                            <input type="text" class="xlarge" name="mrhpass1" value="<?php echo osc_get_preference('mrhpass1', 'rbc'); ?>" />
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-label"><?php _e('Password2', 'rbc'); ?></div>
                        <div class="form-controls">
                            <input type="text" class="xlarge" name="mrhpass2" value="<?php echo osc_get_preference('mrhpass2', 'rbc'); ?>" />
                        </div>
                    </div>



                    <div class="clear"></div>
                    <div class="form-actions">
                        <input type="submit" id="save_changes" value="<?php echo osc_esc_html( __('Save changes') ); ?>" class="btn btn-submit" />
                    </div>
                </div>
            </fieldset>
        </form>
<address class="osclasspro_address">
	<span>&copy; <?php echo date('Y') ?> <a target="_blank" title="osclass-pro.ru" href="https://osclass-pro.ru/">osclass-pro.ru</a>. All rights reserved.</span>
  </p>
  </address>

<?php echo '<script src="' . osc_base_url() . 'oc-content/plugins/rbc/admin/js/jquery.admin.js"></script>'; ?>
</div>
<div id="category">
    <?php include 'conf_prices.php'; ?>
  </div>
  <div id="log">
    <?php include 'log.php'; ?>
</div>
<div id="bonus">
    <?php include 'bonus.php'; ?>
</div>
<div id="help">
    <?php include 'help.php'; ?>
  </div>   

  </div>
