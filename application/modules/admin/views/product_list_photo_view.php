<div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2>
                    <i class="glyphicon glyphicon-picture"></i>
                    <?php echo lang('admin_support_product_photo'); ?>
                </h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
                </div>
            </div>

            <div class="box-content">
                <div class="mb20">
                    <?php echo form_open_multipart('admin/upload/product_list', array('id' => 'searching_form')); ?>
                    <div class="btn-group fLeft mr10">
                        <button id="product_category_id_label" class="btn btn-default btn-minimize"><?php echo lang('product_product_category_id'); ?></button>
                        <button class="btn dropdown-toggle btn-default btn-minimize" data-toggle="dropdown"><span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <?php foreach ($category_list as $val => $label): ?>
                                <li class="<?php echo ($this->input->get_post('product_category_id') == $val ? "active" : ""); ?>">
                                    <a data-value="<?php echo $val; ?>" class="product_category_id_link" href="#"><?php echo $label; ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <input id="product_category_id_input" type="hidden" name="product_category_id" value="<?php echo $this->input->get_post('product_category_id'); ?>" />

                    <input id="search_btn" class="btn btn-primary btn-minimize fLeft" type="submit" value="<?php echo lang('admin_search_btn'); ?>" />
                    <div class="clear"></div>
                    <?php echo form_close(); ?>

                    <script type="text/javascript">
                        $(document).ready(function() {
                            $('#search_btn').click(function(e) {
                                e.preventDefault();
                                $('#searching_form').submit();
                            });

                            $('a.product_category_id_link').click(function(e) {
                                var value = $(this).attr('data-value');
                                $('#product_category_id_input').val(value);
                                var label = $(this).html();
                                $('#product_category_id_label').html(label);
                                e.preventDefault();
                            });
                        });
                    </script>
                </div>				

                <br />
                <?php if (empty($photo_list)) : ?>
                    <div class="center-block">
                        <?php echo lang('empty_list_data'); ?>
                    </div>
                <?php else: ?>
                    <ul class="thumbnails gallery">
                        <?php foreach ($photo_list as $photo): ?>
                            <li class="thumbnail">
                                <a class="thumbnail_link" style="background:url(<?php echo $photo['thumbnail']; ?>)" href="<?php echo base_url($photo['image']); ?>">
                                    <img class="grayscale" src="<?php echo $photo['thumbnail']; ?>" width="100" height="70" />
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <div class="center-block">
                        <?php if (isset($pagination) && $pagination): ?>
                            <ul class="pagination pagination-centered">
                                <?php echo $this->pagination->create_links(); ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!--/span-->
</div><!--/row-->

<iframe id="download_iframe" src="" style="visibility:hidden"></iframe>

<script type="text/javascript">
    function download_photo(link) {
        var _url = link.attr('href');
        _url = _url.substring(('<?php echo base_url(); ?>').length);
        $('#download_iframe').attr('src', '<?php echo site_url('admin/upload/download?filename='); ?>' + _url);
        e.preventDefault();
    }
</script>