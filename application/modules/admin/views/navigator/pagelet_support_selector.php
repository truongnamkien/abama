<ul class="collapse navbar-collapse nav navbar-nav top-menu">
    <li><a target="_blank" href="<?php echo site_url(); ?>"><i class="glyphicon glyphicon-globe"></i> <?php echo lang('authen_preview'); ?></a></li>
    <li class="dropdown">
        <a href="#" data-toggle="dropdown">
            <i class="glyphicon glyphicon-star"></i>
            <?php echo lang('admin_support_select'); ?>
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" role="menu">
            <li>
                <?php if ($constructor == STATUS_ACTIVE): ?>
                    <a href="<?php echo site_url('admin/admin_authen/constructor/' . STATUS_INACTIVE); ?>"><?php echo lang('admin_support_constructor_deactivate'); ?></a>
                <?php else: ?>
                    <a href="<?php echo site_url('admin/admin_authen/constructor/' . STATUS_ACTIVE); ?>"><?php echo lang('admin_support_constructor_activate'); ?></a>
                <?php endif; ?>
            </li>
            <li class="divider"></li>
            <li><a href="<?php echo site_url('admin/upload/product_list'); ?>"><?php echo lang('admin_support_product_photo'); ?></a></li>
            <li class="divider"></li>
            <li><a href="<?php echo site_url('admin/admin_authen/clear_cache'); ?>"><?php echo lang('admin_support_clear_cache'); ?></a></li>
            <li><a href="<?php echo site_url('admin/admin_authen/clear_disk'); ?>"><?php echo lang('admin_support_clear_disk'); ?></a></li>
        </ul>
    </li>
</ul>
