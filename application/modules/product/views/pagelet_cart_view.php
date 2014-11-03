<?php echo asset_link_tag('css/news.css', 'stylesheet', 'text/css'); ?>

<div class="ct_news">
    <div class="new_detail">
		<h1><?php echo lang('product_shopping_cart'); ?></h1>
        <div class="clear"></div>

		<?php if (empty($cart)): ?>
			<div class="desc">
				<?php echo lang('product_shopping_cart_empty'); ?>
			</div>
		<?php else: ?>
			<table>
				<thead>
					<tr>
						<th class="image">&nbsp;</th>
						<th class="item">&nbsp;</th>
						<th class="qty"><?php echo lang('product_shopping_cart_quantity'); ?></th>
						<th class="price"><?php echo lang('product_shopping_cart_unit_price'); ?></th>
						<th class="price"><?php echo lang('product_shopping_cart_total_price'); ?></th>
						<th class="remove">&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($cart as $rowid => $item): ?>
						<tr id="row_<?php echo $rowid; ?>">
							<?php echo Modules::run('product/_pagelet_cart_item', $item); ?>
						</tr>
					<?php endforeach; ?>

					<tr class="summary">
						<td class="item fwb" colspan="4"><?php echo lang('product_shopping_cart_total_price'); ?></td>
						<td class="price">
							<?php echo format_price($total_price); ?>    
						</td>
						<td>&nbsp;</td>
					</tr>
				</tbody>
			</table>
			<div class="clr"></div>

			<div class="buttons">
				<a href="#" rel="async" ajaxify="<?php echo site_url('ajax/product_ajax/checkout'); ?>" class="button fRight ml20">
					<?php echo lang('product_shopping_cart_finish'); ?>
				</a>
				<a class="button fRight" href="<?php echo site_url(); ?>"><?php echo lang('product_shopping_cart_back'); ?></a>
				<div class="clr"></div>
			</div>
			<div class="clr"></div>
		<?php endif; ?>

    </div>
</div>


