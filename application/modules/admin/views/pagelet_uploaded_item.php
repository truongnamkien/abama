<div class="fLeft mv10 mr10 border rounded5 pa10" id="file_<?php echo $photo_id; ?>">
	<img class="fLeft product_photo" src="<?php echo $photo_path; ?>" id="<?php echo $photo_id; ?>" width="60" />
	<a href="#" class="photo_remove" title="<?php echo lang('product_photo_remove'); ?>" rel="async" ajaxify="<?php echo site_url('ajax/upload_ajax/remove_upload/' . $photo_id . '/' . $type); ?>">
        <img class="fLeft ml10" style="left: 10px; top: 10px;" src="<?php echo asset_url('images/admin/icons/cross_grey_small.png'); ?>" />
	</a>
</div>

<?php if ($type == 'product_photo'): ?>
	<input type="hidden" name="product_photo[]" value="<?php echo $photo_id; ?>" />
<?php endif; ?>

