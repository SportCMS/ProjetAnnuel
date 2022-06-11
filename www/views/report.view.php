<h1>Signaler un abus</h1>

<p>Commentaire concerné : "<?= $comment->getContent() ?>" ecrit par <?= $author->getFirstname() . ' ' . $author->getLastname() ?></p>

<?php $this->includePartial("form", $reportManager->getReportForm($params = null)); ?>

<!-- envoie des erreurs -->
<?php if (isset($result)) : ?>
    <?php foreach ($result as $key => $value) : ?>
        <p><?= $value ?></p>
    <?php endforeach; ?>
<?php endif; ?>

