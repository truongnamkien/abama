<div class="pimg">
    <a href="<?php echo product_url($product); ?>" title="<?php echo $product['name_' . $this->_current_lang]; ?>">
        <img src="<?php echo base_url($product['photo']); ?>" alt="<?php echo $product['name_' . $this->_current_lang]; ?>" width="196">
    </a>
</div>
<div class="product_name">
	<a href="<?php echo product_url($product); ?>" class="product_name">
		<p class="tac"><?php echo $product['name_' . $this->_current_lang]; ?></p>
	</a>
</div>

<?php if (!empty($product['price_off'])): ?>
	<a href="<?php echo product_url($product); ?>" class="old_price fLeft">
		<?php echo format_price($product['price']); ?>
	</a>
	<a href="<?php echo product_url($product); ?>" class="new_price fRight">
		<?php echo format_price($product['price_off']); ?>
	</a>
<?php else: ?>
	<a href="<?php echo product_url($product); ?>" class="new_price fRight">
		<?php echo format_price($product['price']); ?>
	</a>
<?php endif; ?>
<div class="clear"></div>

<a class="button view_btn" href="<?php echo product_url($product); ?>" style="color: #FFF;">
	<?php echo lang('product_view_detail'); ?>
</a>

<?php if (!empty($product['price_off'])): ?>
	<div class="promotion_tag">
		<span><?php echo floor(($product['price_off'] - $product['price']) * 100 / $product['price']); ?>%</span>
	</div>
<?php endif; ?>


<?php if ($product['sold_out'] == STATUS_ACTIVE): ?>
	<a href="#" class="fake disabled_btn"><?php echo lang('product_sold_out'); ?></a>
<?php else: ?>
	<form action="<?php echo site_url('ajax/product_ajax/add_to_cart/' . $product['product_id']); ?>" rel="async" method="post">
		<input class="quantity_input mt10 w80" autocomplete="off" type="number" name="quantity" value="1" />
		<input class="button" type="submit" title="<?php echo lang('product_add_to_cart'); ?>" value="<?php echo lang('product_add_to_cart'); ?>" name="submit" />
	</form>
<?php endif; ?>

<script type="text/javascript">
	$(document).ready(function() {
		$('.quantity_input').bind('change', function() {
			if ($(this).val() <= 0) {
				$(this).val(1);
			}
		});
	});
</script>
