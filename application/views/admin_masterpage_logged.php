<!DOCTYPE html>
<html lang="en">
    <head>
        <!--
                ===
                This comment should NOT be removed.

                Charisma v2.0.0

                Copyright 2012-2014 Muhammad Usman
                Licensed under the Apache License v2.0
                http://www.apache.org/licenses/LICENSE-2.0

                http://usman.it
                http://twitter.com/halalit_usman
                ===
        -->
        <meta charset="utf-8" />
        <title><?php echo $PAGE_TITLE ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <?php echo asset_link_tag('admin_charisma/css/bootstrap-cerulean.min.css'); ?>
        <?php echo asset_link_tag('admin_charisma/css/charisma-app.css'); ?>
        <?php echo asset_link_tag('admin_charisma/bower_components/fullcalendar/dist/fullcalendar.css'); ?>
        <?php echo asset_link_tag('admin_charisma/bower_components/fullcalendar/dist/fullcalendar.print.css'); ?>
        <?php echo asset_link_tag('admin_charisma/bower_components/chosen/chosen.min.css'); ?>
        <?php echo asset_link_tag('admin_charisma/bower_components/colorbox/example3/colorbox.css'); ?>
        <?php echo asset_link_tag('admin_charisma/bower_components/responsive-tables/responsive-tables.css'); ?>
        <?php echo asset_link_tag('admin_charisma/bower_components/bootstrap-tour/build/css/bootstrap-tour.min.css'); ?>
        <?php echo asset_link_tag('admin_charisma/css/jquery.noty.css'); ?>
        <?php echo asset_link_tag('admin_charisma/css/noty_theme_default.css'); ?>
        <?php echo asset_link_tag('admin_charisma/css/elfinder.min.css'); ?>
        <?php echo asset_link_tag('admin_charisma/css/elfinder.theme.css'); ?>
        <?php echo asset_link_tag('admin_charisma/css/jquery.iphone.toggle.css'); ?>
        <?php echo asset_link_tag('admin_charisma/css/animate.min.css'); ?>
        <?php echo asset_link_tag('css/jquery/jquery-ui-1.8.16.custom.css'); ?>

        <!-- jQuery -->
        <?php echo asset_js('js/jquery/jquery-1.8.3.min.js'); ?>
        <?php echo asset_js('js/jquery/jquery-ui-1.9.2.custom.min.js'); ?>
        <?php echo asset_js('js/core.js'); ?>

        <!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <!-- The fav icon -->
        <link href="<?php echo asset_url('images/favicon.ico'); ?>" rel="shortcut icon" type="image/x-icon" />

        <?php echo asset_link_tag('css/default.css'); ?>

        <?php echo asset_js('js/tiny_mce/jquery.tinymce.min.js'); ?>
        <?php echo asset_js('js/tiny_mce/tinymce.min.js'); ?>

        <?php
        $config = array(
            'base_url' => $this->config->slash_item('base_url'),
            'site_url' => site_url(),
            'ajax_url' => site_url('ajax'),
            'asset_url' => asset_url(),
            'date_format' => jdate_format($this->config->item('date_format'))
        );
        ?>
        <style>
            .button, .button:hover {
                padding: 5px 10px;
                background: none repeat scroll 0% 0% #1D9CE5;
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

        <script type="text/javascript">
            var _admin = <?php echo json_encode($config) ?>;
            tinymce.init({
                selector: "textarea",
                theme: "modern",
                plugins: [
                    "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                    "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                    "save table contextmenu directionality emoticons template paste textcolor"
                ],
                toolbar: "insertfile undo redo | styleselect | bold italic | fontselect sizeselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",
                style_formats: [
                    {title: 'Bold text', inline: 'b'},
                    {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
                    {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
                    {title: 'Example 1', inline: 'span', classes: 'example1'},
                    {title: 'Example 2', inline: 'span', classes: 'example2'},
                    {title: 'Table styles'},
                    {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
                ]
            });
        </script>
    </head>

    <body>
        <!-- topbar starts -->
        <div class="navbar navbar-default" role="navigation">

            <div class="navbar-inner">
                <a class="navbar-brand" href="<?php echo site_url('admin'); ?>">
                    <span><?php echo PAGE_TITLE; ?></span>
                </a>

                <!-- user dropdown starts -->
                <div class="btn-group pull-right">
                    <?php echo Modules::run('admin/admin_navigator/_pagelet_admin_navigator'); ?>
                </div>
                <!-- user dropdown ends -->

                <!-- theme selector starts -->
                <div class="btn-group pull-right theme-container animated tada">
                    <?php echo Modules::run('admin/admin_navigator/_pagelet_theme_selector'); ?>
                </div>
                <!-- theme selector ends -->

                <?php echo Modules::run('admin/admin_navigator/_pagelet_support_selector'); ?>
            </div>
        </div>
        <!-- topbar ends -->

        <div class="ch-container">
            <div class="row">
                <!-- left menu starts -->
                <div class="col-sm-2 col-lg-2">
                    <div class="sidebar-nav">
                        <div class="nav-canvas">
                            <?php echo Modules::run('admin/admin_navigator/_main_nav'); ?>
                        </div>
                    </div>
                </div>
                <!-- left menu ends -->

                <div id="content" class="col-lg-10 col-sm-10">
                    <!-- content starts -->
                    <?php echo Modules::run('admin/admin_authen/_pagelet_notice'); ?>

                    <?php echo $PAGE_CONTENT ?>
                    <!-- content ends -->
                </div><!--/#content.col-md-0-->
            </div>
            <!--/fluid-row-->
            <hr />

            <footer class="row">
                <p class="col-md-9 col-sm-9 col-xs-12 copyright">&nbsp;</p>
                <p class="col-md-3 col-sm-3 col-xs-12 powered-by">
                    <?php echo lang('authen_developer'); ?>
                </p>
            </footer>
        </div><!--/.fluid-container-->

        <!-- external javascript -->
        <?php echo asset_js('admin_charisma/bower_components/bootstrap/dist/js/bootstrap.min.js'); ?>
        <?php echo asset_js('admin_charisma/js/jquery.cookie.js'); ?>
        <?php echo asset_js('admin_charisma/bower_components/moment/min/moment.min.js'); ?>
        <?php echo asset_js('admin_charisma/bower_components/fullcalendar/dist/fullcalendar.min.js'); ?>
        <?php echo asset_js('admin_charisma/js/jquery.dataTables.min.js'); ?>
        <?php echo asset_js('admin_charisma/bower_components/chosen/chosen.jquery.min.js'); ?>
        <?php echo asset_js('admin_charisma/bower_components/colorbox/jquery.colorbox-min.js'); ?>
        <?php echo asset_js('admin_charisma/js/jquery.noty.js'); ?>
        <?php echo asset_js('admin_charisma/bower_components/responsive-tables/responsive-tables.js'); ?>
        <?php echo asset_js('admin_charisma/bower_components/bootstrap-tour/build/js/bootstrap-tour.min.js'); ?>
        <?php echo asset_js('admin_charisma/js/jquery.raty.min.js'); ?>
        <?php echo asset_js('admin_charisma/js/jquery.iphone.toggle.js'); ?>
        <?php echo asset_js('admin_charisma/js/jquery.autogrow-textarea.js'); ?>
        <?php echo asset_js('admin_charisma/js/jquery.history.js'); ?>
        <?php echo asset_js('admin_charisma/js/charisma.js'); ?>
    </body>
</html>
