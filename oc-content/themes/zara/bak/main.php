<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()) ; ?>">
<head>
  <?php osc_current_web_theme_path('head.php') ; ?>
</head>
<body>
  <?php osc_current_web_theme_path('header.php') ; ?>

  <div class="content home">

    <!-- HOME SLIDER -->
    <?php if(function_exists('osc_slider')) { ?>
      <div id="home-slider">
        <?php osc_slider(); ?>
      </div>
    <?php } ?>


    <!-- LATEST LISTINGS -->
    <div id="latest" class="white">
      <h2 class="home">
        <?php _e('Latest listings', 'zara'); ?>
      </h2>

      <?php View::newInstance()->_exportVariableToView('latestItems', zara_random_items()); ?>

      <?php if( osc_count_latest_items() > 0) { ?>
        <div class="block">
          <div class="wrap">
            <?php $c = 1; ?>
            <?php while( osc_has_latest_items() ) { ?>
              <div class="simple-prod o<?php echo $c; ?>">
                <div class="simple-wrap">
                  <?php if(function_exists('fi_make_favorite')) { echo fi_make_favorite(); } ?>

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
      
        <div class="home-see-all">
          <a href="<?php echo osc_search_url(array('page' => 'search'));?>"><?php _e('See all offers', 'zara'); ?></a>
          <i class="fa fa-angle-down"></i>
        </div>
      <?php } else { ?>
        <div class="empty"><?php _e('No latest listings', 'zara'); ?></div>
      <?php } ?>

      <?php View::newInstance()->_erase('items') ; ?>
    </div>


    <!-- HOME ADSENSE -->
    <?php if(osc_get_preference('theme_adsense', 'zara_theme') == 1) { ?>
      <div class="home-google">
        <?php echo osc_get_preference('banner_home', 'zara_theme'); ?>
      </div>        
    <?php } ?>
  </div>

  <?php osc_current_web_theme_path('footer.php') ; ?>
</body>
</html>	