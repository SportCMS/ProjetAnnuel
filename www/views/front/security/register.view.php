<?php ob_start();
use App\core\Router; ?>

<img class="logo__register" src="../public/assets/images/SportCMS.png" alt="logo SPORT-CMS">

<?php  Router::includePartial("form", $user->getRegisterForm(null)) ?>

<p><span> <?= isset($_SESSION['success'])? $_SESSION['success'] : '' ?></span></p>

<?php if(isset($errors)) : ?>
	<?php foreach ($errors as $error): ?>

		<p><?= $error ?></p>
	
	<?php endforeach; ?>
<?php endif; ?>	
<?php $base = ob_get_clean(); ?>
<?php require('./views/front/base/base.php'); ?>