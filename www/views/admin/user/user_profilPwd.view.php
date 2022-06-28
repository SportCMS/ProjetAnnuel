<?php ob_start();
use App\core\Router; ?>
<ul>
<li><a class="" href="/user-profile">Profil</a></li>
<li><a class="active" href="/user-profilePwd">Changer le mot de passe</a></li>
</ul>
<h1> Mot de passe </h1>

<?php  Router::includePartial("form", $user->getUserPwdForm(null)) ?>
<?php $base = ob_get_clean(); ?>
<?php require('./views/front/base/base.php'); ?>