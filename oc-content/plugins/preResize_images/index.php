<?php
/*
Plugin Name: Pre-resize Images
Description: Pre-resize images on client side before uploading them
Version: 1.1.2
Author: teseo
Short Name: preResize_images
Plugin update URI: pre-resize-images
*/ 
 
function przi_call_after_install()
{
    $normal_dimensions = osc_normal_dimensions(); 
    $dimensions = explode('x', $normal_dimensions);

    osc_set_preference('maxPixels', max($dimensions) * 2, 'preResize_images', 'INTEGER');
    osc_reset_preferences();
}

function przi_call_after_uninstall()
{
    osc_delete_preference('maxPixels', 'preResize_images');
    osc_reset_preferences();
}

function przi_settings()
{
    osc_admin_render_plugin(osc_plugin_path(dirname(__FILE__)) . '/settings.php'); 
}

function przi_admin_menu()
{
    echo '<h3><a href="#">Pre-resize Images</a></h3> 
        <ul> 
            <li><a href="' . osc_admin_render_plugin_url(osc_plugin_folder(__FILE__) . 'settings.php') . '">&raquo; ' . __('Settings & Help', 'preResize_images') . '</a><li>
        </ul>';
}

function przi_ajax_photos($resources = null)
{
    if ($resources == null) {
        $resources = osc_get_item_resources();
    }
    ;
    $aImages = array();
    if (Session::newInstance()->_getForm('photos') != '') {
        $aImages = Session::newInstance()->_getForm('photos');
        $aImages = $aImages['name'];
        Session::newInstance()->_drop('photos');
        Session::newInstance()->_dropKeepForm('photos');
    } ?>

<div id="restricted-fine-uploader"></div>
<div style="clear:both;"></div>
<?php if (count($aImages) > 0 || ($resources != null && is_array($resources) && count($resources) > 0)) { ?>
<h3><?php _e('Images already uploaded');?></h3>
<ul class="qq-upload-list">
    <?php foreach ($resources as $_r) {
    $img = $_r['pk_i_id'] . '.' . $_r['s_extension']; ?>
    <li class=" qq-upload-success">
        <span class="qq-upload-file"><?php echo $img; ?></span>
        <a class="qq-upload-delete" href="#" photoid="<?php echo $_r['pk_i_id']; ?>"
           itemid="<?php echo $_r['fk_i_item_id']; ?>" photoname="<?php echo $_r['s_name']; ?>"
           photosecret="<?php echo Params::getParam('secret'); ?>"
           style="display: inline; cursor:pointer;"><?php _e('Delete'); ?></a>

        <div class="ajax_preview_img"><img
                src="<?php echo osc_apply_filter('resource_path', osc_base_url() . $_r['s_path']) . $_r['pk_i_id'] . '_thumbnail.' . $_r['s_extension']; ?>"
                alt="<?php echo osc_esc_html($img); ?>"></div>
    </li>
    <?php }; ?>
    <?php foreach ($aImages as $img) { ?>
    <li class="qq-upload-success">
        <span class="qq-upload-file"><?php echo $img; $img = osc_esc_html($img); ?></span>
        <a class="qq-upload-delete" href="#" ajaxfile="<?php echo $img; ?>"
           style="display: inline; cursor:pointer;"><?php _e('Delete'); ?></a>

        <div class="ajax_preview_img"><img
                src="<?php echo osc_base_url(); ?>oc-content/uploads/temp/<?php echo $img; ?>"
                alt="<?php echo $img; ?>"></div>
        <input type="hidden" name="ajax_photos[]" value="<?php echo $img; ?>">
    </li>
    <?php } ?>
</ul>
<?php } ?>
<div style="clear:both;"></div>

<script type="text/template" id="qq-template">
    <div class="qq-uploader-selector qq-uploader">
        <div class="qq-total-progress-bar-container-selector qq-total-progress-bar-container">
            <div class="qq-total-progress-bar-selector qq-progress-bar qq-total-progress-bar"></div>
        </div> 
        <div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
            <span><?php _e('Click or Drop for upload images'); ?></span> 
        </div>
        <div class="qq-upload-button-selector qq-upload-button">
            <div><?php echo osc_esc_js(__('Click or Drop for upload images')); ?></div>
        </div>
                <span class="qq-drop-processing-selector qq-drop-processing">
                    <span><?php _e('Processing...'); ?></span>
                    <span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span> 
                </span>
        <ul class="qq-upload-list-selector qq-upload-list">
            <li>
                <div class="qq-progress-bar-container-selector">
                    <div class="qq-progress-bar-selector qq-progress-bar"></div>
                </div>
                <span class="qq-upload-spinner-selector qq-upload-spinner"></span>
                <span class="qq-edit-filename-icon-selector qq-edit-filename-icon"></span>
                <span class="qq-upload-file-selector qq-upload-file"></span>
                <input class="qq-edit-filename-selector qq-edit-filename" tabindex="0" type="text">
                <span class="qq-upload-size-selector qq-upload-size"></span>
                <a class="qq-upload-cancel-selector qq-upload-cancel" href="#"><?php _e('Cancel'); ?></a>
                <a class="qq-upload-retry-selector qq-upload-retry" href="#"><?php _e('Retry'); ?></a>
                <a class="qq-upload-delete-selector qq-upload-delete" href="#"><?php _e('Delete'); ?></a>
                <span class="qq-upload-status-text-selector qq-upload-status-text"></span>
            </li>
        </ul>
    </div>
</script> 
<?php 
    $aExt = explode(',', osc_allowed_extension());
    foreach ($aExt as $key => $value) {
        $aExt[$key] = "'" . $value . "'";
    }

    $allowedExtensions = join(',', $aExt);
    $maxSize = (int)osc_max_size_kb() * 1024;
    $maxImages = (int)osc_max_images_per_item();
    ?>

<style>
    .qq-hide {
        display: none !important;
    }
    .qq-upload-list li {
        width: 162px;
        max-width: 162px;
        overflow: hidden; 
        height: auto;
        vertical-align: top;

    }
    .primary_image {
        display: block;
        width: 100%;
        height: auto;
        top: initial;
        bottom: 8px;
        padding-top: 2px;
        padding-bottom: 8px;  
    }
    .primary_image a {
        display: inline;
        width: 100%;
        right: initial;
        cursor: pointer;
        text-align: center;
    }
</style>
 
<script>
$(document).ready(function() {
    fineUploaderContainer = $('#restricted-fine-uploader');

    $('.qq-upload-delete').on('click', function (evt) {
        evt.preventDefault();
        var parent = $(this).parent()
        var result = confirm('<?php echo osc_esc_js(__("This action can't be undone. Are you sure you want to continue?")); ?>');
        var urlrequest = '';
        if ($(this).attr('ajaxfile') != undefined) {
            urlrequest = 'ajax_photo=' + $(this).attr('ajaxfile');
        } else {
            urlrequest = 'id=' + $(this).attr('photoid') + '&item=' + $(this).attr('itemid') + '&code=' + $(this).attr('photoname') + '&secret=' + $(this).attr('photosecret');
        }
        if (result) {
            $.ajax({
                type:"POST",
                url:'<?php echo osc_base_url(true); ?>?page=ajax&action=delete_image&' + urlrequest,
                dataType:'json',
                success:function (data) {
                    parent.remove();
                }
            });
        }
    });

    fineUploaderContainer.on('click', '.primary_image', function (event) {
        if (parseInt($("div.primary_image").index(this)) > 0) {
            var li_current = $(this).parent();
            var li_current_img = li_current.find('.ajax_preview_img img');
            
            var a_src = li_current_img.attr('src');
            var a_title = li_current_img.attr('alt');
            var a_input = li_current.find('input[name^=ajax_photos]').attr('value');

            // info
            var a1 = li_current.find('span.qq-upload-file').text();
            var a2 = li_current.find('span.qq-upload-size').text();

            var li_first = $('ul.qq-upload-list li').get(0);
            var li_first_img = $(li_first).find('.ajax_preview_img img'); 

            var b_src = li_first_img.attr('src');
            var b_title = li_first_img.attr('alt');
            var b_input = $(li_first).find('input[name^=ajax_photos]').attr('value');
            
            var b1 = $(li_first).find('span.qq-upload-file').text();
            var b2 = $(li_first).find('span.qq-upload-size').text();

            li_first_img.attr('src', a_src);
            li_first_img.attr('alt', a_title);
            $(li_first).find('input').attr('value', a_input);
            $(li_first).find('span.qq-upload-file').text(a1); 
            $(li_first).find('span.qq-upload-size').text(a2);

            li_current_img.attr('src', b_src);
            li_current_img.attr('alt', b_title);
            li_current.find('input').attr('value', b_input);
            li_current.find('span.qq-upload-file').text(b1);
            li_current.find('span.qq-upload-size').text(b2);
            removed_images--; 
        }
    });

    fineUploaderContainer.on('click', '.primary_image', function (event) {
        $(this).addClass('over primary');
    });

    fineUploaderContainer.on('mouseenter mouseleave', '.primary_image', function (event) {
        if (event.type == 'mouseenter') {
            if (!$(this).hasClass('primary')) {
                $(this).addClass('primary');
            }
        } else {
            if (parseInt($("div.primary_image").index(this)) > 0) {
                $(this).removeClass('primary');
            }
        }
    });


    fineUploaderContainer.on('mouseenter mouseleave', 'li.qq-upload-success', function (event) {
        if (parseInt($("li.qq-upload-success").index(this)) > 0) {
            if (event.type == 'mouseenter') {
                $(this).find('div.primary_image').addClass('over');
            } else {
                $(this).find('div.primary_image').removeClass('over');
            }
        }
    });

    window.removed_images = 0;
    window.abortedUpload = 0;
    fineUploaderContainer.on('click', 'a.qq-upload-delete', function (event) {
        removed_images++;
        $('#restricted-fine-uploader .flashmessage-error').remove();
    });

    // INIT settings
        fineUploaderContainer.fineUploader({
            request:{
                endpoint:'<?php echo osc_base_url(true) . "?page=ajax&action=runhook&hook=przi_ajax_uploader"; ?>'
            },
            scaling:{
                sendOriginal:false,
                defaultQuality:80,
                includeExif:true,
                sizes:[
                    {name:"", maxSize: <?php echo osc_get_preference('maxPixels', 'preResize_images'); ?>}
                ]
            },
            multiple:true,
            validation:{
                allowedExtensions:[<?php echo $allowedExtensions; ?>],
                sizeLimit: <?php echo $maxSize; ?>,
                itemLimit: <?php echo $maxImages; ?>
            },
            messages:{
                tooManyItemsError:'<?php echo osc_esc_js(__('Too many items ({netItems}) would be uploaded. Item limit is {itemLimit}.'));?>',
                onLeave:'<?php echo osc_esc_js(__('The files are being uploaded, if you leave now the upload will be cancelled.'));?>',
                typeError:'<?php echo osc_esc_js(__('{file} has an invalid extension. Valid extension(s): {extensions}.'));?>',
                sizeError:'<?php echo osc_esc_js(__('{file} is too large, maximum file size is {sizeLimit}.'));?>',
                emptyError:'<?php echo osc_esc_js(__('{file} is empty, please select files again without it.'));?>'
            },
            deleteFile:{
                enabled:true,
                method:"POST",
                forceConfirm:false,
                endpoint:'<?php echo osc_base_url(true) . "?page=ajax&action=delete_ajax_upload"; ?>'
            },
            retry:{
                showAutoRetryNote:true,
                showButton:true
            },
            text:{
                uploadButton:'<?php echo osc_esc_js(__('Click or Drop for upload images')); ?>',
                waitingForResponse:'<?php echo osc_esc_js(__('Processing...')); ?>',
                retryButton:'<?php echo osc_esc_js(__('Retry')); ?>',
                cancelButton:'<?php echo osc_esc_js(__('Cancel')); ?>',
                failUpload:'<?php echo osc_esc_js(__('Upload failed')); ?>',
                deleteButton:'<?php echo osc_esc_js(__('Delete')); ?>',
                deletingStatusText:'<?php echo osc_esc_js(__('Deleting...')); ?>',
                formatProgress:'<?php echo osc_esc_js(__('{percent}% of {total_size}')); ?>'
            }
        }).on('error',function (event, id, name, errorReason, xhrOrXdr) {
                    removed_images++;
                    $('#restricted-fine-uploader .flashmessage-error').remove(); 
                    fineUploaderContainer.append('<div class="flashmessage flashmessage-error">' + errorReason + '<a class="close" style="color: #fff; float: right; padding-right: 10px; cursor: pointer;" onclick="javascript:$(\'.flashmessage-error\').remove();" >X</a></div>');

                    abortedUpload = abortedUpload + parseInt(errorReason.match(/\((\d+)\)/)[1]) - $('.qq-upload-list li').length;

       }).on('statusChange',function (event, id, old_status, new_status) {
            $(".alert.alert-error").remove();
       }).on('complete', function(event, id, fileName, responseJSON) {
            if (responseJSON.success) {
                $('#restricted-fine-uploader .flashmessage-error').remove();
                $('.qq-upload-delete').show();
        
                var new_id = id - removed_images - abortedUpload;
                var li = $('.qq-upload-list li')[new_id];

                // @TOFIX @FIXME escape $responseJSON_uploadName below
                // need a js function similar to osc_esc_js(osc_esc_html())
                $(li).append('<div class="ajax_preview_img"><img src="<?php echo osc_base_url(); ?>oc-content/uploads/temp/' + responseJSON.uploadName + '" alt="' + responseJSON.uploadName + '"></div>');
                $(li).append('<input type="hidden" name="ajax_photos[]" value="' + responseJSON.uploadName + '"></input>'); 

                    if (parseInt(new_id) == 0) {
                        $(li).append('<div class="primary_image primary"></div>');
                    } else {
                        $(li).append('<div class="primary_image"><a class="qq-upload-delete" style="" title="<?php echo osc_esc_js(osc_esc_html(__('Make primary image'))); ?>"><?php echo osc_esc_js(osc_esc_html(__('Make primary image'))); ?></a></div>');
                        $(".primary_image a").css('text-decoration', $(".qq-upload-delete-selector").css('text-decoration'));
                    }
            }

    <?php if (Params::getParam('action') == 'item_edit') { ?> 
    }).on('validateBatch', function (event, fileOrBlobDataArray) {
        var len = fileOrBlobDataArray.length;
        var result = canContinue(len);
          
        return result.success; 
    });

    function canContinue(numUpload) {
        // strUrl is whatever URL you need to call
        var strUrl = "<?php echo osc_base_url(true) . "?page=ajax&action=ajax_validate&id=" . osc_item_id() . "&secret=" . osc_item_secret(); ?>";
        var strReturn = {}; 

        jQuery.ajax({
            url:strUrl,
            success:function (html) {
                strReturn = html;
            },
            async:false
        });
        var json = JSON.parse(strReturn);
        var total = parseInt(json.count) + $("#restricted-fine-uploader input[name='ajax_photos[]']").size() + (numUpload);

        if (total <=<?php echo $maxImages;?>) {
            json.success = true;
        } else {
            json.success = false; 

            $('.qq-upload-button').after('<div class="flashmessage flashmessage-error" style="margin-bottom: 20px;"><?php echo osc_esc_js(sprintf(__('Too many items were uploaded. Item limit is %d.'), $maxImages)); ?><a class="close" style="color: #fff; float: right; padding-right: 10px; cursor: pointer;" onclick="javascript:$(\'.flashmessage-error\').remove();" >X</a></div>');
            abortedUpload = abortedUpload + numUpload;
        }
        return json;
    }

    <?php } else { ?>
});
    <?php } ?>
})

</script>
<?php }

function przi_ajax_uploader()
{
    require_once 'prziAjaxUploader.php';
    $uploader = new prziAjaxUploader();
    $filename = uniqid("qqfile_") . "." . pathinfo(Params::getParam('qqfilename'), PATHINFO_EXTENSION);
    $result = $uploader->handleUpload(osc_content_path() . 'uploads/temp/' . $filename);
    $result['uploadName'] = $filename;

    echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
}

osc_add_hook('ajax_przi_ajax_uploader', 'przi_ajax_uploader');

function przi_divert_fineuploader($script)
{
    if (strstr($script, 'fineuploader')) {
        if (!Plugins::isEnabled('minifyer/index.php')) {
            return osc_plugin_url(__FILE__) . 'js/fine-uploader/jquery.fineuploader.min.js';
        } else {
            return preg_replace('~(^.*,)(.*?fineuploader.*?\.js)(.*)$~', '$1' . str_replace(osc_base_url(), '', osc_plugin_url(__FILE__) . 'js/fine-uploader/jquery.fineuploader.min.js') . '$3', $script);
        }
}
    else return $script;  
}

osc_add_filter('theme_url', 'przi_divert_fineuploader');

// Hook for registering plugin 
osc_register_plugin(osc_plugin_path(__FILE__), 'przi_call_after_install');
osc_add_hook(osc_plugin_path(__FILE__) . '_uninstall', 'przi_call_after_uninstall');
osc_add_hook(osc_plugin_path(__FILE__) . "_configure", 'przi_settings');

osc_add_hook('admin_menu', 'przi_admin_menu');
?>