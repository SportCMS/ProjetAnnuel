<?php ob_start();
use App\core\Router; ?>

<?php if(!empty($errors)): ?>
	<div class="">
		<?php foreach($errors as $error): ?>
			<p> <?=$error?> </p>
		<?php endforeach ?>
	</div>
<?php endif ?>

<div class="form_container">
    <h1>Mot de passe oublié ?</h1>
    <?php Router::includePartial("form", $user->getForgetPswdForm()) ?>
</div>

<?php $base = ob_get_clean(); ?>
<?php require('./views/front/base/base.php'); ?>