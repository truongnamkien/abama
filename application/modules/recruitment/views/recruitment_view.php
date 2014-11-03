<?php echo asset_link_tag('css/careers.css', 'stylesheet', 'text/css'); ?>
<?php echo asset_link_tag('multi_upload/uploadfile.css'); ?>
<?php echo asset_js('multi_upload/jquery.uploadfile.min.js') ?>

<div class="careers_content" id="detail_cr">
	<div class="career_left">
		<?php echo Modules::run('recruitment/_pagelet_other_recruitment', $recruitment); ?>
	</div>

	<div class="career_right">
        <div class="fRight">
            <div class="fb-like" data-href="<?php echo site_url('recruitment/detail?recruitment_id=' . $recruitment['recruitment_id']); ?>" data-send="true" data-layout="button_count" data-show-faces="true" data-font="verdana"></div>
        </div>
		<div class="careers_title"><h2><?php echo lang('recruitment_position'); ?></h2></div>
		<div class="box_careers">
			<div class="career_right_title"><h1><?php echo $recruitment['position']; ?></h1></div>
			<div class="career_des">
				<?php echo $recruitment['description']; ?>
			</div>
			<div class="career_apply">
				<a href="#apply_form_html" title="" class="apply_cv"><?php echo lang('recruitment_apply'); ?></a>
			</div>
		</div>
	</div>
</div>

<div class="apply_form dpn">
	<div class="frm_apply" id="apply_form_html"></div>
</div>

<script type="text/javascript">
	$(".apply_cv").colorbox({inline: true, width: "700px", height: "700",
		onLoad: function() {
			$(".error_message").remove();
			$("#cboxClose").css({'top': '13px', 'right': '20px'});
		}
	});
	load_application_form();

	function load_application_form() {
		var _uri = '<?php echo site_url('ajax/recruitment_ajax/apply?recruitment_id=' . $recruitment['recruitment_id']); ?>';
		AsyncRequest.bootstrap(new URI(_uri));
	}
</script>