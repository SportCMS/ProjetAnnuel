<?php ob_start(); ?>

<section id="section1">
    <div class="container">
        <div>
            <h1 class="title-animation">Sport<span class="head-title-cms">CMS</span></h1>
            <h2>Une nouvelle façon de construire l'image de son club</h2>
            <a href="/admin-inscription" id="start" class="button">Commencez gratuitement</a>
        </div>
    </div>
</section>

<section id="section2">
    <div class="slider">
        <img src="assets/images/outils.png" alt="Erreur lors du chargment de l'image">
        <img src="assets/images/Abonnement.png" alt="Erreur lors du chargment de l'image">
        <img src="assets/images/Dashboard.png" alt="Erreur lors du chargment de l'image">
    </div>
</section>


<?php $base = ob_get_clean(); ?>
<?php require('./views/front/base/baseSecurity.php'); ?>