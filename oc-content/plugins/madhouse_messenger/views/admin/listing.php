<?php

if(!defined('OC_ADMIN')):
    exit('Direct access is not allowed.');
endif;

?>

<script type="text/javascript">
    $(document).ready(function() {
        $('#filter-select').change( function () {
            var option = $(this).find('option:selected').attr('value');
            // clean values
            $('#fPattern,#fUser,#fItemId').attr('value', '');
            if(option == 'oPattern') {
                $('#fPattern').removeClass('hide');
                $('#fUser, #fItemId').addClass('hide');
            } else if(option == 'oUser'){
                $('#fUser').removeClass('hide');
                $('#fPattern, #fItemId').addClass('hide');
            } else {
                $('#fItemId').removeClass('hide');
                $('#fPattern, #fUser').addClass('hide');
            }
        });

        $('input[name="user"]').attr( "autocomplete", "off" );
        $('#fUser').autocomplete({
            source: "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=userajax",
            minLength: 0,
            select: function( event, ui ) {
                if(ui.item.id=='')
                    return false;
                $('#fUserId').val(ui.item.id);
            },
            search: function() {
                $('#fUserId').val('');
            }
        });

        $('.ui-autocomplete').css('zIndex', 10000);

        // check_all bulkactions
        $("#check_all").change(function(){
            var isChecked = $(this).prop("checked");
            $('.col-bulkactions input').each( function() {
                if( isChecked == 1 ) {
                    this.checked = true;
                } else {
                    this.checked = false;
                }
            });
        });

        $("#form-bulk [name='route']").on("change", function() {
            var $this = $(this);
            if($this.val() == "") {
                $("#form-bulk [name='id[]']").remove();
            }
        });

        $("#form-bulk").on("submit", function(e) {
            var $this = $(this),
                $wrapper = $this.children(".ids"),
                route = $this.find("[name='route']");

            if(route.val() == "") {
                return false;
            }

            $wrapper.html("");
            $("#messages-table [name='id[]']:checked:enabled").each(function(i, e) {
                var $e = $(e),
                    val = $e.val();
                $wrapper.append($('<input type="hidden" name="id[]" value="' + val + '" />'));
            });
        });
    });
</script>

<?php require __DIR__ . "/nav.php"; ?>
<div class="container-fluid messages">

    <div class="space-in">
        <h2 class="h4 row-space-2 space-in-xs">
            <?php echo osc_apply_filter("custom_listing_title", __("Manage messages", mdh_current_plugin_name())); ?>
        </h2>
        <div id="listing-toolbar" class="row">
            <div class="col-xs-4">
                <form id="form-bulk" class="form-inline nocsrf" action="<?php echo osc_admin_base_url(true); ?>">
                    <?php foreach( Params::getParamsAsArray('get') as $key => $value ): ?>
                        <?php if(!in_array($key, array("route"))): ?>
                            <input type="hidden" name="<?php echo $key; ?>" value="<?php echo osc_esc_html($value); ?>" />
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <select class="select-box-extra select-box-input space-out-r-xs" name="route">
                        <option value="">-</option>
                        <option value="<?php echo mdh_current_plugin_name() . "_message_block"; ?>"><?php _e("Block", mdh_current_plugin_name()); ?></option>
                        <option value="<?php echo mdh_current_plugin_name() . "_message_unblock"; ?>"><?php _e("Unblock", mdh_current_plugin_name()); ?></option>
                    </select>
                    <div class="ids"></div>
                    <input type="submit" class="btn btn-default" value="<?php _e("Apply", mdh_current_plugin_name()); ?>" />
                </form>
            </div>
            <div class="col-xs-8">
                <div class="pull-right">
                    <?php mdh_pagination_select(); ?>
                    <form id="shortcut-filters" method="get" action="<?php echo osc_admin_base_url(true); ?>" class="nocsrf inline">
                        <?php foreach( Params::getParamsAsArray('get') as $key => $value ): ?>
                            <?php if(! in_array($key, array("iPage", "filter-type", "userId", "itemId"))): ?>
                                <input type="hidden" name="<?php echo $key; ?>" value="<?php echo osc_esc_html($value); ?>" />
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <select id="filter-select" name="filter-type" class="select-box-extra select-box-input">
                            <option value="oUser" <?php if(Params::getParam('filter-type') == 'oUser'){ echo 'selected="selected"'; } ?>><?php _e('User'); ?></option>
                            <option value="oItem" <?php if(Params::getParam('filter-type') == 'oItem'){ echo 'selected="selected"'; } ?>><?php _e('Item ID'); ?></option>
                        </select>
                        <input id="fUser" type="text" class="fUser input-text input-actions input-has-select <?php echo (Params::getParam('filter-type') && Params::getParam('filter-type') != "oUser") ? "hide" : ""; ?>" value="<?php echo osc_esc_html(Params::getParam('user')); ?>" />
                        <input id="fUserId" name="userId" type="hidden" value="<?php echo osc_esc_html(Params::getParam('userId')); ?>" />
                        <input id="fItemId" type="text" name="itemId" value="<?php echo osc_esc_html(Params::getParam('itemId')); ?>" class="input-text input-actions input-has-select <?php echo (Params::getParam('filter-type') != "oItem") ? "hide" : "";?>"/>
                        <input type="submit" class="btn btn-default pull-right" value="<?php echo osc_esc_html( __('Find') ); ?>">
                    </form>
                </div>
            </div>
        </div>
        <div class="">
            <div class="table-contains-actions bg-white">
                <table id="messages-table" class="table">
                    <tr>
                        <th></th>
                        <th><?php _e("Status", mdh_current_plugin_name()); ?></th>
                        <th><input id="check_all" type="checkbox" /></th>
                        <th><?php _e("From / To", mdh_current_plugin_name()); ?></th>
                        <th><?php _e("Message", mdh_current_plugin_name()); ?></th>
                        <th><?php _e("Related item", mdh_current_plugin_name()); ?></th>
                        <th><?php _e("Date", mdh_current_plugin_name()) ?></th>
                    </tr>
                        <?php if(count(View::newInstance()->_get("mdh_messages")) > 0): ?>
                            <?php while(mdh_has_messages()): ?>
                                <?php include(osc_plugin_path("/madhouse_messenger/views/admin/message-single.php")); ?>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8">
                                    <div class="space-in space-in-b-o text-center text-lg font-bold"><?php _e("No messages, yet.", mdh_current_plugin_name()); ?></div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </table>
                <div id="table-row-actions"></div> <!-- used for table actions -->
            </div>
        </div>
        <?php mdh_pagination_admin(); ?>
    </div>
</div>