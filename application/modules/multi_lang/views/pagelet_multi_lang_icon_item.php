<li class="<?php echo ($language == $this->_current_lang ? 'selected' : ''); ?>">
    <a href="#" rel="async" ajaxify="<?php echo site_url('ajax/multi_lang_ajax/change_lang?lang=' . $language); ?>">
        <span><?php echo lang('multi_language_' . $language); ?></span>
        <img src="<?php echo asset_url('images/icon_lang_' . $language . '.png'); ?>" alt="<?php echo lang('multi_language_' . $language); ?>" width="20" />
    </a>
</li>
