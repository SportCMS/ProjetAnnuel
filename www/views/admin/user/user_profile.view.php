<?php ob_start();
use App\core\Router; ?>
<a class="active" href="/user-profile">Profil</a>
<a class="" href="/user-profilePwd">Changer le mot de passe</a>
<h1> Votre profil </h1>
<?php  Router::includePartial("form", $user->getUserProfileForm(null)) ?>
<p><span> <?= isset($_SESSION['succes'])? $_SESSION['succes'] : '' ?></span></p>

<?php $base = ob_get_clean(); ?>
<?php require('./views/front/base/base.php'); ?>

