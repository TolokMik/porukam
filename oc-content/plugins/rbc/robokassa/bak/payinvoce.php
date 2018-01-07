
    <?php 
    if(isset($_POST['payee_id'])){
        $payee_id = $_POST['payee_id'];
        $shop_order_number =  $_POST['shop_order_number'];
        $bill_amount =  $_POST['bill_amount'];
        $description =  $_POST['description'];
        $itmdescription =  $_POST['itmdescription'];
        $itmtitle = $_POST['itmtitle'];
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
       	$shpBype = $_REQUEST["shp_item"];
		    $shp_user = $_POST['shp_user'];
        $Encoding = $_POST['Encoding'];
        $last_id = 0;
        try{
         /* define("DB_NAME","porukam");
          define("HostName","localhost");
          define("UserName","u_porukam");
          define("DB_PASSWORD","2016knabikas"); */
         // $conn = new mysql_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
          $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
          if(!$conn){
             exit;
          }
          $sql = "INSERT INTO `porukam`.`pr_orders` (`ID`, `userid`, `shpBype`, `MerchantLogin`, `OutSum`, `Description`, `SignatureValue`, `shp_item`, `shp_product`, `shp_user`)"; 
          $sql .= "VALUES (NULL, '".$shp_user."', '".$shpBype."', '0', '".$OutSum."', '".$description."', '".$SignatureValue."', '".$shp_item."', '".$shp_product."', '".$shp_user."')";
          if ($conn->query($sql) === TRUE) {
            $last_id = $conn->insert_id;
          }
         }catch (Exception $e) {
           die( $e->getMessage());
        }
    } else{
        die("Forbidden");
    }
    ?>
    <?php if (strlen($itmdescription) > 250 && $shp_product == 1 ) { 
        ?>
         <div class="contentlimit"> 
            <div class="attention">
                <?php 
                echo __('Attention! Make sure that ad text does not exceed 250 characters','rbc');
            ?> </div> <?php 
                echo '<span class="trimed_h">' . __('The ad will look like','rbc'). '</span></br>';   
                echo '<b>'.trim_text($itmdescription, 250, false).'</b>' ?> 
                <?php echo '<br/><a href="/index.php?page=item&id='.$shpBype .'"><span class="returntoad">'.  __('Return to the ad','rbc'). '</span> </a> <br/>'; ?>
            </div>
    <?php  } ?>
       <h2 class="payinform"><span class="info_pay"> Оплатить стоимость услуги: </span> <?php echo $Description.' ['.$itmtitle.']'; ?> на сумму: <?php echo $OutSum; ?> </h2>

    <div class="wrapper-pay"> 
      <div class="operator_logo"> <img src="http://porukam.kiev.ua/oc-includes/images/portmone.svg"></div>
      <div class="pay_form">
          <form class="nocsrf rbc_<?php echo $type; ?>" action="https://www.portmone.com.ua/gateway/" method="post" accept-charset='UTF-8' style='padding-top: 0;'> 
          <input type='hidden' name="payee_id" value='12611' />
          <input type="hidden" name="shop_order_number" value="<?php echo $last_id; ?>" />
          <input type="hidden" name="bill_amount" value="<?php echo $OutSum; ?>"/>
          <input type="hidden" name="description" value="<?php echo $Description; ?>"/>
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
          <button class='button orange-button round2'>Оплатить</button>
          </form>
      </div>
    </div>
    
 