<?php
$url = '';
$mp = ModelRbc::newInstance();
if(osc_is_web_user_logged_in()) {
    $data = rbc_get_custom(Params::getParam('extra'));
    $product_type =  $data['product'];
    $item = Item::newInstance()->findByPrimaryKey($data['itemid']);
    $wallet = $mp->getWallet_rbc(osc_logged_user_id());
	$summ = isset($wallet['f_outsum'])?$wallet['f_outsum']:null;
    $category_fee = 0;
    if(osc_logged_user_id()==$item['fk_i_user_id']) {
        if ($product_type[0] == '4') {
            if(!$mp->publishFeeIsPaid_rbc($item['pk_i_id'])) {
                $category_fee = $mp->getPublishPrice_rbc($item['fk_i_category_id']);
            }
        } else if ($product_type[0] == '1') {
            if(!$mp->premiumFeeIsPaid_rbc($item['pk_i_id'])) {
           $category_fee = $mp->getPremiumPrice_rbc($item['fk_i_category_id']);
            }
        } else if ($product_type[0] == '2') {
           $category_fee = $mp->getTopPrice_rbc($item['fk_i_category_id']);
         } else if ($product_type[0] == '3') {
           $category_fee = $mp->getColorPrice_rbc($item['fk_i_category_id']);

         } 			
    }
   if($category_fee > 0 && $summ>=$category_fee) {
   if($product_type[0] == 4){
	$pro2co = 'Publish';
	} elseif ($product_type[0] == 3){
	$pro2co = 'Highlighted';
	}elseif ($product_type[0] == 2){
	$pro2co = 'Top';
	}elseif ($product_type[0] == 1){
	$pro2co = 'Premium';
	}
	$user1 = osc_logged_user_id();
	$currency = osc_get_preference("currency", "rbc");
        $payment_id = $mp->saveLog_rbc(
		    'wallet_'.date("YmdHis"),
			$currency,
            $category_fee, //amount
            $user1, //user
            $data['itemid'], //item
            'WALLET' , //product type
            $pro2co); //source
        $mp->addWallet_rbc(osc_logged_user_id(), -$category_fee);
        if ($product_type[0] == '4') {
            $mp->payPublishFee_rbc($data['itemid'], $payment_id);
            $url = osc_search_category_url();
        } else if ($product_type[0] == '1') {
            $mp->payPremiumFee_rbc($data['itemid'], $payment_id);
            $url = osc_search_category_url();
        } else if ($product_type[0] == '3') {
            $mp->setColor_rbc($data['itemid']);
            $url = osc_search_category_url();
        } else if ($product_type[0] == '2') {
            $mp->setTopItem_rbc($data['itemid']);
            $url = osc_search_category_url();
        }
    }
}
if($url!='') {
    osc_add_flash_ok_message(__('Payment processed correctly', 'rbc'));
    rbc_js_redirect_to($url);
} else {
    osc_add_flash_error_message(__('There were some errors, please try again later or contact the administrators', 'rbc'));
    rbc_js_redirect_to(osc_route_url('rbc-user-menu'));
}
?>