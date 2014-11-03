<div class="contact_us">
    <div class="contact_note">
        <h1><?php echo lang('product_checkout_title'); ?></h1>
        <div><h2><?php echo lang('product_checkout_description'); ?></h2></div>
    </div>
    <div class="contact_form">
        <div id="loading_page">
            <div class="frm_contact">
                <div class="frm_contact_l">
					<?php echo form_open('ajax/product_ajax/checkout', array('id' => 'frm_checkout', 'rel' => 'async'), FALSE); ?>
                    <div class="ct_table">
                        <div class="ct_row">
                            <label for="fullname" class="ct_col label"><?php echo lang('product_checkout_fullname'); ?></label>
                            <div class="ct_col control">
                                <input class="w350" value="<?php echo set_value('fullname'); ?>" name="fullname" id="fullname" type="text" />
								<?php echo form_error('fullname', '<div class="error_message">', '</div>'); ?>
                            </div>
                        </div>

                        <div class="ct_row">
                            <label for="email" class="ct_col label"><?php echo lang('product_checkout_email'); ?></label>
                            <div class="ct_col control">
                                <input class="w350" value="<?php echo set_value('email'); ?>" name="email" id="email" type="text" />
								<?php echo form_error('email', '<div class="error_message">', '</div>'); ?>
                            </div>
                        </div>

                        <div class="ct_row">
                            <label for="mobile" class="ct_col label"><?php echo lang('product_checkout_mobile'); ?><span class="required"> *</span></label>
                            <div class="ct_col control">
                                <input class="w350" value="<?php echo set_value('mobile'); ?>" name="mobile" id="mobile" type="text" />
								<?php echo form_error('mobile', '<div class="error_message">', '</div>'); ?>
                            </div>
                        </div>

                        <div class="ct_row">
                            <label for="address" class="ct_col label"><?php echo lang('product_checkout_shipping_address'); ?><span class="required"> *</span></label>
                            <div class="ct_col control">
                                <input class="w350" value="<?php echo set_value('address'); ?>" name="address" id="address" type="text" />
								<?php echo form_error('address', '<div class="error_message">', '</div>'); ?>
                            </div>
                        </div>

                        <div class="ct_row">
                            <label for="delivery_time" class="ct_col label"><?php echo lang('product_order_delivery_time'); ?></label>
                            <div class="ct_col control">
                                <input class="w350" value="<?php echo set_value('delivery_time'); ?>" name="delivery_time" id="delivery_time" type="text" />
								<?php echo form_error('delivery_time', '<div class="error_message">', '</div>'); ?>
                            </div>
                        </div>

                        <div style="height: 170px" class="ct_row">
                            <label for="note" class="ct_col label"><?php echo lang('product_checkout_note'); ?></label>
                            <div class="ct_col control"> 
                                <textarea name="note" id="note" rows="10" cols="5"><?php echo set_value('note'); ?></textarea>
								<?php echo form_error('note', '<div class="error_message">', '</div>'); ?>
                            </div>
                        </div>

                        <div class="ct_row">
                            <div class="ct_col label"></div>
                            <div class="ct_col control">
                                <input class="btn" name="btnSend" id="btnSend" value="<?php echo lang('static_content_home_submit_btn'); ?>" title="<?php echo lang('product_shopping_cart_checkout'); ?>" type="submit" />
                                <input class="btn" name="btnReset" id="btnReset" value="<?php echo lang('static_content_home_reset_btn'); ?>" title="<?php echo lang('static_content_home_reset_btn'); ?>" type="reset" />
							</div>
						</div>
					</div>
				</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		jQuery(function($) {
			$.datepicker.regional["vi-VN"] =
					{
						closeText: "Đóng",
						prevText: "Trước",
						nextText: "Sau",
						currentText: "Hôm nay",
						monthNames: ["Tháng một", "Tháng hai", "Tháng ba", "Tháng tư", "Tháng năm", "Tháng sáu", "Tháng bảy", "Tháng tám", "Tháng chín", "Tháng mười", "Tháng mười một", "Tháng mười hai"],
						monthNamesShort: ["Một", "Hai", "Ba", "Bốn", "Năm", "Sáu", "Bảy", "Tám", "Chín", "Mười", "Mười một", "Mười hai"],
						dayNames: ["Chủ nhật", "Thứ hai", "Thứ ba", "Thứ tư", "Thứ năm", "Thứ sáu", "Thứ bảy"],
						dayNamesShort: ["CN", "Hai", "Ba", "Tư", "Năm", "Sáu", "Bảy"],
						dayNamesMin: ["CN", "T2", "T3", "T4", "T5", "T6", "T7"],
						weekHeader: "Tuần",
						dateFormat: "dd/mm/yy",
						firstDay: 1,
						isRTL: false,
						showMonthAfterYear: false,
						yearSuffix: ""
					};

			$.datepicker.setDefaults($.datepicker.regional["vi-VN"]);
		});

		$('#delivery_time').datepicker({
			inline: true
		});
	})
</script>

