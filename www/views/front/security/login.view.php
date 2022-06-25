<?php ob_start();
use App\core\Router; ?>

<img class="logo__login" src="../public/assets/images/SportCMS.png" alt="logo SPORT-CMS">

<?php if(!empty($result)): ?>
	<div class="">
		<?php foreach($result as $uneErreur): ?>
			<p> <?=$uneErreur?> </p>
		<?php endforeach ?>
	</div>
<?php endif ?>



<?php  Router::includePartial("form", $user->getLoginForm(null)) ?>
<?php $base = ob_get_clean(); ?>
<?php require('./views/front/base/base.php'); ?>