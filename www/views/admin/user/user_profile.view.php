<?php ob_start();
use App\core\Router; ?>

<h1> Votre profil </h1>

<?php  Router::includePartial("form", $user->getUserProfileForm(null)) ?>
<?php $base = ob_get_clean(); ?>


<a class="" href="/user-profilePwd">Changer le mot de passe</a>
        

<?php require('./views/front/base/base.php'); ?>

