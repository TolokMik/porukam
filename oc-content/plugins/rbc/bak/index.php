<?php
		   /*
 * Copyright 2015 osclass-pro.com and osclass-pro.ru
 *
 * You shall not distribute this plugin and any its files (except third-party libraries) to third parties.
 * Rental, leasing, sale and any other form of distribution are not allowed and are strictly forbidden.
 */
/*
Plugin Name: RBC
Plugin URI: https://osclass-pro.ru/
Description: Integration in Robokassa - support@osclass-pro.com
Plugin update URI: robokassa_2
Version: 3.2.1
Author: Dis
*/

require_once osc_plugins_path() . osc_plugin_folder(__FILE__) . 'functions.php';
require_once osc_plugins_path() . osc_plugin_folder(__FILE__) . 'ModelRbc.php';
require_once osc_plugins_path() . osc_plugin_folder(__FILE__) . 'robokassa/Robokassa.php';

function rbc_install()
{
    ModelRbc::newInstance()->install();
}

function rbc_uninstall()
{
    ModelRbc::newInstance()->uninstall();
}

function rbc_configure_link()
{
    osc_redirect_to(osc_route_admin_url('rbc-admin-conf'));
}

function rbc_admin_menu()
{
    osc_add_admin_submenu_divider('plugins', 'Robokassa', 'rbc_divider', 'administrator');
    osc_add_admin_submenu_page('plugins', __('Robokassa options', 'rbc'), osc_route_admin_url('rbc-admin-conf'), 'rbc_settings', 'administrator');
}
function rbc_publish($item) {
        
            // Need to pay to publish ?
            if(osc_get_preference('pay_per_post', 'rbc')==1) {
                $category_fee = ModelRbc::newInstance()->getPublishPrice_rbc($item['fk_i_category_id']);
                rbc_send_email($item, $category_fee);
                if($category_fee>0) {
                    // Catch and re-set FlashMessages
                    $mItems = new ItemActions(false);
                    $mItems->disable($item['pk_i_id']);
                    ModelRbc::newInstance()->createItem_rbc($item['pk_i_id'],0);
                    osc_redirect_to(osc_route_url('rbc-publish', array('itemId' => $item['pk_i_id'])));
                } else {
                    // PRICE IS ZERO
                    ModelRbc::newInstance()->createItem_rbc($item['pk_i_id'], 1);
                }
            } else {
                // NO NEED TO PAY PUBLISH FEE
                 if (!osc_is_admin_user_logged_in())rbc_send_email($item, 0);
                if(!osc_is_admin_user_logged_in() && osc_get_preference('allow_after', 'rbc')==1) {
          osc_redirect_to(osc_route_url('rbc-after', array('itemId' => $item['pk_i_id'])));
                }
            }
        }
    

/**
 * Create a new menu option on users' dashboards
 */
function rbc_user_menu()
{
    echo '<li class="opt_payment" ><a href="' . osc_route_url('rbc-user-menu') . '" >' . __("Listings payment status", "rbc") . '</a></li>';
	if((osc_get_preference('pack_price_1', 'rbc')!='' && osc_get_preference('pack_price_1', 'rbc')!='0') || (osc_get_preference('pack_price_2', 'rbc')!='' && osc_get_preference('pack_price_2', 'rbc')!='0') || (osc_get_preference('pack_price_3', 'rbc')!='' && osc_get_preference('pack_price_3', 'rbc')!='0')) {
            echo '<li class="opt_rbc_pack" ><a href="'.osc_route_url('rbc-user-pack').'" >'.__("Buy credit for Premium services", "rbc").'</a></li>' ;
        }

}

function rbc_item_button()
{
    $itemId = osc_item_id();
    $item = Item::newInstance()->findByPrimaryKey($itemId);
    if ($item) {

        if (!ModelRbc::newInstance()->premiumFeeIsPaid_rbc($item['pk_i_id']) && osc_get_preference('but_premium', 'rbc')==1) {
            $category_fee = ModelRbc::newInstance()->getPremiumPrice_rbc($item['fk_i_category_id']);
            if ($category_fee > 0) {
                Robokassa::button($category_fee, sprintf(__("Premium fee for item %d", "rbc"), $item['pk_i_id']), $item['pk_i_id'], osc_user_id(),1,__("Make Premium", "rbc"));

            }
        }
    }
}
function rbc_item_high_button()
{
    $itemId = osc_item_id();
    $item = Item::newInstance()->findByPrimaryKey($itemId);
    if ($item) {

        if (rbc_get_class_color(osc_item_id()) != 'colorized' && osc_get_preference('but_high', 'rbc')==1) {
            $category_fee = ModelRbc::newInstance()->getColorPrice_rbc($item['fk_i_category_id']);
            if ($category_fee > 0) {
                Robokassa::button($category_fee, sprintf(__("Highlight fee for item %d", "rbc"), $item['pk_i_id']), $item['pk_i_id'], osc_user_id(), 3, __("Highlight", "rbc"));

            }
        }
    }
}
function rbc_item_top_button()
{
    $itemId = osc_item_id();
    $item = Item::newInstance()->findByPrimaryKey($itemId);
    if ($item) {

        if (osc_get_preference('but_top', 'rbc')==1) {
            $category_fee = ModelRbc::newInstance()->getTopPrice_rbc($item['fk_i_category_id']);
            if ($category_fee > 0) {
                Robokassa::button($category_fee, sprintf(__("Move to Top for item %d", "rbc"), $item['pk_i_id']), $item['pk_i_id'], osc_user_id(), 2, __("Move to TOP", "rbc"));

            }
        }
    }
}

function rbc_premium_off($id){
    ModelRbc::newInstance()->premiumOff_rbc($id);
}

function rbc_cron() {
        ModelRbc::newInstance()->purgeExpired_rbc();
    }
	
function rbc_before_edit($item) {
        // avoid category changes once the item is paid
        if((osc_get_preference('pay_per_post', 'rbc') == '1' && ModelRbc::newInstance()->publishFeeIsPaid_rbc($item['pk_i_id']))|| (osc_get_preference('allow_premium','rbc') == '1' && ModelRbc::newInstance()->premiumFeeIsPaid_rbc($item['pk_i_id']))) {
            $cat[0] = Category::newInstance()->findByPrimaryKey($item['fk_i_category_id']);
            View::newInstance()->_exportVariableToView('categories', $cat);
        }
    }
	
function rbc_show_item($item) {
        if(osc_get_preference("pay_per_post", "rbc")=="1" && !ModelRbc::newInstance()->publishFeeIsPaid_rbc($item['pk_i_id']) ) {
            rbc_publish($item);
        };
    };
 function rbc_item_delete($itemId) {
        ModelRbc::newInstance()->deleteItem_rbc($itemId);
    }
	function rbc_update_version() {
        ModelRbc::newInstance()->versionUpdate_rbc();
    }

//function rbc_show_item($id){
//    print "hello";
//}

osc_add_route('rbc-admin-conf', 'rbc/admin/conf', 'rbc/admin/conf', osc_plugin_folder(__FILE__) . 'admin/conf.php');
osc_add_route('rbc-admin-prices', 'rbc/admin/prices', 'rbc/admin/prices', osc_plugin_folder(__FILE__) . 'admin/conf_prices.php');
osc_add_route('rbc-admin-bonus', 'rbc/admin/bonus', 'rbc/admin/bonus', osc_plugin_folder(__FILE__).'admin/bonus.php');
osc_add_route('robokassa-premium', 'robokassa/premium/([0-9]+)', 'robokassa/premium/{itemId}', osc_plugin_folder(__FILE__) . 'user/makepremium.php');
osc_add_route('robokassa-top', 'robokassa/uptop/([0-9]+)', 'robokassa/uptop/{itemId}', osc_plugin_folder(__FILE__) . 'user/uptop.php');
osc_add_route('robokassa-makecolor', 'robokassa/makecolor/([0-9]+)', 'robokassa/makecolor/{itemId}', osc_plugin_folder(__FILE__) . 'user/makecolor.php');
osc_add_route('rbc-publish', 'rbc/publish/([0-9]+)', 'rbc/publish/{itemId}', osc_plugin_folder(__FILE__).'user/payperpublish.php');
osc_add_route('rbc-after', 'rbc/after/([0-9]+)', 'rbc/after/{itemId}', osc_plugin_folder(__FILE__).'user/after.php');
osc_add_route('robokassa-reset', 'robokassa/reset/([0-9]+)', 'robokassa/reset/{itemId}', osc_plugin_folder(__FILE__) . 'user/reset.php');

osc_add_route('rbc-user-menu', 'rbc/menu', 'rbc/menu', osc_plugin_folder(__FILE__) . 'user/menu.php', true);
osc_add_route('rbc-result', 'rbc/robokassa/result', 'rbc/robokassa/result', osc_plugin_folder(__FILE__) . 'robokassa/result.php');
osc_add_route('rbc-success', 'rbc/robokassa/success', 'rbc/robokassa/success', osc_plugin_folder(__FILE__) . 'robokassa/success.php', false);
osc_add_route('rbc-user-pack', 'rbc/pack', 'rbc/pack', osc_plugin_folder(__FILE__).'user/pack.php', true);
osc_add_route('rbc-wallet', 'rbc/wallet/([^\/]+)/([^\/]+)/([^\/]+)/(.+)', 'rbc/wallet/{a}/{extra}/{desc}/{product}', osc_plugin_folder(__FILE__).'/user/wallet.php', true);

osc_register_plugin(osc_plugin_path(__FILE__), 'rbc_install');
osc_add_hook(osc_plugin_path(__FILE__) . "_configure", 'rbc_configure_link');
osc_add_hook(osc_plugin_path(__FILE__) . "_uninstall", 'rbc_uninstall');
osc_add_hook(osc_plugin_path(__FILE__)."_enable", 'rbc_update_version');


osc_add_hook('admin_menu_init', 'rbc_admin_menu');
osc_add_hook('user_menu', 'rbc_user_menu');
osc_add_hook('posted_item', 'rbc_publish', 10);
osc_add_hook('item_premium_off', 'rbc_premium_off');
osc_add_hook('item_detail', 'rbc_item_button');
osc_add_hook('item_detail', 'rbc_item_high_button');
osc_add_hook('item_detail', 'rbc_item_top_button');
osc_add_hook('cron_hourly', 'rbc_cron');
osc_add_hook('before_item_edit', 'rbc_before_edit');
osc_add_hook('show_item', 'rbc_show_item');
osc_add_hook('delete_item', 'rbc_item_delete');

if ($_SERVER['REQUEST_URI'] === '/index.php?page=custom&route=rbc-result') {
    $mrhPass2 = osc_get_preference('mrhpass2', 'rbc');
	$currency = osc_get_preference('currency', 'rbc');
    $outSumm = $_REQUEST['OutSum'];
    $invId = $_REQUEST["InvId"];
    $type = $_REQUEST["shp_product"];
	$shpBype = $_REQUEST["shp_item"];
	$userid = $_REQUEST["shp_user"];
    $crc = $_REQUEST["SignatureValue"];

    $my_crc = strtoupper(md5("$outSumm:$invId:$mrhPass2:shp_item=$shpBype:shp_product=$type:shp_user=$userid"));

    if ($my_crc != $crc) {
        echo "bad sign\n";
        exit();
    }
	if ($type == 5) {
	$ist1 = 'Robokassa';
	$pro2co = 'Wallet';
	$concept = sprintf(__('Add wallet to user (%s)', 'rbc'), $userid);
	} elseif ($type == 4){
	$ist1 = 'Robokassa';
	$pro2co = 'Publish';
	$concept = sprintf(__('Publish fee for item (%s)', 'rbc'), $shpBype);
	} elseif ($type == 3){
	$ist1 = 'Robokassa';
	$pro2co = 'Highlighted';
	$concept = sprintf(__('Highlight fee for item (%s)', 'rbc'), $shpBype);
	}elseif ($type == 2){
	$ist1 = 'Robokassa';
	$pro2co = 'Top';
	$concept = sprintf(__('Move to Top fee for item (%s)', 'rbc'), $shpBype);
	}elseif ($type == 1){
	$ist1 = 'Robokassa';
	$pro2co = 'Premium';
	$concept = sprintf(__('Premium fee for item (%s)', 'rbc'), $shpBype);
	}

    echo "OK$invId\n";

    switch($type){
        case 1:
            $payment_id = ModelRbc::newInstance()->saveLog_rbc($concept,$currency,$outSumm,$userid,$shpBype,$ist1,$pro2co);
            ModelRbc::newInstance()->payPremiumFee_rbc($shpBype, $payment_id);
            break;

        case 2:
		    $payment_id = ModelRbc::newInstance()->saveLog_rbc($concept,$currency,$outSumm,$userid,$shpBype,$ist1,$pro2co);
            ModelRbc::newInstance()->setTopItem_rbc($shpBype);
            break;

        case 3:
		    $payment_id = ModelRbc::newInstance()->saveLog_rbc($concept,$currency,$outSumm,$userid,$shpBype,$ist1,$pro2co);
            ModelRbc::newInstance()->setColor_rbc($shpBype);
            break;
        case 4:
		    $payment_id = ModelRbc::newInstance()->saveLog_rbc($concept,$currency,$outSumm,$userid,$shpBype,$ist1,$pro2co);
            ModelRbc::newInstance()->payPublishFee_rbc($shpBype, $payment_id);
            break;
		case 5:
			$payment_id = ModelRbc::newInstance()->saveLog_rbc($concept,$currency,$outSumm,$userid,$shpBype,$ist1,$pro2co);
            ModelRbc::newInstance()->addWallet_rbc($userid, $outSumm);
            break;
        default:
            break;

    }
    exit();
}

