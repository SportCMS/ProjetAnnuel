<?php ob_start(); ?>


<h1>articles front</h1>

<?php if (isset($all_article)) : ?>

    <?php foreach ($all_article as $article => $value) : ?>
        <h1><?= ucfirst($value['title']) ?></h1>
        <p><?= substr($value['content'], 0, 40) . '...' ?></p>
        <hr>
    <?php endforeach ?>
<?php endif; ?>

<?php $base = ob_get_clean(); ?>
<?php require('./views/front/base/base.php'); ?>