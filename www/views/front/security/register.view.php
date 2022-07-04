<?php ob_start();
use App\core\Router; ?>
<div class="form_container">
<img class="logo__icon" src="../public/assets/images/SportCMS.png" alt="logo SPORT-CMS">

<?php  Router::includePartial("form", $user->getRegisterForm(null)) ?>
</div>
<p><span> <?= isset($_SESSION['success'])? $_SESSION['success'] : '' ?></span></p>

<?php if(isset($errors)) : ?>
	<?php foreach ($errors as $error): ?>

		<p><?= $error ?></p>
	
	<?php endforeach; ?>
<?php endif; ?>	
<?php $base = ob_get_clean(); ?>
<?php require('./views/front/base/base.php'); ?>