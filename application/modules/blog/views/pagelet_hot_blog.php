<div id="event">
    <h6><?php echo lang('blog_hot'); ?></h6>

    <div class="k2ItemsBlock">
        <ul>
            <?php $count = 0; ?>
            <?php foreach ($blog_list as $blog): ?>
                <li class="<?php echo ($count % 2 == 0 ? 'even' : 'odd'); ?> <?php echo ($count == count($blog_list) - 1 ? 'lastItem' : ''); ?>">
                    <a class="moduleItemTitle" href="<?php echo blog_url($blog); ?>"><?php echo $blog['title_' . $this->_current_lang]; ?></a>

                    <div class="moduleItemIntrotext">
                        <?php echo character_limiter(strip_tags($blog['content']), 100); ?>
                        <div class="clr"></div>
                </li>
                <?php $count++; ?>
            <?php endforeach; ?>
            <li class="clearList"></li>
        </ul>
    </div>
</div>