<div class="uudai">
    <div class="bg_uudai">
        <a class="btn_uudai cboxElement" href="#inline_uudai">
            <img src="<?php echo asset_url('css/images/uudai.jpg'); ?>" alt="<?php echo lang('static_content_home_subscribe_header'); ?>" height="48" width="50" />
        </a>

        <div style="display: none;" class="uudai_wapp">
            <div id="inline_uudai">
                <div class="header_uudai"><?php echo lang('static_content_home_subscribe_header'); ?></div>
                <div style="display: none;" id="newsletter" class="newsletter">
                    <div class="email_res">
                        <p id="register_ok"><?php echo lang('static_content_home_customer_email'); ?></p>
                        <form id="subscribe_form" action="<?php echo site_url('ajax/email_ajax/submit'); ?>" rel="async" method="post">
                            <input class="newsletter_email" autocomplete="off" name="email" placeholder="<?php echo lang('static_content_home_email'); ?>" type="text" />
                            <input class="btn_dangkymail" type="submit" value="<?php echo lang('static_content_home_submit_btn'); ?>" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function() {
        $(".uudai_wapp").hide();
        $(".btn_uudai").colorbox({
            inline: true, width: "450px",
            onLoad: function() {
                $("#cboxContent").css({background: 'none'});
                $("#cboxClose").css({'top': '20px', 'right': '20px'});
            }
        });
    });
</script>