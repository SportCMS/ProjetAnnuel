<?php ob_start();

use App\core\Router; ?>

<h1>Signaler un abus</h1>

<p>Commentaire concerné : "<?= $comment->getContent() ?>" ecrit par <?= $author->getFirstname() . ' ' . $author->getLastname() ?></p>

<?php Router::includePartial('form', $reportManager->getReportForm(null)) ?>


<!-- envoie des erreurs -->
<?php if (isset($result)) : ?>
    <?php foreach ($result as $key => $value) : ?>
        <p><?= $value ?></p>
    <?php endforeach; ?>
<?php endif; ?>

<?php $base = ob_get_clean(); ?>
<?php require('./views/front/base/base.php'); ?>