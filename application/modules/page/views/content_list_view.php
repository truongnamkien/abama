<?php $total = 0; ?>
<?php foreach ($contents as $content_id => $content): ?>
    <div id="<?php echo ('content_' . $content_id); ?>" class="content_block lineh18 mt10">
        <?php echo $content; ?>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
    <?php $total++; ?>
<?php endforeach; ?>
