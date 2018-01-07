<?php

/*
 * ========================================================================================
 *
 * TO CUSTOMIZE
 *
 * COPY THIS FILE TO YOUR THEME IN
 * oc-content/themes/{your_theme_name}/plugins/madhouse_messenger/thread.php
 *
 * FOR TRANSLATION, RENAME ALL "madhouse_messenger" in this file by "your_theme_name"
 * Then update your po and mo file of your theme
 *
 * ========================================================================================
 */

/*
 * ========================================================================================
 * /!\ /!\ /!\ /!\ /!\ /!\ /!\ /!\ /!\
 * REMOVE THE LINE UNDER IF YOU COPY THIS VIEW ON YOUR THEME
 * /!\ /!\ /!\ /!\ /!\ /!\ /!\ /!\ /!\
 * ========================================================================================
 */

Madhouse_Utils_Plugins::overrideView();

/**
 * ========================================================================================
 * /!\ /!\ /!\ /!\ /!\ /!\ /!\ /!\ /!\
 * REMOVE THE LINE AVOVE IF YOU COPY THIS VIEW ON YOUR THEME
 * /!\ /!\ /!\ /!\ /!\ /!\ /!\ /!\ /!\
 * ========================================================================================
 */

?>

<link rel="stylesheet" type="text/css" href="<?php echo mdh_current_plugin_url("assets/css/web.css"); ?>" />
<script type="text/javascript">
	$(document).ready(function() {
		// Ajax to load previous message.
		var mmessengerp = <?php echo Params::getParam("p") ?>;

        $(".js-messenger-form").on('submit', function(e) {
            console.log("coucou");
            $(this).find("input[type=submit]").prop('disabled', true);
        });

		$(".more > a").click(function(e) {
			e.preventDefault();

			var more = $(".more");

			$.ajax({
				type: "GET",
				url: "<?php echo mdh_messenger_ajax_url(); ?>",
				data: {
					"do": "more",
					"n": <?php echo Params::getParam("n") ?>,
					"p": mmessengerp + 1,
					"tid": <?php echo Params::getParam("id"); ?>
				},
				dataType: "json",
				success: function(response, text, jqXHR) {
                    $.each(response.data, function(i, e) {
                        if(! e.is_auto) {
                            $("ul.messages").append(
                                '<li class="message box">' +
                                    '<ul class="meta unstyled inline pull-right">' +
                                        '<li class="message-sent-date">' +
                                            e.sent_date.fb_formatted+
                                        '</li>' +
                                    '</ul>' +
                                    '<div class="message-sender">' +
                                        '<a href="' + e.sender.url + '">' +
                                            e.sender.name +
                                        '</a>' +
                                    '</div>' +
                                    '<div class="text">' +
                                        e.text +
                                    '</div>' +
                                    '<div class="clearfix">' +
                                        '<div class="message-delete pull-right">' +
                                            '<a href="' + e.urls.delete + '">' +
                                                '<?php _e("Delete", "madhouse_messenger"); ?>' +
                                            '</a>' +
                                        '</div>' +
                                        '<div class="message-date  inline">'+
                                            '<div class="read">' +
                                                '<?php _e("Read", "madhouse_messenger"); ?>' + ' ' +
                                                e.read_date.fb_formatted +
                                            '</div>' +
                                        '</div>' +
                                    '</div>' +
                                '</li>'
                            );
                        } else {
                            $("ul.messages").append(
                                '<li class="message box auto-message">' +
                                    '<div class="pull-right meta">' + e.sent_date.fb_formatted + '</div>' +
                                    e.text +
                                '</li>'
                            );
                        }
                    });

					// Is there more messages in this thread?
					if(response.hasMore == false) {
						// Remove the link that triggers this ajax call.
						more.remove();
					}
					++mmessengerp;
				}
			});
		});

        if ($('.js-messages').length > 0) {
            $jsMessages = $('.js-messages');

            if ($jsMessages.data('autolinker-enable') == '1') {
                var autolinker = new Autolinker(
                    {
                        newWindow: $jsMessages.data('autolinker-new-window'),
                        stripPrefix: $jsMessages.data('autolinker-strip-prefix'),
                        truncate: $jsMessages.data('autolinker-truncate-length'),
                        "phone": false,
                        "hashtag": "twitter"
                    }
                );

                $(".js-text").map(function(e) {
                    $(this).html(autolinker.link($(this).html()));
                });
            }
        }

	});
	/* add unread messages */
	setInterval("refreshthread();",7000);
	function refreshthread(){
	    var lastmsg_id;
		$.ajax({
    		type: "GET",
    		url: "<?php echo mdh_messenger_ajax_url(); ?>",
    		data: {
    			"do": "widget"
    		},
    		dataType: "json",
    		success: function(response, text, jqXHR) {
    		    console.log(response);
			    response.threads.forEach(function(item, i, arr){
				   	 if( item.has_unread && item.id == <?php echo mdh_thread_id(); ?>){
				   		//location.reload(true);
						var lastmsg= item.last_message;
						var old_msg = $("ul.messages").html();
						//make as read//
					    lastmsg_id = lastmsg.id;
						//
						 $("ul.messages").html(
                                '&nbsp; <li class="message box">' +
                                    '<ul class="meta unstyled inline pull-right">' +
                                        '<li class="message-sent-date">' +
                                            lastmsg.sent_date.fb_formatted+
                                        '</li>' +
                                    '</ul>' +
                                    '<div class="message-sender">' +
                                        '<a href="' + lastmsg.sender.url + '">' +
                                            lastmsg.sender.name +
                                        '</a>' +
                                    '</div>' +
                                    '<div class="text">' +
                                        lastmsg.text +
                                    '</div>' +
                                    '<div class="clearfix">' +
                                        '<div class="message-delete pull-right">' +
                                            '<a href="' + lastmsg.urls.delete + '">' +
                                                '<?php _e("Delete", "madhouse_messenger"); ?>' +
                                            '</a>' +
                                        '</div>' +
                                        '<div class="message-date  inline">'+
                                            '<div class="read">' +
                                                '<?php _e("Read", "madhouse_messenger"); ?>' + ' ' +
                                                '<?php _e("just now", "madhouse_messenger"); ?>'+
                                            '</div>' +
                                        '</div>' +
                                    '</div>' +
                                '</li>'+ 
								old_msg
						);
						 
						 
					}
				});
			}
	 	});
		makeasread(lastmsg_id);
	} 
	/*
	mark messages 
	*/
	function makeasread(idmsg){
		$.ajax({
    		type: "GET",
			url: "<?php echo mdh_messenger_ajax_url(); ?>",
			data: {
					"do": "read",
					"idmsg": idmsg,
					"tid": <?php echo Params::getParam("id"); ?>
			},
			dataType: "json",
			success: null
		});
	}
	/* form submit */


</script>

<div class="messenger">
    <div class="wrapper">

        <h2><?php echo mdh_thread_title_default() ?></h2>
        <div class="main">
            <?php if(mdh_thread()->isBlocked() && !mdh_thread()->isGroup()): ?>
                <div class="alert alert-danger">
                    <?php printf(__("You blocked the user %s. He can't send you messages.", "madhouse_messenger"), mdh_thread()->getOther()->getName()); ?>
                </div>
            <?php endif; ?>
            <div class="box content" style="margin-bottom:0; background:#CCFFFF">
                <form class="form-vertical js-messenger-form" action="<?php echo mdh_messenger_send_url(); ?>" method="POST">
                	<input type="hidden" name="tid" value="<?php echo Params::getParam("id"); ?>" />
            		<div class="control-group">
            		    <div class="controls">
                    		<textarea rows="8" class="say" name="message" placeholder="<?php _e("Write something...", "madhouse_messenger"); ?>" rows="3"></textarea>
                		</div>
                	</div>
                    <div class="clearfix">
                        <?php if(osc_get_preference("enable_block_user", "plugin_madhouse_messenger") && !mdh_thread()->isGroup()): ?>
                            <div class="pull-right">
                                <?php if(!mdh_thread()->isBlocked()): ?>
                                    <a class="btn btn-default" href="<?php echo mdh_messenger_user_block_user_url(mdh_thread()->getOther()->getId()); ?>">
                                        <?php _e("Block user", "madhouse_messenger"); ?>
                                    </a>
                                <?php else: ?>
                                    <a class="btn btn-default" href="<?php echo mdh_messenger_user_unblock_user_url(mdh_thread()->getOther()->getId()); ?>">
                                        <?php _e("Unblock user", "madhouse_messenger"); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                		<div class="pull-left">
                            <div class="">
                                <input class="js-submit-message btn btn-primary" type="submit" value="<?php _e("Send message", "madhouse_messenger"); ?>" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="">
                <?php if(mdh_messenger_status_enabled() && (! mdh_messenger_status_for_owner() || (mdh_messenger_status_for_owner() && mdh_is_thread_item_owner()))): ?>
                    <div class="status-wrapper content">
                        <ul class="unstyled inline">
                            <li><?php _e("Change to", "madhouse_messenger"); ?>:&nbsp;</li>
                            <?php while(mdh_has_status()): ?>
                                <?php if(! mdh_thread_has_status() || (mdh_thread_has_status() && mdh_thread_status_id() !== mdh_status_id())): ?>
                                    <li><a class="thread-status thread-status-<?php echo mdh_status_name(); ?>" href="<?php echo mdh_status_url(); ?>"><?php echo mdh_status_title(); ?></a></li>
                                <?php endif; ?>
                            <?php endwhile; ?>
                        </ul>
                        <div class="clear"></div>
                    </div>
                <?php endif; ?>
                <ul class="messages unstyled js-messages"
                    data-autolinker-enable          = "<?php echo (osc_get_preference("enable_autolinker", "plugin_madhouse_messenger")); ?>"
                    data-autolinker-new-window      = "<?php echo (osc_get_preference("autolinker_new_window", "plugin_madhouse_messenger")); ?>"
                    data-autolinker-truncate-length = "<?php echo (osc_get_preference("autolinker_truncate_length", "plugin_madhouse_messenger")); ?>"
                    data-autolinker-strip-prefix    = "<?php echo (osc_get_preference("autolinker_strip_prefix", "plugin_madhouse_messenger")); ?>"
                >&nbsp;
                    <?php while(mdh_has_messages()): ?>
                        <?php if(! mdh_message_is_auto()): ?>
                            <li class="message box<?php if(osc_logged_user_id() == mdh_message_sender_id()) {echo " my";}?>" <?php if(osc_logged_user_id() == mdh_message_sender_id()) {echo "style=\"background:#DDFFFF; margin-right: 0;\"";}?>  >
                                <div class="meta message-sent-date pull-right">
                                    <?php echo mdh_message_formatted_sent_date(); ?>
                                </div>
                                <ul class="unstyled inline">
                                    <li class="message-sender">
                                        <a href="<?php echo mdh_message_sender_url(); ?>">
                                            <?php echo mdh_message_sender_name(); ?>
                                        </a>
                                    </li>
                                </ul>
                                <div class="text js-text">
                                    <?php echo mdh_message_text(); ?>
                                </div>
                                <div class="clearfix">
                                    <div class="message-delete pull-right">
                                        <a href="<?php echo mdh_message_delete_url(); ?>">
                                            <?php _e("Delete", "madhouse_messenger"); ?>
                                        </a>
                                    </div>
                                    <div class="message-date  inline">
                                        <?php if(mdh_message_is_read()): ?>
                                            <div class="read">
                                                <?php _e("Read ", "madhouse_messenger"); ?>
                                                    <?php echo mdh_message_formatted_read_date(); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </li>
                        <?php else: ?>
                         <!--   <li class="message box auto-message">
                                <div class="pull-right meta"><?php echo mdh_message_formatted_sent_date(); ?></div>
                                <?php echo mdh_message_text(); ?>
                            </li> -->
                        <?php endif; ?>
                    <?php endwhile; ?>
                </ul>
                <?php if(mdh_thread_has_more_messages()): ?>
                    <div class="more box">
                        <a href="#" class="btn btn-primary">
                            <?php _e("Show previous messages", "madhouse_messenger"); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="sidebar">
            <div class="item content text-center">
                <?php if(mdh_thread_has_status()): ?>
                    <h3><div class=" thread-status thread-status-<?php echo mdh_thread_status_name(); ?>"><?php echo mdh_thread_status_title(); ?></div></h3>
                <?php endif; ?>
                <h3>
                    <?php _e("Inquiry about", "madhouse_messenger"); ?>
                    <?php if(mdh_is_thread_item_owner()): ?>
                        <small><?php _e("(your ad)", "madhouse_messenger"); ?></small>
                    <?php endif; ?>
                </h3>
                <?php if(
                        mdh_thread_had_item() ||
                        (mdh_thread_has_item() && osc_item_is_expired()) ||
                        (mdh_thread_has_item() && function_exists("mdh_moreedit_is_archived") && mdh_moreedit_is_archived()) ||
                        (mdh_thread_has_item() && function_exists("mdh_moreedit_is_stopped") && mdh_moreedit_is_stopped())
                    ):
                ?>
                    <span><?php _e("A deleted/disabled ad", "madhouse_messenger"); ?></span>
                <?php elseif(mdh_thread_has_item() && (osc_item_is_spam() || ! osc_item_is_enabled() || ! osc_item_is_active())): ?>
                    <?php _e("A blocked/spam ad", "madhouse_messenger"); ?>
                <?php elseif(mdh_thread_has_item()): ?>
                    <a class="item-link" href="<?php echo osc_item_url(); ?>">
                        <div class="thumbnail">
                            <?php if(osc_has_item_resources()): ?>
                                <img src="<?php echo osc_resource_preview_url(); ?>" />
                            <?php else: ?>
                                <?php _e("No pictures for this ad!", "madhouse_messenger"); ?>
                            <?php endif; ?>
                        </div>
                    </a>
                    <a class="item-link" href="<?php echo osc_item_url(); ?>">
                        <div class="item-title"><?php echo osc_item_title(); ?></div>
                    </a>
                    <div class="item-meta">
                    <span class="item-price"><?php echo osc_item_formated_price(); ?></span>&nbsp;,&nbsp;
                    <span class="item-location"><?php echo osc_item_city(); ?></span>
                    </div>
                <?php else: ?>
                    <?php _e("No item linked to this thread.", "madhouse_messenger"); ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>
<script type="text/javascript">
$(".say").keypress(function(event) {
    if (event.which == 13 && !(event.shiftKey) ) {
        event.preventDefault();
		$(".js-messenger-form").submit();
    }
});
	</script>