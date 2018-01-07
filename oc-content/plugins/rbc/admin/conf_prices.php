<?php if ( (!defined('ABS_PATH')) ) exit('ABS_PATH is not loaded. Direct access is not allowed.'); ?>
<?php if ( !OC_ADMIN ) exit('User access is not allowed.'); ?>
<?php

    $mp = ModelRbc::newInstance();

    if(Params::getParam('plugin_action') == 'done') {
      
        $pr_prices  = Params::getParam("pr_prices");
        $top_prices = Params::getParam("top_prices");
        $color_prices = Params::getParam("color_prices");
		$pub_prices = Params::getParam("pub_prices");

        foreach($pr_prices as $k => $v) {
            $mp->insertPrice_rbc($k,  $v==''?NULL:$v, $top_prices[$k]==''?NULL:$top_prices[$k], $color_prices[$k]==''?NULL:$color_prices[$k], $pub_prices[$k]==''?NULL:$pub_prices[$k]);
        }
        // HACK : This will make possible use of the flash messages ;)
        ob_get_clean();
        osc_add_flash_ok_message(__('Congratulations, the plugin is now configured', 'rbc'), 'admin');
        osc_redirect_to( osc_admin_base_url(true) . '?page=plugins&action=renderplugin&route=rbc-admin-conf#category');
    }

    $categories = Category::newInstance()->toTreeAll();
    $prices     = ModelRbc::newInstance()->getCategoriesPrices_rbc();
    $cat_prices = array();
    foreach($prices as $p) {
        $cat_prices[$p['fk_i_category_id']]['f_premium_cost'] = $p['f_premium_cost'];
        $cat_prices[$p['fk_i_category_id']]['f_top_cost'] = $p['f_top_cost'];
		$cat_prices[$p['fk_i_category_id']]['f_color_cost'] = $p['f_color_cost'];
		$cat_prices[$p['fk_i_category_id']]['f_publish_cost'] = $p['f_publish_cost'];
       
    }

    function drawCategories($categories, $depth = 0, $cat_prices) {
        foreach($categories as $c) { ?>
            <tr>
                <td>
                    <?php for($d=0;$d<$depth;$d++) { echo "&nbsp;&nbsp;"; }; echo $c['s_name']; ?>
                </td>

                <td>
                    <input style="width:150px;text-align:right;" type="text" name="pr_prices[<?php echo $c['pk_i_id']?>]" id="pr_prices[<?php echo $c['pk_i_id']?>]" value="<?php echo isset($cat_prices[$c['pk_i_id']]) ? $cat_prices[$c['pk_i_id']]['f_premium_cost'] : ''; ?>" />
                </td>

                <td>
                    <input style="width:150px;text-align:right;" type="text" name="top_prices[<?php echo $c['pk_i_id']?>]" id="top_prices[<?php echo $c['pk_i_id']?>]" value="<?php echo isset($cat_prices[$c['pk_i_id']]) ? $cat_prices[$c['pk_i_id']]['f_top_cost'] : ''; ?>" />
                </td>
<td>
                    <input style="width:150px;text-align:right;" type="text" name="color_prices[<?php echo $c['pk_i_id']?>]" id="color_prices[<?php echo $c['pk_i_id']?>]" value="<?php echo isset($cat_prices[$c['pk_i_id']]) ? $cat_prices[$c['pk_i_id']]['f_color_cost'] : ''; ?>" />
                </td>
				<td>
                    <input style="width:150px;text-align:right;" type="text" name="pub_prices[<?php echo $c['pk_i_id']?>]" id="pub_prices[<?php echo $c['pk_i_id']?>]" value="<?php echo isset($cat_prices[$c['pk_i_id']]) ? $cat_prices[$c['pk_i_id']]['f_publish_cost'] : ''; ?>" />
                </td>
                
            </tr>
        <?php drawCategories($c['categories'], $depth+1, $cat_prices);
        }
    };


?>
    <div id="category" style="padding: 20px;">
	<h2 class="render-title"><b><i class="fa fa-money"></i> <?php _e('Category prices', 'rbc'); ?><b></h2>
        <div style="float: left; width: 100%;">
            <fieldset>
                <div style="float: left; width: 100%;">
                    <form name="rbc_form" id="rbc_form" action="<?php echo osc_admin_base_url(true);?>" method="POST" enctype="multipart/form-data" >
                        <input type="hidden" name="page" value="plugins" />
                        <input type="hidden" name="action" value="renderplugin" />
                        <input type="hidden" name="route" value="rbc-admin-prices" />
                        <input type="hidden" name="plugin_action" value="done" />
                        <table border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="width:300px;"><?php _e('Category Name', 'rbc'); ?></td>
                                <td style="width:175px;"><?php echo sprintf(__('Premium fee (%s)', 'rbc'), osc_get_preference('currency', 'rbc')); ?></td>
                               <td style="width:175px;"><?php echo sprintf(__('Move to top(%s)', 'rbc'), osc_get_preference('currency', 'rbc')); ?></td>
							   <td style="width:175px;"><?php echo sprintf(__('Highlighting (%s)', 'rbc'), osc_get_preference('currency', 'rbc')); ?></td>
							   <td style="width:175px;"><?php echo sprintf(__('Publish fee (%s)', 'rbc'), osc_get_preference('currency', 'rbc')); ?></td>
                            </tr>
                            <?php drawCategories($categories, 0, $cat_prices); ?>
                        </table>
                        <button type="submit" style="float: right;" class="btn btn-submit"><?php _e('Update', 'rbc'); ?></button>
                    </form>
                </div>
            </fieldset>
        </div></div>
	<div class="form-row">
	 <address class="osclasspro_address">
	<span>&copy;<?php echo date('Y') ?> <a target="_blank" title="osclass-pro.ru" href="https://osclass-pro.ru/">osclass-pro.ru</a>. All rights reserved.</span>
  </p>
  </address></div>
 <?php echo '<script src="' . osc_base_url() . 'oc-content/plugins/rbc/admin/js/jquery.admin.js"></script>'; ?>
