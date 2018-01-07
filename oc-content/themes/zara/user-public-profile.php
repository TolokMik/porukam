<?php
  $address = '';
  if(osc_user_address()!='') {
    $address = osc_user_address();
  }

  $location_array = array();
  if(trim(osc_user_city()." ".osc_user_zip())!='') {
    $location_array[] = trim(osc_user_city()." ".osc_user_zip());
  }

  if(osc_user_region()!='') {
    $location_array[] = osc_user_region();
  }

  if(osc_user_country()!='') {
    $location_array[] = osc_user_country();
  }

  $location = implode(", ", $location_array);
  unset($location_array);

  $user_keep = osc_user();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
<head>
  <?php osc_current_web_theme_path('head.php') ; ?>
  <meta name="robots" content="noindex, nofollow" />
  <meta name="googlebot" content="noindex, nofollow" />
  <script type="text/javascript" src="<?php echo osc_current_web_theme_js_url('jquery.validate.min.js') ; ?>"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
  <?php View::newInstance()->_exportVariableToView('user', $user_keep); ?>
  <?php osc_current_web_theme_path('header.php') ; ?>

  <div class="content user_public_profile">
    <!-- RIGHT BLOCK -->
    <div id="right-block">
      <!-- SELLER INFORMATION -->
      <div id="description">
        <?php if(function_exists('profile_picture_show')) { profile_picture_show(200); } ?>

        <ul id="user_data">
          <li class="name"><?php echo osc_user_name(); ?></li>
          <?php if ( osc_user_phone_mobile() != "" ) { ?><li><span class="left"><?php _e('Mobile', 'zara'); ?></span><span class="right"><?php echo osc_user_phone_mobile() ; ?></span></li><?php } ?>
          <?php if ( osc_user_phone() != "" && osc_user_phone() != osc_user_phone_mobile() ) { ?><li><span class="left"><?php _e('Phone', 'zara'); ?></span><span class="right"><?php echo osc_user_phone() ; ?></span></li><?php } ?>                    
          <?php if ($address != '') { ?><li><span class="left"><?php _e('Address', 'zara'); ?></span><span class="right"><?php echo $address; ?></span></li><?php } ?>
          <?php if ($location != '') { ?><li><span class="left"><?php _e('Location', 'zara'); ?></span><span class="right"><?php echo $location; ?></span></li><?php } ?>
          <?php if (osc_user_website() != '') { ?><li><span class="left"><?php _e('Website', 'zara'); ?></span><span class="right"><a href="<?php echo osc_user_website(); ?>" target="_blank" rel="nofollow"><?php echo osc_user_website(); ?></a></span></li><?php } ?>
          <?php if(osc_user_info() <> '') { ?><li class="desc"><span class="left"><?php _e('Description', 'zara'); ?></span><span class="right"><?php echo osc_user_info(); ?></span></li><?php } ?>
        </ul>
      </div>
      <!-- meggenger form -->
                <div id="show-msg-seller-content">
            <div id="show-msg-seller-form" class="fw-box">
              <div class="head">
                <h2><?php _e('Quick Contact', 'zara'); ?></h2>
                <a href="#" class="def-but fw-close-button round3"><i class="fa fa-times"></i> <?php _e('Close', 'zara'); ?></a>
              </div>

              <?php   if( osc_reg_user_can_contact() && !osc_is_web_user_logged_in() ) { ?>
                <div class="empty">
                  <?php _e('You must log in or register a new account in order to contact the advertiser.', 'zara') ; ?>
                </div>
              <?php } else { ?> 
                  

                <div class="left">
                  <img src="<?php echo osc_base_url(); ?>oc-content/themes/<?php echo osc_current_web_theme(); ?>/images/messenger-seller-form.jpg" />
                </div>

                <div class="middle">
                  <ul id="error_list"></ul>
         
                 <!-- плагін Madhouse Messenger --> 
            <?php
				  if(osc_is_web_user_logged_in() && osc_item_user_id() != osc_logged_user_id()){?> 
				  <a name="messagego"> </a> <div class="madmesseger"> 
				  
				   <?php
    				osc_run_hook('mdh_messenger_contact_form'); // Include the contact form html code.
					?> </div>
             <?php }	?> 
                </div>
          
              <?php } ?>
            </div>
          </div>
        
	<!-- end messenger content -->
      <!-- CONTACT SELLER BLOCK -->
      <div class="pub-contact-wrap">
        <div class="ins">
          <?php if(osc_user_id() == osc_logged_user_id()) { ?>
            <div class="empty"><?php _e('This is your public profile and therefore contact form is disabled for you', 'zara'); ?></div>
          <?php } else { ?>
         	 
            <?php if(osc_reg_user_can_contact() && osc_is_web_user_logged_in() || !osc_reg_user_can_contact() ) { ?>
              <a id="pub-contact" style="padding:0 0 0 0; height:51px;" href="<?php echo osc_item_send_friend_url(); ?>" class="tr1 round3" rel="<?php echo osc_user_id(); ?>">   <div class="left tr1" style="float:left; font-size:21px; text-align:center; padding:13px 20px 13px 20px; width:20%; border-right: solid 1px; border-right-color: #1176D3; height: 100%"><i class="fa fa-envelope-o"></i></div>   <div class="right tr1" style="float:right; width:80%; text-align:center; padding-top:13px;"><?php _e('Contact seller', 'zara'); ?></div></a>
            <?php } ?>
           <!-- плагін Madhouse Messenger --> 
            <?php
				  if(osc_is_web_user_logged_in() && osc_item_user_id() != osc_logged_user_id()){?>
         <a  style="padding:0 0 0 0; height:51px; border: 1px #4CAF50; background: #8BC34A;  margin: 10px 0 10px 0; height:49px;" class="tr1 round3" rel="1">  <div class="msg-seller" style=" border: 1px #4CAF50; height:auto;">
              <div class="left tr"><i class="fa fa-comment-o"></i></div>
              <div class="right tr"><?php _e('Quick Contact', 'zara');?></div>
          </div> 
             </a> <?php }?>
           
            <?php
				 	 if(osc_is_web_user_logged_in() && osc_item_user_id() != osc_logged_user_id()){?>
				  		 <div class="madmesseger" style="display:none;"> <?php
    						osc_run_hook('mdh_messenger_contact_form'); // Include the contact form html code.
					?> </div>
            <?php }	?>
          <?php } ?>
        </div>
      </div>
    </div>


    <!-- LISTINGS OF SELLER -->
    <div id="public-items" class="white">
      <h1><?php _e('Latest items of seller', 'zara'); ?></h1>

      <?php if( osc_count_items() > 0) { ?>
        <div class="block">
          <div class="wrap">
            <?php $c = 1; ?>
            <?php while( osc_has_items() ) { ?>
              <div class="simple-prod o<?php echo $c; ?>">
                <div class="simple-wrap">
                  <div class="item-img-wrap">
                    <?php if(osc_count_item_resources()) { ?>
                      <?php if(osc_count_item_resources() == 1) { ?>
                        <a class="img-link" href="<?php echo osc_item_url(); ?>"><img src="<?php echo osc_resource_thumbnail_url(); ?>" title="<?php echo osc_esc_html(osc_item_title()); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?>" /></a>
                      <?php } else { ?>
                        <a class="img-link" href="<?php echo osc_item_url(); ?>">
                          <?php for ( $i = 0; osc_has_item_resources(); $i++ ) { ?>
                            <?php if($i <= 1) { ?>
                              <img class="link<?php echo $i; ?>" src="<?php echo osc_resource_thumbnail_url(); ?>" title="<?php echo osc_esc_html(osc_item_title()); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?>" />
                            <?php } ?>
                          <?php } ?>
                        </a>
                      <?php } ?>
                    <?php } else { ?>
                      <a class="img-link" href="<?php echo osc_item_url(); ?>"><img src="<?php echo osc_current_web_theme_url('images/no-image.png'); ?>" title="<?php echo osc_esc_html(osc_item_title()); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?>" /></a>
                    <?php } ?>

                    <a class="orange-but" title="<?php echo osc_esc_html(__('Quick view', 'zara')); ?>" href="<?php echo osc_item_url(); ?>" title="<?php echo osc_esc_html(__('Open this listing', 'zara')); ?>"><i class="fa fa-hand-pointer-o"></i></a>
                  </div>

                  <?php
                    $now = time();
                    $your_date = strtotime(osc_item_pub_date());
                    $datediff = $now - $your_date;
                    $item_d = floor($datediff/(60*60*24));

                    if($item_d == 0) {
                      $item_date = __('today', 'zara');
                    } else if($item_d == 1) {
                      $item_date = __('yesterday', 'zara');
                    } else {
                      $item_date = date(osc_get_preference('date_format', 'zara_theme'), $your_date);
                    }
                  ?>

                  <?php if(osc_item_is_premium()) { ?>
                    <div class="new">
                      <span class="top"><?php _e('premium', 'zara'); ?></span>
                    </div>
                  <?php } ?>

                  <a class="title" href="<?php echo osc_item_url(); ?>"><?php echo osc_highlight(osc_item_title(), 100); ?></a>

                  <?php if( osc_price_enabled_at_items() ) { ?>
                    <div class="price"><span><?php echo osc_item_formated_price(); ?></span></div>
                  <?php } ?>
                </div>
              </div>
              
              <?php $c++; ?>
            <?php } ?>
          </div>
        </div>
      <?php } else { ?>
        <div class="empty"><?php _e('No listings posted by this seller', 'zara'); ?></div>
      <?php } ?>
    </div>
  </div>


  <?php osc_current_web_theme_path('footer.php') ; ?>
</body>
</html>