<?php echo asset_link_tag('wowslider/wowslider.css'); ?>
<?php echo asset_js('wowslider/wowslider.js'); ?>

<div class="w920">
	<div class="slider-wrapper">
		<div id="wowslider-container">
			<div class="ws_images">
				<ul>
					<?php foreach ($banner_list as $banner): ?>
	                    <li>
							<?php $photo_path = Modules::run('photo/_get_photo_path', $banner['photo'], $banner['width']); ?>
	                        <img src="<?php echo base_url($photo_path); ?>" height="<?php echo $banner['height']; ?>" width="<?php echo $banner['width']; ?>" />
	                    </li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>

	<div class="bn">
		<?php echo Modules::run('home/_pagelet_sub_banner', 'home_right_1'); ?>
	</div>
</div>


<script type="text/javascript">
	$(document).ready(function() {
		$("#wowslider-container").wowSlider({
			effect: "basic linear",
			duration: 20 * 100,
			delay: 60 * 100,
			width: <?php echo $banner['width']; ?>,
			height: <?php echo $banner['height']; ?>,
			autoPlay: true,
			stopOnHover: true,
			loop: true,
			bullets: true,
			caption: true,
			captionEffect: "slide",
			controls: true,
			images: 0
		});
	});
</script>

