<div class="row">
    <div class="box col-md-12 center-block">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><?php echo lang('manager_config') ?></h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
                </div>
            </div>
            <div class="box-content">
                <table class="table table-striped table-bordered responsive">
                    <tbody>
                        <?php $count = 0; ?>
                        <?php foreach ($config_list as $type => $input): ?>
                            <?php if ($count % 3 == 0): ?>
                                <tr>
                                <?php endif; ?>
                                <td>
                                    <a href="<?php echo site_url('admin/config/edit/' . $type); ?>"><?php echo lang('static_content_config_' . $type); ?></a>
                                </td>
                                <?php if ($count + 1 % 3 == 0 || $count + 1 == count($config_list)): ?>
                                <tr>
                                <?php endif; ?>
                                <?php $count++; ?>
                            <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
