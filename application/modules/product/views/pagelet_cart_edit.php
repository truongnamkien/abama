<td class="image">
    <a href="<?php echo product_url($item['product']); ?>">
        <?php if (!empty($item['product']['photo'])): ?>
            <img width="120" alt="<?php echo $item['product']['name_' . $this->_current_lang]; ?>" src="<?php echo base_url(Modules::run('photo/_get_photo_path', $item['product']['photo']['url'], 120)); ?>" />
        <?php endif; ?>
    </a>
</td>
<td class="item">
    <a href="<?php echo product_url($item['product']); ?>">
        <?php echo $item['product']['name_' . $this->_current_lang]; ?>
    </a>
</td>
<td class="qty">
    <input class="quantity_input" autocomplete="off" type="number" name="quantity" value="<?php echo $item['qty']; ?>" />
</td>
<td class="price unit_price"><?php echo format_price($item['price']); ?></td>
<td class="price total_price"><?php echo format_price($item['total_price']); ?></td>

<td class="remove">
    <a class="button mr5 ml30 fLeft" id="submit_<?php echo $item['rowid']; ?>" href="#">
        <?php echo lang('product_update_submit'); ?>
    </a>
    <a class="disabled_btn fLeft" href="#" rel="async" ajaxify="<?php echo site_url('ajax/product_ajax/remove_from_cart/' . $item['rowid']); ?>">
        <?php echo lang('product_shopping_cart_remove'); ?>
    </a>
</td>

<script type="text/javascript">
    $(document).ready(function() {
        $(".quantity_input").focus();

        $('.quantity_input').change(function() {
            if ($(this).val() <= 0) {
                $(this).val(1);
            }
            var _qty = $(this).val();
            var _ret = $('#row_<?php echo $item['rowid']; ?>').find('.unit_price').html();
            var _price = 0;
            for (var _i = 0; _i < _ret.length; _i++) {
                var _val = _ret.substr(_i, 1);
                if (/[0-9]+/.test(_val)) {
                    _price = _price * 10 + parseInt(_val);
                }
            }
            var _total = _qty * _price + '';

            var _length = _total.length;
            var _first_length = _length % 3;
            var _str = '';
            if (_first_length > 0) {
                _str += _total.substr(0, _first_length);
            }

            while (_first_length < _length - 1) {
                if (_first_length > 0) {
                    _str += '.';
                }
                _str += _total.substr(_first_length, 3);
                _first_length += 3;
            }
            _str += ' VND';
            $('#row_<?php echo $item['rowid']; ?>').find('.total_price').html(_str);
        });

        $('#submit_<?php echo $item['rowid']; ?>').click(function(e) {
            e.preventDefault();
            var _qty = $('#row_<?php echo $item['rowid']; ?>').find('.quantity_input').val();

            if (_qty == 0) {
                show_alert('<?php echo lang('product_shopping_cart_edit_failed_qty'); ?>');
            } else {
                var _uri = '<?php echo site_url('ajax/product_ajax/edit/' . $item['rowid']); ?>';
                request = new AsyncRequest(new URI(_uri)).setData({
                    qty: _qty,
                }).setMethod('POST').send();
            }
        });
    });
</script>
