<?php echo asset_link_tag('css/category.css', 'stylesheet', 'text/css'); ?>
<?php echo asset_link_tag('css/home.css', 'stylesheet', 'text/css'); ?>

<?php echo Modules::run('category/_pagelet_category_list'); ?>

<div class="product_category">
    <?php echo Modules::run('product/_pagelet_hot_products'); ?>
</div>

<div class="w_home mb20">
    <?php echo Modules::run('category/_pagelet_category_list', 'bottom'); ?>

    <div class="w300">
    <?php echo Modules::run('home/_pagelet_sub_banner', 'home_right_2'); ?>
    <?php echo Modules::run('home/_pagelet_sub_banner', 'home_right_3'); ?>
        <?php echo Modules::run('navigator/_pagelet_connect', 'home'); ?>
    </div>        
</div>

<?php if (isset($notice) && !empty($notice)): ?>
    <script type='text/javascript'>
        $(document).ready(function() {
            show_alert('<?php echo $notice['content']; ?>');
        });
    </script>
<?php endif; ?>
