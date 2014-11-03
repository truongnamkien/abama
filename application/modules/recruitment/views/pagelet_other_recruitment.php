<div class="other_careers">
	<div class="career_title"><h2><?php echo lang('recruitment_other_position'); ?></h2></div>
	<ul>
		<?php foreach ($recruitment_list as $index => $recruitment): ?>
			<li>
				<a href="<?php echo site_url('recruitment/detail?recruitment_id=' . $recruitment['recruitment_id']); ?>" title="<?php echo $recruitment['position']; ?>">
					<?php echo ($index + 1) . ' ' . $recruitment['position']; ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>	
