<?php foreach ($order_detail as $detail): ?>
    <tr id="detail_<?php echo $detail['order_detail_id']; ?>" class="product_row">
        <?php echo Modules::run('admin/order/_pagelet_order_item', $detail, $status); ?>
    </tr>
<?php endforeach; ?>

<?php echo Modules::run('admin/order/_pagelet_order_add_button', $status); ?>

<tr>
    <td class="fwb">
        <label class="control-label" for="discount"><?php echo lang('product_order_discount'); ?></label>
    </td>
    <?php if ($status == Order_Model::ORDER_STATUS_PENDING): ?>
        <td colspan="4">
            <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type="text" class="form-control" value="<?php echo $discount; ?>" id="discount" name="discount" />
                <span class="input-group-addon">VND</span>
            </div>
        </td>
    <?php else: ?>
        <td colspan="3" class="tar">
            <?php echo format_price($discount); ?>
        </td>
        <td>&nbsp;</td>
    <?php endif; ?>
    <?php $total_price += $discount; ?>
</tr>
<tr>
    <td class="fwb"><?php echo lang('product_order_discount_note'); ?></td>
    <td colspan="4">
        <?php if ($status == Order_Model::ORDER_STATUS_PENDING): ?>
            <textarea name="discount_note"><?php echo $discount_note; ?></textarea>
        <?php else: ?>
            <?php echo $discount_note; ?>    
        <?php endif; ?>
    </td>
</tr>

<tr class="summary">
    <td class="fwb" colspan="3"><?php echo lang('product_shopping_cart_total_price'); ?></td>
    <td class="tar">
        <?php echo format_price($total_price); ?>    
    </td>
    <td>&nbsp;</td>
</tr>

<?php if ($status == Order_Model::ORDER_STATUS_PENDING): ?>
    <tr>
        <td colspan="4">&nbsp;</td>
        <td>
            <button type="submit" class="btn btn-default"><?php echo lang('admin_action_update'); ?></button>
        </td>
    </tr>
<?php endif; ?>
