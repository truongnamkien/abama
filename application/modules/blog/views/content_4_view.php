<?php $size = @getimagesize($photo); ?>
<div class="fRight photo_content" style="height: <?php echo $size[1]; ?>px;">
    <img src="<?php echo base_url($photo); ?>" width="<?php echo $size[0]; ?>" height="<?php echo $size[1]; ?>" />
</div>
<div class="fLeft w310"><?php echo $content; ?></div>
