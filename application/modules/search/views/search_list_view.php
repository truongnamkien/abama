<?php echo asset_link_tag('css/category.css', 'stylesheet', 'text/css'); ?>

<?php echo Modules::run('category/_pagelet_category_list'); ?>

<div class="product_category">
    <?php echo Modules::run('product/_pagelet_hot_products'); ?>

    <div class="w_category_products">
        <div class="hotoffers">
            <h3>
                <?php echo lang('search_result_desc', '', $query); ?>
            </h3>
        </div>
        <div class="clear"></div>

        <div class="listproduct">
            <div class="product" id="product_list">
                <?php if (empty($products)): ?>
                    <div class="tac fs20 ma15"><?php echo lang('search_result_empty'); ?></div>
                <?php else: ?>
                    <?php $index = 0; ?>
                    <?php foreach ($products as $product): ?>
                        <div class="item <?php echo (($index + 1) % 4 == 0 ? 'last' : ''); ?>">
                            <?php echo Modules::run('product/_pagelet_product_item', $product); ?>
                        </div>
                        <?php $index++; ?>
                    <?php endforeach; ?>                
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
