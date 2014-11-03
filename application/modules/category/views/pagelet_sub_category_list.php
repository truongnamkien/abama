<?php echo asset_js('js/jquery/lemmon-slider.js'); ?>

<div id="slider1" class="slider">
	<ul class="producttab" style="border-bottom-color: #<?php echo $category['color']; ?>">
		<?php foreach ($category_list as $sub_category): ?>
		<?php if ($sub_category['product_category_id'] != $category['product_category_id']): ?>
		<li>
			<h3>
				<a href="<?php echo product_category_url($sub_category); ?>" title="<?php echo $sub_category['category_name_' . $this->_current_lang]; ?>">
					<?php echo $sub_category['category_name_' . $this->_current_lang]; ?>
				</a>
			</h3>
		</li>
		<?php endif; ?>
		<?php endforeach; ?>

		<?php for ($i = 0; $i < (11 - count($category_list)); $i++): ?>
			<li><a href="#" class="fake"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></li> 
		<?php endfor; ?>
	</ul>
</div>
<div class="controls">
	<a href="#" class="next-slide">
		<img src="<?php echo asset_url('css/images/arr1.png'); ?>" width="14" height="14">
	</a>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$('#slider1').lemmonSlider();
	});
</script>