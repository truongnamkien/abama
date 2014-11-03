<p><?php echo lang('email_order_notice'); ?></p>

<p align="left" style="margin-top:0px;margin-bottom:18px;">
    <?php if (!empty($order_info['fullname'])): ?>
        <?php echo lang('email_order_header', '', $order_info['fullname']); ?>
    <?php else: ?>
        <?php echo lang('email_subscribe_header'); ?>
    <?php endif; ?>
</p>

<div align="left" style="font-size:13px;line-height:18px;;margin-top:0px;margin-bottom:18px;">
    <p><?php echo lang('email_order_message_1'); ?></p>

    <table border="0">
        <tr>
            <th colspan="2" style="font-weight: bold;"><?php echo lang('product_checkout_title'); ?></th>
        </tr>
        <tr>
            <td style="width: 150px;"><?php echo lang('product_checkout_fullname'); ?></td>
            <td style="width: 200px;"><?php echo $order_info['fullname']; ?></td>
        </tr>
        <tr>
            <td><?php echo lang('product_checkout_email'); ?></td>
            <td><?php echo $order_info['email']; ?></td>
        </tr>
        <tr>
            <td><?php echo lang('product_checkout_mobile'); ?></td>
            <td><?php echo $order_info['mobile']; ?></td>
        </tr>
        <tr>
            <td><?php echo lang('product_checkout_shipping_address'); ?></td>
            <td><?php echo $order_info['address']; ?></td>
        </tr>
        <tr>
            <td><?php echo lang('product_order_delivery_time'); ?></td>
            <td><?php echo $order_info['delivery_time']; ?></td>
        </tr>
        <tr>
            <td><?php echo lang('product_checkout_note'); ?></td>
            <td><?php echo $order_info['note']; ?></td>
        </tr>
    </table>

    <p><?php echo lang('email_order_message_2'); ?></p>

    <table border="1">
        <tr style="text-align: center;">
            <th style="width:150px"><?php echo lang('product_product'); ?></th>
            <th style="width:80px"><?php echo lang('product_shopping_cart_quantity'); ?></th>
            <th style="width:200px"><?php echo lang('product_shopping_cart_unit_price'); ?></th>
            <th style="width:200px"><?php echo lang('product_shopping_cart_total_price'); ?></th>
        </tr>

        <?php $total = 0; ?>
        <?php foreach ($order_list as $order): ?>
            <tr>
                <td style="text-align:left;padding:0 5px;"><?php echo $product_list[$order['product_id']]['name_' . $this->_current_lang]; ?></td>
                <td style="text-align:right;padding:0 5px;"><?php echo $order['quantity']; ?></td>
                <td style="text-align:right;padding:0 5px;"><?php echo format_price($order['price']); ?></td>
                <td style="text-align:right;padding:0 5px;"><?php echo format_price($order['price'] * $order['quantity']); ?></td>
            </tr>
            <?php $total += $order['price'] * $order['quantity']; ?>
        <?php endforeach; ?>

        <?php if (isset($order_info['discount']) && !empty($order_info['discount'])): ?>
            <tr>
                <td style="text-align:left;font-weight: bold;padding:0 5px;"><?php echo lang('product_order_discount'); ?></td>
                <td style="text-align:left;padding:0 5px;" colspan="2"><?php echo $order_info['discount_note']; ?></td>
                <td style="text-align:right;padding:0 5px;"><?php echo format_price($order_info['discount']); ?></td>
            </tr>
            <?php $total += $order_info['discount']; ?>
        <?php endif; ?>
        <tr>
            <td style="text-align:left;font-weight: bold;padding:0 5px;" colspan="3"><?php echo lang('product_shopping_cart_total_price'); ?></td>
            <td style="text-align:right;padding:0 5px;"><?php echo format_price($total); ?></td>
        </tr>
    </table>
</div>

<p><?php echo lang('email_order_end'); ?></p>

