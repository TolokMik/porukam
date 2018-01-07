<?php

if(!defined('OC_ADMIN')):
    exit('Direct access is not allowed.');
endif;

?>

<?php require __DIR__ . "/nav.php"; ?>
<div class="container-fluid messages">

    <div class="space-in clearfix">
        <h2 class="h3 text-info row-space-2 space-in-xs">
            <i class="glyphicon glyphicon-minus-sign"></i>&nbsp;<?php echo osc_apply_filter("custom_listing_title", __("Manage blocked users", mdh_current_plugin_name())); ?>
        </h2>
        <div class="">
            <div class="table-contains-actions bg-white">
                <table id="messages-table" class="table">
                    <tr>
                        <th></th>
                        <th><?php _e("Status", mdh_current_plugin_name()); ?></th>
                        <th><?php _e("User", mdh_current_plugin_name()); ?></th>
                        <th><?php _e("Blocked", mdh_current_plugin_name()) ?></th>
                        <th><?php _e("Date", mdh_current_plugin_name()) ?></th>
                    </tr>
                    <?php if(count(View::newInstance()->_get("mdh_blocked_users")) > 0): ?>
                        <?php while(mdh_messenger_has_blocked_users()): ?>
                            <tr class="status-blocked">
                                <td class="col-status-border"></td>
                                <td class="col-status"><?php _e("Blocked", mdh_current_plugin_name()); ?></td>
                                <td>
                                    <a target="_blank" href="<?php echo osc_admin_base_url(true) ?>?page=users&amp;userId=<?php echo mdh_messenger_blocked_user()->getUser()->getId(); ?>&amp;user=<?php echo mdh_messenger_blocked_user()->getUser()->getName() ?>">
                                        <?php echo mdh_messenger_blocked_user()->getUser()->getName() ?></a>
                                    <div class="actions">
                                        <ul>
                                            <li>
                                                <a href="<?php echo mdh_messenger_admin_unblock_user_url(mdh_messenger_blocked_user()->getUser()->getId(), mdh_messenger_blocked_user()->getBlockedUser()->getId()) ?>"><?php printf(__("Unblock %s for this user", mdh_current_plugin_name()), mdh_messenger_blocked_user()->getBlockedUser()->getName()) ?></a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td>
                                <a target="_blank" href="<?php echo osc_admin_base_url(true) ?>?page=users&amp;userId=<?php echo mdh_messenger_blocked_user()->getBlockedUser()->getId(); ?>&amp;user=<?php echo mdh_messenger_blocked_user()->getBlockedUser()->getName() ?>">
                               <?php echo mdh_messenger_blocked_user()->getBlockedUser()->getName() ?></a></td>
                                <td><?php echo mdh_messenger_blocked_user()->getCreationDate()->format(osc_date_format() . " " . osc_time_format()) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">
                                <div class="space-in space-in-b-o text-center text-lg font-bold"><?php _e("No blocked users, yet.", mdh_current_plugin_name()); ?></div>
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