<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<title><?php echo meta_title() ; ?></title>
<meta name="title" content="<?php echo osc_esc_html(meta_title()); ?>" />

<?php if( meta_description() != '' ) { ?>
  <meta name="description" content="<?php echo osc_esc_html(meta_description()); ?>" />
<?php } ?>

<?php if( function_exists('meta_keywords') ) { ?>
  <?php if( meta_keywords() != '' ) { ?>
    <meta name="keywords" content="<?php echo osc_esc_html(meta_keywords()); ?>" />
  <?php } ?>
<?php } ?>

<?php if( osc_get_canonical() != '' ) { ?>
  <link rel="canonical" href="<?php echo osc_get_canonical(); ?>"/>
<?php } ?>


<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache" />
<meta http-equiv="Expires" content="Mon, 01 Jul 1970 00:00:00 GMT" />
<meta name="robots" content="index, follow" />
<meta name="googlebot" content="index, follow" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />


<script type="text/javascript">
  var fileDefaultText = '<?php echo osc_esc_js(__('No file selected', 'zara')); ?>';
  var fileBtnText     = '<?php echo osc_esc_js(__('Choose File', 'zara')); ?>';
  var zara_search_img = '<?php echo osc_base_url() . '/oc-content/themes/' . osc_current_web_theme() . '/images/search-sprite.png'; ?>';
</script>

<?php
osc_enqueue_style('style', osc_current_web_theme_url('css/style.css'));
osc_enqueue_style('tabs', osc_current_web_theme_url('css/tabs.css'));
osc_enqueue_style('fancy', osc_current_web_theme_js_url('fancybox/jquery.fancybox.css'));
osc_enqueue_style('responsive', osc_current_web_theme_url('css/responsive.css'));
osc_enqueue_style('bxslider', osc_current_web_theme_url('css/bxslider/jquery.bxslider.css'));

osc_register_script('jquery-uniform', osc_current_web_theme_js_url('jquery.uniform.js'), 'jquery');
osc_register_script('jquery-drag', osc_current_web_theme_js_url('jquery.drag.min.js'), 'jquery');
osc_register_script('global', osc_current_web_theme_js_url('global.js'));
osc_register_script('fancybox', osc_current_web_theme_url('js/fancybox/jquery.fancybox.js'), array('jquery'));
osc_register_script('validate', osc_current_web_theme_js_url('jquery.validate.min.js'), array('jquery'));
osc_register_script('date', osc_base_url() . 'oc-includes/osclass/assets/js/date.js');
osc_register_script('priceFormat', osc_current_web_theme_js_url('priceFormat.js'));
osc_register_script('bxslider', osc_current_web_theme_js_url('jquery.bxslider.js'));


osc_enqueue_script('jquery');
osc_enqueue_script('fancybox');
osc_enqueue_script('validate');

osc_enqueue_style('priceSlider', '//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.min.css');
osc_enqueue_script('date');
osc_enqueue_script('priceFormat');
osc_enqueue_script('global');


if(osc_is_publish_page()){
  osc_enqueue_script('date');
  osc_enqueue_style('priceSlider', '//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.min.css');
}

if (function_exists('watchlist')) {
  osc_register_script('watchlist', osc_base_url(). 'oc-content/plugins/watchlist/js/watchlist.js');

  if(osc_is_ad_page()){
    osc_enqueue_script('watchlist');
  }
}

osc_enqueue_script('jquery-ui');
osc_enqueue_script('jquery-uniform');
osc_enqueue_script('jquery-drag');
osc_enqueue_script('tabber');
osc_enqueue_script('bxslider');

osc_enqueue_style('open-sans', '//fonts.googleapis.com/css?family=Open+Sans:300,600&amp;subset=latin,latin-ext');
osc_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css');
?>

<?php zara_manage_cookies(); ?>

<?php osc_run_hook('header'); ?>