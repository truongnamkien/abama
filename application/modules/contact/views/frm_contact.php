<div class="bn_about">
	<?php echo Modules::run('home/_pagelet_sub_banner', 'contact'); ?>
</div>

<div class="contact_us">
    <div class="contact_note">
        <h1><?php echo lang('contact_contact_title'); ?></h1>
        <div><h2><?php echo lang('contact_message'); ?></h2></div>
    </div>
    <div class="contact_form">
        <div id="loading_page">
            <div class="frm_contact">
                <div class="frm_contact_l">
					<?php echo form_open('contact', array('id' => 'frm_contact'), FALSE); ?>
                    <div class="ct_table">
                        <div class="ct_row">
                            <label for="email" class="ct_col label"><?php echo lang('contact_email'); ?></label>
                            <div class="ct_col control">
                                <input class="w350" value="<?php echo set_value('email'); ?>" name="email" id="email" type="text" />
								<?php echo form_error('email', '<div class="error_message">', '</div>'); ?>
                            </div>
                        </div>

                        <div style="height: 170px" class="ct_row">
                            <label for="contact_content" class="ct_col label"><?php echo lang('contact_content'); ?></label>
                            <div class="ct_col control"> 
                                <textarea name="contact_content" id="contact_content" rows="10" cols="5"><?php echo set_value('contact_content'); ?></textarea>
								<?php echo form_error('contact_content', '<div class="error_message">', '</div>'); ?>
                            </div>
                        </div>

                        <div class="ct_row">
                            <div class="ct_col label"></div>
                            <div class="ct_col control">
                                <input class="btn" name="btnSend" id="btnSend" value="<?php echo lang('static_content_home_submit_btn'); ?>" title="<?php echo lang('static_content_home_submit_btn'); ?>" type="submit" />
                                <input class="btn" name="btnReset" id="btnReset" value="<?php echo lang('static_content_home_reset_btn'); ?>" title="<?php echo lang('static_content_home_reset_btn'); ?>" type="reset" />
                            </div>
                        </div>
                    </div>

					<?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
