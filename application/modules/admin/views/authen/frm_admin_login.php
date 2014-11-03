<div class="row">
    <div class="col-md-12 center login-header">
        <h2><?php echo lang('authen_login') ?></h2>
    </div>
    <!--/span-->
</div><!--/row-->

<div class="row">
    <div class="well col-md-5 center login-box">
        <?php if (validation_errors() || isset($login_failed)): ?>
            <div class="alert alert-info">
                <?php echo validation_errors('<p class="tac fwb fs14 mb5">', '</p>'); ?>
                <?php if (isset($login_failed)): ?>
                    <?php foreach ($login_failed['messages'] as $error) : ?>
                        <?php echo '<p class="tac fwb fs14 mb5">' . $error . '</p>'; ?>
                    <?php endforeach ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php echo form_open('admin/login', array('class' => 'form-horizontal'), array(), FALSE); ?>

        <fieldset>
            <div class="input-group input-group-lg">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user red"></i></span>
                <input name="username" type="text" class="form-control" placeholder="<?php echo lang('authen_username'); ?>" value="<?php echo set_value('username') ?>" />
            </div>
            <div class="clearfix"></div>
            <br />

            <div class="input-group input-group-lg">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock red"></i></span>
                <input name="password" type="password" class="form-control" placeholder="<?php echo lang('authen_password'); ?>" value="<?php echo set_value('password') ?>" />
            </div>
            <div class="clearfix"></div>

            <p class="center col-md-5">
                <button type="submit" class="btn btn-primary"><?php echo lang('authen_login'); ?></button>
            </p>
        </fieldset>
        <?php echo form_close(); ?>

    </div>
    <!--/span-->
</div><!--/row-->

