<?php

use App\Helpers\DateHelper;
?>
<header style="padding-left:10px; padding-right:20px">
    <div class="search">
        <input type="text" class="large" placeholder="Rechercher un utilisateur" id="search">
    </div>
    <div class="profil">
        <a href="user-profile">
            <?php if(isset($_SESSION['email'])):?>
                <span style="padding: 10px; background-color: red; color: white; border: solid 1px grey; border-radius: 50%;">
                    <?= ucfirst(substr($_SESSION['firstname'],0,1)).ucfirst(substr($_SESSION['lastname'],0,1));?>
                </span>
                <p> connecté en tant que <?= $_SESSION['email'] ?></p>
                <?php endif;?> 
        </a>
    </div>
    <?= DateHelper::dateConverter('dayDate') ?>
    <div style="display:flex">
        <div class="report-notifications" style="position:relative">
            <a href="/reports">
                <img src="public/assets/images/bell.svg" alt="" width="25" height="25">
            </a>
            <?php if ($_SESSION['report'] > 0) : ?>
                <span style="display:block;padding:2px 7px;border-radius:50%;color:white;background:red;position:absolute;top:-4px;left:15px">
                    <?= $_SESSION['report'] ?>
                </span>
            <?php endif  ?>
        </div>
        &nbsp;
        &nbsp;
        &nbsp;
        <div style="display:flex;align-items:center">
            <a href="/gerer-mes-messages" style="padding-top:4px">
                <img src="public/assets/images/mail.png" alt="" width="25" height="25">
            </a>
            <span>(<?= $_SESSION['contact'] ?>)</span>
        </div>
    </div>

    <div class="l-navbar" id="navbar">
        <nav class="nav">
            <div>
                <div class="nav__brand">

                    <ion-icon name="menu-outline" class="nav__toggle" id="nav-toggle"></ion-icon>
                    <a href="#" class="nav__logo">Sport<span id="t-cms">CMS</span></a>
                </div>
                <div class="nav__list">
                    <a href="/dashboard" class="nav__link <?= $_SERVER['REQUEST_URI'] == '/dashboard' ? 'active-link' : '' ?>">
                        <ion-icon name="home-outline" class="nav__icon "></ion-icon>
                        <span class="nav__name active">Dashboard</span>
                    </a>
                    <a href="/gerer-mon-menu" class="nav__link <?= $_SERVER['REQUEST_URI'] == '/gerer-mon-menu' ? 'active-link' : '' ?>">
                        <ion-icon name="list-outline" class="nav__icon"></ion-icon>
                        <span class="nav__name">Gérer mon menu</span>
                    </a>
                    <a href="/gerer-mes-pages" class="nav__link <?= $_SERVER['REQUEST_URI'] == '/gerer-mes-pages' ? 'active-link' : '' ?>">
                        <ion-icon name="document-outline" class="nav__icon"></ion-icon>
                        <span class="nav__name">Gérer mes pages</span>
                    </a>

                    <a href="/gerer-mes-articles" class="nav__link <?= $_SERVER['REQUEST_URI'] == '/gerer-mes-articles' ? 'active-link' : '' ?>">
                        <ion-icon name="newspaper-outline" class="nav__icon"></ion-icon>
                        <span class="nav__name">Gérer mes articles</span>
                    </a>

                    <a href="/gerer-mes-categories" class="nav__link <?= $_SERVER['REQUEST_URI'] == '/gerer-mes-categories' ? 'active-link' : '' ?>">
                        <ion-icon name="football-outline" class="nav__icon"></ion-icon>
                        <span class="nav__name">Gérer mes categories</span>
                    </a>

                    <a href="/voir-les-utilisateurs" class="nav__link <?= $_SERVER['REQUEST_URI'] == 'voir-les-utilisateurs' ? 'active-link' : '' ?>">
                        <ion-icon name="person-outline"></ion-icon>
                        <span class="nav__name">Gérer mes utilisateurs</span>
                    </a>

                    <a href="#" class="nav__link <?= $_SERVER['REQUEST_URI'] == '/' ? 'active-link' : '' ?>">
                        <ion-icon name="settings-outline" class="nav__icon" style="font-size:14px;"></ion-icon>
                        <span class="nav__name">Paramètres</span>
                    </a>
                </div>
            </div>

            <a href="/logout?account=admin" class="nav__link">
                <ion-icon name="log-out-outline" class="nav__icon"></ion-icon>
                <span class="nav__name">Déconnexion</span>
            </a>
        </nav>
    </div>
</header>