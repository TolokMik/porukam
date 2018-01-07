<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
  <head>
    <?php osc_current_web_theme_path('head.php') ; ?>
    <meta name="robots" content="noindex, nofollow" />
    <meta name="googlebot" content="noindex, nofollow" />
  </head>
  <body>
    <?php osc_current_web_theme_path('header.php') ; ?>
    <?php osc_render_file(); ?>
    <?php 
     if(isset($_POST['payee_id'])){
        $payee_id = $_POST['payee_id'];
        $shop_order_number =  $_POST['shop_order_number'];
        $bill_amount =  $_POST['bill_amount'];
        $description =  $_POST['description'];
        $failure_url =  $_POST['failure_url']; 
        $SignatureValue =  $_POST['SignatureValue'];
        $lang =  $_POST['lang'];
        $encoding =  $_POST['encoding'];
        /* ======================rbc old  ===========================  */
        $MerchantLogin  = $_POST['MerchantLogin'];
        $OutSum = $_POST['OutSum'];
        $InvId =  $_POST['InvId'];
        $Description = $_POST['Description']; 
		$shp_item = $_POST['shp_item'];
		$shp_product = $_POST['shp_product'];
		$shp_user = $_POST['shp_user'];
        $Encoding = $_POST['Encoding'];
        try{
             $sql = mysql_query("INSERT INTO pr_orders (id, shp_user, shpBype,  SignatureValue, shp_item, ) VALUES(NULL, \"$eml\",  \"$name\", \"$city\", \"$country\") "); 

	 header("Location: comments.php");
     exit;
        }

    }
    ?>
    <div class="operator_logo"> <img src="http://porukam.kiev.ua/oc-includes/portmone.svg"></div>
    <div class="pay_form">
            <form class="nocsrf rbc_<?php echo $type; ?>" action="https://www.portmone.com.ua/gateway/" method="post" accept-charset='UTF-8' style='padding-top: 0;'> 
            <input type='hidden' name="payee_id" value='12611' />
            <input type="hidden" name="shop_order_number" value="1" />
            <input type="hidden" name="bill_amount" value="<?php echo $outSumm; ?>"/>
            <input type="hidden" name="description" value="<?php echo $description; ?>"/>
            <input type="hidden" name="success_url" value="http://porukam.kiev.ua/index.php?page=custom&route=rbc-success" />
            <input type="hidden" name="failure_url" value="http://porukam.kiev.ua/index.php?page=custom&route=rbc-cancel" />
            <input type='hidden' name='SignatureValue' value="<?php echo $crc ?>" />
            <input type="hidden" name="lang" value="uk" />
            <input type="hidden" name="encoding" value="UTF-8" />
            <!-- ======================rbc old  =========================== -->
            <input type='hidden' name='MerchantLogin' value="<?php echo $mrhLogin; ?>" />
            <input type='hidden' name='OutSum' value="<?php echo $outSumm ?>" />
            <input type='hidden' name='InvId' value='0' />
            <input type='hidden' name='Description' value="<?php echo $description; ?>" />
            <input type='hidden' name='SignatureValue' value="<?php echo $crc; ?>" />
			<input type='hidden' name='shp_item' value="<?php echo $invId; ?>" />
			<input type='hidden' name='shp_product' value="<?php echo $type; ?>" />
			<input type='hidden' name='shp_user' value="<?php echo $userid; ?>" />
            <input type='hidden' name='Encoding' value="<?php echo $encoding; ?>" />
             <button class=''><?php echo $labelButton; ?></button>
        </form>
    </div>
    <?php osc_current_web_theme_path('footer.php') ; ?>
  </body>
</html>