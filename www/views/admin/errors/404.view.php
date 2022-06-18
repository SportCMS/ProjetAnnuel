<?php ob_start(); ?>

<img src="public/assets/images/yellow.png" alt="yellow" width="100" height="100">
<h2>Ooops! page non trouvée, elle à peut être été supprimée ou est temporairempent indisponible<br>
</h2>
<a href="/dashboard">Retour dans votre camp</a>



<?php $content = ob_get_clean(); ?>
<?php require('./views/admin/base/base.php'); ?>