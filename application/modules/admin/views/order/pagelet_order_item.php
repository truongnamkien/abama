<td><?php echo $item['product']['name_' . $this->_current_lang]; ?></td>
<td class="tar"><?php echo $item['quantity']; ?></td>
<td class="tar"><?php echo format_price($item['price']); ?></td>
<td class="tar"><?php echo format_price($item['price'] * $item['quantity']); ?></td>
<td class="remove">
    <?php if ($status == Order_Model::ORDER_STATUS_PENDING): ?>
        <a class="btn btn-success" href="#" rel="async" ajaxify="<?php echo site_url('ajax/product_ajax/admin_edit_item/' . $item['order_detail_id']); ?>" title="<?php echo lang('product_shopping_cart_edit'); ?>">
            <i class="glyphicon glyphicon-edit icon-white"></i>
        </a>
        <a class="btn btn-danger" href="#" rel="async" ajaxify="<?php echo site_url('ajax/product_ajax/admin_remove/' . $item['order_detail_id']); ?>" title="<?php echo lang('product_shopping_cart_remove'); ?>">
            <i class="glyphicon glyphicon-trash icon-white"></i>
        </a>
    <?php endif; ?>
</td>
