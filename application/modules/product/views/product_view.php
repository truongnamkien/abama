<?php echo asset_link_tag('css/category.css', 'stylesheet', 'text/css'); ?>
<?php echo asset_link_tag('css/detail.css', 'stylesheet', 'text/css'); ?>
<?php echo asset_link_tag('css/jquery/jquery.jscrollpane.css', 'stylesheet', 'text/css'); ?>
<?php echo asset_js('js/jquery/jquery.mousewheel.min.js'); ?>
<?php echo asset_js('js/jquery/jquery.jscrollpane.min.js'); ?>

<?php echo asset_link_tag('css/jqzoom/jquery.jqzoom.css'); ?>
<?php echo asset_js('js/jquery/jquery.jqzoom-core.js'); ?>

<?php echo Modules::run('category/_pagelet_category_list'); ?>

<div class="product_category">
	<?php echo Modules::run('product/_pagelet_hot_products'); ?>

    <div class="w_category_products">
		<?php $background_color = "#FFFFFF"; ?>
		<?php if (isset($product['category']) && !empty($product['category'])): ?>
			<?php $background_color = "#" . $product['category']['color']; ?>
			<div class="tab" id="view_detail" style="background: none repeat scroll 0px 0px <?php echo $background_color; ?> !important;">
				<h1>
					<span>
						<a href="<?php echo product_category_url($product['category']); ?>" title="<?php echo $product['category']['category_name_' . $this->_current_lang]; ?>">
							<?php echo $product['category']['category_name_' . $this->_current_lang]; ?>
						</a>
					</span>
				</h1>
			</div>
		<?php endif; ?>
        <div class="clear"></div>

        <div class="listproduct">
            <div class="primg">
				<?php if (!empty($product['photo_list']) && isset($product['photo_list'][0])): ?>
					<div class="img" id="photo_panel">
						<?php $photo_path = Modules::run('photo/_get_photo_path', $product['photo_list'][0]['url'], 362); ?>
						<a href="<?php echo base_url(Modules::run('photo/_get_photo_path', $product['photo_list'][0]['url'], 900)); ?>" class="jqzoom" title="<?php echo $product['name_' . $this->_current_lang]; ?>">
							<img width="362" src="<?php echo base_url($photo_path); ?>" alt="<?php echo $product['name_' . $this->_current_lang]; ?>" />
						</a>
					</div>
					<div class="clear"></div>

					<?php if (count($product['photo_list']) > 1): ?>
						<div class="list_photo pt15">
							<ul id="slider_photo">
								<?php foreach ($product['photo_list'] as $photo): ?>
									<li class="fLeft mr5">
										<a class="photo_thumb" href="<?php echo base_url(Modules::run('photo/_get_photo_path', $photo['url'], 362)); ?>" zoom_src="<?php echo base_url(Modules::run('photo/_get_photo_path', $photo['url'], 900)); ?>">
											<img width="85" alt="<?php echo $product['name_' . $this->_current_lang]; ?>" src="<?php echo base_url(Modules::run('photo/_get_photo_path', $photo['url'], 90)); ?>" />
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>

						<script type="text/javascript">
							$(document).ready(function() {
								$('#slider_photo').carouFredSel({
									width: 360,
									height: 70,
									scroll: 1,
									auto: {
										duration: 1000,
										timeoutDuration: 2000
									}
								});
							});
						</script>
					<?php endif; ?>
				<?php endif; ?>
                <div class="clear"></div>
            </div>

            <div class="prdetail">
                <div class="prdesc">
                    <h1 style="background: none repeat scroll 0px 0px <?php echo $background_color; ?> !important;"><?php echo $product['name_' . $this->_current_lang]; ?></h1>
                    <div class="product_desc">
                        <h3><?php echo lang('product_description'); ?></h3>

						<?php echo $product['description_' . $this->_current_lang]; ?>
                    </div>
					<hr />
                </div>

                <div class="sale_price tac">
					<?php if (!empty($product['price_off'])): ?>
						<a href="<?php echo product_url($product); ?>" class="old_price mr20">
							<?php echo format_price($product['price']); ?>
						</a>
						<a href="<?php echo product_url($product); ?>" class="new_price">
							<?php echo format_price($product['price_off']); ?>
						</a>
					<?php else: ?>
						<a href="<?php echo product_url($product); ?>" class="new_price">
							<?php echo format_price($product['price']); ?>
						</a>
					<?php endif; ?>
                </div>
                <div class="clear"></div>

				<div id="detail_button">
					<?php if ($product['sold_out'] == STATUS_ACTIVE): ?>
						<a href="#" class="fLeft fake disabled_btn"><?php echo lang('product_sold_out'); ?></a>
					<?php else: ?>
						<form action="<?php echo site_url('ajax/product_ajax/add_to_cart/' . $product['product_id']); ?>" rel="async" method="post">
							<input class="fLeft mr10 quantity_input mt5 w80" autocomplete="off" type="number" name="quantity" value="1" />
							<input class="fLeft button" type="submit" title="<?php echo lang('product_add_to_cart'); ?>" value="<?php echo lang('product_add_to_cart'); ?>" name="submit" />
						</form>
					<?php endif; ?>
					<div class="fLeft ma5">
						&nbsp;&nbsp;&nbsp;- <?php echo lang('product_view_or'); ?> -&nbsp;&nbsp;&nbsp;
					</div>
					<a class="fLeft button" href="<?php echo site_url('product/cart'); ?>" title="<?php echo lang('product_shopping_cart_view'); ?>">
						<?php echo lang('product_shopping_cart_view'); ?>
					</a>
				</div>
				<div class="clear"></div>
            </div>
			<div class="facebook_button">
				<div class="fb-like" data-href="<?php echo product_url($product); ?>" data-send="true" data-layout="button_count" data-width="300" data-show-faces="true" data-font="verdana"></div>
			</div>

			<div class="facebook_comment mt20">
				<div class="fb-comments" data-href="<?php echo product_url($product); ?>" data-width="905" data-numposts="5" data-colorscheme="light"></div>
			</div>

			<?php echo Modules::run('product/_pagelet_suggest_product'); ?>
        </div>
    </div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('.quantity_input').change(function() {
			if ($(this).val() <= 0) {
				$(this).val(1);
			}
		});

		$('.product_desc').jScrollPane();

		init_zoom();
		$('.photo_thumb').click(function(e) {
			var _img = $(this);
			var _html = '<a href="' + _img.attr('zoom_src') + '" class="jqzoom" title="<?php echo $product['name_' . $this->_current_lang]; ?>">';
			_html += '<img width="362" src="' + _img.attr('href') + '" />';
			_html += '</a>';

			$('#photo_panel').html(_html);
			init_zoom();
			e.preventDefault();
		});

		function init_zoom() {
			$('.jqzoom').jqzoom({
				zoomType: 'reverse',
				lens: true,
				preloadImages: false,
				zoomWidth: 550,
				zoomHeight: 360,
				xOffset: 20,
				alwaysOn: false
			});
		}
	});
</script>  
