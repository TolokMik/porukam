<?php
		   /*
 * Copyright 2015 osclass-pro.com and osclass-pro.ru
 *
 * You shall not distribute this plugin and any its files (except third-party libraries) to third parties.
 * Rental, leasing, sale and any other form of distribution are not allowed and are strictly forbidden.
 */
if (isset($_POST['SHOPORDERNUMBER'])) {
    $mp = ModelRbc::newInstance();
    $data = rbc_get_custom(Params::getParam('extra'));
    $mrhPass1 = osc_get_preference('mrhpass1', 'rbc');
    $order_no = $_POST['SHOPORDERNUMBER'];
    $sql =  mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
    mysql_select_db(DB_NAME);
    $msgtonews =  osc_item_description();
    $qrstring = "SELECT * FROM pr_orders where pr_orders.ID = $order_no ";
    $rq = mysql_query($qrstring);
   	$f = mysql_fetch_array($rq);
    $outSumm = $f['OutSum'];
    $invId = $order_no;
	$type = $f["shp_product"];
	//$shpBype = $_REQUEST["shp_item"];
	$shpBype =  $f["shpBype"];
    $userid = $f["shp_user"];
    $signature = $f['SignatureValue'];
    $signature = strtoupper($signature);
    $mySignature = strtoupper(md5("$outSumm:$invId:$mrhPass1:shp_item=$shpBype:shp_product=$type:shp_user=$userid"));
   // echo 'type: '.$type. ' shpBype: '.$shpBype;
    /*if ($mySignature != $signature) {
        echo "bad sign\n";
        exit();
    } */
    if ($type == 1) {
        if (!($mp->premiumFeeIsPaid_rbc($shpBype))) {
		 osc_add_flash_ok_message(__('Payment processed correctly', 'rbc'));
		 $item = Item::newInstance()->findByPrimaryKey($shpBype);
		 $category = Category::newInstance()->findByPrimaryKey($item['fk_i_category_id']);
         View::newInstance()->_exportVariableToView('category', $category);
        /* $paid = $item->getPremiumData_rbc($shpBype);
         $paymentId = 0;
        if(empty($paid)) {
            $item->dao->insert($item->getTable_rbc_premium(), array('dt_date' => date("Y-m-d H:i:s"), 'fk_i_payment_id' => $paymentId, 'fk_i_item_id' => $itemId));
        } else {
            $item->dao->update($item->getTable_rbc_premium(), array('dt_date' => date("Y-m-d H:i:s"), 'fk_i_payment_id' => $paymentId), array('fk_i_item_id' => $itemId));
        } */
        $mp->payPremiumFee_rbc($shpBype, 0);
        /* $mItem = new ItemActions(false);
        $mItem->premium($shpBype, true); */
        // mail($email, $subject ,$message, $options);
        $options  = "From: porukam.kiev.ua<noreplay@porukam.kiev.ua>\r\n";
		$options = iconv("UTF8","KOI8-R" , $options); 
		$options .= "Content-type: text/html; charset=utf-8\r\n";
		$options = convert_cyr_string($options,w,k);
        mail('admin<main@design-plus.kiev.ua>',"Stolyca", 
        'Текст сообщения:\r\n '.$item['s_description'].'\r\n Моб.: '.$item['s_title'], $options);
		 rbc_js_redirect_to(osc_search_category_url());
        }
    } elseif ($type == 2) {
         osc_add_flash_ok_message(__('Payment processed correctly', 'rbc'));
         $mp->setTopItem_rbc($shpBype);
		 rbc_js_redirect_to(osc_search_category_url());

    } elseif ($type == 3) {
         osc_add_flash_ok_message(__('Payment processed correctly', 'rbc'));
         $mp->setColor_rbc($shpBype);
		 rbc_js_redirect_to(osc_search_category_url());
    }
	elseif ($type == 4) {
         osc_add_flash_ok_message(__('Payment processed correctly', 'rbc'));
		 rbc_js_redirect_to(osc_search_category_url());
    }
	elseif ($type == 5) {
		 osc_add_flash_ok_message(__('Payment processed correctly', 'rbc'));
		 rbc_js_redirect_to(osc_route_url('rbc-user-pack'));
    }
	 else {
         osc_add_flash_error_message(__("There was a problem processing your Payment. Please contact the administrators",'rbc'));
		 if(osc_is_web_user_logged_in()) {
             rbc_js_redirect_to(osc_route_url('rbc-user-menu'));
        } else {
             View::newInstance()->_exportVariableToView('item', Item::newInstance()->findByPrimaryKey($shpBype));
             rbc_js_redirect_to(osc_item_url());
        }
    }
    

}
?>








