<?php

$item = Item::newInstance()->findByPrimaryKey(Params::getParam('itemId'));
if ($item) {

    $category_fee = ModelRbc::newInstance()->getColorPrice_rbc($item['fk_i_category_id']);
    if ($category_fee > 0) {
        ?>
        <div style="padding: 0 0 15px 0;">
            <div style="float:left; width: 33%;">
            <h1><?php _e('Highlight Item', 'rbc'); ?></h1>
                <label style="font-weight: bold;"><?php _e("Item's title", 'rbc'); ?>
                    :</label> <?php echo $item['s_title']; ?><br/>
                <label style="font-weight: bold;"><?php _e("Item's description", 'rbc'); ?>
                    :</label> <?php echo $item['s_description']; ?><br/>
            </div>
            <div style="float:left; width: 33%;">
                <?php _e("In order to Highlight your ad , it's required to pay a fee. The duration of the option in days:", 'rbc'); ?><br/>
                <?php echo sprintf(__("The current fee for this category is: %.2f %s", 'rbc'), $category_fee, osc_get_preference('currency', 'rbc')); ?>
                <br/>
               

            </div>
             <div style="float:left; width: 33%; padding: 0 0 0 20px;">
              <?php if(osc_is_web_user_logged_in()) {
                                $wallet = ModelRbc::newInstance()->getWallet_rbc(osc_logged_user_id());
								$summ = isset($wallet['f_outsum'])?$wallet['f_outsum']:null;
                                if($summ>=$category_fee) {
                                    rbc_button($category_fee, sprintf(__("Highlight fee for item %d", "rbc"), $item['pk_i_id']), $item['pk_i_id'], osc_logged_user_id(),3, __("Highlight", "rbc"));
                        
                                } else { 
									   Robokassa::button($category_fee, sprintf(__("Highlight fee for item %d", "rbc"), $item['pk_i_id']), $item['pk_i_id'], osc_logged_user_id(),3, __("Highlight", "rbc"));

                                } 
								}else {
                                        Robokassa::button($category_fee, sprintf(__("Highlight fee for item %d", "rbc"), $item['pk_i_id']), $item['pk_i_id'], osc_user_id(),3, __("Highlight", "rbc"));

                                }?>
             </div>
            <div style="clear:both;"></div>
            <div name="result_div" id="result_div"></div>
        </div>
    <?php
    } else {
        ?>
        <h1><?php _e('There was an error', 'rbc'); ?></h1>
        <div>
            <p><?php _e('The item is already a Highlight', 'rbc'); ?></p>
        </div>
    <?php
    }

} else {
    //ITEM DOES NOT EXIST! STOP HERE
    ?>
    <h1><?php _e('There was an error', 'rbc'); ?></h1>
    <div>
        <p><?php _e('The item doesn not exists', 'rbc'); ?></p>
    </div>
<?php
}
