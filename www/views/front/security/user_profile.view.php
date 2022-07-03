<?php ob_start();
use App\core\Router; ?>
<a class="active" href="/user-profile">Profil</a>
<a class="" href="/user-profilePwd">Changer le mot de passe</a>
<h1> Votre profil </h1>
<?php  Router::includePartial("form", $user->getUserProfileForm(null)); ?>

<?php if(isset($errors)) : ?>
	<?php foreach ($errors as $error): ?>

		<p><?= $error ?></p>
	
	<?php endforeach; ?>
<?php endif; ?>	

<?php if(isset($infos)) : ?>
	<?php foreach ($infos as $info): ?>

		<p><?= $info ?></p>
	
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


