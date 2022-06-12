<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Template front</title>

        <!-- SCRIPT RECAPTCHA -->
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>

        <!-- LINK CSS -->
        <link rel="stylesheet" href="../public/dist/main.css">
        <link rel="stylesheet" href="../public/dist/login.css">
        <link rel="stylesheet" href="../public/dist/registerr.css">
        <link rel="stylesheet" href="../public/dist/login.css">

        <!-- SCRIPTS -->
        <script src="../public/src/js/vendor/jquery-3.6.0.min.js"></script>
        <script src="../public/src/js/main.js"></script>
    </head>
    <body>

    <?php 
    
    if($_SERVER['REQUEST_URI'] != '/login') :?>
        <header id="site-header">
            <div class="container">
                <button id="menu-button"></button>
                <nav id="site-nav">
                    <ul>
                        <li><a href="">Tarification</a></li>
                        <li><a href="">À propos de nous</a></li>
                        <li><a href="">Contact</a></li>
                        <li id="se_connecter"><a href="/login" class="button">Se connecter</a></li>
                    </ul>
                </nav>
            </div>
        </header>

        <?php endif?>
        <?php
            include $this->view . '.view.php';
        ?>
        <footer>
            <div class="container">
                <ul>
                    <li><a href="#">Termes et condition</a></li>
                    <li><a href="#">Politique de confidentialité</a></li>
                    <li><a href="https://github.com/SportCMS/ProjetAnnuel">Documentation</a></li>
                    <li><a href="/">Sport<span class="bottom-title-cms">CMS</span></a></li>
                </ul>
            </div>
        </footer>
    </body>
</html>