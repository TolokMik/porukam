<?php
if(osc_get_preference('allow_premium', 'rbc')) {
    // Load Item Information, so we could tell the user which item is he/she paying for
    $item = Item::newInstance()->findByPrimaryKey(Params::getParam('itemId'));
    if($item) {
        // Check if it's already payed or not
        if(!ModelRbc::newInstance()->premiumFeeIsPaid_rbc($item['pk_i_id'])) {
            // Item is not paid, continue
            $category_fee = ModelRbc::newInstance()->getPremiumPrice_rbc($item['fk_i_category_id']);
            if($category_fee > 0) {
                ?>
               <div style="padding: 0 0 15px 0;">
                    <div style="float:left; width: 33%;">
                     <h1><?php _e('Make the ad premium', 'rbc'); ?></h1>
                        <label style="font-weight: bold;"><?php _e("Item's title", 'rbc'); ?>:</label> <?php echo $item['s_title']; ?><br/>
                        <label style="font-weight: bold;"><?php _e("Item's description", 'rbc'); ?>:</label> <?php echo $item['s_description']; ?><br/>
                    </div>
                    <div style="float:left; width: 33%;">
                        <?php _e("In order to make premium your ad , it's required to pay a fee. The duration of the option in days:", 'rbc'); ?>.<br/>
                        <?php echo sprintf(__("The current fee for this category is: %.2f %s", 'rbc'), $category_fee, osc_get_preference('currency', 'rbc')); ?><br/>
						
                    </div>
                    <div style="float:left; width: 33%; padding: 0 0 0 20px;">
                    <?php if(osc_is_web_user_logged_in()) {
                                $wallet = ModelRbc::newInstance()->getWallet_rbc(osc_logged_user_id());
								$summ = isset($wallet['f_outsum'])?$wallet['f_outsum']:null;
                                if($summ>=$category_fee) {
                                    rbc_button($category_fee, sprintf(__("Premium fee for item %d", "rbc"), $item['pk_i_id']), $item['pk_i_id'], osc_logged_user_id(),1,__("Make Premium", "rbc"));

                                } else {
                                        Robokassa::button($category_fee, sprintf(__("Premium fee for item %d", "rbc"), $item['pk_i_id']), $item['pk_i_id'], osc_logged_user_id(),1,__("Make Premium", "rbc"));
                                }
                            } else {
                         Robokassa::button($category_fee, sprintf(__("Premium fee for item %d", "rbc"), $item['pk_i_id']), $item['pk_i_id'], osc_user_id(),1,__("Make Premium", "rbc"));
                      }; ?>
                    </div>
                    <div style="clear:both;"></div>
                    <div name="result_div" id="result_div"></div>
                </div>
            <?php
            } else {
                // PRICE IS ZERO!
                ?>
                <h1><?php _e('There was an error', 'rbc'); ?></h1>
                <div>
                    <p><?php _e("There's no need to pay the premium fee", 'rbc'); ?></p>
                </div>
            <?php
            }

        } else {
            // ITEM WAS ALREADY PAID! STOP HERE
            ?>
            <h1><?php _e('There was an error', 'rbc'); ?></h1>
            <div>
                <p><?php _e('The item is already a premium ad', 'rbc'); ?></p>
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