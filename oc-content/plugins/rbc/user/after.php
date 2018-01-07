<?php
/*
 * Copyright 2015 osclass-pro.com and osclass-pro.ru
 *
 * You shall not distribute this plugin and any its files (except third-party libraries) to third parties.
 * Rental, leasing, sale and any other form of distribution are not allowed and are strictly forbidden.
 */
        $item = Item::newInstance()->findByPrimaryKey(Params::getParam('itemId'));
       $category_fee = ModelRbc::newInstance()->getPremiumPrice_rbc($item['fk_i_category_id']);
                if($category_fee > 0) {
                ?>
                 <div style="text-align:left;margin-bottom: 10px;">
                  <label style="font-weight: bold;"><?php _e("Item's title", 'rbc'); ?>:</label> <?php echo $item['s_title']; ?><br/>
                        <label style="font-weight: bold;"><?php _e("Item's description", 'rbc'); ?>:</label> <?php echo $item['s_description']; ?><br/>
                    </div>
                 <div style="display:inline-block; width:100%;">					
                    <div style="float:left; width:30%; border:0; margin: 5px 0 0 0; ">
					<h1 style="margin: 10px 0 10px 0; color:#FF0000; display:none;" ><?php _e('Premium', 'rbc'); ?></h1>
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
} ?> <p style="clear:both;"></p> <br/>

<p style="margin:  5px 0 5px 5xp;"><?php _e('Premium status is when the ads are highlighted and shown on top of free ads in . This promotes more rapid sales. The duration of the option in days:', 'rbc'); ?></p>
                       <br/><br/>
						 
                    </div>
        
                <?php
                } 

$category_fee1 = ModelRbc::newInstance()->getColorPrice_rbc($item['fk_i_category_id']);
    if ($category_fee1 > 0) {
        ?>
   
            <div style="float:left; width:30%; border: 0; margin: 5px 0 0 10px; ">
			<h1 style="margin: 10px 0 10px 0; color:#0066CC;display:none;"><?php _e('Highlight', 'rbc'); ?></h1>
<?php if(osc_is_web_user_logged_in()) {
                                $wallet = ModelRbc::newInstance()->getWallet_rbc(osc_logged_user_id());
								$summ = isset($wallet['f_outsum'])?$wallet['f_outsum']:null;
                                if($summ>=$category_fee1) {
                                    rbc_button($category_fee1, sprintf(__("Highlight fee for item %d", "rbc"), $item['pk_i_id']), $item['pk_i_id'], osc_logged_user_id(),3, __("Highlight", "rbc"));
                        
                                } else { 
									   Robokassa::button($category_fee1, sprintf(__("Highlight fee for item %d", "rbc"), $item['pk_i_id']), $item['pk_i_id'], osc_logged_user_id(),3, __("Highlight", "rbc"));

                                } 
								}else {
                                        Robokassa::button($category_fee1, sprintf(__("Highlight fee for item %d", "rbc"), $item['pk_i_id']), $item['pk_i_id'], osc_user_id(),3, __("Highlight", "rbc"));

                                }?> <p style="clear:both;"></p> <br/>
<p style="margin: 15px 0 5px 5xp;"><?php _e('This option allows to attract the visitors attention on you ads. Background of your ad becomes highlighted. The duration of the option in days:', 'rbc'); ?> </p>
               <br/><br/>
		
             <div style="clear:both;"></div>
			</div>
<div style="text-align:center; width:38%; float:right; padding: 40px 0 10px 10px; background:url(/oc-content/uploads/img/nothunks.png) no-repeat 30px 85px; min-height: 200px;">
          <a href="<?php echo osc_base_url(); ?>">
                        <button><?php _e('No, Thanks', 'rbc'); ?></button>
                    </a> <br/></div>
			</div>
			
	
    <?php
    } 



?>