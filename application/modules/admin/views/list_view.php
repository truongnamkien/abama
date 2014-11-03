<?php if (isset($main_nav) && !empty($main_nav)): ?>
    <?php foreach ($main_nav as $key => $nav): ?>
        <a class="btn btn-default btn-sm" href="<?php echo $nav['url']; ?>">
            <i class="glyphicon glyphicon-<?php echo $nav['icon']; ?>"></i>
            <?php echo lang('admin_action_' . $key); ?>
        </a>
    <?php endforeach; ?>
<?php endif; ?>

<div class="row">
    <div class="box col-md-12 center-block">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><?php echo lang($type . '_manager_title') ?></h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
                </div>
            </div>
            <div class="box-content">
                <?php if (empty($objects)) : ?>
                    <?php echo lang('empty_list_data'); ?>
                <?php else: ?>
                    <div class="mb20">
                        <?php $has_search = FALSE; ?>

                        <?php echo form_open_multipart('admin/' . $type . '/index', array('id' => 'searching_form')); ?>
                        <?php if (isset($search_fields) && !empty($search_fields)): ?>
                            <?php $has_search = TRUE; ?>
                            <div class="input-group w25p fLeft mr10">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-search red"></i></span>
                                <input class="form-control" autocomplete="off" name="keyword" id="keyword" value="<?php echo $keyword; ?>" placeholder="<?php echo lang('admin_search_placeholder'); ?>" type="text" />
                            </div>
                        <?php endif; ?>

                        <?php if (isset($select_fields) && !empty($select_fields)): ?>
                            <?php foreach ($select_fields as $field => $options): ?>
                                <?php if (!empty($options)): ?>
                                    <?php $has_search = TRUE; ?>
                                    <div class="btn-group fLeft mr10">
                                        <button id="<?php echo $field . '_label'; ?>" class="btn btn-default btn-minimize"><?php echo lang($type . '_' . $field); ?></button>
                                        <button class="btn dropdown-toggle btn-default btn-minimize" data-toggle="dropdown"><span class="caret"></span></button>
                                        <ul class="dropdown-menu">
                                            <?php foreach ($options as $val => $label): ?>
                                                <li class="<?php echo ($this->input->get_post($field) == $val ? "active" : ""); ?>">
                                                    <a data-value="<?php echo $val; ?>" class="<?php echo $field . '_link'; ?>" href="#"><?php echo $label; ?></a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    <input id="<?php echo $field . '_input'; ?>" type="hidden" name="<?php echo $field; ?>" value="<?php echo $this->input->get_post($field); ?>" />
                                    <script type="text/javascript">
                                        $(document).ready(function() {
                                            $('a.<?php echo $field . '_link'; ?>').click(function(e) {
                                                var value = $(this).attr('data-value');
                                                $('#<?php echo $field . '_input'; ?>').val(value);
                                                var label = $(this).html();
                                                $('#<?php echo $field . '_label'; ?>').html(label);
                                                e.preventDefault();
                                            });
                                        });
                                    </script>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <?php if ($has_search): ?>
                            <input id="search_btn" class="btn btn-primary btn-minimize fLeft" type="submit" value="<?php echo lang('admin_search_btn'); ?>" />
                        <?php endif; ?>
                        <div class="clear"></div>
                        <?php echo form_close(); ?>
                    </div>				

                    <table class="table table-striped table-bordered responsive">
                        <thead>
                            <tr>
                                <th><input class="check-all" type="checkbox" /></th>
                                <?php foreach (array_keys($objects[0]) as $key): ?>
                                    <th><?php echo lang($type . '_' . $key); ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($objects as $obj): ?>
                                <?php $obj_id = $obj[get_object_key($obj, $type)]; ?>
                                <tr>
                                    <td><input type="checkbox" value="<?php echo $obj_id; ?>" /></td>
                                    <?php foreach ($obj as $key => $val): ?>
                                        <td>
                                            <?php if ($key == 'actions' && is_array($val)): ?>
                                                <?php foreach ($val as $key => $action): ?>
                                                    <a class="mb5 btn btn-<?php echo $action['button']; ?>" href="<?php echo $action['url']; ?>" title="<?php echo lang('admin_action_' . $key); ?>">
                                                        <i class="glyphicon glyphicon-<?php echo $action['icon']; ?> icon-white"></i>
                                                    </a>
                                                <?php endforeach; ?>
                                            <?php elseif ($val == STATUS_ACTIVE): ?>
                                                <span class="label-success label label-default"><?php echo lang('admin_status_' . $val); ?></span>
                                            <?php elseif ($val == STATUS_INACTIVE): ?>
                                                <span class="label-danger label label-default"><?php echo lang('admin_status_' . $val); ?></span>
                                            <?php else: ?>
                                                <?php echo $val; ?>
                                            <?php endif; ?>
                                        </td>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                        </tbody>
                    </table>

                    <?php if (isset($pagination) && $pagination): ?>
                        <ul class="pagination pagination-centered">
                            <?php echo $this->pagination->create_links(); ?>
                        </ul>
                    <?php endif; ?>

                    <?php if (isset($mass_action_options) && !empty($mass_action_options)): ?>
                        <?php echo form_open_multipart('admin/' . $type . '/mass', array('id' => 'frm_mass_action')); ?>
                        <?php echo form_hidden('ids'); ?>
                        <?php echo form_hidden('mass_action_dropdown'); ?>

                        <div class="btn-group">
                            <button class="btn btn-default btn-minimize"><?php echo lang('admin_mass_submit'); ?></button>
                            <button class="btn dropdown-toggle btn-default btn-minimize" data-toggle="dropdown"><span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <?php foreach ($mass_action_options as $val => $label): ?>
                                    <li>
                                        <a data-value="<?php echo $val; ?>" class="mass_link" href="#"><?php echo $label; ?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php echo form_close(); ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!--/span-->

</div><!--/row-->

<script type="text/javascript">
    $(document).ready(function() {
        $('#search_btn').click(function(e) {
            e.preventDefault();
            $('#searching_form').submit();
        });
        
        $('a.mass_link').click(function(e) {
            var value = $(this).attr('data-value');
            $('input[name="mass_action_dropdown"]').val(value);
            e.preventDefault();
            $('#frm_mass_action').submit();
        });

        $('#frm_mass_action').submit(function(e) {
            var _checkboxs = $('.table tbody').find('input[type="checkbox"]');
            var _ids = "";
            for (var i = 0; i < _checkboxs.length; i++) {
                if (_checkboxs[i].checked) {
                    if (_ids == "") {
                        _ids = _checkboxs[i].value;
                    } else {
                        _ids += "," + _checkboxs[i].value;
                    }
                }
            }
            if (_ids == "") {
                show_alert('<?php echo lang('admin_mass_select_empty') ?>');
            } else {
                var _result = confirm('<?php echo lang('admin_confirm_content'); ?>');
                if (_result) {
                    $('input[name="ids"]').val(_ids);
                    return true;
                }
            }
            e.preventDefault();
            return false;
        });

        // Check all checkboxes when the one in a table head is checked:
        $('.check-all').click(function() {
            $(this).parent().parent().parent().parent().find("input[type='checkbox']").attr('checked', $(this).is(':checked'));
        });
    });
</script>
