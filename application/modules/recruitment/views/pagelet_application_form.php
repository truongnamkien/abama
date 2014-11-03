<div class="header_apply"><?php echo lang('recruitment_apply'); ?></div>
<div class="header_text"><?php echo lang('recruitment_apply_description'); ?></div>
<div class="frm_contact_l">
	<div id="frm_sendcv">
		<form id="apply_form" action="<?php echo site_url('ajax/recruitment_ajax/apply'); ?>" rel="async" method="post" enctype="multipart/form-data" >
			<input name="recruitment_id" type="hidden" value="<?php echo $recruitment['recruitment_id']; ?>" />
			<div class="ct_table">
				<div class="ct_row">
					<div class="ct_col label"><?php echo lang('recruitment_application_recruitment_id'); ?></div>
					<div class="ct_col control pt5">
						<?php echo $recruitment['position']; ?>
					</div>
				</div>
				<div class="ct_row">
					<label for="fullname" class="ct_col label"><?php echo lang('recruitment_application_fullname'); ?></label>
					<div class="ct_col control">
						<input class="w350" type="text" value="<?php echo set_value('fullname'); ?>" name="fullname" id="fullname" />
						<?php echo form_error('fullname', '<div class="error_message">', '</div>'); ?>
					</div>
				</div>

				<div class="ct_row">
					<label for="email" class="ct_col label"><?php echo lang('recruitment_application_email'); ?></label>
					<div class="ct_col control">
						<input class="w350" type="text" value="<?php echo set_value('email'); ?>" name="email" id="email" />
						<?php echo form_error('email', '<div class="error_message">', '</div>'); ?>
					</div>
				</div>
				<div class="ct_row">
					<label for="mobile" class="ct_col label"><?php echo lang('recruitment_application_mobile'); ?></label>
					<div class="ct_col control">
						<input class="w350" type="text" value="<?php echo set_value('mobile'); ?>" name="mobile" id="mobile" />
					</div>
				</div>
				<div style="height: 170px" class="ct_row">
					<label for="content" class="ct_col label"><?php echo lang('recruitment_application_content'); ?></label>
					<div class="ct_col control"> 
						<textarea name="content" id="content" rows="10" cols="5"></textarea>
					</div>
				</div>
				<div class="ct_row">
					<label for="file" class="ct_col label"><?php echo lang('recruitment_application_url'); ?></label>
					<div class="ct_col control" id="upload_input">
						<div id="mulitplefileuploader">Upload</div>

						<script>
							$(document).ready(function() {
								var settings = {
									url: "<?php echo base_url('upload.php'); ?>",
									dragdropWidth: 330,
									statusBarWidth: 330,
									maxFileCount: 1,
									method: "POST",
									allowedTypes: "docx,doc,pdf,zip",
									fileName: "myfile",
									multiple: true,
									onSuccess: function(files, data, xhr) {
										$("#apply_form").append('<input name="file" type="hidden" value="' + files[0] + '" />');
										$('#upload_input').html(files[0]);
										$('#upload_input').show();
										$('#upload_input').addClass('pt2');
									}, onError: function(files, status, errMsg) {
										show_alert('<?php echo lang('recruitment_application_apply_error'); ?>');
									}, onSubmit:function(s,x) {
										$('#upload_input').hide();
									}
								}
								$("#mulitplefileuploader").uploadFile(settings);
							});
						</script>
					</div>
				</div>
				<div class="ct_row">
					<div class="ct_col label"></div>
					<div class="ct_col control">
						<input class="btn" type="submit" name="btnSend" id="btnSendCV" value="<?php echo lang('recruitment_application_apply'); ?>" title="<?php echo lang('recruitment_application_apply'); ?>" />
						<input class="btn" type="reset" name="btnReset" id="btnReset" value="<?php echo lang('recruitment_application_reset'); ?>" title="<?php echo lang('recruitment_application_reset'); ?>" />
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
