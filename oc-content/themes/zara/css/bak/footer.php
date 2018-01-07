    <?php
      osc_show_widgets('footer');
      $sQuery = __('Search in', 'zara') . ' ' . osc_total_active_items() . ' ' .  __('listings', 'zara');
    ?>
  </div>
</div>
<?php osc_run_hook('footer') ; ?>

<div class="adv_band"> <a href="#"><img src="/adv/new_baner_222.gif"></a> <a href="#"><img src="/adv/new_baner_333.gif"></a></div>

<div id="footer-new">
  <div class="inside">
    <div class="bottom-place">
      <?php if(osc_is_search_page()) { ?>
        <?php osc_alert_form(); ?>
      <?php } else { ?>
        <div id="n-block" class="block quick">
          <div class="head sc-click not767"> 
            <h4><?php _e('Quick publish', 'zara'); ?></h4> 
          </div>
          
          <div class="n-wrap sc-block not767">
            <form action="<?php echo osc_item_post_url_in_category(); ?>" method="post" name="add_listing" id="add_listing">
              <input type="text" value="<?php _e('Enter name of item', 'zara'); ?>" name="add_title" id="add_title" />
              <input type="submit" value="<?php _e('Publish listing', 'zara'); ?>" class="button orange-button round2" name="submitNewsletter"> 
            </form>
          </div>
            
          <div class="under not767">
            <div class="row"><?php _e('Buy & sell items with us', 'zara'); ?>:</div>
            <div class="row"><i class="fa fa-dollar"></i> <?php _e('Lower price', 'zara'); ?></div>
            <div class="row"><i style="margin-right: 4px;" class="fa fa-truck"></i> <?php _e('Fast delivery', 'zara'); ?></div>
          </div>
          <div id="footer-share" class="no-border">
            <div class="text">
              <span class="facebook"><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo osc_base_url(); ?>" title="<?php echo osc_esc_html(__('Share us on Facebook', 'zara')); ?>" target="_blank"><i class="fa fa-facebook"></i></a></span>
              <span class="pinterest"><a href="https://pinterest.com/pin/create/button/?url=<?php echo osc_base_url(); ?>/oc-content/themes/' . osc_current_web_theme() . '/images/logo.jpg&media=<?php echo osc_base_url(); ?>&description=" title="<?php echo osc_esc_html(__('Share us on Pinterest', 'zara')); ?>" target="_blank"><i class="fa fa-pinterest"></i></a></span>
              <span class="twitter"><a href="https://twitter.com/home?status=<?php echo osc_base_url(); ?>%20-%20<?php _e('your', 'zara'); ?>%20<?php _e('classifieds', 'zara'); ?>" title="<?php echo osc_esc_html(__('Tweet us', 'zara')); ?>" target="_blank"><i class="fa fa-twitter"></i></a></span>
              <span class="google-plus"><a href="https://plus.google.com/share?url=<?php echo osc_base_url(); ?>" title="<?php echo osc_esc_html(__('Share us on Google+', 'zara')); ?>" target="_blank"><i class="fa fa-google-plus"></i></a></span>
            </div>
          </div>
        </div>
      <?php } ?>


      <div class="some-block not767">
        <h4><?php _e('Categories', 'zara'); ?></h4>
        <div class="text">
          <?php osc_goto_first_category(); $c = 1; ?>
          <?php while(osc_has_categories() and $c <= 10) { ?>
            <span><a href="<?php echo osc_search_category_url() ; ?>" title="<?php echo osc_esc_html(osc_category_name()); ?>"><?php echo ucfirst(osc_category_name());?></a></span>
          <?php $c++; } ?>
        </div>
      </div>

      <div class="some-block not767 not1200">
        <h4><?php _e('Information', 'zara'); ?></h4>
        <div class="text">
          <?php osc_reset_static_pages(); ?>
          <?php $c = 1; ?>
          <?php while(osc_has_static_pages() and $c <= 8) { ?>
            <span><a href="<?php echo osc_static_page_url(); ?>" title="<?php echo osc_esc_html(osc_static_page_title()); ?>"><?php echo ucfirst(osc_static_page_title());?></a></span>
          <?php $c++; } ?>
        </div>
      </div>
    </div>
  </div>
</div>


<?php if(osc_get_preference('enable_partner', 'zara_theme') == 1) { ?>
  <div id="footer-sponsor" class="not767">
    <div class="sponsor-inside">
      <div id="sponsor">
        <div class="lead"><?php _e('Our partners', 'zara'); ?></div>

        <?php 
          $sponsor_path = osc_base_path() . 'oc-content/themes/' . osc_current_web_theme() . '/images/sponsor-logos'; 
          $sponsor_url = osc_base_url() . 'oc-content/themes/' . osc_current_web_theme() . '/images/sponsor-logos'; 
          $sponsor_images = scandir($sponsor_path);

          if(isset($sponsor_images) && !empty($sponsor_images) && $sponsor_images <> '') {
            foreach($sponsor_images as $img) {
              $ext = strtolower(pathinfo($sponsor_path . '/' . $img, PATHINFO_EXTENSION));
              $allowed_ext = array('png', 'jpg', 'jpeg', 'gif');

              if(in_array($ext, $allowed_ext)) {
                echo '<img class="sponsor-image" src="' . $sponsor_url . '/' . $img . '" alt="' . __('Our sponsor logo', 'zara') . '" />';
              }
            }
          }
        ?>
      </div>
    </div>
  </div>
<?php } ?>

<div id="footer-contact">
  <div class="contact-inside">
    <div class="top-place">
      <div class="not767">
        <a class="orang" href="<?php echo osc_base_url(); ?>"><?php echo osc_esc_html( osc_get_preference('website_name', 'zara_theme') ); ?></a> | 
        <a href="<?php echo osc_contact_url(); ?>"><?php _e('Contact', 'zara'); ?></a>   
         
        <?php if(osc_get_preference('footer_link', 'zara_theme')) { ?>
          <?php _e('This website is powered by', 'zara'); ?> <a href="http://www.osclass.org">OSclass</a> | 
          <a href="http://www.mb-themes.com/">Created by MB themes</a>
        <?php } ?> 
      </div>

      <div class="cop"><?php _e('Copyright', 'zara'); ?> &copy; <?php echo date("Y"); ?> <strong><?php echo osc_esc_html( osc_get_preference('website_name', 'zara_theme') ); ?></strong></div>
    </div>
  </div>
</div>

<div id="rs-cover"></div>
<div id="ro-cover"></div>

<div id="o-box" class="closed resp">
  <div class="o-lead">
    <?php if( osc_is_web_user_logged_in() ) { ?>
      <span><?php echo __('Hello', 'zara') . ' ' . osc_logged_user_name() . '!'; ?></span>
    <?php } else { ?>
      <span><?php _e('Hello!', 'zara'); ?></span> <a href="<?php echo osc_user_login_url(); ?>"><?php _e('Sign in', 'zara'); ?></a> <span><?php _e('to get more.', 'zara'); ?></span>
    <?php } ?>
  </div>

  <div class="o-body">
    <a href="<?php echo osc_base_url(); ?>"><i class="fa fa-home"></i><?php _e('Home', 'zara'); ?></a>
    <a class="o-border-bottom" href="<?php echo osc_search_url(); ?>"><i class="fa fa-search"></i><?php _e('Search', 'zara'); ?></a>

    <?php if(function_exists('fi_list_items')) { ?>
      <a href="<?php echo osc_route_url('favorite-lists', array('list-id' => '0', 'current-update' => '0', 'notification-update' => '0', 'list-remove' => '0', 'iPage' => '0')); ?>"><i class="fa fa-star-o"></i><?php _e('My favorite items', 'zara'); ?></a>
    <?php } ?>

    <a href="<?php echo osc_user_dashboard_url(); ?>"><i class="fa fa-folder-o"></i><?php _e('My account', 'zara'); ?></a>
    <a href="<?php echo osc_user_alerts_url(); ?>"><i class="fa fa-bell-o"></i><?php _e('My alerts', 'zara'); ?></a>
    <a href="<?php echo osc_user_profile_url(); ?>"><i class="fa fa-edit"></i><?php _e('My personal info', 'zara'); ?></a>
    <a href="<?php echo osc_user_list_items_url(); ?>"><i class="fa fa-list-ul"></i><?php _e('My listings', 'zara'); ?></a>
    <?php if( osc_is_web_user_logged_in() ) { ?><a href="<?php echo osc_user_public_profile_url(osc_logged_user_id()); ?>"><i class="fa fa-copy"></i><?php _e('My public profile', 'zara'); ?></a><?php } ?>
    <?php if( osc_is_web_user_logged_in() ) { ?><a href="<?php echo osc_user_logout_url(); ?>"><i class="fa fa-sign-out"></i><?php _e('Log out', 'zara'); ?></a><?php } ?>

    <a class="o-border-top" href="<?php echo osc_contact_url(); ?>"><i class="fa fa-envelope-o"></i><?php _e('Mail us', 'zara'); ?></a>

    <?php if(osc_get_preference('phone', 'zara_theme') <> '') { ?>
      <a href="tel:<?php echo osc_esc_html( osc_get_preference('phone', 'zara_theme') ); ?>"><i class="fa fa-phone"></i><?php _e('Call us', 'zara'); ?></a>
    <?php } ?>


    <?php if ( osc_count_web_enabled_locales() > 1) { ?>
      <?php $current_locale = mb_get_current_user_locale(); ?>

      <?php osc_goto_first_locale(); ?>

      <div class="o-elem">
        <div class="o-head sc-click">
          <?php _e('Language', 'zara'); ?>
        </div>

        <div class="o-lang sc-block">
          <?php while ( osc_has_web_enabled_locales() ) { ?>
            <a id="<?php echo osc_locale_code() ; ?>" href="<?php echo osc_change_language_url ( osc_locale_code() ) ; ?>" <?php if (osc_locale_code() == $current_locale['pk_c_code'] ) { ?>class="current"<?php } ?>>
              <span><?php echo osc_locale_field('s_short_name'); ?></span>
              <?php if (osc_locale_code() == $current_locale['pk_c_code']) { ?>
                <i class="fa fa-check"></i>
              <?php } ?>
            </a>
          <?php } ?>
        </div>
      </div>
    <?php } ?>

  </div>
</div>


<div id="s-box" class="closed resp">
  <form action="<?php echo osc_base_url(true); ?>" method="get" onsubmit="" class="nocsrf">
    <input type="hidden" name="page" value="search" />
    <input type="hidden" name="cookie-action-side" id="cookie-action-side" value="" />
    <input type="hidden" name="sOrder" value="<?php echo osc_search_order(); ?>" />
    <input type="hidden" name="iOrderType" value="<?php $allowedTypesForSorting = Search::getAllowedTypesForSorting() ; echo isset($allowedTypesForSorting[osc_search_order_type()]) ? $allowedTypesForSorting[osc_search_order_type()] : ''; ?>" />
    <?php foreach(osc_search_user() as $userId) { ?>
      <input type="hidden" name="sUser[]" value="<?php echo $userId; ?>" />
    <?php } ?>
    <input type="hidden" name="sCompany" class="sCompany" id="sCompany" value="<?php echo Params::getParam('sCompany');?>" />
    <input type="hidden" id="priceMin" name="sPriceMin" value="<?php echo Params::getParam('sPriceMin'); ?>" size="6" maxlength="6" />
    <input type="hidden" id="priceMax" name="sPriceMax" value="<?php echo Params::getParam('sPriceMax'); ?>" size="6" maxlength="6" />

    <fieldset>
      <div class="s-lead"><span><?php _e('Refine search', 'zara'); ?></span></div>

      <div class="s-elem">
        <div class="s-head sc-click">
          <?php _e('Keyword', 'zara'); ?>
        </div>

        <div class="s-body sc-block">
          <input type="text" name="sPattern" id="query" value="<?php echo osc_esc_html(osc_search_pattern()); ?>" placeholder="<?php echo osc_esc_html(__('I\'m looking for...', 'zara')); ?>" />
        </div>
      </div>

      <div class="s-elem">
        <div class="s-head sc-click">
          <?php _e('Category', 'zara'); ?>
        </div>

        <div class="s-body sc-block">
          <?php
            $cat_id = Params::getParam('sCategory');
            $cat = array('pk_i_id' => $cat_id); 
          ?>

          <?php osc_categories_select('sCategory', $cat, __('Select a category', 'zara')); ?>
        </div>
      </div>

      <div class="s-elem">
        <div class="s-head sc-click">
          <?php _e('Location', 'zara'); ?>
        </div>

        <div class="s-body sc-block">
          <?php $aCountries = Country::newInstance()->listAll(); ?>
        
          <div class="s-row">
            <h4><?php _e('Country', 'zara') ; ?></h4>

            <?php
              // IF THERE IS JUST 1 COUNTRY, PRE-SELECT IT TO ENABLE REGION DROPDOWN
              $s_country = Country::newInstance()->listAll();
              if(count($s_country) <= 1) {
                $s_country = isset($s_country[0]) ? $s_country[0] : '';
              }
            ?>

            <select id="countryId" name="sCountry">
              <option value=""><?php _e('Select a country', 'zara'); ?></option>

              <?php foreach ($aCountries as $country) {?>
                <?php
                  if(!isset($country['pk_c_code'])) {
                    $country['pk_c_code'] = '';
                  }
                ?>

                <option value="<?php echo $country['pk_c_code']; ?>" <?php if(Params::getParam('sCountry') <> '' && (Params::getParam('sCountry') == $country['pk_c_code'] or Params::getParam('sCountry') == $country['s_name']) or $s_country['pk_c_code'] <> '' && $s_country['pk_c_code'] == $country['pk_c_code']) { ?>selected="selected"<?php } ?>><?php echo $country['s_name'] ; ?></option>

                <?php 
                  if(Params::getParam('sCountry') <> '' && (Params::getParam('sCountry') == $country['pk_c_code'] or Params::getParam('sCountry') == $country['s_name']) or $s_country['pk_c_code'] <> '' && $s_country['pk_c_code'] == $country['pk_c_code']) {
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

          <div class="s-row">
            <h4><?php _e('Region', 'zara') ; ?></h4>

            <?php if(count($aRegions) >= 1 ) { ?>
              <select id="regionId" name="sRegion" <?php if(Params::getParam('sRegion') == '' && Params::getParam('region')) {?>disabled<?php } ?>>
                <option value=""><?php _e('Select a region', 'zara'); ?></option>
                
                <?php if(isset($aRegions) && !empty($aRegions) && $aRegions <> '' && count($aRegions) >= 1) { ?>
                  <?php foreach ($aRegions as $region) {?>
                    <option value="<?php echo $region['pk_i_id']; ?>" <?php if(Params::getParam('sRegion') == $region['pk_i_id'] or Params::getParam('sRegion') == $region['s_name']) { ?>selected="selected"<?php } ?>><?php echo $region['s_name']; ?></option>
                  <?php } ?>
                <?php } ?>
              </select>
            <?php } else { ?>
              <input type="text" name="sRegion" id="sRegion-side" value="<?php echo Params::getParam('sRegion'); ?>" placeholder="<?php _e('Enter a region', 'zara'); ?>" />
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

          <div class="s-row last">
            <h4><?php _e('City', 'zara') ; ?></h4>

            <?php if(count($aCities) >= 1 && !empty($aCities) ) { ?>
              <select name="sCity" id="cityId" <?php if(Params::getParam('sCity') == '' && Params::getParam('city') == '') {?>disabled<?php } ?>> 
                <option value=""><?php _e('Select a city', 'zara'); ?></option>
          
                <?php if(isset($aCities) && !empty($aCities) && $aCities <> '' && count($aCities) >= 1) { ?>
                  <?php foreach ($aCities as $city) {?>
                    <option value="<?php echo $city['pk_i_id']; ?>" <?php if(Params::getParam('sCity') == $city['pk_i_id'] or Params::getParam('sCity') == $city['s_name']) { ?>selected="selected"<?php } ?>><?php echo $city['s_name']; ?></option>
                  <?php } ?>
                <?php } ?>
              </select>
            <?php } else { ?>
              <input type="text" name="sCity" id="sCity-side" value="<?php echo Params::getParam('sCity'); ?>" placeholder="<?php _e('Enter a city', 'zara'); ?>" />
            <?php } ?>
          </div>
        </div>
      </div>

      <div class="s-elem check">
        <div class="s-head sc-click">
          <?php _e('Pictures', 'zara'); ?>
        </div>

        <div class="s-body sc-block">
          <?php if( osc_images_enabled_at_items() ) { ?>
            <input type="checkbox" name="bPic" id="withPicture" value="1" <?php echo (osc_search_has_pic() ? 'checked="checked"' : ''); ?> />
            <label for="withPicture" class="with-pic-label"><?php _e('Only listings with picture', 'zara') ; ?></label>
          <?php } ?>
        </div>
      </div>


      <?php if( osc_price_enabled_at_items() ) { ?>
        <div class="s-elem price">
          <div class="s-head sc-click">
            <?php _e('Price', 'zara'); ?>
          </div>

          <div class="s-body sc-block">
            <h4><?php _e('Price', 'zara'); ?>:</h4>
            <div id="amount-min"></div><div id="amount-del">-</div><div id="amount-max"></div>
            <div id="slider-range"></div>
          </div>
        </div>
      <?php } ?>


      <div class="s-elem s-hooks">
        <div class="s-head sc-click">
          <?php _e('Advanced', 'zara'); ?>
        </div>

        <div class="s-body sc-block s-hooks">
          <div class="sidebar-hooks">
            <?php 
              GLOBAL $search_hooks;
              echo $search_hooks;
            ?>
          </div>
        </div>
      </div>

      <div class="s-elem">
        <button type="submit" id="blue"><?php _e('Search', 'zara') ; ?></button>
        <a href="<?php echo osc_search_url(array('page' => 'search'));?>" class="clear-search" title="<?php echo osc_esc_html(__('Clear search parameters', 'zara')); ?>"><i class="fa fa-eraser"></i><?php _e('Clean search', 'zara'); ?></a>
      </div>

    </fieldset>
  </form>
</div>

<div class="adv_img right_ads">
<a href="http://porukam.kiev.ua/index.php?page=page&id=49">
<img src="/adv/<?php echo __('BANER_PO_RUKAM_eng.jpg','zara'); ?>"></a>
<div class="fancybox-item fancybox-close-adv"> </div>
</div>

<script>
  <?php if(osc_is_search_page()) { ?>
    var addQuery = "<?php echo osc_esc_js(AlertForm::default_email_text()); ?>";
  <?php } else { ?>
    var addQuery = "<?php echo osc_esc_js(__('Enter name of item', 'zara')); ?>";
  <?php } ?>

  var base_url_js = "<?php echo osc_base_url();?>";
</script>



<!-- JAVASCRIPT FOR PRICE SLIDER IN SEARCH BOX -->
<script>
  <?php
    $search_cat_id = osc_search_category_id();
    $search_cat_id = isset($search_cat_id[0]) ? $search_cat_id[0] : '';

    $max = zara_max_price($search_cat_id, Params::getParam('sCountry'), Params::getParam('sRegion'), Params::getParam('sCity'));
    $max_price = ceil($max['max_price']/25)*25;
    $max_currency = $max['max_currency'];
    $format_sep = osc_get_preference('format_sep', 'zara_theme');
    $format_cur = osc_get_preference('format_cur', 'zara_theme');

    if($format_cur == 0) {
      $format_prefix = $max_currency;
      $format_suffix = '';
    } else if ($format_cur == 1) {
      $format_prefix = '';
      $format_suffix = $max_currency;
    } else {
      $format_prefix = '';
      $format_suffix = '';
    }
  ?>

  $(function() {
    $( "#slider-range" ).slider({
      range: true,
      step: <?php echo $max_price/25; ?>,
      min: 0,
      max: <?php echo $max_price; ?>,
      values: [<?php echo (Params::getParam('sPriceMin') <> '' ? Params::getParam('sPriceMin') : '0'); ?>, <?php echo (Params::getParam('sPriceMax') <> '' ? Params::getParam('sPriceMax') : $max_price); ?> ],
      slide: function( event, ui ) {
        if(ui.values[ 0 ] <= 0) {
          $( "#amount-min" ).text( "<?php echo osc_esc_js(__('Free', 'zara')); ?>" );
          $( "#amount-max" ).text( ui.values[ 1 ] );
          $( "#amount-max" ).priceFormat({prefix: '<?php echo $format_prefix; ?>', suffix: '<?php echo $format_suffix; ?>', thousandsSeparator: '<?php echo $format_sep; ?>', centsLimit: 0});
        } else {
          $( "#amount-min" ).text( ui.values[ 0 ] );
          $( "#amount-max" ).text( ui.values[ 1 ] );
          $( "#amount-min" ).priceFormat({prefix: '<?php echo $format_prefix; ?>', suffix: '<?php echo $format_suffix; ?>', thousandsSeparator: '<?php echo $format_sep; ?>', centsLimit: 0});
          $( "#amount-max" ).priceFormat({prefix: '<?php echo $format_prefix; ?>', suffix: '<?php echo $format_suffix; ?>', thousandsSeparator: '<?php echo $format_sep; ?>', centsLimit: 0});
        }

        if(ui.values[ 0 ] <= 0) { 
          $( "#priceMin" ).val('');
        } else {
          $( "#priceMin" ).val(ui.values[ 0 ]);
        }

        if(ui.values[ 1 ] >= <?php echo $max_price; ?>) {
          $( "#priceMax" ).val('');
        } else {
          $( "#priceMax" ).val(ui.values[ 1 ]);
        }

        $("#cookie-action-side").val('done');
      }
    });
    

    if( $( "#slider-range" ).slider( "values", 0 ) <= 0 ) {
      if( $( "#slider-range" ).slider( "values", 1 ) <= 0 ) {
        $( "#amount-min" ).text( "<?php echo osc_esc_js(__('Free', 'zara')); ?>" );
        $( "#amount-max" ).text( "" );
        $( "#amount-del" ).hide(0);
      } else {
        $( "#amount-min" ).text( "<?php echo osc_esc_js(__('Free', 'zara')); ?>" );
        $( "#amount-max" ).text( $( "#slider-range" ).slider( "values", 1 ) );
        $( "#amount-del" ).show(0);
        $( "#amount-max" ).priceFormat({prefix: '<?php echo $format_prefix; ?>', suffix: '<?php echo $format_suffix; ?>', thousandsSeparator: '<?php echo $format_sep; ?>', centsLimit: 0});
      }
    } else {
      if( $( "#slider-range" ).slider( "values", 0 ) == $( "#slider-range" ).slider( "values", 1 ) ) {
        $( "#amount-min" ).text( "" );
        $( "#amount-max" ).text( $( "#slider-range" ).slider( "values", 0 ) );
        $( "#amount-del" ).hide(0);
        $( "#amount-max" ).priceFormat({prefix: '<?php echo $format_prefix; ?>', suffix: '<?php echo $format_suffix; ?>', thousandsSeparator: '<?php echo $format_sep; ?>', centsLimit: 0});
      } else {
        $( "#amount-min" ).text( $( "#slider-range" ).slider( "values", 0 ) );
        $( "#amount-max" ).text( $( "#slider-range" ).slider( "values", 1 ) );
        $( "#amount-del" ).show(0);
        $( "#amount-min" ).priceFormat({prefix: '<?php echo $format_prefix; ?>', suffix: '<?php echo $format_suffix; ?>', thousandsSeparator: '<?php echo $format_sep; ?>', centsLimit: 0});
        $( "#amount-max" ).priceFormat({prefix: '<?php echo $format_prefix; ?>', suffix: '<?php echo $format_suffix; ?>', thousandsSeparator: '<?php echo $format_sep; ?>', centsLimit: 0});
      }
    }
  });
</script>


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
  $(document).on('click', '.fancybox-close-adv', function(){
   
		$('.fancybox-close-adv, .adv_img.right_ads').hide();
  });
</script>


<?php if (1==2) { 
  $cat = osc_search_category_id();
  $cat = $cat[0];

  echo 'Page: ' . Params::getParam('page') . '<br />';
  echo 'Param Country: ' . Params::getParam('sCountry') . '<br />';
  echo 'Param Region: ' . Params::getParam('sRegion') . '<br />';
  echo 'Param City: ' . Params::getParam('sCity') . '<br />';
  echo 'Param Locator: ' . Params::getParam('sLocator') . '<br />';
  echo 'Param Category: ' . Params::getParam('sCategory') . '<br />';
  echo 'Search Region: ' . osc_search_region() . '<br />';
  echo 'Search City: ' . osc_search_city() . '<br />';
  echo 'Search Category: ' . $cat . '<br />';
  echo 'Param Locator: ' . Params::getParam('sLocator') . '<br />';
  echo '<br/> ------------------------------------------------- </br>';
  echo 'Cookie Category: ' . mb_get_cookie('zara-sCategory') . '<br />';
  echo 'Cookie Country: ' . mb_get_cookie('zara-sCountry') . '<br />';
  echo 'Cookie Region: ' . mb_get_cookie('zara-sRegion') . '<br />';
  echo 'Cookie City: ' . mb_get_cookie('zara-sCity') . '<br />';
  echo '<br/> ------------------------------------------------- </br>';

  echo '<br/>';
  echo '<br/>';
  echo 'end<br/>';

}
?>	