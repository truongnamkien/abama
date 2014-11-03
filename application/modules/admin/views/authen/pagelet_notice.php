<?php if (!empty($notice_list)): ?>
    <div class="row">
        <div class="box col-md-12">
            <?php foreach ($notice_list as $notice): ?>
                <div class="alert alert-<?php echo $notice['type']; ?>">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <div><?php echo $notice['content']; ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>