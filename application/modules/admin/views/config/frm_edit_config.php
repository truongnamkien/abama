<div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><?php echo lang('static_content_config') . ' - ' . lang('static_content_config_' . $type) ?></h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
                </div>
            </div>

            <div class="box-content">
                <?php echo form_open_multipart('admin/config/edit/' . $type); ?>
                <div class="form-group">
                    <label class="control-label" for="<?php echo $type; ?>"><?php echo lang('static_content_config_' . $type); ?></label>
                    <input type="<?php echo $input; ?>" class="form-control" value="<?php echo $value; ?>" id="<?php echo $type; ?>" name="<?php echo $type; ?>" />
                </div>
                <button type="submit" class="btn btn-default"><?php echo lang('admin_action_update'); ?></button>
                <?php echo form_close(); ?>
                <div class="clear"></div>
            </div>
        </div>
    </div>
    <!--/span-->
</div><!--/row-->
