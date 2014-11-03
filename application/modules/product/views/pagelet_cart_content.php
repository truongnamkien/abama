<a class="fLeft button" href="<?php echo site_url('product/cart'); ?>" title="<?php echo lang('product_shopping_cart_view'); ?>">
	<?php echo lang('product_shopping_cart_view'); ?>
</a>
<a class="fRight mt2" title="<?php echo lang('product_shopping_cart'); ?>" href="<?php echo site_url('product/cart'); ?>">
	<?php echo lang('product_shopping_cart_total_items', '', $total_product); ?>
    :
	<?php echo format_price($total_price); ?>
</a>
<div class="clear"></div>