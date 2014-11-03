<?php if (isset($main_nav) && !empty($main_nav)): ?>
    <?php foreach ($main_nav as $key => $nav): ?>
        <a class="btn btn-default btn-sm" href="<?php echo $nav['url']; ?>">
            <i class="glyphicon glyphicon-<?php echo $nav['icon']; ?>"></i>
            <?php echo lang('admin_action_' . $key); ?>
        </a>
    <?php endforeach; ?>
<?php endif; ?>

<div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><?php echo lang('admin_action_show') ?></h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
                </div>
            </div>

            <div class="box-content">
                <?php if (!empty($object)): ?>
                    <?php foreach ($object as $key => $value): ?>
                        <div class="form-group">
                            <label class="fLeft control-label w250"><?php echo lang($type . '_' . $key); ?></label>
                            <div class="fLeft"><?php echo $value; ?></div>
                            <div class="clear"></div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <div class="clear"></div>
            </div>
        </div>
    </div>
    <!--/span-->
</div><!--/row-->

