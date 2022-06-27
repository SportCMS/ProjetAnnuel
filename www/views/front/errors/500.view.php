<?php ob_start(); ?>

<img src="public/assets/img/yellow.png" alt="" width="100" height="100">
<h2>Veuillez nous excuser pour ce petit problème technique... <br>Nous travaillons actuellement à sa résolution</h2>
<a href="/home">Retour dans votre camp</a>


<?php $base = ob_get_clean(); ?>
<?php require('./views/front/base/base.php'); ?>