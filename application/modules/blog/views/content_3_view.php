<?php $size = @getimagesize($photo); ?>
<div class="mb10 photo_content tac" style="height: <?php echo $size[1]; ?>px;">
    <img src="<?php echo base_url($photo); ?>" width="<?php echo $size[0]; ?>" height="<?php echo $size[1]; ?>" />
</div>
<div class="mb10"><?php echo $content; ?></div>
