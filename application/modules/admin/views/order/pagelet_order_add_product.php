<?php if (empty($product_list)): ?>
    <?php echo lang('empty_list_data'); ?>
<?php else: ?>
    <label class="control-label" for="product_id"><?php echo lang('product_product'); ?></label>
    <div>
        <div class="controls fLeft mr10">
            <?php echo form_dropdown('product_id', $product_list, array(), 'id="product_id"'); ?>
        </div>
        <input class="quantity_input fLeft" autocomplete="off" type="number" name="quantity" value="1" />
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#admin_submit').fadeIn();
            $('.quantity_input').change(function() {
                if ($(this).val() <= 0) {
                    $(this).val(1);
                }
            });
            $('#product_id').chosen();

            $('#admin_submit').click(function(e) {
                var qty = $('.quantity_input').val();
                var product_id = $('#product_id').val();
                var _uri = _admin.ajax_url + '/product_ajax/admin_add_submit?product_id=' + product_id + '&qty=' + qty + '&order_id=' + order_id;
                AsyncRequest.bootstrap(new URI(_uri));

                e.preventDefault();
                return false;
            });
        });
    </script>
<?php endif; ?>
