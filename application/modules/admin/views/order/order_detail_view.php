<a class="btn btn-default btn-sm" href="<?php echo site_url('admin/order'); ?>">
    <i class="glyphicon glyphicon-list-alt"></i>
    <?php echo lang('admin_action_back_list'); ?>
</a>

<div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><?php echo lang('product_order_manager') ?></h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
                </div>
            </div>

            <div class="box-content">
                <div class="form-group">
                    <label class="control-label w250"><?php echo lang('product_order_order_id'); ?></label>
                    <?php echo $order['order_id']; ?>
                </div>
                <div class="form-group">
                    <label class="control-label w250"><?php echo lang('product_checkout_fullname'); ?></label>
                    <?php echo $order['fullname']; ?>
                </div>
                <div class="form-group">
                    <label class="control-label w250"><?php echo lang('product_checkout_email'); ?></label>
                    <?php echo $order['email']; ?>
                </div>
                <div class="form-group">
                    <label class="control-label w250"><?php echo lang('product_checkout_mobile'); ?></label>
                    <?php echo $order['mobile']; ?>
                </div>
                <div class="form-group">
                    <label class="control-label w250"><?php echo lang('product_checkout_shipping_address'); ?></label>
                    <?php echo $order['address']; ?>
                </div>
                <div class="form-group">
                    <label class="control-label w250"><?php echo lang('product_order_delivery_time'); ?></label>
                    <?php echo $order['delivery_time']; ?>
                </div>
                <div class="form-group">
                    <label class="control-label w250"><?php echo lang('product_order_created_at'); ?></label>
                    <?php echo date($this->config->item('date_time_format'), $order['created_at']); ?>
                </div>
                <div class="form-group">
                    <label class="control-label w250"><?php echo lang('product_checkout_note'); ?></label>
                    <?php echo $order['note']; ?>
                </div>
                <div class="form-group">
                    <label class="control-label w250"><?php echo lang('product_order_status'); ?></label>

                    <div class="btn-group">
                        <button class="btn btn-default btn-minimize"><?php echo lang('product_order_status'); ?></button>
                        <button class="btn dropdown-toggle btn-default btn-minimize" data-toggle="dropdown"><span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <?php foreach ($status_list as $status): ?>
                                <li class="<?php echo ($order['status'] == $status ? "active" : ""); ?>">
                                    <?php if ($status !== $order['status']): ?>
                                        <a href="<?php echo site_url('admin/order/update_status/' . $order['order_id'] . '/' . $status); ?>"><?php echo lang('product_order_status_' . $status); ?></a>
                                    <?php else: ?>
                                        <a href="#" class="fake"><?php echo lang('product_order_status_' . $status); ?></a>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <?php echo form_open_multipart('admin/order/update/' . $order['order_id']); ?>
                <table class="table table-striped table-bordered responsive">
                    <thead>
                        <tr>
                            <th><?php echo lang('product_product'); ?></th>
                            <th><?php echo lang('product_shopping_cart_quantity'); ?></th>
                            <th><?php echo lang('product_shopping_cart_unit_price'); ?></th>
                            <th><?php echo lang('product_shopping_cart_total_price'); ?></th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>

                    <tbody id="cart_content">
                        <?php echo Modules::run('admin/order/_pagelet_order_view', $order['order_id']); ?>
                    </tbody>
                </table>
                <?php echo form_close(); ?>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var order_id = <?php echo $order['order_id']; ?>
</script>