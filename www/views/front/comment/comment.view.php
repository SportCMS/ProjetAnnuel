<?php ob_start();

use App\core\Router; ?>

<h1>Poster un commentaire</h1>

<?php Router::includePartial("form", $comment->getCommentForm()); ?>

<?php $base = ob_get_clean(); ?>
<?php require('./views/front/base/base.php'); ?>