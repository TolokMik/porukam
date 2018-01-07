<link href="<?php echo osc_current_web_theme_url('admin/admin.css'); ?>" rel="stylesheet" type="text/css" />
<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />

<?php
//Save images
if(Params::getParam('action_specific')=='images') { 
  $upload_dir_small = osc_themes_path() . osc_current_web_theme() . '/images/small_cat/';
  $upload_dir_large = osc_themes_path() . osc_current_web_theme() . '/images/large_cat/';

  if (!file_exists($upload_dir_small)) { mkdir($upload_dir_small, 0777, true); }
  if (!file_exists($upload_dir_large)) { mkdir($upload_dir_large, 0777, true); }

  $count_real = 0;
  for ($i=1; $i<=1000; $i++) {
    if(isset($_POST['fa-icon' .$i])) {
      $fields['fields'] = array('s_icon' => Params::getParam('fa-icon' .$i));
      $fields['aFieldsDescription'] = array();
      Category::newInstance()->updateByPrimaryKey($fields, $i);
      message_ok(__('Font Awesome icon successfully saved for category' . ' <strong>#' . $i . '</strong>' ,'zara'));
    }

    if(isset($_POST['color' .$i])) {
      $fields['fields'] = array('s_color' => Params::getParam('color' .$i));
      $fields['aFieldsDescription'] = array();
      Category::newInstance()->updateByPrimaryKey($fields, $i);
      message_ok(__('Color successfully saved for category' . ' <strong>#' . $i . '</strong>' ,'zara'));
    }

    if(isset($_FILES['small' .$i]) and $_FILES['small' .$i]['name'] <> ''){

      $file_ext   = strtolower(end(explode('.', $_FILES['small' .$i]['name'])));
      $file_name  = $i . '.' . $file_ext;
      $file_tmp   = $_FILES['small' .$i]['tmp_name'];
      $file_type  = $_FILES['small' .$i]['type'];   
      $extensions = array("png");

      if(in_array($file_ext,$extensions )=== false) {
        $errors = __('extension not allowed, only allowed extension is .png!','zara');
      } 
				
      if(empty($errors)==true){
        move_uploaded_file($file_tmp, $upload_dir_small.$file_name);
        message_ok(__('Small image #','zara') . $i . __(' uploaded successfully.','zara'));
        $count_real++;
      } else {
        message_error(__('There was error when uploading small image #','zara') . $i . ': ' .$errors);
      }
    }
  }

  $count_real = 0;
  for ($i=1; $i<=1000; $i++) {
    if(isset($_FILES['large' .$i]) and $_FILES['large' .$i]['name'] <> ''){
      $file_ext   = strtolower(end(explode('.', $_FILES['large' .$i]['name'])));
      $file_name  = $i . '.' . $file_ext;
      $file_tmp   = $_FILES['large' .$i]['tmp_name'];
      $file_type  = $_FILES['large' .$i]['type'];   
      $extensions = array("jpg");

      if(in_array($file_ext,$extensions )=== false) {
        $errors = __('extension not allowed, only allowed extension for large images is .jpg!','zara');
      }
				
      if(empty($errors)==true){
        move_uploaded_file($file_tmp, $upload_dir_large.$file_name);
        message_ok(__('Large image #','zara') . $i . __(' uploaded successfully.','zara'));
        $count_real++;
      } else {
        message_error(__('There was error when uploading large image #','zara') . $i . ': ' .$errors);
      }
    }
  }
}
?>


<h2 class="render-title <?php echo (osc_get_preference('footer_link', 'zara_theme') ? '' : 'separate-top'); ?>"><?php _e('Theme settings', 'zara'); ?></h2>
<form action="<?php echo osc_admin_render_theme_url('oc-content/themes/' . osc_current_web_theme() . '/admin/settings.php'); ?>" method="post">
  <input type="hidden" name="action_specific" value="settings" />
  <fieldset>
    <div class="form-horizontal">
      <div class="form-row">
        <div class="form-label"><?php _e('Contact number', 'zara'); ?></div>
        <div class="form-controls"><input type="text" class="xlarge" name="phone" value="<?php echo osc_esc_html( osc_get_preference('phone', 'zara_theme') ); ?>"> <span class="after-text"><?php _e('(leave blank to disable)', 'zara'); ?></span></div>
      </div>

      <div class="form-row">
        <div class="form-label"><?php _e('Website Name', 'zara'); ?></div>
        <div class="form-controls"><input type="text" class="xlarge" name="website_name" value="<?php echo osc_esc_html( osc_get_preference('website_name', 'zara_theme') ); ?>"></div>
      </div>

      <div class="form-row">
        <div class="form-label"><?php _e('Currency in search box', 'zara'); ?></div>
        <div class="form-controls">
          <div class="form-label-checkbox">
            <select name="def_cur" id="def_cur">
            <?php foreach(osc_get_currencies() as $c) { ?>
              <option value="<?php echo $c['s_description']; ?>" <?php echo (osc_get_preference('def_cur', 'zara_theme') == $c['s_description'] ? 'selected="selected"' : ''); ?>><?php echo $c['s_description']; ?></option>
            <?php } ?>
            </select>
            <span class="after-text"><?php _e('(this currency format is used on latest & search listings)', 'zara'); ?></span>
          </div>
        </div>
      </div>

      <div class="form-row">
        <div class="form-label"><?php _e('Date format', 'zara'); ?></div>
        <div class="form-controls">
          <div class="form-label-checkbox">
            <select name="date_format" id="date_format">
              <option value="m/d" <?php echo (osc_get_preference('date_format', 'zara_theme') == 'm/d' ? 'selected="selected"' : ''); ?>>m/d (12/01)</option>
              <option value="d/m" <?php echo (osc_get_preference('date_format', 'zara_theme') == 'd/m' ? 'selected="selected"' : ''); ?>>d/m (01/12)</option>
              <option value="m-d" <?php echo (osc_get_preference('date_format', 'zara_theme') == 'm-d' ? 'selected="selected"' : ''); ?>>m-d (12-01)</option>
              <option value="d-m" <?php echo (osc_get_preference('date_format', 'zara_theme') == 'd-m' ? 'selected="selected"' : ''); ?>>d-m (01-12)</option>
              <option value="j. M" <?php echo (osc_get_preference('date_format', 'zara_theme') == 'j. M' ? 'selected="selected"' : ''); ?>>j. M (1. Dec)</option>
              <option value="M" <?php echo (osc_get_preference('date_format', 'zara_theme') == 'M' ? 'selected="selected"' : ''); ?>>M (Dec)</option>
              <option value="F" <?php echo (osc_get_preference('date_format', 'zara_theme') == 'F' ? 'selected="selected"' : ''); ?>>F (December)</option>
            </select>
            <span class="after-text"><?php _e('(showcase date: 1. December 2014)', 'zara'); ?></span>
          </div>
        </div>
      </div>

      <div class="form-row">
        <div class="form-label"><?php _e('Default view in search', 'zara'); ?></div>
        <div class="form-controls">
          <div class="form-label-checkbox">
            <select name="def_view" id="def_view">
              <option value="0" <?php echo (osc_get_preference('def_view', 'zara_theme') == 0 ? 'selected="selected"' : ''); ?>><?php _e('Gallery view', 'zara'); ?></option>
              <option value="1" <?php echo (osc_get_preference('def_view', 'zara_theme') == 1 ? 'selected="selected"' : ''); ?>><?php _e('List view', 'zara'); ?></option>
            </select>
          </div>
        </div>
      </div>

      <div class="form-row check">
        <div class="form-label"><?php _e('Footer link', 'zara'); ?></div>
        <div class="form-controls">
          <div class="form-label-checkbox"><input type="checkbox" name="footer_link" value="1" <?php echo (osc_get_preference('footer_link', 'zara_theme') ? 'checked' : ''); ?> > <?php _e('I want to help OSClass & MB themes by linking to <a href="http://osclass.org/" target="_blank">osclass.org</a> and <a href="http://www.mb-themes.com" target="_blank">MB-Themes.com</a> from my site', 'zara'); ?></div>
        </div>
      </div>

      <div class="form-row check">
        <div class="form-label"><?php _e('Default logo', 'zara'); ?></div>
        <div class="form-controls">
          <div class="form-label-checkbox"><input type="checkbox" name="default_logo" value="1" <?php echo (osc_get_preference('default_logo', 'zara_theme') ? 'checked' : ''); ?> > <?php _e("Show default logo in case you didn't upload one previously", 'zara'); ?></div>
        </div>
      </div>

      <div class="form-row check">
        <div class="form-label"><?php _e('Use Drag & Drop photo uploader', 'zara'); ?></div>
        <div class="form-controls">
          <div class="form-label-checkbox"><input type="checkbox" name="image_upload" value="1" <?php echo (osc_get_preference('image_upload', 'zara_theme') ? 'checked' : ''); ?> > <?php _e("Use new Drag & Drop image uploader instead old one", 'zara'); ?></div>
        </div>
      </div>

      <div class="clear"></div>

      <div class="form-row check">
        <div class="form-label"><?php _e('Category Icons', 'zara'); ?></div>
        <div class="form-controls">
          <div class="form-label-checkbox"><input type="checkbox" name="cat_icons" value="1" <?php echo (osc_get_preference('cat_icons', 'zara_theme') ? 'checked' : ''); ?> > <?php _e("Use FontAwesome icons instead of small image icons for categories on homepage", 'zara'); ?></div>
        </div>
      </div>

      <div class="form-row check">
        <div class="form-label"><?php _e('Partner section', 'zara'); ?></div>
        <div class="form-controls">
          <div class="form-label-checkbox"><input type="checkbox" name="enable_partner" value="1" <?php echo (osc_get_preference('enable_partner', 'zara_theme') ? 'checked' : ''); ?> > <?php _e("Enable partner section in footer", 'zara'); ?></div>
          <div class="form-label-checkbox italic"><?php _e("In partner section are shown images uploaded to folder oc-content/themes/zara/images/sponsor-logos", 'zara'); ?></div>
        </div>
      </div>

      <div class="form-row check">
        <div class="form-label"><?php _e('Dropdown subcategories when publishing', 'zara'); ?></div>
        <div class="form-controls">
          <div class="form-label-checkbox"><input type="checkbox" name="drop_cat" value="1" <?php echo (osc_get_preference('drop_cat', 'zara_theme') ? 'checked' : ''); ?> > <?php _e("Use categories/subcategories dropdown when publishing or editing listings", 'zara'); ?></div>
        </div>
      </div>

      <div class="form-row check">
        <div class="form-label"><?php _e('Photo pager', 'zara'); ?></div>
        <div class="form-controls">
          <div class="form-label-checkbox"><input type="checkbox" name="item_pager" value="1" <?php echo (osc_get_preference('item_pager', 'zara_theme') ? 'checked' : ''); ?> > <?php _e("Show photo icons on listing page under slide show (pager)", 'zara'); ?></div>
        </div>
      </div>

      <div class="form-row">
        <div class="form-label"><?php _e('Price Slider - currency position', 'zara'); ?></div>
        <div class="form-controls">
          <div class="form-label-checkbox">
            <select name="format_cur" id="format_cur">
              <option value="0" <?php echo (osc_get_preference('format_cur', 'zara_theme') == 0 ? 'selected="selected"' : ''); ?>><?php _e('Before price', 'zara'); ?></option>
              <option value="1" <?php echo (osc_get_preference('format_cur', 'zara_theme') == 1 ? 'selected="selected"' : ''); ?>><?php _e('After price', 'zara'); ?></option>
              <option value="2" <?php echo (osc_get_preference('format_cur', 'zara_theme') == 2 ? 'selected="selected"' : ''); ?>><?php _e('Do not show', 'zara'); ?></option>
            </select>
          </div>
        </div>
      </div>

      <div class="form-row">
        <div class="form-label"><?php _e('Price Slider - thousands separator', 'zara'); ?></div>
        <div class="form-controls">
          <div class="form-label-checkbox">
            <select name="format_sep" id="format_sep">
              <option value="" <?php echo (osc_get_preference('format_sep', 'zara_theme') == '' ? 'selected="selected"' : ''); ?>><?php _e('None', 'zara'); ?></option>
              <option value="." <?php echo (osc_get_preference('format_sep', 'zara_theme') == '.' ? 'selected="selected"' : ''); ?>>.</option>
              <option value="," <?php echo (osc_get_preference('format_sep', 'zara_theme') == ',' ? 'selected="selected"' : ''); ?>>,</option>
              <option value=" " <?php echo (osc_get_preference('format_sep', 'zara_theme') == ' ' ? 'selected="selected"' : ''); ?>><?php _e('(blank)', 'zara'); ?></option>
            </select>
          </div>
        </div>
      </div>


      <div class="form-row check">
        <div class="form-label"><?php _e('Location selector', 'zara'); ?></div>
        <div class="form-controls">
          <div class="form-label-checkbox"><input type="checkbox" name="home_location" value="1" <?php echo (osc_get_preference('home_location', 'zara_theme') ? 'checked' : ''); ?> > <?php _e("Show location selector on homepage (under categories select)", 'zara'); ?></div>
        </div>
      </div>


      <div class="form-row check">
        <div class="form-label"><?php _e('Latest items random', 'zara'); ?></div>
        <div class="form-controls">
          <div class="form-label-checkbox"><input type="checkbox" name="latest_random" value="1" <?php echo (osc_get_preference('latest_random', 'zara_theme') ? 'checked' : ''); ?> > <?php _e("Show latest items in random order", 'zara'); ?></div>
        </div>
      </div>


      <div class="form-row check">
        <div class="form-label"><?php _e('Latest items picture', 'zara'); ?></div>
        <div class="form-controls">
          <div class="form-label-checkbox"><input type="checkbox" name="latest_picture" value="1" <?php echo (osc_get_preference('latest_picture', 'zara_theme') ? 'checked' : ''); ?> > <?php _e("Show only items with picture in latest items section on homepage", 'zara'); ?></div>
        </div>
      </div>


      <div class="form-row check">
        <div class="form-label"><?php _e('Latest items premium', 'zara'); ?></div>
        <div class="form-controls">
          <div class="form-label-checkbox"><input type="checkbox" name="latest_premium" value="1" <?php echo (osc_get_preference('latest_premium', 'zara_theme') ? 'checked' : ''); ?> > <?php _e("Show only premium items in latest items section on homepage", 'zara'); ?></div>
        </div>
      </div>


      <div class="form-row">
        <div class="form-label"><?php _e('Latest items category', 'zara'); ?></div>
        <div class="form-controls">
          <div class="form-label-checkbox">
            <select name="latest_category" id="latest_category">
              <option value="" <?php echo (osc_get_preference('latest_category', 'zara_theme') == '' ? 'selected="selected"' : ''); ?>><?php _e('All categories', 'zara'); ?></option>

              <?php while(osc_has_categories()) { ?>
                <option value="<?php echo osc_category_id(); ?>" <?php echo (osc_get_preference('latest_category', 'zara_theme') == osc_category_id() ? 'selected="selected"' : ''); ?>><?php echo osc_category_name(); ?></option>
              <?php } ?>
            </select>
          </div>
        </div>
      </div>


      <div class="form-row check">
        <div class="form-label"><?php _e('Google Adsense', 'zara'); ?></div>
        <div class="form-controls">
          <div class="form-label-checkbox"><input type="checkbox" name="theme_adsense" value="1" <?php echo (osc_get_preference('theme_adsense', 'zara_theme') ? 'checked' : ''); ?> > <?php _e("Show Adsense banners", 'zara'); ?></div>
        </div>
      </div>

      <div class="form-row">
        <div class="form-label"><?php _e('Home page banner code', 'zara'); ?></div>
        <div class="form-controls"><textarea class="xlarge" name="banner_home" style="width:700px;" placeholder="<?php _e('Will be shown at bottom of home page, recommended is responsive banner with width 1200px', 'zara'); ?>"><?php echo stripslashes( osc_get_preference('banner_home', 'zara_theme') ); ?></textarea></div>
      </div>

      <div class="form-row">
        <div class="form-label"><?php _e('Search page banner code', 'zara'); ?></div>
        <div class="form-controls"><textarea class="xlarge" name="banner_search" style="width:700px;" placeholder="<?php _e('Will be shown in left sidebar on search page, recommended is responsive banner with width 270px', 'zara'); ?>"><?php echo stripslashes( osc_get_preference('banner_search', 'zara_theme') ); ?></textarea></div>
      </div>

      <div class="form-row">
        <div class="form-label"><?php _e('Listing page banner code', 'zara'); ?></div>
        <div class="form-controls"><textarea class="xlarge" name="banner_item" style="width:700px;" placeholder="<?php _e('Will be shown in right sidebar on item page, recommended is responsive banner with width 360px', 'zara'); ?>"><?php echo stripslashes( osc_get_preference('banner_item', 'zara_theme') ); ?></textarea></div>
      </div>
      
      <div class="form-actions">
        <input type="submit" value="<?php _e('Save changes', 'zara'); ?>" class="btn btn-submit">
      </div>      
    </div>
  </fieldset>
</form>

<div class="clear"></div>
<br />


<form name="promo_form" id="load_image" action="<?php echo osc_admin_render_theme_url('oc-content/themes/' . osc_current_web_theme() . '/admin/settings.php'); ?>" method="POST" enctype="multipart/form-data" >
  <input type="hidden" name="action_specific" value="images" />
  <fieldset>
    <div class="form-horizontal">
      <table>
        <tr style="background:#eee;font-weight:bold;text-align:left;">
          <th class="id">ID</th>
          <th class="name">Name</th>
          <th class="icon">Has small image</th>
          <th>Small image (50x30px - png)</th>
          <th class="icon">Has large image</th>
          <th>Large image (150x250px - jpg)</th>
          <th class="fa-icon"><a href="http://fortawesome.github.io/Font-Awesome/icons/" target="_blank">Font-Awesome icon</a></th>
          <th class="color">Color</th>
        </tr>

        <?php osc_has_subcategories_special(Category::newInstance()->toTree(),  0); ?> 
      </table>

      <input type="submit" value="<?php _e('Save', 'zara'); ?>" class="btn btn-submit">
    </div>
  </fieldset>
</form>

<?php
function osc_has_subcategories_special($categories, $deep = 0) {
  foreach($categories as $c) {
    echo '<tr' . ($deep == 0 ? ' class="parent"' : '') . '>';
    echo '<td class="id">' . $c['pk_i_id'] . '</td>';
    echo '<td class="sub' . $deep . ' name">' . $c['s_name'] . '</td>';

    if (file_exists(osc_themes_path() . osc_current_web_theme() . '/images/small_cat/' . $c['pk_i_id'] . '.png')) { 
      echo '<td class="icon"><img src="' . osc_base_url() . 'oc-content/themes/' . osc_current_web_theme() . '/images/img_yes.png" alt="Has Image" /></td>';  
    } else {
      echo '<td class="icon"><img src="' . osc_base_url() . 'oc-content/themes/' . osc_current_web_theme() . '/images/img_no.png" alt="Has not Image" rel="' .$upload_dir_large . $c['pk_i_id'] . '.png'. '" /></td>';  
    }

    echo '<td><a class="add_img" id="small' . $c['pk_i_id'] . '" href="#">' . __('Add small image', 'zara') . '</a>';

    if (file_exists(osc_themes_path() . osc_current_web_theme() . '/images/large_cat/' . $c['pk_i_id'] . '.jpg')) { 
      echo '<td class="icon"><img src="' . osc_base_url() . 'oc-content/themes/' . osc_current_web_theme() . '/images/img_yes.png" alt="Has Image" /></td>';  
    } else {
      echo '<td class="icon"><img src="' . osc_base_url() . 'oc-content/themes/' . osc_current_web_theme() . '/images/img_no.png" alt="Has not Image" /></td>';  
    }

    echo '<td><a class="add_img" id="large' . $c['pk_i_id'] . '" href="#">' . __('Add large image', 'zara') . '</a></td>';
    echo '<td class="fa-icon"><a class="add_fa" id="fa-icon' . $c['pk_i_id'] . '" href="#" title="To remove icon click on link and leave input empty.">' . __('Add / remove icon', 'zara') . '</a>';
 
    if(($c['s_icon'] == '' and $_POST['fa-icon' .$c['pk_i_id']] == '') or (isset($_POST['fa-icon' .$c['pk_i_id']]) and $_POST['fa-icon' .$c['pk_i_id']] == '')) { } else {
      echo '<span><i class="fa ' . ($_POST['fa-icon' .$c['pk_i_id']] <> '' ? $_POST['fa-icon' .$c['pk_i_id']] : $c['s_icon']) . '"></i></span>';
    }
    
    echo '</td>';

    echo '<td class="color"><a class="add_color" id="color' . $c['pk_i_id'] . '" href="#" title="To remove color click on link and leave input empty.">';

    if(($c['s_color'] == '' and $_POST['color' .$c['pk_i_id']] == '') or (isset($_POST['color' .$c['pk_i_id']]) and $_POST['color' .$c['pk_i_id']] == '')) { 
      echo  __('Add / remove color', 'zara');
    } else {
      echo __('Color', 'zara') . ': ' . ($_POST['color' .$c['pk_i_id']] <> '' ? $_POST['color' .$c['pk_i_id']] : $c['s_color']) . '<span class="show-color" style="background:' . ($_POST['color' .$c['pk_i_id']] <> '' ? $_POST['color' .$c['pk_i_id']] : $c['s_color']) . '"></span>';
    }

    echo '</a></td>';
    echo '</tr>';

    if(isset($c['categories']) && is_array($c['categories']) && !empty($c['categories'])) {
      osc_has_subcategories_special($c['categories'], $deep+1);
    }   
  }
}

if(!function_exists('message_ok')) {
  function message_ok( $text ) {
    $final  = '<div style="padding: 1%;width: 98%;margin-bottom: 15px;" class="flashmessage flashmessage-ok flashmessage-inline">';
    $final .= $text;
    $final .= '</div>';
    echo $final;
  }
}

if(!function_exists('message_error')) {
  function message_error( $text ) {
    $final  = '<div style="padding: 1%;width: 98%;margin-bottom: 15px;" class="flashmessage flashmessage-error flashmessage-inline">';
    $final .= $text;
    $final .= '</div>';
    echo $final;
  }
}
?>

<script>
$('.add_img').click(function() {
  var id = $(this).attr('id');
  $(this).parent().html('<input type="file" name="' + id + '" />');
  return false;
});

$('.add_fa').click(function() {
  var id = $(this).attr('id');
  $(this).parent().html('<input type="text" name="' + id + '" placeholder="example: fa-star" />');
  return false;
});

$('.add_color').click(function() {
  var id = $(this).attr('id');
  $(this).parent().html('<input type="text" name="' + id + '" placeholder="example: #CCFFDD" />');
  return false;
});
</script>