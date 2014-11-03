<?php if ($position == 'top'): ?>
    <div class="category">
        <h3><?php echo lang('product_product_category_id'); ?></h3>
        <ul>
            <?php foreach ($category_list as $category): ?>
                <li>
                    <a style="color: rgb(51, 51, 51);" href="<?php echo product_category_url($category); ?>" title="<?php echo $category['category_name_' . $this->_current_lang]; ?>">
                        <?php echo $category['category_name_' . $this->_current_lang]; ?>
                    </a>
                    <div class="submenu" style="background-color: #<?php echo $category['color']; ?>">
                        <ul class="catpro">
                            <?php for ($i = 0; $i < 4; $i++): ?>
                                <?php if (isset($category['sub_categories'][$i]) && !empty($category['sub_categories'][$i])): ?>
                                    <?php $sub_category = $category['sub_categories'][$i]; ?>
                                    <li>
                                        <a href="<?php echo product_category_url($sub_category); ?>" title="<?php echo $sub_category['category_name_' . $this->_current_lang]; ?>">
                                            <?php $photo_url = Modules::run('photo/_get_photo_path', $sub_category['url'], 200); ?>
                                            <img src="<?php echo base_url($photo_url); ?>" alt="<?php echo $sub_category['category_name_' . $this->_current_lang]; ?>" height="110" width="153" />
                                            <span><?php echo $sub_category['category_name_' . $this->_current_lang]; ?></span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </ul>
                        <div class="catlist">
                            <?php foreach ($category['sub_categories'] as $sub_category): ?>
                                <span>
                                    <a href="<?php echo product_category_url($sub_category); ?>" title="<?php echo $sub_category['category_name_' . $this->_current_lang]; ?>">
                                        <?php echo $sub_category['category_name_' . $this->_current_lang]; ?>
                                    </a>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.category li').hover(function() {
                var a = $(this).children('div.submenu').css("background-color");
                $(this).css({'background-color': a});
                $(this).children('a').css('color', '#FFF');
            }).mouseleave(function() {
                $(this).css({'background-color': ''});
                $(this).children('a').css('color', '#333');
            });

        });
    </script>

    <div class="banner_top">
        <div class="catbn">
            <?php echo Modules::run('home/_pagelet_sub_banner', 'product'); ?>
        </div>

        <?php echo Modules::run('branch/_pagelet_branch_list'); ?>
    </div>
<?php else: ?>
    <div class="listcategory">
        <?php foreach ($category_list as $index => $category): ?>
            <div class="bloc <?php echo ($index % 2 == 0 ? 'mr10' : ''); ?>">
                <p style="background-color: #<?php echo $category['color']; ?>">
                    <a href="<?php echo product_category_url($category); ?>" title="<?php echo $category['category_name_' . $this->_current_lang]; ?>">
                        <?php echo $category['category_name_' . $this->_current_lang]; ?>
                    </a>
                </p>
                <a href="<?php echo product_category_url($category); ?>">
                    <div class="catimg">
                        <?php $photo_url = Modules::run('photo/_get_photo_path', $category['url'], 300); ?>
                        <img src="<?php echo base_url($photo_url); ?>" alt="<?php echo $category['category_name_' . $this->_current_lang]; ?>" width="290" />
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
