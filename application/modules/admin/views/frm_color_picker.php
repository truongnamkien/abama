<?php echo asset_link_tag('colorpicker/css/colorpicker.css'); ?>
<?php echo asset_link_tag('colorpicker/css/layout.css'); ?>
<?php echo asset_js('colorpicker/js/colorpicker.js') ?>

<div id="customWidget">
    <div id="colorSelector2"><div style="background-color: #<?php echo $default_color; ?>"></div></div>
    <div id="colorpickerHolder2"></div>
</div>
<input type="hidden" id="color_input" name="color" value="<?php echo $default_color; ?>" />

<script type="text/javascript">
	$(document).ready(function() {

		$('#colorpickerHolder2').ColorPicker({
			flat: true,
			color: '#<?php echo $default_color; ?>',
			onSubmit: function(hsb, hex, rgb) {
				$('#colorSelector2 div').css('backgroundColor', '#' + hex);
				$('#color_input').val(hex);
			}
		});
		$('#colorpickerHolder2>div').css('position', 'absolute');
		var widt = false;
		$('#colorSelector2').bind('click', function() {
			$('#colorpickerHolder2').stop().animate({height: widt ? 0 : 173}, 500);
			widt = !widt;
		});
		$('.colorpicker_submit').bind('click', function() {
			if ($('#colorpickerHolder2').height() > 0) {
				$('#colorSelector2').click();
			}
		});

	});

</script>