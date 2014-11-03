<div class="contact_info">
    <div class="info_main">
        <?php if (isset($mobile) && !empty($mobile)): ?>
            <div class="info">
                <a href="#" title="<?php echo $mobile; ?>" class="fake img_tel">&nbsp;</a>
                <p class="first"><?php echo lang('static_content_config_mobile'); ?>: <?php echo $mobile; ?></p>
            </div>	
        <?php endif; ?>

        <?php if (isset($email) && !empty($email)): ?>
            <div class="info">
                <a href="mailto:<?php echo $email; ?>" title="<?php echo $email; ?>" class="img_email">&nbsp;</a>
                <p><a href="mailto:<?php echo $email; ?>" title="<?php echo $email; ?>"><?php echo lang('static_content_config_email'); ?>: <?php echo $email; ?></a></p>
            </div>	
        <?php endif; ?>

        <?php if (isset($facebook_page) && !empty($facebook_page)): ?>
            <div class="info">
                <a target="_blank" href="<?php echo $facebook_page; ?>" title="<?php echo PAGE_TITLE; ?>" class="img_address">&nbsp;</a>
                <h2><p class="contact_add"><a target="_blank" href="<?php echo $facebook_page; ?>" title="<?php echo PAGE_TITLE; ?>"><?php echo lang('static_content_config_facebook_page'); ?>: <?php echo PAGE_TITLE; ?></a></p></h2>
            </div>	
        <?php endif; ?>
    </div>
</div>