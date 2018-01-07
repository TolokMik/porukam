<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '846272025519017',
      xfbml      : true,
      version    : 'v2.5'
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>
<?php osc_goto_first_locale(); ?>
<!-- container -->
<div id="top-bar">
  <div class="top-inside">
    <a href="#" id="h-options" class="resp is767">
      <span></span>
      <span></span>
      <span></span>
    </a>

    <a id="h-search" class="resp is767">
      <span></span>
    </a>

    <div class="resp-logo-wrap resp is767">
      <a id="logo" class="resp-logo" href="<?php echo osc_base_url() ; ?>"><?php echo logo_header(); ?></a>
    </div>

    <!-- LANGUAGE SELECTION -->
    <?php if ( osc_count_web_enabled_locales() > 1) { ?>
      <?php $current_locale = mb_get_current_user_locale(); ?>

      <?php osc_goto_first_locale(); ?>
      <span id="lang-open-box" class="not767">
        <div class="mb-tool-cover">
          <span id="lang_open" <?php if( osc_is_web_user_logged_in() ) { ?>class="logged"<?php } ?>><span class="label"><?php _e('language:', 'zara');?></span><span><?php echo $current_locale['s_short_name']; ?><i class="fa fa-angle-down"></i></span></span>

          <div id="lang-wrap" class="mb-tool-wrap">
            <div class="mb-tool-cover">
              <ul id="lang-box">
                <span class="info"><?php _e('Select language', 'zara'); ?></span>

                <?php $i = 0 ;  ?>
                <?php while ( osc_has_web_enabled_locales() ) { ?>
                  <li <?php if( $i == 0 ) { echo "class='first'" ; } ?> title="<?php echo osc_esc_html(osc_locale_field("s_description")); ?>"><a id="<?php echo osc_locale_code() ; ?>" href="<?php echo osc_change_language_url ( osc_locale_code() ) ; ?>"><img src="<?php echo osc_current_web_theme_url();?>images/country_flags/<?php echo strtolower(substr(osc_locale_code(), 3)); ?>.png" alt="<?php _e('Country flag', 'zara');?>" /><span><?php echo osc_locale_name(); ?></span></a><?php if (osc_locale_code() == $current_locale['pk_c_code']) { ?><i class="fa fa-check"></i><?php } ?></li>
                  <?php $i++ ; ?>
                <?php } ?>
              </ul>
            </div>
          </div>
        </div>
      </span>
    <?php } ?>


    


    <!-- USER ACCOUNT LINKS -->
    <div class="top-my not767">
      <div class="my-open">
        <i class="fa fa-gear"></i>
      </div>

      <div class="my-wrap">
        <div class="top-ins">
          <?php if( osc_is_web_user_logged_in() ) { ?>
            <?php if(osc_logged_user_name() <> '') { ?>
              <span class="welcome"><?php _e('Welcome', 'zara'); ?> <?php echo osc_logged_user_name(); ?>!</span>
            <?php } else { ?>
              <span class="welcome"><?php _e('Welcome back', 'zara'); ?></span>
            <?php } ?>

            <span class="space-white"></span>
            <a class="reg-button round2" href="<?php echo osc_user_logout_url(); ?>" title="<?php echo osc_esc_html(__('Log me out', 'zara')); ?>" class="logout" rel="nofollow"><?php _e('Log out', 'zara'); ?></a>
          <?php } else { ?>
            <span class="welcome"><?php _e('Welcome to', 'zara'); ?> <?php echo osc_esc_html( osc_get_preference('website_name', 'zara_theme') ); ?>!</span>
            <a class="log-button round2" href="<?php echo osc_user_login_url(); ?>"><?php _e('Sign In', 'zara'); ?></a>
         
         
            <span class="space"></span>

            <span class="unreg"><?php _e('Are you new to our site?', 'zara'); ?></span>
            <a class="reg-button round2" href="<?php echo osc_register_account_url(); ?>"><?php _e('Register now', 'zara'); ?></a>
          <?php } ?>
        </div>

        <div class="bottom-inside">
          <span class="top"><i class="fa fa-briefcase"></i> <?php echo osc_esc_html( osc_get_preference('website_name', 'zara_theme') ); ?></span>

          <?php if(function_exists('fi_list_items')) { ?>
            <a href="<?php echo osc_route_url('favorite-lists', array('list-id' => '0', 'current-update' => '0', 'notification-update' => '0', 'list-remove' => '0', 'iPage' => '0')); ?>" class="elem"><?php _e('My favorite items', 'zara'); ?></a>
          <?php } ?>

          <a href="<?php echo osc_user_dashboard_url(); ?>" class="elem"><?php _e('My account', 'zara'); ?></a>
          <a href="<?php echo osc_user_alerts_url(); ?>" class="elem"><?php _e('My alerts', 'zara'); ?></a>
          <a href="<?php echo osc_user_profile_url(); ?>" class="elem"><?php _e('My personal info', 'zara'); ?></a>
          <a href="<?php echo osc_user_list_items_url(); ?>" class="elem"><?php _e('My listings', 'zara'); ?></a>

          <?php if( osc_is_web_user_logged_in() ) { ?><a href="<?php echo osc_user_public_profile_url(osc_logged_user_id()); ?>" class="elem"><?php _e('My public profile', 'zara'); ?></a><?php } ?>
          <?php if( osc_is_web_user_logged_in() ) { ?><a href="<?php echo osc_user_logout_url(); ?>" class="elem"><?php _e('Log me out', 'zara'); ?></a><?php } ?>
        </div>
      </div>
    </div>   


    <!-- USER LINKS IN HEADER -->
    <?php if(osc_users_enabled()) { ?>
      <?php if( osc_is_web_user_logged_in() ) { ?>
        <div class="logout right not767"><a href="<?php echo osc_user_logout_url() ; ?>"><i class="fa fa-sign-out"></i><?php _e('Logout', 'zara') ; ?></a></div>
        <div class="my-account right not767"><a href="<?php echo osc_user_dashboard_url() ; ?>"><?php _e('My account', 'zara') ; ?></a></div>
        <div class="welcome right not767"><span><?php echo __('Hi', 'zara') . ' ' . osc_logged_user_name() . ' !'; ?> </span></div>
   		 <!-- плагин   mdh_messenger -->
      <div class="my-account right not767">  <?php
    // Includes the widget html code.
    	osc_run_hook('mdh_messenger_widget'); // Includes the widget who will display : Message ({NUMBER UNREAD MESSAGE})
		?> </div>
      <?php } else { ?>

        <?php if(osc_user_registration_enabled()) { ?>
          <div class="sign-in right not767"><a href="<?php echo osc_register_account_url() ; ?>"><i class="fa fa-sign-in"></i> <?php _e('Sign in', 'zara'); ?></a></div>
        <?php } ?>  
      <?php } ?>
    <?php } ?>


    <!-- CONTACT PHONE AND LINK IN HEADER -->
    <div class="top-contact right not767"><a class="left-link" href="<?php echo osc_contact_url(); ?>"><?php _e('Contact', 'zara'); ?></a></div>

    <?php if(osc_get_preference('phone', 'zara_theme') <> '') { ?>
      <div class="top-phone right not767"><a href="tel:<?php echo osc_get_preference('phone', 'zara_theme'); ?>" title="<?php echo osc_esc_html(__('Call the number', 'zara')); ?>"><?php echo osc_esc_html( osc_get_preference('phone', 'zara_theme') ); ?></a></div>
    <?php } ?>


  </div>
</div>

<div id="top-navi">
  <div class="navi-wrap">
    <div id="header">
      <a id="logo" class="not767" href="<?php echo osc_base_url() ; ?>"><?php echo logo_header(); ?></a>
    </div>

    <!-- Search Bar -->
    <div class="header-right">
      <?php osc_current_web_theme_path('inc.search.php') ; ?>
    </div>
  </div>
</div>

<?php osc_current_web_theme_path('inc.category.php') ; ?>

<?php
  $position = array(osc_get_osclass_location(), osc_get_osclass_section());
  $position = array_filter($position);
  $position = implode('-', $position);
?>

<div class="container-outer <?php echo $position; ?>">
<div class="container">
<!-- header -->
<?php if (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'],'mb-themes') !== false) { ?>
  <div id="piracy" class="noselect" title="Click to hide this box">This theme is ownership of MB Themes and can be bought only on http://www.mb-themes.com or via Osclass Market that is official reseller. When you buy this theme on other site, you will have no support for this theme, you will be supporting piracy and violate ACTA anti-piracy agreement!</div>
  <script>$(document).ready(function(){ $('#piracy').click(function(){ $(this).fadeOut(200); }); });</script>
<?php } ?>
<?php if(function_exists('scrolltop')) { scrolltop(); } ?>

<div class="clear"></div>
<!-- /header -->

<?php osc_show_flash_message(); ?>


<!-- BREAD CRUMBS - hide on search page -->
<?php
  osc_show_widgets('header');
  $breadcrumb = osc_breadcrumb('<span class="bread-arrow"><i class="fa fa-angle-right"></i></span>', false);
?>

<?php if( $breadcrumb != '' && !osc_is_search_page()) { ?>
  <div class="breadcrumb">
    <div class="bread-home"><i class="fa fa-home"></i></div><?php echo $breadcrumb; ?><?php if (osc_is_ad_page()) { if (osc_item_is_premium()) { ?><span id="top-item" title="<?php echo osc_esc_html(__('Premium listing', 'zara')); ?>"><i class="fa fa-star"></i></span><?php } } ?>

    <?php if((!isset($content_only) || isset($content_only) && !$content_only) && osc_is_ad_page()) { ?>
      <div id="report" class="noselect">
        <i class="fa fa-bullhorn"></i>
        <div class="cont-wrap">
          <div class="cont">
            <a id="item_spam" href="<?php echo osc_item_link_spam() ; ?>" rel="nofollow"><?php _e('spam', 'zara') ; ?></a>
            <a id="item_bad_category" href="<?php echo osc_item_link_bad_category() ; ?>" rel="nofollow"><?php _e('misclassified', 'zara') ; ?></a>
            <a id="item_repeated" href="<?php echo osc_item_link_repeated() ; ?>" rel="nofollow"><?php _e('duplicated', 'zara') ; ?></a>
            <a id="item_expired" href="<?php echo osc_item_link_expired() ; ?>" rel="nofollow"><?php _e('expired', 'zara') ; ?></a>
            <a id="item_offensive" href="<?php echo osc_item_link_offensive() ; ?>" rel="nofollow"><?php _e('offensive', 'zara') ; ?></a>
          </div>
        </div>
      </div>
    <?php } ?>

    <div class="clear"></div>
  </div>
<?php } ?>

<?php View::newInstance()->_erase('countries'); ?>
<?php View::newInstance()->_erase('regions'); ?>
<?php View::newInstance()->_erase('cities'); ?>