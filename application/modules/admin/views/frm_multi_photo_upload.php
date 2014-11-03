<?php echo asset_link_tag('multi_upload/uploadfile.css'); ?>
<?php echo asset_js('multi_upload/jquery.uploadfile.min.js') ?>

<div id="uploaded_files">
    <?php if (isset($photo_list) && !empty($photo_list)): ?>
        <?php foreach ($photo_list as $file): ?>
            <?php echo Modules::run('admin/upload/render', $file); ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<div class="clear"></div>

<div id="mulitplefileuploader">Upload</div>

<script>
    $(document).ready(function() {
        var settings = {
            url: "<?php echo base_url('upload.php'); ?>",
            method: "POST",
            allowedTypes: "jpg,png,gif,doc,pdf,zip",
            fileName: "myfile",
            multiple: true,
            onSuccess: function(files, data, xhr) {
                var _uri = _admin.ajax_url + '/upload_ajax/render/' + files[0];
                AsyncRequest.bootstrap(new URI(_uri));
            }, onError: function(files, status, errMsg) {
                show_alert('<?php echo lang('product_photo_upload_error'); ?>');
            }
        }
        $("#mulitplefileuploader").uploadFile(settings);
    });
</script>