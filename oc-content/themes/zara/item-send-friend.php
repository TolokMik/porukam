<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
<head>
  <?php osc_current_web_theme_path('head.php') ; ?>
  <meta name="robots" content="noindex, nofollow" />
  <meta name="googlebot" content="noindex, nofollow" />
  <script type="text/javascript" src="<?php echo osc_current_web_theme_js_url('jquery.validate.min.js') ; ?>"></script>
  <script type="text/javascript">var RecaptchaOptions = {theme : 'white', custom_theme_widget: 'recaptcha_widget'};</script>
</head>

<body class="fw-supporting">
  <div style="display:none!important;"><?php osc_current_web_theme_path('header.php'); ?></div></div></div>
  <?php $type = Params::getParam('content_type'); ?>
  <?php $user_id = Params::getParam('user_id'); ?>


  <!-- SEND TO FRIEND FORM -->

  <?php if($type == 'send_friend') { ?>
    <div id="send-friend-form" class="fw-box" style="display:block;">
      <div class="head">
        <h2><?php _e('Send to friend', 'zara'); ?></h2>
        <a href="#" class="def-but fw-close-button round3"><i class="fa fa-times"></i> <?php _e('Close', 'zara'); ?></a>
      </div>

      <div class="left">
        <img src="<?php echo osc_base_url(); ?>oc-content/themes/<?php echo osc_current_web_theme(); ?>/images/contact-seller-form.jpg" />
      </div>

      <div class="middle">
        <ul id="error_list"></ul>

        <form target="_top" id="sendfriend" name="sendfriend" action="<?php echo osc_base_url(true); ?>" method="post">
          <fieldset>
            <input type="hidden" name="action" value="send_friend_post" />
            <input type="hidden" name="page" value="item" />
            <input type="hidden" name="id" value="<?php echo osc_item_id(); ?>" />

            <?php if(osc_is_web_user_logged_in()) { ?>
              <input type="hidden" name="yourName" value="<?php echo osc_esc_html( osc_logged_user_name() ); ?>" />
              <input type="hidden" name="yourEmail" value="<?php echo osc_logged_user_email();?>" />
            <?php } else { ?>
              <div class="row">
                <div class="ins">
                  <label for="yourName"><span><?php _e('Your name', 'zara'); ?></span><div class="req">*</div></label> 
                  <?php SendFriendForm::your_name(); ?>
                </div>

                <div class="ins">
                  <label for="yourEmail"><span><?php _e('Your e-mail address', 'zara'); ?></span><div class="req">*</div></label>
                  <?php SendFriendForm::your_email(); ?>
                </div>
              </div>
            <?php } ?>

            <div class="row">
              <div class="ins">
                <label for="friendName"><span><?php _e("Your friend's name", 'zara'); ?></span><div class="req">*</div></label>
                <?php SendFriendForm::friend_name(); ?>
              </div>

              <div class="ins">
                <label for="friendEmail"><span><?php _e("Your friend's e-mail address", 'zara'); ?></span><div class="req">*</div></label>
                <?php SendFriendForm::friend_email(); ?>
              </div>
            </div>
                  
            <div class="row last">        
              <?php SendFriendForm::your_message(); ?>
            </div>
            <div class="req-what"><div class="req">*</div><div class="small-info"><?php _e('This field is required', 'zara'); ?></div></div>

            <?php osc_show_recaptcha(); ?>
            <button type="submit" id="blue"><?php _e('Send message', 'zara'); ?></button>
          </fieldset>
        </form>

        <?php SendFriendForm::js_validation(); ?>
      </div>
    </div>
  <?php } ?>

 


  <!-- NEW COMMENT FORM -->

  <?php if($type == 'add_comment') { ?>
    <?php if( osc_comments_enabled() && (osc_reg_user_post_comments () && osc_is_web_user_logged_in() || !osc_reg_user_post_comments()) ) { ?>
      <form target="_top" action="<?php echo osc_base_url(true) ; ?>" method="post" name="comment_form" id="comment_form" class="fw-box" style="display:block;">
        <input type="hidden" name="action" value="add_comment" />
        <input type="hidden" name="page" value="item" />
        <input type="hidden" name="id" value="<?php echo osc_item_id() ; ?>" />

        <fieldset>
          <div class="head">
            <h2><?php _e('Add new comment', 'zara'); ?></h2>
            <a href="#" class="def-but fw-close-button round3"><i class="fa fa-times"></i> <?php _e('Close', 'zara'); ?></a>
          </div>

          <div class="left">
            <img src="<?php echo osc_base_url(); ?>oc-content/themes/<?php echo osc_current_web_theme(); ?>/images/post-comment-form.jpg" />
          </div>


          <div class="middle">
            <?php CommentForm::js_validation(); ?>
            <ul id="comment_error_list"></ul>

            <?php if(osc_is_web_user_logged_in()) { ?>
              <input type="hidden" name="authorName" value="<?php echo osc_esc_html( osc_logged_user_name() ); ?>" />
              <input type="hidden" name="authorEmail" value="<?php echo osc_logged_user_email();?>" />
            <?php } else { ?>
              <div class="row">
                <label for="authorName"><?php _e('Name', 'zara') ; ?></label> 
                <?php CommentForm::author_input_text(); ?>
              </div>

              <div class="row">
                <label for="authorEmail"><span><?php _e('E-mail', 'zara') ; ?></span><span class="req">*</span></label> 
                <?php CommentForm::email_input_text(); ?>
              </div>                  
            <?php } ?>

            <div class="row" id="last">
              <label for="title"><?php _e('Title', 'zara') ; ?></label>
              <?php CommentForm::title_input_text(); ?>
            </div>
        
            <div class="row">
              <label for="body"><?php _e('Comment', 'zara') ; ?></label>
              <?php CommentForm::body_input_textarea(); ?>
            </div>

            <div class="req-what"><div class="req">*</div><div class="small-info"><?php _e('This field is required', 'zara'); ?></div></div>
            <button type="submit" id="blue"><?php _e('Send comment', 'zara') ; ?></button>
          </div>
        </fieldset>
      </form>
    <?php } ?>
  <?php } ?>



  <!-- PUBLIC PROFILE CONTACT SELLER -->

  <?php if($type == 'pub_contact') { ?>
    <?php if(osc_reg_user_can_contact() && osc_is_web_user_logged_in() || !osc_reg_user_can_contact() ) { ?>
      <form target="_top" action="<?php echo osc_base_url(true) ; ?>" method="post" name="contact_form" id="contact_form" class="fw-box" style="display:block;">
        <input type="hidden" name="action" value="contact_post" class="nocsrf" />
        <input type="hidden" name="page" value="user" />
        <input type="hidden" name="id" value="<?php echo $user_id; ?>" />

        <fieldset>
          <div class="head">
            <h2><?php _e('Contact seller', 'zara'); ?></h2>
            <a href="#" class="def-but fw-close-button round3"><i class="fa fa-times"></i> <?php _e('Close', 'zara'); ?></a>
          </div>

          <div class="left">
            <img src="<?php echo osc_base_url(); ?>oc-content/themes/<?php echo osc_current_web_theme(); ?>/images/contact-seller-form.jpg" />
          </div>


          <div class="middle">
            <?php ContactForm::js_validation(); ?>
            <ul id="error_list"></ul>

            <div class="row">
              <label for="yourName"><?php _e('Name', 'zara'); ?></label> 
              <?php ContactForm::your_name(); ?>
            </div>

            <div class="row">
              <label for="yourEmail"><span><?php _e('E-mail', 'zara') ; ?></span><span class="req">*</span></label> 
              <?php ContactForm::your_email(); ?>
            </div>                  

            <div class="row last">
              <label for="phoneNumber"><span><?php _e('Phone number', 'zara') ; ?></span></label>
              <?php ContactForm::your_phone_number(); ?>
            </div>

            <div class="row">
              <label for="message"><?php _e('Message', 'zara'); ?></label>
              <?php ContactForm::your_message(); ?>
            </div>

            <div class="req-what"><div class="req">*</div><div class="small-info"><?php _e('This field is required', 'zara'); ?></div></div>

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
          </div>
        </fieldset>
      </form>
    <?php } ?>
  <?php } ?>



  <!-- LOCATION SELECT BUTTON ON HOME PAGE -->

  <?php if($type == 'location_select') { ?>
    <form target="_top" action="<?php echo osc_base_url(true); ?>" method="get" id="home-loc-box" class="fw-box nocsrf" style="display:block;">
      <input type="hidden" name="page" value="search" />
      <input type="hidden" name="cookie-action-side" id="cookie-action-side" value="done" />
      <input type="hidden" name="sOrder" value="<?php echo osc_search_order(); ?>" />
      <input type="hidden" name="iOrderType" value="<?php $allowedTypesForSorting = Search::getAllowedTypesForSorting() ; echo $allowedTypesForSorting[osc_search_order_type()]; ?>" />

      <fieldset>
        <div class="head">
          <h2><?php _e('Select location', 'zara'); ?></h2>
          <a href="#" class="def-but fw-close-button round3"><i class="fa fa-times"></i> <?php _e('Close', 'zara'); ?></a>
        </div>

        <div class="left">
          <img src="<?php echo osc_base_url(); ?>oc-content/themes/<?php echo osc_current_web_theme(); ?>/images/home-loc-form.jpg" />
        </div>


        <div class="middle">
           <?php $aCountries = Country::newInstance()->listAll(); ?>
        
          <div class="row">
            <h4><?php _e('Country', 'zara') ; ?></h4>

            <?php
              // IF THERE IS JUST 1 COUNTRY, PRE-SELECT IT TO ENABLE REGION DROPDOWN
              $s_country = Country::newInstance()->listAll();
              if(count($s_country) <= 1) {
                $s_country = $s_country[0];
              }
            ?>

            <select id="countryId" name="sCountry">
              <option value=""><?php _e('Select a country', 'zara'); ?></option>

              <?php foreach ($aCountries as $country) {?>
                <option value="<?php echo $country['pk_c_code']; ?>" <?php if(Params::getParam('sCountry') <> '' && (Params::getParam('sCountry') == $country['pk_c_code'] or Params::getParam('sCountry') == $country['s_name']) or $s_country['pk_c_code'] <> '' && $s_country['pk_c_code'] = $country['pk_c_code']) { ?>selected="selected"<?php } ?>><?php echo $country['s_name'] ; ?></option>

                <?php 
                  if(Params::getParam('sCountry') <> '' && (Params::getParam('sCountry') == $country['pk_c_code'] or Params::getParam('sCountry') == $country['s_name']) or $s_country['pk_c_code'] <> '' && $s_country['pk_c_code'] = $country['pk_c_code']) {
                    $current_country_code = $country['pk_c_code'];
                  } 
                ?>
              <?php } ?>
            </select>
          </div>

        
          <?php
            $current_country = Params::getParam('country') <> '' ? Params::getParam('country') : Params::getParam('sCountry');
            if($current_country <> '') {
              $aRegions = Region::newInstance()->findByCountry($current_country_code);
            } else {
              if(osc_count_countries() <= 1) {
                $aRegions = Region::newInstance()->findByCountry($s_country['pk_c_code']);
              } else {
                $aRegions = '';
              }
            }
          ?>

          <div class="row">
            <h4><?php _e('Region', 'zara') ; ?></h4>

            <?php if(count($aRegions) >= 1 ) { ?>
              <select id="regionId" name="sRegion" <?php if(Params::getParam('sRegion') == '' && Params::getParam('region')) {?>disabled<?php } ?>>
                <option value=""><?php _e('Select a region', 'zara'); ?></option>
                
                <?php foreach ($aRegions as $region) {?>
                  <option value="<?php echo $region['pk_i_id']; ?>" <?php if(Params::getParam('sRegion') == $region['pk_i_id'] or Params::getParam('sRegion') == $region['s_name']) { ?>selected="selected"<?php } ?>><?php echo $region['s_name']; ?></option>
                <?php } ?>
              </select>
            <?php } else { ?>
              <input type="text" name="sRegion" id="sRegion-side" value="<?php echo Params::getParam('sRegion'); ?>" placeholder="<?php echo osc_esc_html(__('Enter a region', 'zara')); ?>" />
            <?php } ?>
          </div>
        
          <?php 
            $current_region = Params::getParam('region') <> '' ? Params::getParam('region') : Params::getParam('sRegion');

            if(!is_numeric($current_region) && $current_region <> '') {
              $reg = Region::newInstance()->findByName($current_region);
              $current_region = $reg['pk_i_id'];
            }

            if($current_region <> '' && !empty($current_region)) {
              $aCities = City::newInstance()->findByRegion($current_region);
            } else {
              $aCities = '';
            }
          ?> 

          <div class="row">
            <h4><?php _e('City', 'zara') ; ?></h4>

            <?php if(count($aCities) >= 1 && !empty($aCities) ) { ?>
              <select name="sCity" id="cityId" <?php if(Params::getParam('sCity') == '' && Params::getParam('city') == '') {?>disabled<?php } ?>> 
                <option value=""><?php _e('Select a city', 'zara'); ?></option>
          
                <?php foreach ($aCities as $city) {?>
                  <option value="<?php echo $city['pk_i_id']; ?>" <?php if(Params::getParam('sCity') == $city['pk_i_id'] or Params::getParam('sCity') == $city['s_name']) { ?>selected="selected"<?php } ?>><?php echo $city['s_name']; ?></option>
                <?php } ?>
              </select>
            <?php } else { ?>
              <input type="text" name="sCity" id="sCity-side" value="<?php echo Params::getParam('sCity'); ?>" placeholder="<?php echo osc_esc_html(__('Enter a city', 'zara')); ?>" />
            <?php } ?>
          </div>
          
          <div class="row r-but">
            <button type="submit" id="blue"><?php _e('Search', 'zara') ; ?></button>
          </div>

          <div class="row r-clean">
            <a target="_top" href="<?php echo osc_search_url(array('page' => 'search', 'cookie-action-side' => 'done'));?>" class="clear-search tr1" title="<?php  echo osc_esc_html(__('Clear location', 'zara')); ?>"><i class="fa fa-eraser"></i><?php _e('Clean search', 'zara'); ?></a>
          </div>
        </div>
      </fieldset>
    </form>
  <?php } ?>



  <script>
    $(document).ready(function(){
      // PLACEHOLDERS FOR SEND TO FRIEND FORM
      $('#sendfriend #yourName').attr('placeholder', '<?php echo osc_esc_js(__('Your real or user name', 'zara')); ?>');
      $('#sendfriend #yourEmail').attr('placeholder', '<?php echo osc_esc_js(__('Contact email', 'zara')); ?>');
      $('#sendfriend #friendName').attr('placeholder', '<?php echo osc_esc_js(__('Name of your friend', 'zara')); ?>');
      $('#sendfriend #friendEmail').attr('placeholder', '<?php echo osc_esc_js(__('Friend\'s email', 'zara')); ?>');
      $('#sendfriend #message').attr('placeholder', '<?php echo osc_esc_js(__('I would like to ask you...', 'zara')); ?>');


      // PLACEHODERS FOR PUBLIC PROFILE CONTACT SELLER FORM
      $('#yourName').attr('placeholder', '<?php echo osc_esc_js(__('Your real or user name', 'zara')); ?>');
      $('#yourEmail').attr('placeholder', '<?php echo osc_esc_js(__('Contact email', 'zara')); ?>');
      $('#phoneNumber').attr('placeholder', '<?php echo osc_esc_js(__('Phone seller can contact you', 'zara')); ?>');

      <?php if(osc_is_web_user_logged_in()) { ?>
        $('#yourName').attr('disabled', true).addClass('disabled');
        $('#yourEmail').attr('disabled', true).addClass('disabled');
      <?php } ?>


      // PLACEHODERS FOR ADD NEW COMMENT FORM
      $('#comment_form #authorName').attr('placeholder', '<?php echo osc_esc_js(__('Your real or user name', 'zara')); ?>');
      $('#comment_form #authorEmail').attr('placeholder', '<?php echo osc_esc_js(__('Contact email', 'zara')); ?>');
      $('#comment_form #title').attr('placeholder', '<?php echo osc_esc_js(__('Comment title', 'zara')); ?>');
      $('#comment_form #body').attr('placeholder', '<?php echo osc_esc_js(__('Review or comment...', 'zara')); ?>');
    });
  </script>


  <!-- JAVASCRIPT AJAX LOADER FOR COUNTRY/REGION/CITY SELECT BOX -->
  <?php if($type == 'location_select') { ?>
    <script>
      $(document).ready(function(){
        $("#countryId").live("change",function(){
          var pk_c_code = $(this).val();
          var url = '<?php echo osc_base_url(true)."?page=ajax&action=regions&countryId="; ?>' + pk_c_code;
          var result = '';

          if(pk_c_code != '') {
            $("#regionId").attr('disabled',false);
            $("#uniform-regionId").removeClass('disabled');
            $("#cityId").attr('disabled',true);
            $("#uniform-cityId").addClass('disabled');

            $.ajax({
              type: "POST",
              url: url,
              dataType: 'json',
              success: function(data){
                var length = data.length;
                
                if(length > 0) {

                  result += '<option value=""><?php echo osc_esc_js(__('Select a region', 'zara')); ?></option>';
                  for(key in data) {
                    result += '<option value="' + data[key].pk_i_id + '">' + data[key].s_name + '</option>';
                  }

                  $("#sRegion-side").before('<div class="selector" id="uniform-regionId"><span><?php echo osc_esc_js(__('Select a region', 'zara')); ?></span><select name="sRegion" id="regionId" ></select></div>');
                  $("#sRegion-side").remove();

                  $("#sCity-side").before('<div class="selector" id="uniform-cityId"><span><?php echo osc_esc_js(__('Select a city', 'zara')); ?></span><select name="sCity" id="cityId" ></select></div>');
                  $("#sCity-side").remove();
                  
                  $("#regionId").val("");
                  $("#uniform-regionId").find('span').text('<?php echo osc_esc_js(__('Select a region', 'zara')); ?>');
                } else {

                  $("#regionId").parent().before('<input placeholder="<?php echo osc_esc_js(__('Enter a region', 'zara')); ?>" type="text" name="sRegion" id="sRegion-side" />');
                  $("#regionId").parent().remove();
                  
                  $("#cityId").parent().before('<input placeholder="<?php echo osc_esc_js(__('Enter a city', 'zara')); ?>" type="text" name="sCity" id="sCity-side" />');
                  $("#cityId").parent().remove();

                  $("#sCity-side").val('');
                }

                $("#regionId").html(result);
                $("#cityId").html('<option selected="selected" value=""><?php echo osc_esc_js(__('Select a city', 'zara')); ?></option>');
                $("#uniform-cityId").find('span').text('<?php echo osc_esc_js(__('Select a city', 'zara')); ?>');
              }
             });

           } else {

             // add empty select
             $("#sRegion-side").before('<div class="selector" id="uniform-regionId"><span><?php echo osc_esc_js(__('Select a region', 'zara')); ?></span><select name="sRegion" id="regionId" ><option value=""><?php echo osc_esc_js(__('Select a region', 'zara')); ?></option></select></div>');
             $("#sRegion-side").remove();
             
             $("#sCity-side").before('<div class="selector" id="uniform-cityId"><span><?php echo osc_esc_js(__('Select a city', 'zara')); ?></span><select name="sCity" id="cityId" ><option value=""><?php echo osc_esc_js(__('Select a city', 'zara')); ?></option></select></div>');
             $("#sCity-side").remove();

             if( $("#regionId").length > 0 ){
               $("#regionId").html('<option value=""><?php echo osc_esc_js(__('Select a region', 'zara')); ?></option>');
             } else {
               $("#sRegion-side").before('<div class="selector" id="uniform-regionId"><span><?php echo osc_esc_js(__('Select a region', 'zara')); ?></span><select name="sRegion" id="regionId" ><option value=""><?php echo osc_esc_js(__('Select a region', 'zara')); ?></option></select></div>');
               $("#sRegion-side").remove();
             }

             if( $("#cityId").length > 0 ){
               $("#cityId").html('<option value=""><?php echo osc_esc_js(__('Select a city', 'zara')); ?></option>');
             } else {
               $("#sCity-side").parent().before('<div class="selector" id="uniform-cityId"><span><?php echo osc_esc_js(__('Select a city', 'zara')); ?></span><select name="sCity" id="cityId" ><option value=""><?php echo osc_esc_js(__('Select a city', 'zara')); ?></option></select></div>');
               $("#sCity-side").parent().remove();
             }

             $("#regionId").attr('disabled',true);
             $("#uniform-regionId").addClass('disabled');
             $("#uniform-regionId").find('span').text('<?php echo osc_esc_js(__('Select a region', 'zara')); ?>');
             $("#cityId").attr('disabled',true);
             $("#uniform-cityId").addClass('disabled');
             $("#uniform-cityId").find('span').text('<?php echo osc_esc_js(__('Select a city', 'zara')); ?>');

          }
        });

        $("#regionId").live("change", function(){
          var pk_c_code = $(this).val();
          var url = '<?php echo osc_base_url(true)."?page=ajax&action=cities&regionId="; ?>' + pk_c_code;
          var result = '';

          if(pk_c_code != '') {
            
            $("#cityId").attr('disabled',false);
            $("#uniform-cityId").removeClass('disabled');

            $.ajax({
              type: "POST",
              url: url,
              dataType: 'json',
              success: function(data){
                var length = data.length;
                if(length > 0) {
                  result += '<option selected="selected" value=""><?php echo osc_esc_js(__('Select a city', 'zara')); ?></option>';
                  for(key in data) {
                    result += '<option value="' + data[key].pk_i_id + '">' + data[key].s_name + '</option>';
                  }

                  $("#sCity-side").before('<div class="selector" id="uniform-cityId"><span><?php echo osc_esc_js(__('Select a city', 'zara')); ?></span><select name="sCity" id="cityId" ></select></div>');
                  $("#sCity-side").remove();

                  $("#cityId").val("");
                  $("#uniform-cityId").find('span').text('<?php echo osc_esc_js(__('Select a city', 'zara')); ?>');
                } else {
                  result += '<option value=""><?php echo osc_esc_js(__('No cities found', 'zara')); ?></option>';
                  $("#cityId").parent().before('<input type="text" placeholder="<?php echo osc_esc_js(__('Enter a city', 'zara')); ?>" name="sCity" id="sCity-side" />');
                  $("#cityId").parent().remove();
                }
                $("#cityId").html(result);
              }
            });
          } else {
            $("#cityId").attr('disabled',true);
            $("#uniform-cityId").addClass('disabled');
            $("#uniform-cityId").find('span').text('<?php echo osc_esc_js(__('Select a city', 'zara')); ?>');
          }
        });

        if( $("#regionId").attr('value') == "")  {
          $("#cityId").attr('disabled',true);
          $("#uniform-cityId").addClass('disabled');
        } else {
          $("#cityId").attr('disabled',false);
          $("#uniform-cityId").removeClass('disabled');
        }

        if($("#countryId").length != 0) {
          if( $("#countryId").attr('value') == "")  {
            $("#regionId").attr('disabled',true);
            $("#uniform-regionId").addClass('disabled');
          }
        }

        //Make sure when select loads after input, span wrap is correctly filled
        $("#countryId").live('change', function() {
          $(this).parent().find('span').text($(this).find("option:selected" ).text());
        });

        $("#regionId").live('change', function() {
          $(this).parent().find('span').text($(this).find("option:selected" ).text());
        });

        $("#cityId").live('change', function() {
          $(this).parent().find('span').text($(this).find("option:selected" ).text());
        });

      });
    </script>
  <?php } ?>
</body>
</html>
	