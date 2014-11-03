<?php echo asset_link_tag('css/news.css', 'stylesheet', 'text/css'); ?>

<?php echo Modules::run('category/_pagelet_category_list'); ?>

<div class="ct_news">
    <div class="new_detail">
        <div class="fRight">
            <div class="fb-like" data-href="<?php echo blog_url($blog); ?>" data-send="true" data-layout="button_count" data-show-faces="true" data-font="verdana"></div>
        </div>
        <h1><?php echo $blog['title_' . $this->_current_lang]; ?></h1>
        <div class="clear"></div>

        <div class="desc">
            <?php echo Modules::run('blog/_display_content', $blog['blog_id']); ?>
        </div>
    </div>
</div>
