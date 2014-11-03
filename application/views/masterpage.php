<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

        <title><?php echo PAGE_TITLE . (empty($PAGE_TITLE) ? '' : ' - ' . $PAGE_TITLE); ?></title>

        <meta name="robots" content="index, follow" />
        <meta name="viewport" content="width=1024, initial-scale=0.4" />

        <script type="text/javascript">
			var _asset_url = '<?php echo asset_url(); ?>';
        </script>

		<?php echo asset_link_tag('css/reset.css', 'stylesheet', 'text/css'); ?>
		<?php echo asset_link_tag('css/default.css', 'stylesheet', 'text/css'); ?>
		<?php echo asset_link_tag('css/template.css', 'stylesheet', 'text/css'); ?>
		<?php echo asset_link_tag('css/menu.css', 'stylesheet', 'text/css'); ?>
		<?php echo asset_link_tag('css/contact.css', 'stylesheet', 'text/css'); ?>
		<?php echo asset_link_tag('css/jquery/jquery-ui-1.8.16.custom.css'); ?>
		<?php echo asset_link_tag('css/colorbox/colorbox.css'); ?>

        <!-- jQuery -->
		<?php echo asset_js('js/jquery/jquery-1.8.3.min.js'); ?>
		<?php echo asset_js('js/jquery/jquery-ui-1.9.2.custom.min.js'); ?>
		<?php echo asset_js('js/jquery/jquery.colorbox-min.js'); ?>
		<?php echo asset_js('js/jquery/jquery.carouFredSel.js'); ?>
		<?php echo asset_js('js/core.js'); ?>

		<?php echo Modules::run('navigator/_pagelet_meta_tag'); ?>
        <link href="<?php echo asset_url('images/favicon.ico'); ?>" rel="shortcut icon" type="image/x-icon" />

        <meta http-equiv="content-script-type" content="text/javascript" />
        <meta http-equiv="content-style-type" content="text/css" />
        <meta http-equiv="imagetoolbar" content="no" />
    </head>

    <body>
        <div id="fb-root"></div>
        <script>
			(function(d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id))
					return;
				js = d.createElement(s);
				js.id = id;
				js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
				fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));
        </script>

		<?php echo Modules::run('navigator/_pagelet_header_bar'); ?>

        <div id="demoContainer">
            <div class="wapper_body">
                <div class="header">
                    <div class="wrapper_head">
                        <div class="logo_div">
                            <a class="logo mt20" href="<?php echo site_url(); ?>">
                                <img src="<?php echo asset_url('images/logo.png'); ?>" alt="<?php echo PAGE_TITLE; ?>" />
                            </a>
                        </div>
                        <div class="language">
                            <div id="cart_mini">
								<?php echo Modules::run('product/_pagelet_cart_content'); ?>
                            </div>
							<div class="clear"></div>

							<div class="hotline">
								<?php echo Modules::run('navigator/_pagelet_hotline'); ?>
							</div>
                        </div>
                    </div>
                </div>

                <div class="maincontent">
                    <div class="wrap">
                        <div class="mainnav">
                            <div class="menu_wrap" id="main-menu">
								<?php echo Modules::run('navigator/_pagelet_main_menu'); ?>

                                <div class="search">
									<?php echo Modules::run('search/_search_form'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="mainpage">
							<?php echo $PAGE_CONTENT; ?>
                        </div>
                        <div class="clear"></div>

						<?php echo Modules::run('navigator/_pagelet_contact'); ?>
                    </div>
                    <div class="clear"></div>
                </div>

                <div class="bottom">
                    <div class="mnfooter">
                        <div class="menu_footer">
							<?php echo Modules::run('navigator/_pagelet_main_menu', 'bottom'); ?>
                        </div>
                    </div>  

					<?php echo Modules::run('navigator/_pagelet_footer'); ?>
                </div>
				<?php echo Modules::run('navigator/_pagelet_back_top'); ?>

				<?php echo Modules::run('navigator/_pagelet_connect'); ?>
				<?php echo Modules::run('navigator/_pagelet_subscribe'); ?>
				<?php echo Modules::run('navigator/_pagelet_communication'); ?>
            </div>
        </div>
    </body>
</html>

