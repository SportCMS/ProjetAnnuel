<?php ob_start();
use App\core\Router; ?>

<?php if(isset($errors)) : ?>
	<?php foreach ($errors as $error): ?>
		<div class="alert">
			<p class="errormsg"><ion-icon name="close-circle-outline"></ion-icon><?= $error ?></p>
		</div>
	<?php endforeach; ?>
<?php endif; ?>	

<div class="form_container" style="margin-top:1rem; margin-bottom:100px">
<img class="logo__icon" src="../public/assets/images/SportCMS.png" alt="logo SPORT-CMS">

<?php  Router::includePartial("form", $user->getRegisterForm(null)) ?>
</div>

<?php if(isset($_SESSION['success'])) : ?>
		<div class="alert">
			<p class="successmsg"><ion-icon name="checkmark-circle-outline"></ion-icon><?= $_SESSION['success'] ?></p>
		</div>
<?php endif; ?>

<?php $base = ob_get_clean(); ?>
<?php require('./views/front/base/baseSecurity.php'); ?>