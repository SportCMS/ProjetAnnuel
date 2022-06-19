<?php ob_start();
use App\core\Router; ?>

<h1>Login</h1>

<?php if(!empty($result)): ?>
	<div class="">
		<?php foreach($result as $uneErreur): ?>
			<p> <?=$uneErreur?> </p>
		<?php endforeach ?>
	</div>
<?php endif ?>

<?php  Router::includePartial("form", $user->getLoginForm(null)) ?>