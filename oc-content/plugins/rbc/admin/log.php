<?php if ( (!defined('ABS_PATH')) ) exit('ABS_PATH is not loaded. Direct access is not allowed.'); ?>
<?php if ( !OC_ADMIN ) exit('User access is not allowed.'); ?>
<?php 
/*
 * Copyright 2015 osclass-pro.com
 *
 * You shall not distribute this plugin and any its files (except third-party libraries) to third parties.
 * Rental, leasing, sale and any other form of distribution are not allowed and are strictly forbidden.
 */
$chekout_log = ModelRbc::newInstance()->getLogs_rbc();
?>
<h2 class="render-title"><b><i class="fa fa-file-text"></i> <?php _e('Robokassa log', 'rbc'); ?><b></h2>
<div class="dataTables_wrapper">
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="datatables_list">
                        <thead>
                            <tr>
                                <th class="sorting"><?php _e('ID', 'rbc'); ?></th>
                                <th class="sorting"><?php _e('Date', 'rbc'); ?></th>
								<th ><?php _e('Description', 'rbc'); ?></th>
                                <th ><?php _e('Amount', 'rbc'); ?></th>
                                <th ><?php _e('User e-mail', 'rbc'); ?></th>
                                <th ><?php _e('ItemID', 'rbc'); ?></th>
                                <th ><?php _e('Source', 'rbc'); ?></th>
                                <th ><?php _e('Product type', 'rbc'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $odd = 1;
                                foreach($chekout_log as $logs) {
                                    if($odd==1) {
                                        $odd_even = "odd";
                                        $odd = 0;
                                    } else {
                                        $odd_even = "even";
                                        $odd = 1;
                                    }
              $userid = $logs['fk_i_user_id'];
               $usermail = ModelRbc::newInstance()->getUseremail_rbc($userid);  									
                            ?>
                            
                            <tr class="<?php echo $odd_even;?>">
                            	<td><?php echo $logs['pk_i_id']; ?></td>
                            	<td><?php echo osc_format_date($logs['dt_date']); ?></td>
								<td><?php echo $logs['s_concept']; ?></td>
                                <td><?php echo $logs['f_outsum']; echo $logs['s_currency_code']; ?></td>
                            	<td><?php echo $usermail ; ?></td>
                            	<td><?php echo $logs['fk_i_item_id']; ?></td>
                                <td><?php echo $logs['s_source']; ?></td>
                            	<td><?php echo $logs['i_product_type'];?></td>
                            </tr>
							<?php }?>
                        </tbody>
                    </table>
                    
</div>
<div class="form-row">
	 <address class="osclasspro_address">
	<span>&copy;<?php echo date('Y') ?> <a target="_blank" title="osclass-pro.ru" href="https://osclass-pro.ru/">osclass-pro.ru</a>. All rights reserved.</span>
  </p>
  </address></div>
  
 <?php echo '<script src="' . osc_base_url() . 'oc-content/plugins/rbc/admin/js/jquery.admin.js"></script>'; ?>