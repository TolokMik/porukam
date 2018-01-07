<?php if ( (!defined('ABS_PATH')) ) exit('ABS_PATH is not loaded. Direct access is not allowed.'); ?>
<?php if ( !OC_ADMIN ) exit('User access is not allowed.'); ?>
<h2 class="render-title"><b><i class="fa fa-file-text"></i> <?php _e('Help', 'rbc'); ?></b></h2>
    <div class="form-horizontal">
        <div class="form-row">          
			<h3><?php _e('Tips For Administrators', 'rbc'); ?></h3>
			<p>
			   <?php _e('Metod - POST. Signature - MD5', 'rbc'); ?>
               <br/></p>
  <div class="form-row">
            Result URL: <b style="color: #2aa4db;">http://<?php echo $_SERVER['SERVER_NAME'].'/index.php?page=custom&route=rbc-result';?></b>
        </div>
        <div class="form-row">
            Success URL: <b style="color: green;">http://<?php echo $_SERVER['SERVER_NAME'].'/index.php?page=custom&route=rbc-success';?></b>
        </div>        <div class="form-row">
            Fail URL: <b style="color: red;">http://<?php echo $_SERVER['SERVER_NAME'].'/index.php?page=custom&route=rbc-cancel';?></b>
        </div>
			<h3><?php _e('After publish option', 'rbc'); ?></h3>
			<p>
                <?php _e('What is this function?If this feature is enabled. User after the published ad redirect to page immediately offered to pay Premium and Highlighting.', 'rbc'); ?>
                <br/>
				<?php _e('This allows you to get more payments. Bottom of the After page there is a button - no thanks.The user can press it and nothing pay at once - publish a classified. Function work only if Publish fee is disabled!', 'rbc'); ?>
                <br/>
				<?php _e('If you test the plugin and publish listings from Front-end, you must logout from  admin part. Otherwise you will not see After publish option or Publish option.', 'rbc'); ?>
			    <br/>
			</p>
			</p>
			<h3><?php _e('Setup highlighting', 'rbc'); ?></h3>
			<p>
			   <?php _e('Need small modifictions of theme files. Need add 2 id with functions in search files.  In themes usualy this is search-list.php and search-gallery.php or loop-single.php, loop-single-premium.php', 'rbc'); ?>
               <br/>
			   <?php _e('For premium items id="&lt;?php if(function_exists(\'rbc_premium_get_class_color\')){echo rbc_premium_get_class_color(osc_premium_id());}?&gt;" .', 'rbc'); ?>
				<br/>
				<?php _e('For items id="&lt;?php if(function_exists(\'rbc_get_class_color\')){echo rbc_get_class_color(osc_item_id());}?&gt;"', 'rbc'); ?>
				<br/>
                <?php _e('Example, the template Bender:', 'rbc'); ?>
				<br/>
                <?php _e('File loop-single.php - 2-line :', 'rbc'); ?>
				<br/>
                <?php _e('&lt;li class = "listing-card &lt;?php echo $class; if(osc_item_is_premium()){echo\'premium\';}?&gt;" id="&lt;?php if(function_exists(\'rbc_get_class_color\')){echo rbc_get_class_color(osc_item_id());}?&gt;"&gt;', 'rbc'); ?>
                <br/>
				<?php _e('File loop-single-premium.php :', 'rbc'); ?>
				<br/>
                <?php _e('&lt;li class = "listing-card &lt;?php echo $class; if(osc_item_is_premium()){echo\'premium\';}?&gt;" id="&lt;?php if(function_exists(\'rbc_premium_get_class_color\')){echo rbc_premium_get_class_color(osc_premium_id());}?&gt;"&gt;', 'rbc'); ?>
                <br/>
				<?php _e('and add the main.css #colorized{background:#F0E68C!important;} . Color of course you can change as you need.', 'rbc'); ?>
				<br/>
			</p>
			<h3><?php _e('Insert buttons in item page style', 'rbc'); ?></h3>
			<p> 
                 <?php _e('Maybe you not like the style of buttons', 'rbc'); ?>.
                 <br/>	
                 <?php _e('You can change style of buttons in you theme css file. Buttons have class .rbc', 'rbc'); ?>.
                 <br/>					 
                <?php _e('Example, you can insert in you CSS style file code: .rbc{display: inline-block;float:left;width:auto;}', 'rbc'); ?>
                <br/>

			</p>
				 </p></div>
     
    </div>

	<div class="form-row">
	 <address class="osclasspro_address">
	<span>&copy;<?php echo date('Y') ?> <a target="_blank" title="osclass-pro.ru" href="https://osclass-pro.ru/">osclass-pro.ru</a>. All rights reserved.</span>
  </p>
  </address></div>
 <?php echo '<script src="' . osc_base_url() . 'oc-content/plugins/rbc/admin/js/jquery.admin.js"></script>'; ?>
  

         
