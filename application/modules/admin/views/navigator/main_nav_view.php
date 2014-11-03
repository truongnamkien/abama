<ul class="nav nav-pills nav-stacked main-menu">
	<?php foreach ($main_navs as $main => $options): ?>
		<li class="nav-header">
			<?php echo lang('manager_submenu_' . $main); ?>
		</li>

		<?php if (isset($options['navs']) && !empty($options['navs'])): ?>
			<?php foreach ($options['navs'] as $sub => $nav): ?>
				<li class="<?php echo ($controller == $sub ? 'active' : ''); ?>">
					<a href="<?php echo $nav['url']; ?>">
						<i class="glyphicon glyphicon-<?php echo $nav['icon']; ?>"></i>
						<span><?php echo lang('manager_' . $sub); ?></span>
					</a>
				</li>
			<?php endforeach; ?>
		<?php endif; ?>
	</li>
<?php endforeach; ?>
</ul>
