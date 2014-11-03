<?php $size = @getimagesize($photo); ?>
<div class="fLeft mr20 mt5 photo_content" style="height: <?php echo $size[1]; ?>px;">
    <img src="<?php echo base_url($photo); ?>" width="<?php echo $size[0]; ?>" height="<?php echo $size[1]; ?>" />
</div>
<?php echo $content; ?>

