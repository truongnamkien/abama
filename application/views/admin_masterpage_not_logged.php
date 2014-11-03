<!DOCTYPE html>
<html lang="vi">
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

        <!-- The styles -->
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
		<?php echo asset_link_tag('admin_charisma/css/uploadify.css'); ?>
		<?php echo asset_link_tag('admin_charisma/css/animate.min.css'); ?>

        <!-- jQuery -->
		<?php echo asset_js('admin_charisma/bower_components/jquery/jquery.min.js'); ?>

        <!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <!-- The fav icon -->
        <link rel="shortcut icon" href="<?php echo asset_url('images/favicon.ico'); ?>" />

		<?php
		$config = array(
			'base_url' => $this->config->slash_item('base_url'),
			'site_url' => site_url(),
			'ajax_url' => site_url('ajax'),
			'asset_url' => asset_url(),
			'date_format' => jdate_format($this->config->item('date_format'))
		);
		?>
        <script type="text/javascript">
			var _admin = <?php echo json_encode($config) ?>;
        </script>
    </head>

    <body>
        <div class="ch-container">
            <div class="row">
				<?php echo $PAGE_CONTENT; ?>
            </div><!--/fluid-row-->
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
		<?php echo asset_js('admin_charisma/js/jquery.uploadify-3.1.min.js'); ?>
		<?php echo asset_js('admin_charisma/js/jquery.history.js'); ?>
		<?php echo asset_js('admin_charisma/js/charisma.js'); ?>
    </body>
</html>
