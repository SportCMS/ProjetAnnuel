<?php ob_start(); ?>

<section class="mainuser">
<img src="public/assets/images/red.png" alt="" width="100" height="100">
<h2>Vous ne disposez pas des droits necessaires pour consulter cette ressource.</h2>
<a href="/home">Retour dans votre camp</a>
</section>

<?php $base = ob_get_clean(); ?>
<?php require('./views/front/base/base.php'); ?>