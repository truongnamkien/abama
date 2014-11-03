<?php if ($position == 'side'): ?>
    <div class="like_face">
        <a class="inline_face cboxElement" href="#inline_facebook">
            <img src="<?php echo asset_url('css/images/bnt_like.png'); ?>" alt="<?php echo PAGE_TITLE; ?>" height="50" width="50" />
        </a>
    </div>

    <div style="display: none;" class="facebooks">
        <div id="inline_facebook">
            <div style="margin-top: 0px;">
                <div class="header_face"><?php echo lang('static_content_home_connect_us'); ?></div>
                <div class="face_text">
                    <p><?php echo lang('static_content_home_connect_us_follow', '', '<a href="' . $support . '" target="_blank" style="color: #fe8a03;" title="' . PAGE_TITLE . '">' . PAGE_TITLE . '</a>'); ?></p>
                    <div class="fb-like-box mt20" data-href="<?php echo $support; ?>" data-width="440" data-colorscheme="light" data-show-faces="true" data-header="true" data-stream="false" data-show-border="true"></div>
                </div>
            </div> 
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $(".facebooks").hide();
            $(".inline_face").colorbox({
                inline: true, width: "500px",
                onLoad: function() {
                    $("#cboxClose").css({'top': '20px', 'right': '20px'});
                }
            });
        });
    </script>
<?php else: ?>
    <div class="fb-like-box" data-href="<?php echo $support; ?>" data-width="300" data-colorscheme="light" data-show-faces="true" data-header="true" data-stream="false" data-show-border="true"></div>
<?php endif; ?>
