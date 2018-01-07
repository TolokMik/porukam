<?php 
  // GET IF PAGE IS LOADED VIA QUICK VIEW
  $content_only = (Params::getParam('content_only') == 1 ? true : false);
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
<head>
  <?php osc_current_web_theme_path('head.php') ; ?>

  <?php 
    if(osc_item_price() == '') {
      $og_price = __('Check with seller', 'zara');
    } else if(osc_item_price() == 0) {
      $og_price = __('Free', 'zara');
    } else {
      $og_price = osc_item_price(); 
    }
  ?>
  <!-- FACEBOOK OPEN GRAPH  TAGS -->

  <?php osc_get_item_resources(); ?>
  <meta property="og:title" content="<?php echo osc_esc_html(osc_item_title()); ?>" />
  <?php if(osc_count_item_resources() > 0) { ?><meta property="og:image" content="<?php echo osc_resource_url(); ?>" /><?php } ?>
  <meta property="og:site_name" content="<?php echo osc_esc_html(osc_page_title()); ?>"/>
  <meta property="og:url" content="<?php echo osc_item_url(); ?>" />
  <meta property="og:description" content="<?php echo osc_esc_html(osc_highlight(osc_item_description(), 500)); ?>" />
  <meta property="og:type" content="article" />
  <meta property="og:locale" content="<?php echo osc_current_user_locale(); ?>" />
  <meta property="product:retailer_item_id" content="<?php echo osc_item_id(); ?>" /> 
  <meta property="product:price:amount" content="<?php echo $og_price; ?>" />
  <?php if(osc_item_price() <> '' and osc_item_price() <> 0) { ?><meta property="product:price:currency" content="<?php echo osc_item_currency(); ?>" /><?php } ?>



  <!-- GOOGLE RICH SNIPPETS -->

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><span itemscope itemtype="http://schema.org/Product">
    <meta itemprop="name" content="<?php echo osc_esc_html(osc_item_title()); ?>" />
    <meta itemprop="description" content="<?php echo osc_esc_html(osc_highlight(osc_item_description(), 500)); ?>" />
    <?php if(osc_count_item_resources() > 0) { ?><meta itemprop="image" content="<?php echo osc_resource_url(); ?>" /><?php } ?>
  </span>
</head>

<body class="page-body" <?php if($content_only) { ?> id="content_only"<?php } ?>>
  <?php if(!$content_only) { ?>
    <?php osc_current_web_theme_path('header.php') ; ?>
    <?php if( osc_item_is_expired () ) { ?><div id="exp_box"></div><div id="exp_mes"><?php _e('This listing has expired.', 'zara'); ?></div><?php } ?>
  <?php } ?>

  <div id="listing" class="content list">
    <!-- LISTING BODY -->
    <div id="main">
      <div id="left">

        <!-- IMAGE BOX -->
        <?php if( osc_images_enabled_at_items() ) { ?> 
          <?php osc_get_item_resources(); ?>
          <?php if( osc_count_item_resources() > 0 ) { ?>  
            <div id="pictures" class="item-pictures">
              <ul class="item-bxslider">
                <?php osc_reset_resources(); ?>
                <?php for( $i = 0; osc_has_item_resources(); $i++ ) { ?>
                  <li>
                    <a rel="image_group" href="<?php echo osc_resource_url(); ?>" title="<?php echo osc_esc_html(osc_item_title()); ?> - <?php _e('Image', 'zara'); ?> <?php echo $i+1;?>/<?php echo osc_count_item_resources();?>">
                      <img src="<?php echo osc_resource_url(); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?> - <?php echo $i+1;?>/<?php echo osc_count_item_resources();?>"/>
                    </a>
                  </li>
                <?php } ?>
              </ul>

              <div id="photo-count" class="round2">
                <div class="top"><i class="fa fa-camera"></i></div>
                <div class="bottom">
                  <?php if(osc_count_item_resources() == 1) { ?>
                    <span class="p-total"><?php echo osc_count_item_resources(); ?></span> <?php _e('photo', 'zara'); ?>
                  <?php } else { ?>
                    <span class="p-from">1</span> <span class="p-del">-</span> <span class="p-to">2</span> <?php _e('of', 'zara'); ?> <span class="p-total"><?php echo osc_count_item_resources(); ?></span>
                  <?php } ?>
                </div>
              </div>

              <?php if(osc_count_item_resources() > 1 && osc_get_preference('item_pager', 'zara_theme') == 1) { ?>
                <div id="item-bx-pager">
                  <?php osc_reset_resources(); ?>
                  <?php for( $i = 0; osc_has_item_resources(); $i++ ) { ?>

                    <a data-slide-index="<?php echo $i; ?>" href="" class="bx-navi<?php if($i + 2 == osc_count_item_resources()) { ?> last<?php } ?>">
                      <img src="<?php echo osc_resource_thumbnail_url(); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?> - <?php _e('Image', 'zara'); ?> <?php echo $i+1;?>/<?php echo osc_count_item_resources();?>"/>

                      <?php if($i + 2 == osc_count_item_resources()) { ?>
                        <?php View::newInstance()->_next('resources'); ?>
                        <img src="<?php echo osc_resource_thumbnail_url(); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?> - <?php _e('Image', 'zara'); ?> <?php echo $i+2;?>/<?php echo osc_count_item_resources();?>"/>
                        <?php $i++; ?>
                      <?php } ?> 
                    </a>
                  <?php } ?>
                </div>
              <?php } ?>
            </div>
          <?php } else { ?>
            <div id="image-empty">
              <img class="round3" src="<?php echo osc_base_url(); ?>oc-content/themes/<?php echo osc_current_web_theme(); ?>/images/item-no-picture.png" alt="<?php echo osc_esc_html(__('Seller did not upload any pictures', 'zara')); ?>" />
              <span><?php _e('Seller did not upload any pictures', 'zara'); ?></span>
            </div>
          <?php } ?>
        <?php } ?>

      </div>

      <div id="right">
        <h2><?php echo ucfirst(osc_item_title()); ?></h2>

        <div class="item-details">
          <?php if( osc_price_enabled_at_items() ) { ?>
            <div class="elem price">
              <div class="ins" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                <div class="left"><?php _e('Price', 'zara'); ?></div>
                <div class="right"><?php echo osc_item_formated_price(); ?></div>

                <meta itemprop="price" content="<?php echo $og_price; ?>" />

                <?php if(osc_item_price() <> '' and osc_item_price() <> 0) { ?>
                  <meta itemprop="priceCurrency" content="<?php echo osc_item_currency(); ?>" />
                <?php } ?>
              </div>
            </div>
          <?php } ?>

          <div class="elem">
            <div class="ins">
              <div class="left"><?php _e('Category', 'zara'); ?></div>
              <div class="right"><a class="tr1" href="<?php echo osc_search_category_url();?>"><?php echo osc_item_category(); ?></a></div>
            </div>
          </div>

          <div class="elem">
            <div class="ins">
              <div class="left"><?php _e('Listing ID', 'zara'); ?></div>
              <div class="right">#<?php echo osc_item_id(); ?></div>
            </div>
          </div>

          <div class="elem resp">
            <div class="ins">
              <div class="left"><?php _e('Viewed', 'zara'); ?></div>
              <div class="right"><?php echo osc_item_views(); ?></div>
            </div>
          </div>

          <?php if (osc_item_pub_date() != '') { ?>
            <div class="elem">
              <div class="ins">
                <div class="left"><?php _e('Publish date', 'zara'); ?></div>
                <div class="right"><?php echo osc_format_date(osc_item_pub_date()); ?></div>
              </div>
            </div>
          <?php } ?>

          <?php if (osc_item_mod_date() != '') { ?>
            <div class="elem">
              <div class="ins">
                <div class="left"><?php _e('Last modification', 'zara'); ?></div>
                <div class="right"><?php echo osc_format_date(osc_item_mod_date()); ?></div>
              </div>
            </div>
          <?php } ?>
        </div>
      </div>

      <?php $has_custom = false; ?>
      <?php if( osc_count_item_meta() >= 1 ) { ?>
        <div id="custom_fields">
          <h3><span><?php _e('Additional information', 'zara'); ?></span></h3>

          <div class="meta_list">
            <?php $class = 'odd'; ?>
            <?php while( osc_has_item_meta() ) { ?>
              <?php if(osc_item_meta_value()!='') { ?>
                <?php $has_custom = true; ?>
                <div class="meta <?php echo $class; ?>">
                  <div class="ins">
                    <span><?php echo osc_item_meta_name(); ?>:</span> <?php echo osc_item_meta_value(); ?>
                  </div>
                </div>
              <?php } ?>

              <?php $class = ($class == 'even') ? 'odd' : 'even'; ?>
            <?php } ?>
          </div>

        </div>
      <?php } ?>
      <div id="plugin-details">
        <?php osc_run_hook('item_detail', osc_item() ); ?> 
      </div>
      <!-- ITEM BUTTONS - SEND TO FRIEND / PRINT / MAKE FAVORITE -->
      <?php if(!$content_only) { ?>
        <div id="item-buttons">
          <?php if(function_exists('fi_make_favorite')) { echo fi_make_favorite(); } ?>

          <a id="send-friend" href="<?php echo osc_item_send_friend_url(); ?>" class="tr1" title="<?php echo osc_esc_html(__('Send this listing to your friend', 'zara')); ?>"><i class="fa fa-paper-plane tr1"></i></a>

          <?php if (function_exists('print_ad')) { ?>
            <div id="item-print-box">
              <?php print_ad(); ?>
            </div>
          <?php } ?>

          <?php if (function_exists('show_printpdf')) { ?>
            <a id="print_pdf" class="tr1" target="_blank" href="<?php echo osc_base_url(); ?>oc-content/plugins/printpdf/download.php?item=<?php echo osc_item_id(); ?>" title="<?php echo osc_esc_html(__('Show PDF sheet for this listing', 'zara')); ?>"><i class="fa fa-file-pdf-o tr1"></i></a>
          <?php } ?>
        </div>
      <?php } ?>


      <div id="more-info">
        <h2 class="sc-click"><span><?php _e('Description', 'zara'); ?></span></h2>
      
        <div class="item-description sc-block">
          <?php echo osc_item_description(); ?>
        </div>
        <!-- Related  Ads -->
              <?php if (function_exists('related_ads_start')) {related_ads_start();} ?>

        <?php if( osc_comments_enabled() && !$content_only) { ?>
          <div class="item-comments">

            <h2 class="sc-click">
              <span><?php _e('Comments', 'zara'); ?></span>

              <?php if( osc_reg_user_post_comments () && osc_is_web_user_logged_in() || !osc_reg_user_post_comments() ) { ?>
                <a class="add-com tr1 round2 non-resp" href="<?php echo osc_item_send_friend_url(); ?>"><i class="fa fa-plus"></i><?php _e('Add new', 'zara'); ?></a>
              <?php } ?>
            </h2>

            <!-- LIST OF COMMENTS -->
            <div id="comments" class="sc-block">
              <?php if( osc_reg_user_post_comments () && osc_is_web_user_logged_in() || !osc_reg_user_post_comments() ) { ?>
                <a class="add-com round2 resp is767" href="<?php echo osc_item_send_friend_url(); ?>"><i class="fa fa-plus"></i><?php _e('Add new comment', 'zara'); ?></a>
              <?php } ?>

              <?php if( osc_count_item_comments() >= 1 ) { ?>
                <?php if( osc_reg_user_post_comments () && !osc_is_web_user_logged_in() ) { ?>
                  <div class="empty"><?php _e('Comments can be published by registered users only.', 'zara'); ?> <a href="<?php echo osc_register_account_url(); ?>"><?php _e('Sign in', 'zara'); ?></a> <?php _e('and leave comment', 'zara'); ?>.</div>
                <?php } ?>
              <?php } else { ?>
                <?php if( osc_reg_user_post_comments () && !osc_is_web_user_logged_in() ) { ?>
                  <div class="empty"><?php _e('No comments added yet.', 'zara'); ?> <a href="<?php echo osc_register_account_url(); ?>"><?php _e('Log in', 'zara'); ?></a> <?php _e('and be first to leave comment!', 'zara'); ?></div>
                <?php } else { ?>
                  <div class="empty"><?php _e('No comments added yet. Be first to leave comment!', 'zara'); ?></div>
                <?php } ?>
              <?php } ?>

              
              <?php if( osc_count_item_comments() >= 1 ) { ?>
                <div class="comments_list">
                  <?php $class = 'even'; ?>
                  <?php while ( osc_has_item_comments() ) { ?>
                    <div class="comment-wrap <?php echo $class; ?>">
                      <div class="ins">
                        <div class="comment-image">
                          <?php if(function_exists('profile_picture_show')) { profile_picture_show(40, 'comment'); } ?>
                        </div>

                        <div class="comment">
                          <h4><span class="bold"><?php if(osc_comment_title() == '') { _e('Review', 'zara'); } else { echo osc_comment_title(); } ?></span> <?php _e('by', 'zara') ; ?> <?php if(osc_comment_title() == '') { _e('Anonymous', 'zara'); } else { echo osc_comment_author_name(); } ?>:</h4>
                          <div class="body"><?php echo osc_comment_body() ; ?></div>

                          <?php if ( osc_comment_user_id() && (osc_comment_user_id() == osc_logged_user_id()) ) { ?>
                            <a rel="nofollow" class="remove" href="<?php echo osc_delete_comment_url(); ?>" title="<?php echo osc_esc_html(__('Delete your comment', 'zara')); ?>"><?php _e('Delete', 'zara'); ?></a>
                          <?php } ?>
                        </div>
                      </div>
                    </div>

                    <div class="clear"></div>
                    <?php $class = ($class == 'even') ? 'odd' : 'even'; ?>
                  <?php } ?>

                  <div class="pagination"><?php echo osc_comments_pagination(); ?></div>
                </div>
              <?php } ?>
            </div>

          </div>
        <?php } ?>


        <!-- SELLER CONTACT FORM -->
        <?php if(!$content_only) { ?>
          <div id="show-c-seller-content">
            <div id="show-c-seller-form" class="fw-box">
              <div class="head">
                <h2><?php _e('Contact seller', 'zara'); ?></h2>
                <a href="#" class="def-but fw-close-button round3"><i class="fa fa-times"></i> <?php _e('Close', 'zara'); ?></a>
              </div>

              <?php if( osc_item_is_expired () ) { ?>
                <div class="empty">
                  <?php _e('This listing expired, you cannot contact seller.', 'zara') ; ?>
                </div>
              <?php } else if( (osc_logged_user_id() == osc_item_user_id()) && osc_logged_user_id() != 0 ) { ?>
                <div class="empty">
                  <?php _e('It is your own listing, you cannot contact yourself.', 'zara') ; ?>
                </div>
              <?php } else if( osc_reg_user_can_contact() && !osc_is_web_user_logged_in() ) { ?>
                <div class="empty">
                  <?php _e('You must log in or register a new account in order to contact the advertiser.', 'zara') ; ?>
                </div>
              <?php } else { ?> 
                  

                <div class="left">
                  <img src="<?php echo osc_base_url(); ?>oc-content/themes/<?php echo osc_current_web_theme(); ?>/images/contact-seller-form.jpg" />
                </div>

                <div class="middle">
                  <ul id="error_list"></ul>
                  <?php ContactForm::js_validation(); ?>

                  <form action="<?php echo osc_base_url(true) ; ?>" method="post" name="contact_form" id="contact_form">
                    <input type="hidden" name="action" value="contact_post" />
                    <input type="hidden" name="page" value="item" />
                    <input type="hidden" name="id" value="<?php echo osc_item_id() ; ?>" />

                    <?php osc_prepare_user_info() ; ?>

                    <fieldset>
                      <div class="row first">
                        <label><?php _e('Name', 'zara') ; ?></label>
                        <?php ContactForm::your_name(); ?>
                      </div>

                      <div class="row second">
                        <label><span><?php _e('E-mail', 'zara'); ?></span><span class="req">*</span></label>
                        <?php ContactForm::your_email(); ?>
                      </div>

                      <div class="row third">
                        <label><span><?php _e('Phone number', 'zara'); ?></span></label>
                        <?php ContactForm::your_phone_number(); ?>
                      </div>

                      <div class="row full">
                        <label><span><?php _e('Message', 'zara') ; ?></span><span class="req">*</span></label>
                        <?php ContactForm::your_message(); ?>
                      </div>
                      <div class="req-what"><div class="req">*</div><div class="small-info"><?php _e('This field is required', 'zara'); ?></div></div>

                      <?php osc_run_hook('item_contact_form', osc_item_id()); ?>

                      <!-- ReCaptcha -->
                      <?php if( osc_recaptcha_public_key() ) { ?>
                        <script type="text/javascript">
                          var RecaptchaOptions = {
                            theme : 'custom',
                            custom_theme_widget: 'recaptcha_widget'
                          };
                        </script>

                        <div id="recaptcha_widget">
                          <div id="recaptcha_image"><img /></div>
                          <span class="recaptcha_only_if_image"><?php _e('Enter the words above','zara'); ?>:</span>
                          <input type="text" id="recaptcha_response_field" name="recaptcha_response_field" />
                          <div><a href="javascript:Recaptcha.showhelp()"><?php _e('Help', 'zara'); ?></a></div>
                        </div>
                      <?php } ?>
                      <?php osc_show_recaptcha(); ?>

                      <button type="submit" id="blue"><?php _e('Send message', 'zara') ; ?></button>
                    </fieldset>
                  </form>    
                
                </div>
          
          
              <?php } ?>
            </div>
          </div>
        <?php } ?>
        
        <!-- messenger form content -->
        <?php if(!$content_only) { ?>
          <div id="show-msg-seller-content">
            <div id="show-msg-seller-form" class="fw-box">
              <div class="head">
                <h2><?php _e('Quick Contact', 'zara'); ?></h2>
                <a href="#" class="def-but fw-close-button round3"><i class="fa fa-times"></i> <?php _e('Close', 'zara'); ?></a>
              </div>

              <?php if( osc_item_is_expired () ) { ?>
                <div class="empty">
                  <?php _e('This listing expired, you cannot contact seller.', 'zara') ; ?>
                </div>
              <?php } else if( (osc_logged_user_id() == osc_item_user_id()) && osc_logged_user_id() != 0 ) { ?>
                <div class="empty">
                  <?php _e('It is your own listing, you cannot contact yourself.', 'zara') ; ?>
                </div>
              <?php } else if( osc_reg_user_can_contact() && !osc_is_web_user_logged_in() ) { ?>
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
        <?php } ?>
	<!-- end messenger content -->
      </div>
    </div>
<!-- messager -->


    <!-- RIGHT SIDEBAR -->
    <div id="side-right">
 
      <!- SELLER INFO -->
      <div id="seller" <?php if(osc_item_user_id() == 0) { ?>class="unreg"<?php } ?>>
        <h2 class="sc-click">
          <?php if(osc_is_web_user_logged_in() && osc_item_user_id() == osc_logged_user_id()) { ?>
            <div class="left"><i class="fa fa-wrench"></i></div>
            <?php _e('Seller\'s tools', 'zara'); ?>
          <?php } else { ?>
            <div class="left"><i class="fa fa-user"></i></div>
            <?php _e('Seller\'s info', 'zara'); ?>
          <?php } ?>
        </h2>

        <div class="body sc-block">

          <!-- IF USER OWN THIS LISTING, SHOW SELLER TOOLS -->
          <?php if(osc_is_web_user_logged_in() && osc_item_user_id() == osc_logged_user_id()) { ?>
            <div id="s-tools">
              <div class="text"><?php _e('You are seller of this item and therefore you can edit or delete it.', 'zara'); ?></div>
              <a href="<?php echo osc_item_edit_url(); ?>" class="tr1"><i class="fa fa-edit tr1"></i><?php _e('Edit listing', 'zara'); ?></a>
              <a href="<?php echo osc_item_delete_url(); ?>" class="tr1" onclick="return confirm('<?php _e('Are you sure you want to delete this listing? This action cannot be undone.', 'zara'); ?>?')"><i class="fa fa-trash-o tr1"></i><?php _e('Delete listing', 'zara'); ?></a>
            </div>
          <?php } else { ?>
  
            <!-- USER IS NOT OWNER OF LISTING -->
            <?php if(function_exists('profile_picture_show')) { ?>
              <?php if(osc_item_user_id() <> 0 and osc_item_user_id() <> '') { ?>
                <a href="<?php echo osc_user_public_profile_url(osc_item_user_id()); ?>" title="<?php echo osc_esc_html(__('Check profile of this user', 'zara')); ?>">
                  <?php profile_picture_show(null, 'item', 200); ?>
                </a>
              <?php } else { ?>
                <?php profile_picture_show(null, 'item', 200); ?>
              <?php } ?>
             
            <?php } ?>

            <div class="name">
              <?php
                $c_name = '';
                if(osc_item_contact_name() <> '' and osc_item_contact_name() <> __('Anonymous', 'zara')) {
                  $c_name = osc_item_contact_name();
                }

                if($c_name == '' and $item_user['s_name'] <> '') { 
                  $c_name = $item_user['s_name'];
                }

                if($c_name == '') {
                  $c_name = __('Anonymous', 'zara');
                }
              ?>

              <?php if(osc_item_user_id() <> 0 and osc_item_user_id() <> '') { ?>
                <a href="<?php echo osc_user_public_profile_url(osc_item_user_id()); ?>" title="<?php echo osc_esc_html(__('Check profile of this user', 'zara')); ?>">
                  <?php echo $c_name; ?>
                </a>
              <?php } else { ?>
                <?php echo $c_name; ?>
              <?php } ?>
            </div>
            <?php if(function_exists('show_feedback_overall')) { ?>
              <div class="elem feedback"><?php echo show_feedback_overall(); ?></div>
            <?php } ?>

            <?php if(osc_item_user_id() <> 0) { ?>
              <div class="elem type">
                <?php $user = User::newInstance()->findByPrimaryKey(osc_item_user_id() ); ?>
                <?php if($user['b_company'] == 1) { ?>
                  <span><i class="fa fa-users"></i> <?php _e('Company', 'zara'); ?></span>
                <?php } else { ?>
                  <span><i class="fa fa-user"></i> <?php _e('Private person', 'zara'); ?></span>
                <?php } ?>
              </div>
            <?php } ?>

            <div class="elem regdate">
              <?php if(osc_item_user_id() <> 0) { ?>
                <?php echo __('Registered on', 'zara') . ' ' . osc_format_date(osc_user_regdate()); ?>
              <?php } else { ?>
                <?php echo __('Unregistered user', 'zara'); ?>
              <?php } ?>
            </div>
            <?php if(osc_item_user_id() <> 0 && !$content_only) { ?>
              <div class="seller-bottom">
                <?php if(function_exists('seller_post') && !$content_only) { ?>
                  <?php seller_post(); ?>
                <?php } ?>

                <a href="<?php echo osc_user_public_profile_url(osc_item_user_id()); ?>"><?php _e('Dashboard', 'zara'); ?></a>
              </div>
            <?php } ?>
          <?php } ?>
        </div>
      </div>

      <?php 
        if(osc_item_user_id() <> 0) {
          $item_user = User::newInstance()->findByPrimaryKey(osc_item_user_id());
        }
      ?>


      <!-- CONTACT SELLER OPTIONS - DESKTOP VIEW -->
      <div class="non-resp is1200">
        <!-- CLICK TO CALL BUTTON -->
        <?php 
          $mobile = '';
          if($mobile == '') { $mobile = osc_item_city_area(); }      
          if($mobile == '' && osc_item_user_id() <> 0) { $mobile = $item_user['s_phone_mobile']; }      
          if($mobile == '' && osc_item_user_id() <> 0) { $mobile = $item_user['s_phone_land']; }      
          if($mobile == '') { $mobile = __('No phone number', 'zara'); }      
        ?> 

        <a class="phone-show tr1" href="#" rel="<?php echo $mobile; ?>" title="<?php echo osc_esc_html(__('Click to show phone number', 'zara')); ?>">
          <div class="left tr1">
            <i class="fa fa-mobile"></i>
          </div>

          <div class="right">
            <span class="top tr1" rel=" - <?php _e('click to call', 'zara'); ?> -">- <?php echo $mobile == __('No phone number', 'zara') ? '- - -' : __('click to show', 'zara'); ?> -</span>
            <span class="bottom tr1">
              <?php 
                if(strlen($mobile) > 3 and $mobile <> __('No phone number', 'zara')) {
                  echo substr($mobile, 0, strlen($mobile) - 3) . 'XXX'; 
                } else {
                  echo $mobile;
                }
              ?>
            </span>
          </div>
        </a>

        <?php if( osc_item_show_email() ) { ?>
          <?php $mail_first = substr(osc_item_contact_email(), 0, strrpos( osc_item_contact_email(), '.') ); ?>
          <?php $mail_last = substr(osc_item_contact_email(), strrpos(osc_item_contact_email(), '.') + 1); ?>
          

          <div class="email-show tr1" rel="<?php echo strrev($mail_first) . '.' . $mail_last; ?>">
            <div class="left tr1"><i class="fa fa-at"></i></div>
            <div class="right tr1 noselect">
              <?php echo '<span>' . strrev($mail_first) . '</span>.' . $mail_last; ?>
            </div>
          </div>
        <?php } ?>

        <?php 
          if( osc_item_is_expired()  or (osc_logged_user_id() == osc_item_user_id() && osc_logged_user_id() != 0 ) or ( osc_reg_user_can_contact() && !osc_is_web_user_logged_in() )) {
            $c_seller_class = 'is_empty';
          } else {
            $c_seller_class = '';
          }
        ?>

        <?php if(!$content_only) { ?>
          <div class="c-seller tr1<?php echo ' ' . $c_seller_class; ?>">
            <div class="left tr1"><i class="fa fa-envelope-o"></i></div>
            <div class="right tr1">
              <?php _e('Contact seller', 'zara') ; ?>
            </div>
          </div>
           
        <?php } ?>
        
        <!-- MESSENGER BUTTON -->
        <?php
		if(osc_is_web_user_logged_in() && osc_item_user_id() != osc_logged_user_id()){?>
         <a title="<?php _e('For registered users only', 'zara');?>" id="quickmsg"  href="#">   <div class="msg-seller">
              <div class="left tr"><i class="fa fa-comment-o"></i></div>
              <div class="right tr"><?php _e('Quick Contact', 'zara');?></div>
          </div> 
          </a> <?php }?>

      </div>
      

      <!-- ITEM LOCATION -->
      <div id="location">
        <h2 class="sc-click">
          <div class="left"><i class="fa fa-map-marker"></i></div>
          <?php _e('Listing location', 'zara') ; ?>
        </h2>

        <div class="body sc-block">
          <div class="loc-text">
            <?php if(trim(osc_item_country() . osc_item_region() . osc_item_city()) == '') {?>
              <div class="empty"><?php _e('Location of item was not specified', 'zara'); ?></div>
            <?php } ?>

            <?php
              $location_array = array(osc_item_country(), osc_item_region(), osc_item_city());
              $location_array = array_filter($location_array);
              $item_loc = implode(', ', $location_array);
            ?>

            <?php if($item_loc <> '') { ?>
              <div class="elem"><?php echo $item_loc; ?></div>
            <?php } ?>

            <?php if(osc_item_address() <> '') { ?>
              <div class="elem"><?php echo osc_item_address(); ?></div>
            <?php } ?>
          </div>

          <div class="map">
            <?php osc_run_hook('location') ; ?>
          </div>  
        </div>  
      </div>


      <!-- CONTACT SELLER OPTIONS - MOBILE VIEW -->
      <div class="contact-options-resp resp is767">
        <!-- CLICK TO CALL BUTTON -->
        <?php 
          $mobile = '';
          if($mobile == '') { $mobile = osc_item_city_area(); }      
          if($mobile == '' && osc_item_user_id() <> 0) { $mobile = $item_user['s_phone_mobile']; }      
          if($mobile == '' && osc_item_user_id() <> 0) { $mobile = $item_user['s_phone_land']; }      
          if($mobile == '') { $mobile = __('No phone number', 'zara'); }      
        ?> 

        <a class="phone-show tr1" href="#" rel="<?php echo $mobile; ?>" title="<?php echo osc_esc_html(__('Click to show phone number', 'zara')); ?>">
          <div class="left tr1">
            <i class="fa fa-mobile"></i>
          </div>

          <div class="right">
            <span class="top tr1" rel=" - <?php _e('click to call', 'zara'); ?> -">- <?php echo $mobile == __('No phone number', 'zara') ? '- - -' : __('click to show', 'zara'); ?> -</span>
            <span class="bottom tr1">
              <?php 
                if(strlen($mobile) > 3 and $mobile <> __('No phone number', 'zara')) {
                  echo substr($mobile, 0, strlen($mobile) - 3) . 'XXX'; 
                } else {
                  echo $mobile;
                }
              ?>
            </span>
          </div>
        </a>

        <?php if( osc_item_show_email() ) { ?>
          <?php $mail_first = substr(osc_item_contact_email(), 0, strrpos( osc_item_contact_email(), '.') ); ?>
          <?php $mail_last = substr(osc_item_contact_email(), strrpos(osc_item_contact_email(), '.') + 1); ?>
          

          <div class="email-show tr1" rel="<?php echo strrev($mail_first) . '.' . $mail_last; ?>">
            <div class="left tr1"><i class="fa fa-at"></i></div>
            <div class="right tr1 noselect">
              <?php echo '<span>' . strrev($mail_first) . '</span>.' . $mail_last; ?>
            </div>
          </div>
        <?php } ?>
       
        <?php 
          if( osc_item_is_expired()  or (osc_logged_user_id() == osc_item_user_id() && osc_logged_user_id() != 0 ) or ( osc_reg_user_can_contact() && !osc_is_web_user_logged_in() )) {
            $c_seller_class = 'is_empty';
          } else {
            $c_seller_class = '';
          }
        ?>
        
        <?php if(!$content_only) { ?>
          <div class="c-seller tr1<?php echo ' ' . $c_seller_class; ?>">
            <div class="left tr1"><i class="fa fa-envelope-o"></i></div>
            <div class="right tr1">
              <?php _e('Contact seller', 'zara') ; ?>
            </div>
          </div>
        <?php } ?>
      </div>


      <!-- LISTING SHARE LINKS -->
      <?php if(!$content_only) { ?>
        <!-- SOCIAL SHARING -->
        <div class="listing-share">
          <?php osc_reset_resources(); ?>
          <a class="single single-facebook" title="<?php echo osc_esc_html(__('Share on Facebook', 'zara')); ?>" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo osc_item_url(); ?>"><i class="fa fa-facebook-square"></i></a> 
          <a class="single single-google-plus" title="<?php echo osc_esc_html(__('Share on Google Plus', 'zara')); ?>" target="_blank" href="https://plus.google.com/share?url=<?php echo osc_item_url(); ?>"><i class="fa fa-google-plus-square"></i></a> 
          <a class="single single-twitter" title="<?php echo osc_esc_html(__('Share on Twitter', 'zara')); ?>" target="_blank" href="https://twitter.com/home?status=<?php echo osc_item_title(); ?>"><i class="fa fa-twitter-square"></i></a> 
         <!-- <a class="single single-pinterest" title="<?php echo osc_esc_html(__('Share on Pinterest', 'zara')); ?>" target="_blank" href="https://pinterest.com/pin/create/button/?url=<?php echo osc_item_url(); ?>&media=<?php echo osc_resource_url(); ?>&description=<?php echo htmlspecialchars(osc_item_title()); ?>"><i class="fa fa-pinterest-square"></i></a> -->
        </div>

        <?php if (function_exists('show_qrcode')) { ?>
          <div class="qr-right noselect">
            <?php show_qrcode(); ?>
          </div>
        <?php } ?>

        <?php if(osc_get_preference('theme_adsense', 'zara_theme') == 1) { ?>
          <?php if(osc_get_preference('banner_item', 'zara_theme') <> '') { ?>
            <div class="item-google">
              <?php echo osc_get_preference('banner_item', 'zara_theme'); ?>
            </div>        
          <?php } ?>
        <?php } ?>
      <?php } ?>
    </div>


    <?php if(!$content_only) { ?>
      <?php if(function_exists('related_ads_start')) { related_ads_start(); } ?>
    <?php } ?>
  </div>

  <?php if($content_only) { ?>
    <div class="visit-wrap">
      <a id="visit" target="_parent" href="<?php echo osc_item_url(); ?>"><?php _e('View details of this listing', 'zara'); ?> <i class="fa fa-angle-double-right"></i></a>
    </div>
  <?php } ?>

  <div id="show-email-form-content">
    <div id="show-email-form" class="fw-box">
      <div class="head">
        <h2><?php _e('Seller\'s email address', 'zara'); ?></h2>
        <a href="#" class="def-but fw-close-button round3"><i class="fa fa-times"></i> <?php _e('Close', 'zara'); ?></a>
      </div>

      <div class="left">
        <img src="<?php echo osc_base_url(); ?>oc-content/themes/<?php echo osc_current_web_theme(); ?>/images/show-email-banner.jpg" />
      </div>

      <div class="middle">
        <div class="big-mail noselect"><?php echo '<span>' . strrev(isset($mail_first) ? $mail_first : '') . '</span>.' . (isset($mail_last) ? $mail_last : ''); ?></div>

        <div class="text">- <?php _e('you cannot copy & paste mail, you need to rewrite it', 'zara'); ?></div>
        <div class="text">- <?php _e('do not send spam', 'zara'); ?></div>
        <div class="text">- <?php _e('ask only questions related to this product', 'zara'); ?></div>
        <div class="text">- <?php _e('do not be rude', 'zara'); ?></div>
        
      </div>
    </div>     
  </div>     

  <script type="text/javascript">
    $(document).ready(function(){
      // WRAP TEXT IN H2 & H3 IN ATTRIBUTES PLUGIN INTO SPAN
      $('#plugin-details h2, #plugin-details h3').each(function(){
        $(this).html('<span>' + $(this).text() + '</span>');
      });

      // SHOW PHONE NUMBER ON CLICK
      <?php if($mobile <> __('No phone number', 'zara')) { ?>  
        $('.phone-show').click(function(){
          if($(this).attr('href') == '#') {
            $('.phone-show span.bottom').text($('.phone-show').attr('rel')).css('font-weight', 'bold');
            $('.phone-show span.top').text($('.phone-show span.top').attr('rel'));
            $('.phone-show').attr('href', 'tel:' + $('.phone-show').attr('rel'));
            return false;
          }
        });
      <?php } else { ?>
        $('.phone-show').click(function(){
          return false;
        });
      <?php } ?>


      // PLACEHOLDERS FOR CONTACT SELLER FORM
      $('#contact_form #yourName').attr('placeholder', '<?php echo osc_esc_js(__('Your name', 'zara')); ?>');
      $('#contact_form #yourEmail').attr('placeholder', '<?php echo osc_esc_js(__('Contact email', 'zara')); ?>');
      $('#contact_form #phoneNumber').attr('placeholder', '<?php echo osc_esc_js(__('Contact mobile/phone', 'zara')); ?>');
      $('#contact_form #message').attr('placeholder', '<?php echo osc_esc_js(__('I would like to ask you...', 'zara')); ?>');

      // PLACEHOLDERS FOR CONTACT SELLER FORM
      $('#comment_form #authorName').attr('placeholder', '<?php echo osc_esc_js(__('Your name', 'zara')); ?>');
      $('#comment_form #authorEmail').attr('placeholder', '<?php echo osc_esc_js(__('Contact email', 'zara')); ?>');
      $('#comment_form #title').attr('placeholder', '<?php echo osc_esc_js(__('Comment title', 'zara')); ?>');
      $('#comment_form #body').attr('placeholder', '<?php echo osc_esc_js(__('I would like to share my experience...', 'zara')); ?>');
    });
  </script>
     
  <!-- Scripts -->
  <script type="text/javascript">
  $(document).ready(function(){
    $('.comment-wrap').hover(function(){
      $(this).find('.hide').fadeIn(200);}, 
      function(){
      $(this).find('.hide').fadeOut(200);
    });

    $('.comment-wrap .hide').click(function(){
      $(this).parent().fadeOut(200);
    });

    $('#but-con').click(function(){
      $(".inner-block").slideToggle();
      $("#rel_ads").slideToggle();
    }); 

    
    <?php if(!$has_custom) { echo '$("#custom_fields").hide();';} ?>
  });
  </script>


  <?php if(!$content_only) { ?>
    <?php osc_current_web_theme_path('footer.php') ; ?>
  <?php } ?>
</body>
</html>			