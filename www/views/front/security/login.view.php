<?php ob_start();
use App\core\Router; ?>
<?php if(!empty($errors)): ?>
	<div class="">
		<?php foreach($errors as $error): ?>
			<p> <?=$error?> </p>
		<?php endforeach ?>
	</div>
<?php endif ?>
<img class="logo__login" src="../public/assets/images/SportCMS.png" alt="logo SPORT-CMS">





<?php  Router::includePartial("form", $user->getLoginForm(null)) ?>
<?php $base = ob_get_clean(); ?>
<?php require('./views/front/base/base.php'); ?>