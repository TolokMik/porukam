<?php
$inv_id = $_REQUEST["InvId"];
osc_add_flash_error_message(__("There was a problem processing your Payment $inv_id\n. Please contact the administrators",'rbc'));  
if(osc_is_web_user_logged_in()) {
                    rbc_js_redirect_to(osc_route_url('rbc-user-menu'));
                } else {
                    View::newInstance()->_exportVariableToView('item', Item::newInstance()->findByPrimaryKey($itemId));
                    rbc_js_redirect_to(osc_item_url());
                }     
?>

