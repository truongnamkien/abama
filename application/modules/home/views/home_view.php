<?php echo asset_link_tag('css/category.css', 'stylesheet', 'text/css'); ?>
<?php echo asset_link_tag('css/home.css', 'stylesheet', 'text/css'); ?>

<?php echo Modules::run('category/_pagelet_category_list'); ?>

<div class="product_category">
    <div class="w250">
        <?php echo Modules::run('product/_pagelet_hot_products'); ?>

        <?php echo Modules::run('navigator/_pagelet_connect', 'home'); ?>
    </div>

    <div class="w_category_products">
        <div class="hotoffers">
            <h3>
                <?php echo lang('product_best_seller'); ?>
            </h3>
        </div>
        <div class="clear"></div>

        <div class="listproduct">
            <div class="product" id="product_list">
                <?php echo Modules::run('category/_pagelet_best_seller'); ?>
            </div>
        </div>
    </div>

</div>

<?php if (isset($notice) && !empty($notice)): ?>
    <script type='text/javascript'>
        $(document).ready(function() {
            show_alert('<?php echo $notice['content']; ?>');
        });
    </script>
<?php endif; ?>
