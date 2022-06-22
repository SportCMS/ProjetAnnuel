<?php ob_start();
use App\core\Router; ?>

<h1> Votre profil </h1>

<?php  Router::includePartial("form", $user->getUserProfileForm(null)) ?>
<?php $base = ob_get_clean(); ?>
<?php require('./views/front/base/base.php'); ?>