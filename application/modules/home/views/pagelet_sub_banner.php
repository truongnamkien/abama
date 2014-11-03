<?php $photo_path = Modules::run('photo/_get_photo_path', $photo['photo'], $photo['width']); ?>
<a href="<?php echo site_url(); ?>" title="<?php echo PAGE_TITLE; ?>">
    <img src="<?php echo base_url($photo_path); ?>" <?php echo (isset($photo['height']) && !empty($photo['height']) ? 'height="' . $photo['height'] . '"' : ''); ?> width="<?php echo $photo['width']; ?>" />
</a>
