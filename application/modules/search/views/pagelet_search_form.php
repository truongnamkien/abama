<form method="post" name="frsearch" id="frmsearch" action="<?php echo site_url('search'); ?>">
    <div class="frm">
        <input id="query" name="query" placeholder="<?php echo lang('search_frm_placeholder'); ?>" type="text" />
        <input id="btsearch" name="btsearch" value="" type="submit">
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function() {
        $('#frmsearch').live('submit', function(e) {
            var query = $.trim($("#query").val());
            if (query == '' || query == '<?php echo lang('search_frm_placeholder'); ?>') {
                e.preventDefault();
                show_alert('<?php echo lang('search_frm_empty_error'); ?>');
                return false;
            }
        });
    });
</script>
