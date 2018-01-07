<div id="list-view">

  <!-- PREMIUM ITEMS, DISABLED BY DEFAULT -->
  <?php if(1==2) { ?>
  <?php osc_get_premiums(4); ?>
  <?php while(osc_has_premiums()) { ?>
    <div class="list-prod" id="<?php if(function_exists('rbc_premium_get_class_color')){echo rbc_premium_get_class_color(osc_premium_id());}?>" >
      <?php if(function_exists('fi_make_favorite')) { echo fi_make_favorite(); } ?>

      <div class="left">
        <h3 class="resp-title"><a href="<?php echo osc_premium_url(); ?>"><?php echo osc_highlight(osc_premium_title(), 80); ?></a></h3>

        <?php if(osc_images_enabled_at_items() and osc_count_premium_resources() > 0) { ?>
          <a class="big-img" href="<?php echo osc_premium_url(); ?>"><img src="<?php echo osc_resource_thumbnail_url(); ?>" title="<?php echo osc_esc_html(osc_premium_title()); ?>" alt="<?php echo osc_esc_html(osc_premium_title()); ?>" /></a>

          <div class="img-bar">
            <?php osc_reset_resources(); ?>
            <?php for ( $i = 0; osc_has_premium_resources(); $i++ ) { ?>
              <?php if($i < 3) { ?>
                <span class="small-img" id="bar_img_<?php echo $i; ?>"><img src="<?php echo osc_resource_thumbnail_url(); ?>" title="<?php echo osc_esc_html(osc_premium_title()); ?>" alt="<?php echo osc_esc_html(osc_premium_title()); ?>" <?php echo ($i==0 ? 'class="selected"' : ''); ?> /></span>
              <?php } ?>
            <?php } ?>
          </div>
        <?php } else { ?>
          <a class="big-img no-img" href="<?php echo osc_premium_url(); ?>"><img src="<?php echo osc_current_web_theme_url('images/no-image.png'); ?>" title="<?php echo osc_esc_html(osc_premium_title()); ?>" alt="<?php echo osc_esc_html(osc_premium_title()); ?>" /></a>
        <?php } ?>
      </div>

      <div class="middle">
        <div class="flag"><?php _e('top', 'zara'); ?></div>

        <h3><a href="<?php echo osc_premium_url(); ?>"><?php echo osc_highlight(osc_premium_title(), 80); ?></a></h3>
        <div class="desc <?php if(osc_count_premium_resources() > 0) { ?>has_images<?php } ?>"><?php echo osc_highlight(osc_premium_description(), 300); ?></div>
        <div class="loc"><i class="fa fa-map-marker"></i><?php echo zara_location_format(osc_premium_country(), osc_premium_region(), osc_premium_city()); ?></div>
        <div class="author">
          <i class="fa fa-pencil"></i><?php _e('Published by', 'zara'); ?> 
          <?php if(osc_premium_user_id() <> 0) { ?>
            <a href="<?php echo osc_user_public_profile_url(osc_premium_user_id()); ?>"><?php echo osc_premium_contact_name(); ?></a>
          <?php } else { ?>
            <?php echo (osc_premium_contact_name() <> '' ? osc_premium_contact_name() : __('Anonymous', 'zara')); ?>
          <?php } ?>
        </div>
      </div>

      <div class="right">
        <?php if( osc_price_enabled_at_items() ) { ?>
          <div class="price"><?php echo osc_premium_formated_price(); ?></div>
        <?php } ?>

        <a class="view round2" href="<?php echo osc_premium_url(); ?>"><?php _e('view', 'zara'); ?></a>
        <a class="category" href="<?php echo osc_search_url(array('sCategory' => osc_premium_category_id())); ?>"><?php echo osc_premium_category(); ?></a>

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
        <span class="date">
          <?php 
            if($item_d == 0 or $item_d  == 1) {
              echo __('published', 'zara') . ' <span>' . $item_date . '</span>'; 
            } else {
              echo __('published on', 'zara') . ' <span>' . $item_date . '</span>'; 
            }
          ?>
        </span>

        <span class="viewed">
          <?php echo __('viewed', 'zara') . ' <span>' . osc_premium_views() . '' . '</span>'; ?>
        </span>
      </div>

    </div>
  <?php } ?>
  <?php } ?>
  <!-- END PREMIUM ITEMS -->


  <?php while(osc_has_items()) { ?>
    <div class="list-prod" id="<?php if(function_exists('rbc_get_class_color')){echo rbc_get_class_color(osc_item_id());}?>" >
      <?php if(function_exists('fi_make_favorite')) { echo fi_make_favorite(); } ?>

      <div class="left">
        <h3 class="resp-title"><a href="<?php echo osc_item_url(); ?>"><?php echo osc_highlight(osc_item_title(), 80); ?></a></h3>

        <?php if(osc_images_enabled_at_items() and osc_count_item_resources() > 0) { ?>
          <a class="big-img" href="<?php echo osc_item_url(); ?>"><img src="<?php echo osc_resource_thumbnail_url(); ?>" title="<?php echo osc_esc_html(osc_item_title()); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?>" /></a>

          <div class="img-bar">
            <?php osc_reset_resources(); ?>
            <?php for ( $i = 0; osc_has_item_resources(); $i++ ) { ?>
              <?php if($i < 3) { ?>
                <span class="small-img" id="bar_img_<?php echo $i; ?>"><img src="<?php echo osc_resource_thumbnail_url(); ?>" title="<?php echo osc_esc_html(osc_item_title()); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?>" <?php echo ($i==0 ? 'class="selected"' : ''); ?> /></span>
              <?php } ?>
            <?php } ?>
          </div>
        <?php } else { ?>
          <a class="big-img no-img" href="<?php echo osc_item_url(); ?>"><img src="<?php echo osc_current_web_theme_url('images/no-image.png'); ?>" title="<?php echo osc_esc_html(osc_item_title()); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?>" /></a>
        <?php } ?>
      </div>

      <div class="middle">
        <?php if(osc_item_is_premium()) { ?>
          <div class="flag"><?php _e('top', 'zara'); ?></div>
        <?php } ?>

        <h3><a href="<?php echo osc_item_url(); ?>"><?php echo osc_highlight(osc_item_title(), 80); ?></a></h3>
        <div class="desc <?php if(osc_count_item_resources() > 0) { ?>has_images<?php } ?>"><?php echo osc_highlight(osc_item_description(), 300); ?></div>
        <div class="loc"><i class="fa fa-map-marker"></i><?php echo zara_location_format(osc_item_country(), osc_item_region(), osc_item_city()); ?></div>
        <div class="author">
          <i class="fa fa-pencil"></i><?php _e('Published by', 'zara'); ?> 
          <?php if(osc_item_user_id() <> 0) { ?>
            <a href="<?php echo osc_user_public_profile_url(osc_item_user_id()); ?>"><?php echo osc_item_contact_name(); ?></a>
          <?php } else { ?>
            <?php echo (osc_item_contact_name() <> '' ? osc_item_contact_name() : __('Anonymous', 'zara')); ?>
          <?php } ?>
        </div>
      </div>

      <div class="right">
        <?php if( osc_price_enabled_at_items() ) { ?>
          <div class="price"><?php echo osc_item_formated_price(); ?></div>
        <?php } ?>

        <a class="view round2" href="<?php echo osc_item_url(); ?>"><?php _e('view', 'zara'); ?></a>
        <a class="category" href="<?php echo osc_search_url(array('sCategory' => osc_item_category_id())); ?>"><?php echo osc_item_category(); ?></a>

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
        <span class="date">
          <?php 
            if($item_d == 0 or $item_d  == 1) {
              echo __('published', 'zara') . ' <span>' . $item_date . '</span>'; 
            } else {
              echo __('published on', 'zara') . ' <span>' . $item_date . '</span>'; 
            }
          ?>
        </span>

        <span class="viewed">
          <?php echo __('viewed', 'zara') . ' <span>' . osc_item_views() . ' ' . '</span>'; ?>
        </span>
      </div>

    </div>
  <?php } ?>
</div>


