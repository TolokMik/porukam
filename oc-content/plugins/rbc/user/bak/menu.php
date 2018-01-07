<?php
$itemsPerPage = (Params::getParam('itemsPerPage') != '') ? Params::getParam('itemsPerPage') : 5;
$page         = (Params::getParam('iPage') != '') ? Params::getParam('iPage') : 0;
$total_items  = Item::newInstance()->countByUserIDEnabled($_SESSION['userId']);
$total_pages  = ceil($total_items/$itemsPerPage);
$items        = Item::newInstance()->findByUserIDEnabled($_SESSION['userId'], $page * $itemsPerPage, $itemsPerPage);

View::newInstance()->_exportVariableToView('items', $items);
View::newInstance()->_exportVariableToView('list_total_pages', $total_pages);
View::newInstance()->_exportVariableToView('list_total_items', $total_items);
View::newInstance()->_exportVariableToView('items_per_page', $itemsPerPage);
View::newInstance()->_exportVariableToView('list_page', $page);
?>
<h2><?php _e('Robokassa & your listings', 'rbc'); ?></h2>
<h4><strong style="color:#84C441;padding-left:3px; "><?php _e('Premium', 'rbc'); ?> </strong></h4>
<p><?php _e('Premium status is when the ads are highlighted and shown on top of free ads in . This promotes more rapid sales. The duration of the option in days:', 'rbc'); ?> </p>
<h4><strong style="color:#B854A0;padding-left:3px;"><?php _e('Move ad on top', 'rbc'); ?> </strong></h4>
<p><?php _e('This function moves you ad once on the top of the you category and the main page too.', 'rbc'); ?></p>
<h4><strong style="color:#F89820;padding-left:3px;"><?php _e('Highlight', 'rbc'); ?> </strong></h4>
<p><?php _e('This option allows to attract the visitors attention on you ads. Background of your ad becomes highlighted', 'rbc'); ?></p>
<br />
<?php if(osc_count_items() == 0) { ?>
    <h3><?php _e('You don\'t have any listing yet', 'rbc'); ?></h3>
<?php } else { ?>
    <?php while(osc_has_items()) { ?>
        <div class="item">
            <h3>
                <a href="<?php echo osc_item_url(); ?>"><?php echo osc_item_title(); ?></a>
            </h3>
            <p>
                <?php _e('Publication date', 'rbc') ; ?>: <?php echo osc_format_date(osc_item_pub_date()) ; ?><br />
                <?php _e('Price', 'rbc') ; ?>: <?php echo osc_format_price(osc_item_price()); ?>
            </p>
            <p class="options">
                <?php if(osc_get_preference("pay_per_post", "rbc")=="1") { ?>
                    <?php if(ModelRbc::newInstance()->publishFeeIsPaid_rbc(osc_item_id())) { ?>
                        <strong><?php _e('Paid!', 'rbc'); ?></strong>
                    <?php } else { ?>
                        <strong><a href="<?php echo osc_route_url('rbc-publish', array('itemId' => osc_item_id())); ?>"><?php _e('Pay for this item', 'rbc'); ?></a></strong>
                    <?php }; ?>
                <?php }; ?>

                <?php if(osc_get_preference("pay_per_post", "rbc")=="1" && osc_get_preference("allow_premium", "rbc")=="1") { ?>
                    <span>|</span>
                <?php }; ?>

                <?php if(osc_get_preference("allow_premium", "rbc")=="1") { ?>

                    <?php if(ModelRbc::newInstance()->premiumFeeIsPaid_rbc(osc_item_id())) { ?>
                        <strong><?php _e('Already premium!', 'rbc'); ?></strong>


                    <?php } else { ?>
                        <strong id="rbcprbut">
                            <a href="<?php echo osc_route_url('robokassa-premium', array('itemId' => osc_item_id())); ?>"  style="text-decoration: none">
                                <button><?php _e('Make Premium', 'rbc'); ?></button>
                            </a>
                        </strong>
                    <?php }; ?>
                <?php }; ?>
                <?php if(osc_get_preference("allow_move", "rbc")=="1") { ?>
                <strong id="rbctopbut">
                    <a href="<?php echo osc_route_url('robokassa-top', array('itemId' => osc_item_id())); ?>"  style="text-decoration: none">
                        <button><?php _e('Move on Top', 'rbc'); ?></button>
                    </a>
                </strong>
				 <?php }; ?>
				 <?php if(osc_get_preference("allow_high", "rbc")=="1") { ?>
				<?php if(rbc_get_class_color(osc_item_id()) == 'colorized') { ?>
				<strong style="margin: 10px 0 0 0; padding: 10px 0 0 0;"><?php _e('Already highlight!', 'rbc'); ?></strong>
				<?php } else { ?>
				<strong id="rbccolorbut">
                    <a href="<?php echo osc_route_url('robokassa-makecolor', array('itemId' => osc_item_id())); ?>"  style="text-decoration: none">
                        <button> <?php _e('Highlight', 'rbc'); ?></button>
                    </a>
                </strong>
              <?php }; ?>
             <?php }; ?>

            </p>

            <br />
        </div>
    <?php } ?>
    <br />
   <div class="paginate">
   <?php for($i = 0 ; $i < $total_pages ; $i++) {
        if($i == $page) {
            printf('<a class="searchPaginationSelected" href="%s">%d</a>', osc_route_url('rbc-user-menu', array('iPage' => $i)), ($i + 1));
        } else {
            printf('<a class="searchPaginationNonSelected" href="%s">%d</a>', osc_route_url('rbc-user-menu', array('iPage' => $i)), ($i + 1));
        }
    } ?>
    </div>
<?php } ?>


