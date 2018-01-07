<?php
    $packs = array();
    if(osc_get_preference("pack_price_1", "rbc")!='' && osc_get_preference("pack_price_1", "rbc")!='0') {
        $packs[] = osc_get_preference("pack_price_1", "rbc");
    }
    if(osc_get_preference("pack_price_2", "rbc")!='' && osc_get_preference("pack_price_2", "rbc")!='0') {
        $packs[] = osc_get_preference("pack_price_2", "rbc");
    }
    if(osc_get_preference("pack_price_3", "rbc")!='' && osc_get_preference("pack_price_3", "rbc")!='0') {
        $packs[] = osc_get_preference("pack_price_3", "rbc");
    }
    @$user = User::newInstance()->findByPrimaryKey(osc_logged_user_id());
    $wallet = ModelRbc::newInstance()->getWallet_rbc(osc_logged_user_id());
	$summ = isset($wallet['f_outsum'])?$wallet['f_outsum']:null;
     if($summ!=0) {
     $formatted_amount = $summ." ГРН"; //.osc_get_preference('currency', 'rbc');
      $credit_msg = sprintf(__('Credit packs. Your current credit is %s', 'rbc'), $formatted_amount);
        } else {
            $credit_msg = __('Your wallet is empty. Buy some credits.', 'rbc');
        }
   

?>

<h2><?php echo $credit_msg; ?></h2>
<?php $pack_n = 0;
foreach($packs as $pack) { $pack_n++; ?>
    <div class="payrbc_o">
        <h3><?php echo sprintf(__('Credit pack #%d', 'rbc'), $pack_n); ?></h3>
        <div id="rbcpaylb"> <span><?php echo $pack."</span> ГРН"; /*.osc_get_preference('currency', 'rbc'); */ ?></div>
        <?php { ?>
            <div id="robopay<?php echo $pack_n; ?>" >
                <?php Robokassa::button($pack, sprintf(__("Credit for %s%s at %s", "rbc"), $pack, /* osc_get_preference("currency", "rbc")*/ "ГРН", osc_page_title()),0, $user['pk_i_id'],5,__(" ", "rbc")); ?> <!-- Add funds-->
            </div>
        <?php } ?>
    </div>
    <div style="clear:both;"></div>
 <?php } ?>
<div name="result_div" id="result_div"></div>
<script type="text/javascript">
    var rd = document.getElementById("result_div");
</script>
