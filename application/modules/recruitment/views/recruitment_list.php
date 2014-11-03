<?php echo asset_link_tag('css/careers.css', 'stylesheet', 'text/css'); ?>

<div class="careers_content">
    <div class="careers_main_sr">
        <div class="careers_title">
            <div class="fRight">
                <div class="fb-like" data-href="<?php echo site_url('recruitment'); ?>" data-send="true" data-layout="button_count" data-show-faces="true" data-font="verdana"></div>
            </div>
            <h2><?php echo lang('recruitment_recruitment'); ?></h2>
        </div>

		<?php if (empty($recruitment_list)): ?>
			<div class="tac pa20">
				<?php echo lang('recruitment_list_empty'); ?>
			</div>
		<?php else: ?> 
	        <div class="list_carees_items">
				<table class="tbl_carees" cellpadding="0" cellspacing="0">
					<tbody>
						<tr>
							<td class="title_cr" width="50">&nbsp;</td>
							<td class="title_cr" width="430"><?php echo lang('recruitment_position'); ?></td>
							<td class="title_cr" width="250"><?php echo lang('recruitment_from_time'); ?></td>
							<td class="title_cr" width="250"><?php echo lang('recruitment_to_time'); ?></td>
							<td class="title_cr" width="200">&nbsp;</td>
						</tr>
						<?php foreach ($recruitment_list as $index => $recruitment): ?>
							<tr class="<?php echo ($index % 2 == 0 ? 'le' : 'chan'); ?>">
								<td width="50">
									<a href="<?php echo site_url('recruitment/detail?recruitment_id=' . $recruitment['recruitment_id']); ?>">
										<?php echo ($index + 1); ?>
									</a>
								</td>
								<td width="430">
									<a href="<?php echo site_url('recruitment/detail?recruitment_id=' . $recruitment['recruitment_id']); ?>">
										<?php echo $recruitment['position']; ?>
									</a>
								</td>
								<td width="250">
									<a href="<?php echo site_url('recruitment/detail?recruitment_id=' . $recruitment['recruitment_id']); ?>">
										<?php echo $recruitment['from_time']; ?>
									</a>
								</td>
								<td width="250">
									<a href="<?php echo site_url('recruitment/detail?recruitment_id=' . $recruitment['recruitment_id']); ?>">
										<?php echo $recruitment['to_time']; ?>
									</a>
								</td>
								<td width="200">
									<a href="<?php echo site_url('recruitment/detail?recruitment_id=' . $recruitment['recruitment_id']); ?>" title="<?php echo $recruitment['position']; ?>" class="ungtuyen">
										<span><?php echo lang('recruitment_apply'); ?></span>
									</a>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
	        </div>
		<?php endif; ?>
    </div>
</div>

