<?php ob_start();
use App\core\Router; ?>
<?php if(!empty($errors)): ?>
	<div class="">
		<?php foreach($errors as $error): ?>
			<p> <?=$error?> </p>
		<?php endforeach ?>
	</div>
<?php endif ?>
<div class="form_container" style="margin-top:1rem; margin-bottom:100px">
<img class="logo__icon" src="../public/assets/images/SportCMS.png" alt="logo SPORT-CMS">

<?php  Router::includePartial("form", $user->getLoginForm(null)) ?>
</div>
<?php $base = ob_get_clean(); ?>
<?php require('./views/front/base/baseSecurity.php'); ?>


