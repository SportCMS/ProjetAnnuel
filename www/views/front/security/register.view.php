<?php ob_start();
use App\core\Router; ?>

<h1>Inscrit</h1>

<?php  Router::includePartial("form", $user->getRegisterForm(null)) ?>