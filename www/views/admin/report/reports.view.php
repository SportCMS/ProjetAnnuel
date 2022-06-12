<?php ob_start(); ?>
<h1>Signalements</h1>

<?php foreach ($reports as $report) : ?>
    <span>commentaire n° : <?= $report->getCommentId() ?></span><br>
    <span>utilisateur : <?= $report->getEmail() ?></span><br>
    <span>Message concerné : <?= $report->getMessage() ?></span><br>
    <span>Date du message : <?= $report->getCreatedAt() ?></span><br>
    <hr><br>
<?php endforeach ?>

<?php $content = ob_get_clean(); ?>
<?php require('./views/admin/base/base.php'); ?>