<div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><?php echo lang('customer_email_send_email') ?></h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
                </div>
            </div>

            <div class="box-content">
                <?php echo form_open_multipart('admin/customer_email/mass'); ?>
                <input type="hidden" name="email" value="<?php echo $email_list; ?>" />
                <div class="form-group">
                    <label class="control-label" for="email"><?php echo lang('customer_email_receiver'); ?></label>
                    <br />
                    <?php echo $email_list; ?>
                </div>

                <div class="form-group">
                    <label class="control-label" for="subject"><?php echo lang('customer_email_subject'); ?></label>
                    <input type="text" class="form-control" id="subject" name="subject" />
                </div>
                <div class="form-group">
                    <label class="control-label" for="content"><?php echo lang('customer_email_content'); ?></label>
                    <?php echo form_textarea('content', FALSE); ?>
                </div>
                <button type="submit" class="btn btn-default"><?php echo lang('customer_email_send_btn'); ?></button>
                <?php echo form_close(); ?>
                <div class="clear"></div>
            </div>
        </div>
    </div>
    <!--/span-->
</div><!--/row-->
