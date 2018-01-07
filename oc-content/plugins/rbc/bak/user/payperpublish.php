<?php
    if(osc_get_preference('pay_per_post', 'rbc')) {
        // Load Item Information, so we could tell the user which item is he/she paying for
        $item = Item::newInstance()->findByPrimaryKey(Params::getParam('itemId'));
        if($item) {
            // Check if it's already payed or not
            if(!ModelRbc::newInstance()->publishFeeIsPaid_rbc(Params::getParam("itemId"))) {
                // Item is not paid, continue
                $category_fee = ModelRbc::newInstance()->getPublishPrice_rbc($item['fk_i_category_id']);
                if($category_fee > 0) {
                ?>

                <h1><?php _e('Continue the publish process', 'rbc'); ?></h1>
                <div>
                    <div style="float:left; width: 50%;">
                        <label style="font-weight: bold;"><?php _e("Item's title", 'rbc'); ?>:</label> <?php echo $item['s_title']; ?><br/>
                        <label style="font-weight: bold;"><?php _e("Item's description", 'rbc'); ?>:</label> <?php echo $item['s_description']; ?><br/>
                    </div>
                    <div style="float:left; width: 50%;">
                        <?php _e("In order to make visible your ad to other users, it's required to pay a fee", 'rbc'); ?>.<br/>
                        <?php echo sprintf(__('The current fee for this category is: %.2f %s', 'rbc'), $category_fee, osc_get_preference('currency', 'rbc')); ?><br/>
						<?php if(osc_is_web_user_logged_in()) {
                                $wallet = ModelRbc::newInstance()->getWallet_rbc(osc_logged_user_id());
								$summ = isset($wallet['f_outsum'])?$wallet['f_outsum']:null;
                                if($summ>=$category_fee) {
                                    rbc_button($category_fee, sprintf(__('Publish fee for item %d', 'rbc'), $item['pk_i_id']), $item['pk_i_id'], osc_logged_user_id(),4,__("Publish item", "rbc"));
                                } else {
                                        Robokassa::button($category_fee, sprintf(__('Publish fee for item %d', 'rbc'), $item['pk_i_id']), $item['pk_i_id'], osc_logged_user_id(),4,__("Publish item", "rbc"));

                                } }else {
						Robokassa::button($category_fee, sprintf(__('Publish fee for item %d', 'rbc'), $item['pk_i_id']), $item['pk_i_id'], osc_user_id(),4,__("Publish item", "rbc"));
                       }?>                   
                    </div>
                    <div style="clear:both;"></div>
                    <div name="result_div" id="result_div"></div>
                </div>
                <?php
                } else {
                    // PRICE IS ZERO!
                    ?>
                    <h1><?php _e("There was an error", 'rbc'); ?></h1>
                    <div>
                        <p><?php _e("There's no need to pay the publish fee", 'rbc'); ?></p>
                    </div>
                    <?php
                }
            } else {
                // ITEM WAS ALREADY PAID! STOP HERE
                ?>
                <h1><?php _e('There was an error', 'rbc'); ?></h1>
                <div>
                    <p><?php _e('The publish fee is already paid', 'rbc'); ?></p>
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
	    }
?>