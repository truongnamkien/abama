<div class="listbrand">
    <ul id="slider_brand" class="slider_brand_item">
        <?php foreach ($branch_list as $branch): ?>
            <li>
                <?php $photo_url = Modules::run('photo/_get_photo_path', $branch['url'], 110); ?>
                <img src="<?php echo base_url($photo_url); ?>" width="110" height="50" alt="<?php echo $branch['name']; ?>" />
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#slider_brand').carouFredSel({
            width: 750,
            items: 5,
            scroll: 1,
            auto: {
                duration: 700,
                timeoutDuration: 2000
            }
        });
    });
</script>