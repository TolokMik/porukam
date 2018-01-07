<div class="bg-white">
	<ul class="nav nav-tabs nav-tabs-alt nav-md vpadder-lg">
		<?php
			$aMenus = AdminMenu::newInstance()->get_array_menu();
			foreach ($aMenus["madhouse"]["sub"] as $key => $value):
				if(preg_match('/^' . mdh_current_plugin_name() . '_.*$/', $key)):
		?>
					<li class="<?php echo (Params::getParam("route") === $key) ? "active" : ""; ?>">
						<?php if(true): ?>
							<a href="<?php echo $value[1]; ?>">
								<?php echo $value[0]; ?>
								<?php if($key == mdh_current_plugin_name() . "_dashboard" && ! mdh_get_preference("version")): ?>
									<span class="label label-primary space-out-l-sm"><?php _e("Upgrade now!", mdh_current_plugin_name()); ?></a>
								<?php endif; ?>
							</a>
						<?php endif; ?>
					</li>
		<?php
				endif;
			endforeach;
		?>
		<li class="">
			<a href="https://wearemadhouse.wordpress.com/documentation-madhouse-messenger/" target="_blank">
                <span class="text-info">
                    <?php _e("Full documentation", mdh_current_plugin_name()); ?>
                </span>
            </a>
		</li>
		<li class="">
			<a href="http://market.osclass.org/user/profile/madhouse" target="_blank">
                <span class="label label-primary">
                    <?php _e("See more plugins by Madhouse", mdh_current_plugin_name()); ?>
                </span>
            </a>
		</li>
	</ul>
</div>