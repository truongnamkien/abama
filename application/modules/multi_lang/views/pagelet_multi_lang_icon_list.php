<ul>
    <?php foreach ($lang_list as $lang): ?>
        <?php echo Modules::run('multi_lang/_icon_lang_item', $lang); ?>
    <?php endforeach; ?>
</ul>