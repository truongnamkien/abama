<td><?php echo $item['product']['name_' . $this->_current_lang]; ?></td>
<td>
    <input class="quantity_input" autocomplete="off" type="number" name="quantity" value="<?php echo $item['quantity']; ?>" />
</td>
<td class="tar"><?php echo format_price($item['price']); ?></td>
<td class="tar"><?php echo format_price($item['price'] * $item['quantity']); ?></td>
<td class="remove">
    <a class="btn btn-success" href="#" id="submit_<?php echo $item['order_detail_id']; ?>" title="<?php echo lang('admin_action_update'); ?>">
        <i class="glyphicon glyphicon-ok icon-white"></i>
    </a>
    <a class="btn btn-danger" href="#" rel="async" ajaxify="<?php echo site_url('ajax/product_ajax/admin_remove/' . $item['order_detail_id']); ?>" title="<?php echo lang('product_shopping_cart_remove'); ?>">
        <i class="glyphicon glyphicon-trash icon-white"></i>
    </a>
</td>

<script type="text/javascript">
    $(document).ready(function() {
        $(".quantity_input").focus();

        $('.quantity_input').change(function() {
            if ($(this).val() <= 0) {
                $(this).val(1);
            }
        });

        $('#submit_<?php echo $item['order_detail_id']; ?>').click(function(e) {
            e.preventDefault();
            var _qty = get_input_quantity($('#detail_<?php echo $item['order_detail_id']; ?>').find('.quantity_input'));

            if (_qty == 0) {
                show_alert('<?php echo lang('product_shopping_cart_edit_failed_qty'); ?>');
            } else {
                var _uri = '<?php echo site_url('ajax/product_ajax/admin_edit/' . $item['order_detail_id']); ?>';
                request = new AsyncRequest(new URI(_uri)).setData({
                    qty: _qty,
                }).setMethod('POST').send();
            }
        });
    });

    function get_input_quantity(_input) {
        var _qty = _input.val();
        if (/[0-9]+/.test(_qty) == false || _qty < 0) {
            _qty = 0;
        }
        return _qty;
    }
</script>
