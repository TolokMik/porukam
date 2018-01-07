<?php
  $locales = __get('locales');
  $user = osc_user();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
<head>
  <?php osc_current_web_theme_path('head.php') ; ?>
  <meta name="robots" content="noindex, nofollow" />
  <meta name="googlebot" content="noindex, nofollow" />
</head>
<body>
  <?php osc_current_web_theme_path('header.php') ; ?>
  <div class="content user_account">
    <div class="user-menu-sh resp is767 sc-click"><?php _e('User menu', 'zara'); ?></div>

    <div id="sidebar" class="sc-block">
      <?php if(function_exists('profile_picture_show')) { ?>
        <div class="user-side-img">
          <a href="#" id="pict-update">
            <?php profile_picture_show(null, null, 80); ?>
          </a>
        </div>
      <?php } ?>

      <?php echo osc_private_user_menu(); ?>
      <?php if(function_exists('profile_picture_upload')) { profile_picture_upload(); } ?>
    </div>

    <div id="main" class="modify_profile">
      <?php //UserForm::location_javascript(); ?>
      <form action="<?php echo osc_base_url(true) ; ?>" method="post">
      <input type="hidden" name="page" value="user" />
      <input type="hidden" name="action" value="profile_post" />

      <div id="left-user">
        <h3 class="title_block"><?php _e('Personal information', 'zara'); ?></h3>
        <div class="row">
          <label for="name"><span><?php _e('Name', 'zara') ; ?></span><span class="req">*</span></label>
          <?php UserForm::name_text(osc_user()) ; ?>
        </div>

        <div class="row">
          <label for="email"><span><?php _e('E-mail', 'zara') ; ?></span><span class="req">*</span></label>
          <span class="update">
            <span><?php echo osc_user_email(); ?></span>
            <a href="<?php echo osc_change_user_email_url(); ?>" id="user-change-email"><?php _e('Modify e-mail', 'zara') ; ?></a> <a href="<?php echo osc_change_user_password_url(); ?>" id="user-change-password"><?php _e('Modify password', 'zara') ; ?></a>
          </span>
        </div>

        <div class="row">
          <label for="phoneMobile"><span><?php _e('Mobile phone', 'zara'); ?></span><span class="req">*</span></label>
          <?php UserForm::mobile_text(osc_user()) ; ?>
        </div>

        <div class="row">
          <label for="phoneLand"><?php _e('Land Phone', 'zara') ; ?></label>
          <?php UserForm::phone_land_text(osc_user()) ; ?>
        </div>                        

        <div class="row">
          <label for="info"><?php _e('Some info about you', 'zara') ; ?></label>
          <?php UserForm::multilanguage_info($locales, osc_user()); ?>
        </div>
      </div>

      <div id="right-user">
        <h3 class="title_block"><?php _e('Business information & location', 'zara'); ?></h3>
        <div class="row">
          <label for="user_type"><?php _e('User type', 'zara') ; ?></label>
          <?php UserForm::is_company_select(osc_user()) ; ?>
        </div>

        <div class="row">
          <label for="webSite"><?php _e('Website', 'zara') ; ?></label>
          <?php UserForm::website_text(osc_user()) ; ?>
        </div>

        <?php $user = osc_user(); ?>
        <?php $country = Country::newInstance()->listAll(); ?>

        <?php 
          if(count($country) <= 1) {
            $u_country = Country::newInstance()->listAll();
            $u_country = $u_country[0];
            $user['fk_c_country_code'] = $u_country['pk_c_code'];
          }
        ?>

        <div class="row">
          <label for="country"><span><?php _e('Country', 'zara') ; ?></span><span class="req">*</span></label>
          <?php UserForm::country_select(Country::newInstance()->listAll(), osc_user()); ?>
        </div>
        

        <div class="row">
          <label for="region"><span><?php _e('Region', 'zara') ; ?></span><span class="req">*</span></label>
          <?php UserForm::region_select($user['fk_c_country_code'] <> '' ? osc_get_regions($user['fk_c_country_code']) : '', osc_user()) ; ?>
        </div>

        <div class="row">
          <label for="city"><span><?php _e('City', 'zara') ; ?></span><span class="req">*</span></label>
          <?php UserForm::city_select($user['fk_i_region_id'] <> '' ? osc_get_cities($user['fk_i_region_id']) : '', osc_user()) ; ?>
        </div>


        <div class="row">
          <label for="address"><?php _e('Address', 'zara') ; ?></label>
          <?php UserForm::address_text(osc_user()) ; ?>
        </div>

        <div class="req-what"><div class="req">*</div><div class="small-info"><?php _e('This field is required', 'zara'); ?></div></div>
      </div>
           
      <?php osc_run_hook('user_form') ; ?>

      <div class="row user-buttons">
        <button type="submit" id="blue" class="round3 button"><?php _e('Update profile', 'zara') ; ?></button>
        <a id="uniform-gray" class="round3" href="<?php echo osc_base_url(true).'?page=user&action=delete&id='.osc_user_id().'&secret='.$user['s_secret']; ?>" onclick="return confirm('<?php echo osc_esc_js(__('Are you sure you want to delete your account? This action cannot be undone', 'zara')); ?>?')"><span><?php _e('delete account', 'zara'); ?></span></a>
      </div>

      </form>
    </div>
  </div>


  <!-- JAVASCRIPT AJAX LOADER FOR COUNTRY/REGION/CITY SELECT BOX -->
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

                $("#region").before('<div class="selector" id="uniform-regionId"><span><?php echo osc_esc_js(__('Select a region', 'zara')); ?></span><select name="regionId" id="regionId" ></select></div>');
                $("#region").remove();

                $("#city").before('<div class="selector" id="uniform-cityId"><span><?php echo osc_esc_js(__('Select a city', 'zara')); ?></span><select name="cityId" id="cityId" ></select></div>');
                $("#city").remove();
                
                $("#regionId").val("");
                $("#uniform-regionId").find('span').text('<?php echo osc_esc_js(__('Select a region', 'zara')); ?>');
              } else {

                $("#regionId").parent().before('<input placeholder="<?php echo osc_esc_html(__('Enter a region', 'zara')); ?>" type="text" name="sRegion" id="region" />');
                $("#regionId").parent().remove();
                
                $("#cityId").parent().before('<input placeholder="<?php echo osc_esc_html(__('Enter a city', 'zara')); ?>" type="text" name="sCity" id="city" />');
                $("#cityId").parent().remove();

                $("#city").val('');
              }

              $("#regionId").html(result);
              $("#cityId").html('<option selected value=""><?php echo osc_esc_js(__('Select a city', 'zara')); ?></option>');
              $("#uniform-cityId").find('span').text('<?php echo osc_esc_js(__('Select a city', 'zara')); ?>');
              $("#cityId").attr('disabled',true);
              $("#uniform-cityId").addClass('disabled');
            }
           });

         } else {

           // add empty select
           $("#region").before('<div class="selector" id="uniform-regionId"><span><?php echo osc_esc_js(__('Select a region', 'zara')); ?></span><select name="regionId" id="regionId" ><option value=""><?php echo osc_esc_js(__('Select a region', 'zara')); ?></option></select></div>');
           $("#region").remove();
           
           $("#city").before('<div class="selector" id="uniform-cityId"><span><?php echo osc_esc_js(__('Select a city', 'zara')); ?></span><select name="cityId" id="cityId" ><option value=""><?php echo osc_esc_js(__('Select a city', 'zara')); ?></option></select></div>');
           $("#city").remove();

           if( $("#regionId").length > 0 ){
             $("#regionId").html('<option value=""><?php echo osc_esc_js(__('Select a region', 'zara')); ?></option>');
           } else {
             $("#region").before('<div class="selector" id="uniform-regionId"><span><?php echo osc_esc_js(__('Select a region', 'zara')); ?></span><select name="regionId" id="regionId" ><option value=""><?php echo osc_esc_js(__('Select a region', 'zara')); ?></option></select></div>');
             $("#region").remove();
           }

           if( $("#cityId").length > 0 ){
             $("#cityId").html('<option value=""><?php echo osc_esc_js(__('Select a city', 'zara')); ?></option>');
           } else {
             $("#city").parent().before('<div class="selector" id="uniform-cityId"><span><?php echo osc_esc_js(__('Select a city', 'zara')); ?></span><select name="cityId" id="cityId" ><option value=""><?php echo osc_esc_js(__('Select a city', 'zara')); ?></option></select></div>');
             $("#city").parent().remove();
           }

           $("#regionId").attr('disabled',true);
           $("#uniform-regionId").addClass('disabled');
           $("#uniform-regionId").find('span').text('<?php echo osc_esc_js(__('Select a region', 'zara')); ?>');
           $("#cityId").attr('disabled',true);
           $("#uniform-cityId").addClass('disabled');
           $("#uniform-cityId").find('span').text('<?php echo osc_esc_js(__('Select a city', 'zara')); ?>');

        }
      });

      $("#regionId").live("change",function(){
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
                result += '<option selected value=""><?php echo osc_esc_js(__('Select a city', 'zara')); ?></option>';
                for(key in data) {
                  result += '<option value="' + data[key].pk_i_id + '">' + data[key].s_name + '</option>';
                }

                $("#city").before('<div class="selector" id="uniform-cityId"><span><?php echo osc_esc_js(__('Select a city', 'zara')); ?></span><select name="cityId" id="cityId" ></select></div>');
                $("#city").remove();

                $("#cityId").val("");
                $("#uniform-cityId").find('span').text('<?php echo osc_esc_js(__('Select a city', 'zara')); ?>');
              } else {
                result += '<option value=""><?php echo osc_esc_js(__('No cities found', 'zara')); ?></option>';
                $("#cityId").parent().before('<input type="text" placeholder="<?php echo osc_esc_html(__('Enter a city', 'zara')); ?>" name="sCity" id="city" />');
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
        $("#city").attr('disabled',true);
        $("#uniform-cityId").addClass('disabled');
      }

      if($("#countryId").length != 0) {
        if( $("#countryId").attr('value') == "")  {
          $("#regionId").attr('disabled',true);
          $("#uniform-regionId").addClass('disabled');
        }
      }


      // For 1 country enable region select
      <?php if(count($country) <= 1) { ?>
        function checkReg(){
          if($('#uniform-regionId').hasClass('disabled')) {
            $('#uniform-regionId').removeClass('disabled');
            $('#uniform-regionId select').attr('disabled', false);
          }
        }

        checkReg();
        setTimeout(function(){ checkReg(); }, 100);
        setTimeout(function(){ checkReg(); }, 500);
        setTimeout(function(){ checkReg(); }, 1000);
        setTimeout(function(){ checkReg(); }, 3000);
      <?php } ?>


      //Make sure when select loads after input, span wrap is correctly filled
      $(".row").on('change', '#cityId, #regionId', function() {
        $(this).parent().find('span').text($(this).find("option:selected" ).text());
      });
    });
  </script>

  <?php osc_current_web_theme_path('footer.php') ; ?>
</body>
</html>