<?php ob_start();
use App\core\Router; ?>
<div style="display:flex; align-items:flex-start;">
    <ul class="card">
        <li><a class="" href="/user-profile">Profil</a></li>
        <li class="card__active"><a href="/user-profilePwd">Changer le mot de passe</a></li>
    </ul>
    <div class="form_container">
        <h1> Mot de passe </h1>
        <?php  Router::includePartial("form", $user->getUserPwdForm(null)) ?>
    </div>
</div>
<?php if(isset($errors)) : ?>
	<?php foreach ($errors as $error): ?>

		<p><?= $error ?></p>
	
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



