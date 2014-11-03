<div class="communication">
    <div class="bg_uudai">
        <a class="btn_communication cboxElement" href="#inline_communication">
            <img src="<?php echo asset_url('css/images/communication.png'); ?>" alt="<?php echo lang('static_content_home_subscribe_header'); ?>" height="48" width="50" />
        </a>

        <div style="display: none;" class="communication_wapp">
            <div id="inline_communication">
                <div class="header_communication"><?php echo lang('static_content_home_contact'); ?></div>
                <div style="display: none;" class="newsletter">
                    <div class="ma20">
                        <?php if (isset($mobile) && !empty($mobile)): ?>
                            <div class="h30">
                                <img class="mr20 fLeft" src="<?php echo asset_url('css/images/mobile.png'); ?>" />
                                <span class="fLeft mt2"><?php echo $mobile; ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($email) && !empty($email)): ?>
                            <div class="h30">
                                <a href="mailto:<?php echo $email; ?>">
                                    <img class="mr20 fLeft" src="<?php echo asset_url('css/images/email.png'); ?>" />
                                    <span class="fLeft mt2"><?php echo $email; ?></span>
                                </a>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($skype) && !empty($skype)): ?>
                            <div class="h30">
                                <a href="skype:<?php echo $skype; ?>">
                                    <img class="mr20 fLeft" src="<?php echo asset_url('css/images/skype.png'); ?>" />
                                    <span class="fLeft mt2"><?php echo $skype; ?></span>
                                </a>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($yahoo) && !empty($yahoo)): ?>
                            <div class="h30">
                                <a href="ymsgr:sendIM?<?php echo $yahoo; ?>">
                                    <img class="mr20 fLeft" src="<?php echo asset_url('css/images/yahoo.png'); ?>" />
                                    <span class="fLeft mt2"><?php echo $yahoo; ?></span>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $(".communication_wapp").hide();
        $(".btn_communication").colorbox({
            inline: true, width: "450px",
            onLoad: function() {
                $("#cboxContent").css({background: 'none'});
                $("#cboxClose").css({'top': '20px', 'right': '20px'});
            }
        });
    });
</script>