<p id="back-top">
    <a id="back-top-btn" href="#"><span></span></a>
</p>

<script type="text/javascript">
    $(document).ready(function() {
        if ($(window).scrollTop() > 100) {
            $('#back-top').fadeIn('slow');
        }
        $(document).scroll(function() {
            if ($(window).scrollTop() > 100) {
                $('#back-top').fadeIn('slow');
            } else {
                $('#back-top').fadeOut('slow');
            }
        });

        $('#back-top-btn').click(function(e) {
            $('html, body').animate({scrollTop: 0}, 500);
            e.preventDefault();
            return false;
        });
    });
</script>
