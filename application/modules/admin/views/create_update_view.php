<?php if (isset($main_nav) && !empty($main_nav)): ?>
    <?php foreach ($main_nav as $key => $nav): ?>
        <a class="btn btn-default btn-sm" href="<?php echo $nav['url']; ?>">
            <i class="glyphicon glyphicon-<?php echo $nav['icon']; ?>"></i>
            <?php echo lang('admin_action_' . $key); ?>
        </a>
    <?php endforeach; ?>
<?php endif; ?>

<div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><?php echo lang('admin_action_' . $action) ?></h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
                </div>
            </div>

            <div class="box-content">
                <?php echo form_open_multipart('admin/' . $type . '/' . $action . '/' . (isset($id) ? $id : ''), array('id' => 'create_update_form', 'role' => 'form')); ?>
                <?php foreach ($form_data['object'] as $key => $val): ?>
                    <?php if (!isset($form_data['specific_input'][$key])): ?>
                        <div class="form-group">
                            <label class="control-label" for="<?php echo $key; ?>"><?php echo lang($type . '_' . $key); ?></label>
                            <input type="text" class="form-control" value="<?php echo $val; ?>" id="<?php echo $key; ?>" name="<?php echo $key; ?>" />
                        </div>
                    <?php elseif ($form_data['specific_input'][$key]['input'] == 'datepicker'): ?>
                        <div class="form-group">
                            <label class="control-label" for="<?php echo $key; ?>"><?php echo lang($type . '_' . $key); ?></label>
                            <input type="text" class="form-control datepicker" value="<?php echo $val; ?>" id="<?php echo $key; ?>" name="<?php echo $key; ?>" />
                        </div>
                    <?php elseif ($form_data['specific_input'][$key]['input'] == 'suffix'): ?>
                        <div class="form-group">
                            <label class="control-label" for="<?php echo $key; ?>"><?php echo lang($type . '_' . $key); ?></label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="<?php echo $val; ?>" id="<?php echo $key; ?>" name="<?php echo $key; ?>" />
                                <span class="input-group-addon">
                                    <?php if (isset($form_data['specific_input'][$key]['icon'])): ?>
                                        <i class="glyphicon glyphicon-<?php echo $form_data['specific_input'][$key]['icon']; ?>"></i>
                                    <?php else: ?>
                                        <?php echo $form_data['specific_input'][$key]['extra']; ?>
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    <?php elseif ($form_data['specific_input'][$key]['input'] == 'prefix'): ?>
                        <div class="form-group">
                            <label class="control-label" for="<?php echo $key; ?>"><?php echo lang($type . '_' . $key); ?></label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <?php if (isset($form_data['specific_input'][$key]['icon'])): ?>
                                        <i class="glyphicon glyphicon-<?php echo $form_data['specific_input'][$key]['icon']; ?>"></i>
                                    <?php else: ?>
                                        <?php echo $form_data['specific_input'][$key]['extra']; ?>
                                    <?php endif; ?>
                                </span>
                                <input type="text" class="form-control" value="<?php echo $val; ?>" id="<?php echo $key; ?>" name="<?php echo $key; ?>" />
                            </div>
                        </div>
                    <?php elseif ($form_data['specific_input'][$key]['input'] == 'radio_image'): ?>
                        <div class="form-group">
                            <label class="control-label" for="<?php echo $key; ?>"><?php echo lang($type . '_' . $key); ?></label>
                            <div>
                                <?php foreach ($form_data['specific_input'][$key]['options'] as $option_val => $option_key): ?>
                                    <div class="fLeft ma5 tac border rounded pa5">
                                        <?php echo form_radio($key, $option_val, ($val !== FALSE && $val !== '' && $val == $option_val), 'id="radio_' . $key . '_' . $option_val . '"'); ?>
                                        <div class="clear"></div>
                                        <?php echo form_label($option_key, 'radio_' . $key . '_' . $option_val); ?>
                                    </div>
                                <?php endforeach; ?>
                                <div class="clear"></div>
                            </div>
                        </div>
                    <?php elseif ($form_data['specific_input'][$key]['input'] == 'checkbox_toggle'): ?>
                        <div class="form-group">
                            <label class="control-label" for="<?php echo $key; ?>"><?php echo lang($type . '_' . $key); ?></label>
                            <input data-no-uniform="true" <?php echo ($val == STATUS_ACTIVE ? 'checked="checked"' : ''); ?> type="checkbox" class="iphone-toggle" id="<?php echo $key; ?>" name="<?php echo $key; ?>" />
                        </div>
                    <?php elseif ($form_data['specific_input'][$key]['input'] == 'none'): ?>
                        <div class="form-group">
                            <label class="control-label" for="<?php echo $key; ?>"><?php echo lang($type . '_' . $key); ?></label>
                            <br />
                            <?php echo $val; ?>
                        </div>
                    <?php elseif ($form_data['specific_input'][$key]['input'] == 'textarea'): ?>
                        <div class="form-group">
                            <label class="control-label" for="<?php echo $key; ?>"><?php echo lang($type . '_' . $key); ?></label>
                            <?php echo form_textarea($key, $val); ?>
                        </div>
                    <?php elseif ($form_data['specific_input'][$key]['input'] == 'upload'): ?>
                        <div class="form-group">
                            <label class="control-label" for="<?php echo $key; ?>"><?php echo lang($type . '_' . $key); ?></label>
                            <input type="file" id="<?php echo $key; ?>" name="<?php echo $key; ?>" />
                        </div>
                    <?php elseif ($form_data['specific_input'][$key]['input'] == 'dropdown'): ?>
                        <div class="form-group">
                            <label class="control-label" for="<?php echo $key; ?>"><?php echo lang($type . '_' . $key); ?></label>
                            <div class="controls">
                                <select id="<?php echo $key; ?>" name="<?php echo $key; ?>" data-rel="chosen">
                                    <?php foreach ($form_data['specific_input'][$key]['options'] as $option_val => $option_key): ?>
                                        <option value="<?php echo $option_val; ?>" <?php echo ($option_val == $val ? 'selected="selected"' : ''); ?>><?php echo $option_key; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
                <button type="submit" class="btn btn-default"><?php echo lang('admin_action_' . $action); ?></button>
                <?php echo form_close(); ?>
                <div class="clear"></div>
            </div>
        </div>
    </div>
    <!--/span-->
</div><!--/row-->

<script type="text/javascript">
    $(document).ready(function() {
        $('#create_update_form').submit(function(e) {
            if ($('#uploaded_files').length > 0 && $('#uploaded_files').find('.product_photo').length == 0) {
                show_alert('<?php echo lang('admin_multi_upload_error'); ?>');
                e.preventDefault();
                return false;
            }
        });
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
        $('.datepicker').datepicker({
            inline: true
        });
    });
</script>