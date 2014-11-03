<?php echo asset_link_tag('css/news.css', 'stylesheet', 'text/css'); ?>

<?php echo Modules::run('category/_pagelet_category_list'); ?>

<div class="ct_news">
    <div class="new_detail">
        <div class="fRight">
            <div class="fb-like" data-href="<?php echo blog_category_url($category); ?>" data-send="true" data-layout="button_count" data-show-faces="true" data-font="verdana"></div>
        </div>
        <h1><?php echo $category['name_' . $this->_current_lang]; ?></h1>
        <div class="clear"></div>

        <?php if (empty($blog_list)): ?>
            <div class="desc">
                <?php echo lang('blog_list_empty'); ?>
            </div>
        <?php else: ?>
            <div id="load_data">
                <div class="grid_3">
                    <div class="wrap">
                        <?php foreach ($blog_list as $blog): ?>
                            <div class="col">
                                <?php if (!empty($blog['photo_url'])): ?>
                                    <a href="<?php echo blog_url($blog); ?>" title="<?php echo $blog['title_' . $this->_current_lang]; ?>">
                                        <img src="<?php echo $blog['photo_url']; ?>" alt="<?php echo $blog['title_' . $this->_current_lang]; ?>" width="363"> 
                                    </a>
                                <?php endif; ?>
                                <h2>
                                    <a href="<?php echo blog_url($blog); ?>" title="<?php echo $blog['title_' . $this->_current_lang]; ?>"><?php echo $blog['title_' . $this->_current_lang]; ?></a>
                                </h2>
                                <div class="desc">
                                    <?php echo character_limiter(strip_tags($blog['content']), (!empty($blog['photo_url']) ? 100 : 400)); ?>
                                </div>
                                <div class="more">
                                    <a href="<?php echo blog_url($blog); ?>" title="<?php echo $blog['title_' . $this->_current_lang]; ?>">
                                        <span>
                                            <?php echo lang('blog_read_more'); ?>
                                            <img src="<?php echo asset_url('css/images/arr_detail_new.png'); ?>" class="icon_detail" alt="<?php echo $blog['title_' . $this->_current_lang]; ?>" height="7" width="7">
                                        </span>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="paging">
                    <?php echo $this->pagination->create_links(); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

