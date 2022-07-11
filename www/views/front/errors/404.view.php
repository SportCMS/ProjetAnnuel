<?php ob_start();?>

<img src="public/assets/images/yellow.png" alt="" width="100" height="100">
<h1>Erreur 404</h1>
<h2>Ooops! page non trouvée, elle à peut être été supprimée ou est temporairempent indisponible<br>
</h2>
<a href="/home">Retour dans votre camp</a>



<?php $base = ob_get_clean(); ?>
<?php require('./views/front/base/base.php'); ?>