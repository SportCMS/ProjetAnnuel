<?php ob_start();
use App\core\Router; ?>

<?php if(!empty($errors)): ?>
	<?php foreach ($errors as $error): ?>
		<div class="alert">
			<p class="errormsg"><ion-icon name="close-circle-outline"></ion-icon><?= $error ?></p>
		</div>
	<?php endforeach; ?>
<?php endif ?>

<div class="form_container">
    <h1>Mot de passe oublié ?</h1>
    <?php Router::includePartial("form", $user->getForgetPswdForm()) ?>
</div>

<?php $base = ob_get_clean(); ?>
<?php require('./views/front/base/base.php'); ?>