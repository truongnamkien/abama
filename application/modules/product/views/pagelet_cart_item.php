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
<td class="qty"><?php echo $item['qty']; ?></td>
<td class="price unit_price"><?php echo format_price($item['price']); ?></td>
<td class="price total_price"><?php echo format_price($item['total_price']); ?></td>

<td class="remove">
    <a class="button mr5 ml30 fLeft" href="#" rel="async" ajaxify="<?php echo site_url('ajax/product_ajax/edit_item/' . $item['rowid']); ?>">
        <?php echo lang('product_shopping_cart_edit'); ?>
    </a>
    <a class="disabled_btn fLeft" href="#" rel="async" ajaxify="<?php echo site_url('ajax/product_ajax/remove_from_cart/' . $item['rowid']); ?>">
        <?php echo lang('product_shopping_cart_remove'); ?>
    </a>
</td>


