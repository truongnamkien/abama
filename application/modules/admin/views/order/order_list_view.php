<div class="btn-group fLeft mr10">
    <button class="btn btn-default btn-minimize"><?php echo lang('product_order_status'); ?></button>
    <button class="btn dropdown-toggle btn-default btn-minimize" data-toggle="dropdown"><span class="caret"></span></button>
    <ul class="dropdown-menu">
        <?php foreach ($status_list as $status): ?>
            <li class="<?php echo ($current_status == $status ? "active" : ""); ?>">
                <a href="<?php echo site_url('admin/order/index/0/' . $status); ?>"><?php echo lang('product_order_status_' . $status); ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
<a class="btn btn-default" href="<?php echo site_url('admin/order/export'); ?>">
    <i class="glyphicon glyphicon-download-alt"></i>
    <?php echo lang('admin_action_export'); ?>
</a>

<div class="row">
    <div class="box col-md-12 center-block">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><?php echo lang('product_order_manager') ?></h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
                </div>
            </div>
            <div class="box-content">
                <?php if (empty($orders)) : ?>
                    <?php echo lang('empty_list_data'); ?>
                <?php else : ?>
                    <div class="mb20">
                        <?php echo form_open_multipart('admin/order', array('id' => 'searching_form')); ?>
                        <div class="input-group w25p fLeft mr10">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-search red"></i></span>
                            <input class="form-control" autocomplete="off" name="keyword" id="keyword" value="<?php echo $keyword; ?>" placeholder="<?php echo lang('admin_search_placeholder'); ?>" type="text" />
                        </div>
                        <input id="search_btn" class="btn btn-primary btn-minimize fLeft" type="submit" value="<?php echo lang('admin_search_btn'); ?>" />
                        <div class="clear"></div>
                        <?php echo form_close(); ?>
                    </div>

                    <table class="table table-striped table-bordered responsive">
                        <thead>
                            <tr>
                                <th><?php echo lang('product_order_order_id'); ?></th>
                                <th><?php echo lang('product_checkout_fullname'); ?></th>
                                <th><?php echo lang('product_checkout_email'); ?></th>
                                <th><?php echo lang('product_order_created_at'); ?></th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?php echo $order['order_id']; ?></td>
                                    <td><?php echo $order['fullname']; ?></td>
                                    <td><?php echo $order['email']; ?></td>
                                    <td><?php echo date($this->config->item('date_time_format'), $order['created_at']); ?></td>
                                    <td>
                                        <a class="btn btn-success" href="<?php echo site_url('admin/order/detail?order_id=' . $order['order_id']); ?>" title="<?php echo lang('admin_action_update'); ?>">
                                            <i class="glyphicon glyphicon-edit icon-white"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <?php if (isset($pagination) && $pagination): ?>
                        <ul class="pagination pagination-centered">
                            <?php echo $this->pagination->create_links(); ?>
                        </ul>
                    <?php endif; ?>
                    <div class="clear"></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#search_btn').click(function(e) {
            e.preventDefault();
            $('#searching_form').submit();
        });
    });
</script>
