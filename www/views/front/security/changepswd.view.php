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
    <h1>Change password</h1>
    <?php Router::includePartial("form", $user->getChangePswdForm()) ?>
</div>

<?php $base = ob_get_clean(); ?>
<?php require('./views/front/base/base.php'); ?>