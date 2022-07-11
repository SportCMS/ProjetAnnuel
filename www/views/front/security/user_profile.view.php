<?php ob_start();
use App\core\Router; ?>

<div style="display:flex; align-items:flex-start; margin-top: 1em">
	<ul class="card" style="margin-left: 1em;">
		<li class="card__active"><a href="/user-profile">Profil</a></li>
		<li><a href="/user-profilePwd">Changer le mot de passe</a></li>
	</ul>
	<div class="form_container">
		<h1> Votre profil </h1>
		<?php  Router::includePartial("form", $user->getUserProfileForm(null)); ?>
	</div>
</div>

<?php if(isset($errors)) : ?>
	<?php foreach ($errors as $error): ?>
		<div class="alert">
			<p class="errormsg"><ion-icon name="close-circle-outline"></ion-icon><?= $error ?></p>
		</div>
	<?php endforeach; ?>
<?php endif; ?>	

<?php if(isset($infos)) : ?>
	<?php foreach ($infos as $info): ?>
		<div class="alert">
			<p class="successmsg"><ion-icon name="checkmark-circle-outline"></ion-icon><?= $info ?></p>
		</div>
	<?php endforeach; ?>
<?php endif; 

if ($user->getRole() == 'admin'){
    $content = ob_get_clean();
    require('./views/admin/base/base.php');
}

if ($user->getRole() == 'user'){
    $base = ob_get_clean();
    require('./views/front/base/base.php');
}



