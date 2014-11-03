<?php foreach ($categories as $category): ?>
    <div class="search_item">
        <a href="<?php echo product_category_url($category); ?>">
            <?php echo $category['category_name_' . $this->_current_lang]; ?>
        </a>
    </div>
<?php endforeach; ?>

<?php foreach ($products as $product): ?>
    <div class="search_item">
        <a href="<?php echo product_url($product); ?>">
            <?php echo $product['name_' . $this->_current_lang]; ?>
        </a>
    </div>
<?php endforeach; ?>

