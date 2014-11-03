<meta property="og:title" content="<?php echo PAGE_TITLE; ?>" />
<meta property="og:site_name" content="<?php echo PAGE_TITLE; ?>" />
<meta property="og:url" content="<?php echo $url; ?>" />

<meta property="og:image" content="<?php echo $photo_path; ?>" />
<link rel="image_src" href="<?php echo $photo_path; ?>" />

<meta property="og:description" content="<?php echo htmlspecialchars($description); ?>" />
<meta name="description" content="<?php echo htmlspecialchars($description); ?>"/>

<?php if (isset($keyword) && !empty($keyword)): ?>
    <meta name="keywords" content="<?php echo $keyword; ?>"/>
<?php endif; ?>

<?php if (isset($display) && !empty($display) && isset($keyword) && !empty($keyword)): ?>
    <script type="text/javascript">
        $(document).ready(function() {
            show_alert("<?php echo $keyword; ?>");
        });
    </script>
<?php endif; ?>