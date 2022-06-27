<?php ob_start(); ?>
<h1>Signalements</h1>

<?php if(isset($reports)) :?>

<?php foreach ($reports as $report) : ?>
    <span>commentaire n° : <?= $report->getCommentId() ?></span><br>
    <span>utilisateur : <?= $report->getEmail() ?></span><br>
    <span>Message concerné : <?= $report->getMessage() ?></span><br>
    <span>Date du message : <?= $report->getCreatedAt() ?></span><br>
    <hr><br>
<?php endforeach ?>
<?php else :?>
    <p>il n'y pas de signalements</p>
<?php endif; ?>

<?php $content = ob_get_clean(); ?>
<?php require('./views/admin/base/base.php'); ?>