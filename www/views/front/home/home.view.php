<?php ob_start(); ?>

<h1>Home</h1>

<?php $base = ob_get_clean(); ?>
<?php require('./views/front/base/base.php'); ?>