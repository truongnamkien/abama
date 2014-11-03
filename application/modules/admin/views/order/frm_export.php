<div class="btn-group">
    <button class="btn btn-default btn-minimize"><?php echo lang('product_order_status'); ?></button>
    <button class="btn dropdown-toggle btn-default btn-minimize" data-toggle="dropdown"><span class="caret"></span></button>
    <ul class="dropdown-menu">
        <?php foreach ($status_list as $status): ?>
            <li>
                <a href="<?php echo site_url('admin/order/index/0/' . $status); ?>"><?php echo lang('product_order_status_' . $status); ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><?php echo lang('product_order_export') ?></h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
                </div>
            </div>

            <div class="box-content">
                <?php echo form_open_multipart('admin/order/export'); ?>
                <div class="form-group">
                    <label class="control-label" for="from_time"><?php echo lang('product_order_export_from_time'); ?></label>
                    <div class="input-group col-md-4">
                        <span class="input-group-addon">
                            <i class="glyphicon glyphicon-calendar"></i>
                        </span>
                        <input type="text" class="datepicker form-control" value="<?php echo date('d/m/Y'); ?>" id="from_time" name="from_time" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label" for="to_time"><?php echo lang('product_order_export_to_time'); ?></label>
                    <div class="input-group col-md-4">
                        <span class="input-group-addon">
                            <i class="glyphicon glyphicon-calendar"></i>
                        </span>
                        <input type="text" class="datepicker form-control" value="<?php echo date('d/m/Y'); ?>" id="to_time" name="to_time" />
                    </div>
                </div>
                <button type="submit" class="btn btn-default"><?php echo lang('admin_action_update'); ?></button>
                <?php echo form_close(); ?>
                <div class="clear"></div>
            </div>
        </div>
    </div>
    <!--/span-->
</div><!--/row-->

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

        $('.datepicker').datepicker({
            inline: true
        });
    });
</script>