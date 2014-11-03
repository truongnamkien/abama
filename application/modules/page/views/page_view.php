<?php echo asset_link_tag('css/news.css', 'stylesheet', 'text/css'); ?>

<?php echo Modules::run('category/_pagelet_category_list'); ?>

<div class="ct_news">
    <div class="new_detail">
        <div class="fRight">
            <div class="fb-like" data-href="<?php echo page_url($page); ?>" data-send="true" data-layout="button_count" data-show-faces="true" data-font="verdana"></div>
        </div>
        <h1><?php echo $page['name_' . $this->_current_lang]; ?></h1>
        <div class="clear"></div>

        <div class="desc">
            <?php echo Modules::run('page/_display_content', $page['page_id']); ?>
        </div>
    </div>
</div>
