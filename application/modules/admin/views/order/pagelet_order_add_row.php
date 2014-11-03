<td colspan="2">
    <div class="form-group">
        <label class="control-label" for="product_category_id"><?php echo lang('product_product_category_id'); ?></label>
        <div class="controls">
            <?php echo form_dropdown('product_category_id', $category_list, array(), 'id="product_category_id"'); ?>
        </div>
    </div>
</td>
<td colspan="2" id="product_list"></td>
<td class="remove">
    <a class="btn btn-success" href="#" id="admin_submit" href="#" title="<?php echo lang('admin_action_update'); ?>">
        <i class="glyphicon glyphicon-ok icon-white"></i>
    </a>
    <a class="btn btn-danger" href="#" rel="async" ajaxify="<?php echo site_url('ajax/product_ajax/admin_add_cancel'); ?>" title="<?php echo lang('product_shopping_cart_remove'); ?>">
        <i class="glyphicon glyphicon-trash icon-white"></i>
    </a>
</td>

<script type="text/javascript">
    $(document).ready(function() {
        $('#product_category_id').change(function() {
            var _uri = _admin.ajax_url + '/product_ajax/admin_add_product/' + $(this).val();
            AsyncRequest.bootstrap(new URI(_uri));
        });
        $('#product_category_id').chosen();
        $('#admin_submit').hide();
    });
</script>