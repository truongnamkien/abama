<div class="listpro">
	<div id="product_list" class="suggest_list">
		<?php foreach ($product_list as $product): ?>
			<div class="item">
				<?php echo Modules::run('product/_pagelet_product_item', $product); ?>
			</div>
		<?php endforeach; ?>
	</div>
</div>
<div class="clear"></div>

<script language="javascript">
	$(document).ready(function() {
		$('.suggest_list').carouFredSel({
			width: 905,
			scroll: 1,
			auto: {
				duration: 1250,
				timeoutDuration: 2500
			},
		});
	});
</script>