<?php echo asset_link_tag('css/slide_hot_off.css', 'stylesheet', 'text/css'); ?>

<div class="w250">
    <div class="hotoffers">
        <h3><?php echo lang('product_hot'); ?></h3>

        <div id="carousel">
			<?php foreach ($product_list as $product): ?>
				<div class="offer_item">
					<div class="item_left">
						<a href="<?php echo product_url($product); ?>">
							<img src="<?php echo base_url($product['photo']); ?>" title="<?php echo $product['name_' . $this->_current_lang]; ?>" alt="<?php echo $product['name_' . $this->_current_lang]; ?>" width="230" />
						</a>
					</div>
					<div class="item_right">
						<div class="offer_info">
							<div class="product_name">
	                            <a href="<?php echo product_url($product); ?>" class="product_name">
	                                <p class="tac"><?php echo $product['name_' . $this->_current_lang]; ?></p>
	                            </a>
							</div>
							<div class="clear"></div>

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
						</div>
					</div>
					<a class="button view_btn" href="<?php echo product_url($product); ?>" style="color: #FFF;">
						<?php echo lang('product_view_detail'); ?>
					</a>

					<?php if (!empty($product['price_off'])): ?>
						<div class="promotion_tag">
							<span><?php echo floor(($product['price_off'] - $product['price']) * 100 / $product['price']); ?>%</span>
						</div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
        </div>
    </div>
</div>

<script type='text/javascript'>
	$('#carousel').carouFredSel({
		width: 250,
		direction: 'up',
		items: 3,
		scroll: {
			items: 1,
			duration: 1000,
			pauseOnHover: true
		}
	});
</script>