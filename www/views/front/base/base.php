<!DOCTYPE html>
<html lang="fr">

<?php include_once './views/front/partials/head.php' ?>

<body id="body-front">
    <?php include_once './views/front/partials/header.php' ?>
    <!-- lien de redirection au dashboard si admin -->
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') : ?>
        <a class="back-dash" href="/dashboard">Revenir au dashboard</a>
    <?php endif ?>
    <main style="margin-bottom: 100px;">
        <?= $base ?>
    </main>
    <?php include_once './views/front/partials/footer.php' ?>

    
</body>

</html>