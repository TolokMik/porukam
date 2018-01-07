<?php
		   /*
 * Copyright 2015 osclass-pro.com and osclass-pro.ru
 *
 * You shall not distribute this plugin and any its files (except third-party libraries) to third parties.
 * Rental, leasing, sale and any other form of distribution are not allowed and are strictly forbidden.
 */

class Robokassa {


    public function __construct(){}

    public static function button($outSumm,$description,$invId,$userid,$type,$labelButton){
        $mrhLogin = osc_get_preference('mrhlogin', 'rbc');
        $mrhPass1 = osc_get_preference('mrhpass1', 'rbc');
		$OutSumCurrency = osc_get_preference('currency', 'rbc');
        $crc  = md5("$mrhLogin:$outSumm:0:$mrhPass1:shp_item=$invId:shp_product=$type:shp_user=$userid");
		$crcue  = md5("$mrhLogin:$outSumm:0:$OutSumCurrency:$mrhPass1:shp_item=$invId:shp_product=$type:shp_user=$userid");
        $encoding = 'utf-8';
        ?>    <form class="nocsrf rbc_<?php echo $type; ?>" action="http://porukam.kiev.ua/index.php?page=custom&route=rbc-payinvoce" method="post" accept-charset='UTF-8' style='padding-top: 0;'> 
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
            <input type='hidden' name='itmdescription' value="<?php echo osc_item_description(); ?>" />
             <input type='hidden' name='itmtitle' value="<?php echo  osc_item_title(); ?>" />
           
            <input type='hidden' name='SignatureValue' value="<?php echo $crc; ?>" />
			<input type='hidden' name='shp_item' value="<?php echo $invId; ?>" />
			<input type='hidden' name='shp_product' value="<?php echo $type; ?>" />
			<input type='hidden' name='shp_user' value="<?php echo $userid; ?>" />
            <input type='hidden' name='Encoding' value="<?php echo $encoding; ?>" />
             <button class=''><?php echo $labelButton; ?></button>
        </form>
        <?php
/*
		if($OutSumCurrency == "UAH") {

        print "<form class='nocsrf rbc_".$type; " action='/?page=page&id=29'  method='POST' accept-charset='UTF-8' style='padding-top: 0;'>
                    <input type='hidden' name='MerchantLogin' value='".$mrhLogin."' />
                    <input type='hidden' name='OutSum' value='".$outSumm."' />
                    <input type='hidden' name='InvId' value='0' />
                    <input type='hidden' name='Description' value='".$description."' />
                    <input type='hidden' name='SignatureValue' value='".$crc."' />
					<input type='hidden' name='shp_item' value='".$invId."' />
					<input type='hidden' name='shp_product' value='".$type."' />
					<input type='hidden' name='shp_user' value='".$userid."' />
                    <input type='hidden' name='Encoding' value='".$encoding."' />
                  <button class=''>".$labelButton."</button>
               </form>
        ";
		  }else{

        print "<form class='nocsrf rbc_".$type."'  action='/?page=page&id=29'  method='POST' accept-charset='UTF-8' style='padding-top: 0px;'>
                    <input type='hidden' name='MerchantLogin' value='".$mrhLogin."' />
                    <input type='hidden' name='OutSum' value='".$outSumm."' />
                    <input type='hidden' name='InvId' value='0' />
					<input type='hidden' name='OutSumCurrency' value='".$OutSumCurrency."' />
                    <input type='hidden' name='Description' value='".$description."' />
                    <input type='hidden' name='SignatureValue' value='".$crcue."' />
                    <input type='hidden' name='shp_item' value='".$invId."' />
					<input type='hidden' name='shp_product' value='".$type."' />
					<input type='hidden' name='shp_user' value='".$userid."' />
                    <input type='hidden' name='Encoding' value='".$encoding."' />

                  <button class=''>".$labelButton."</button>
               </form>
        ";
		  } */
		  
    }
	


}
