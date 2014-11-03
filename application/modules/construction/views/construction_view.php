<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <title><?php echo PAGE_TITLE; ?></title>
        <?php echo Modules::run('navigator/_pagelet_meta_tag'); ?>

        <?php echo asset_link_tag('css/reset.css', 'stylesheet', 'text/css'); ?>
        <?php echo asset_link_tag('css/default.css', 'stylesheet', 'text/css'); ?>
        <?php echo asset_link_tag('css/colorbox/colorbox.css'); ?>
        <?php echo asset_link_tag('construction/construction.css', 'stylesheet', 'text/css'); ?>

        <?php echo asset_js('js/jquery/jquery-1.8.3.min.js'); ?>
        <?php echo asset_js('js/jquery/jquery.colorbox-min.js'); ?>
        <?php echo asset_js('js/core.js'); ?>
        <?php echo asset_js('js/core_' . $PAGE_LANG . '.js'); ?>
        <?php echo asset_js('construction/cufon-yui.js'); ?>
        <?php echo asset_js('construction/Bebas_400.font.js'); ?>

        <script type="text/javascript">
            Cufon.replace('a.logo', {fontFamily: 'Bebas'});
        </script>

        <link href="<?php echo asset_url('images/favicon.ico'); ?>" rel="shortcut icon" type="image/x-icon" />
        <style>
            .button, .button:hover {
                padding: 5px 10px;
                background: none repeat scroll 0% 0% #0e1d3a;
                border: medium none;
                color: #FFF;
                text-transform: uppercase;
                font-size: 12px;
                cursor: pointer;
                margin-bottom: 10px;
                -webkit-border-radius: 3px;
                -moz-border-radius: 3px;
                border-radius: 3px;
            }
        </style>
    </head>
    <body>
        <div class="main_container">
            <div class="header">
                <a class="logo fake" href="#">
                    <?php echo strip_unicode(PAGE_TITLE); ?>
                </a>
                <div class="clear"></div>
            </div>
            <div class="content">
                <h1><?php echo lang('construction_coming_soon'); ?></h1>
                <div class="right_side">
                    <p>
                        <?php if (isset($email) && !empty($email)): ?>
                            <?php echo $email; ?>
                        <?php endif; ?>
                        <?php if (isset($phone) && !empty($phone)): ?>
                            <?php echo $phone; ?>
                        <?php endif; ?>
                    </p>
                    <ul class="s_icons">
                        <?php if (isset($facebook_page) && !empty($facebook_page)): ?>
                            <li class="fb">
                                <a target="_blank" href="<?php echo $facebook_page; ?>"></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <form id="subscribe_form" class="email" action="<?php echo site_url('ajax/email_ajax/submit'); ?>" rel="async" method="post">
                <input class="field" autocomplete="off" name="email" placeholder="<?php echo lang('static_content_home_subscribe_header') . ' - ' . lang('static_content_home_email'); ?>" type="text" />
                <input class="submit" type="submit" id="subscribe_btn" value="<?php echo lang('static_content_home_submit_btn'); ?>" />
            </form>

        </div>
    </body>
</html>
