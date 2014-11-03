<?php echo asset_link_tag('css/category.css', 'stylesheet', 'text/css'); ?>

<?php echo Modules::run('category/_pagelet_category_list'); ?>

<div class="product_category">
	<?php echo Modules::run('product/_pagelet_hot_products'); ?>

    <div class="w_category_products">
        <div class="tab" id="view_detail" style="background: none repeat scroll 0px 0px #<?php echo $category['color']; ?> !important;">
            <h1>
                <span>
                    <a href="<?php echo product_category_url($category); ?>" title="<?php echo $category['category_name_' . $this->_current_lang]; ?>">
						<?php echo $category['category_name_' . $this->_current_lang]; ?>
                    </a>
                </span>
            </h1>
			<?php echo Modules::run('category/_pagelet_sub_category_list', $category); ?>
        </div>
        <div class="fRight">
            <div class="fb-like" data-href="<?php echo product_category_url($category); ?>" data-send="true" data-layout="button_count" data-width="300" data-show-faces="true" data-font="verdana"></div>
        </div>
        <div class="clear"></div>

        <div class="listproduct">
            <div class="product" id="product_list">
				<?php echo Modules::run('category/_product_list', $category['product_category_id']); ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
	scroll_loading = false;
	category_offset = 0;
	$(document).ready(function() {
		$(document).scroll(function() {
			if ($(window).scrollTop() > $("#product_list .item:last").offset().top - 400 && !scroll_loading) {
				scroll_loading = true;
				category_offset++;
				var _uri = "<?php echo site_url('ajax/category_ajax/product_list?product_category_id=' . $category['product_category_id']); ?>&offset=" + category_offset;
				AsyncRequest.bootstrap(new URI(_uri));
			}
		});
	});
</script>
