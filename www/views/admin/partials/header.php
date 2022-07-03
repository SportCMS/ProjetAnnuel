<header>
    <div class="search">
        <!--<a href="#"> <img src="assets/images/Group 1.png" alt=""></a>
        <input type="text" class="large" placeholder="Rechercher ce que vous souhaitez">
        <input type="text" class="small" placeholder="Rechercher">-->
    </div>
    <div class="profil">
        <a href=""><img src="assets/images/Vector.png" alt="Mail"> <span class="t-contact">Contact</span></a>
        <span class="line"></span>
        <a href="">
            <?php if(isset($_SESSION['email'])):?>
                <span style="padding: 10px; background-color: red; color: white; border: solid 1px grey; border-radius: 50%;">
                    <?= ucfirst(substr($_SESSION['firstname'],0,1)).ucfirst(substr($_SESSION['lastname'],0,1));?>
                </span>
                <?php endif;?>
            <img src="assets/images/unsplash_dcZsxUAAJXs.png" alt="Photo Profil">
        </a>
    </div>

    <div class="report-notifications" style="position:relative">
        <a href="/reports">
            <img src="public/assets/img/bell.svg" alt="" width="25" height="25">
        </a>
        <?php if ($_SESSION['report'] > 0) : ?>
            <span style="display:block;padding:2px 7px;border-radius:50%;color:white;background:red;position:absolute;top:-4px;left:15px">
                <?= $_SESSION['report'] ?>
            </span>
        <?php endif  ?>
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
                        <ion-icon name="folder-outline" class="nav__icon"></ion-icon>
                        <span class="nav__name">Gérer mon menu</span>
                    </a>
                    <a href="/gerer-mes-pages" class="nav__link <?= $_SERVER['REQUEST_URI'] == '/gerer-mes-pages' ? 'active-link' : '' ?>">
                        <ion-icon name="document-outline" class="nav__icon"></ion-icon>
                        <span class="nav__name">Gérer mes pages</span>
                    </a>

                    <a href="/gerer-mes-articles" class="nav__link <?= $_SERVER['REQUEST_URI'] == '/gerer-mes-articles' ? 'active-link' : '' ?>">
                        <ion-icon name="folder-outline" class="nav__icon"></ion-icon>
                        <span class="nav__name">Gérer mes articles</span>
                    </a>

                    <a href="/gerer-mes-categories" class="nav__link <?= $_SERVER['REQUEST_URI'] == '/gerer-mes-categories' ? 'active-link' : '' ?>">
                        <ion-icon name="folder-outline" class="nav__icon"></ion-icon>
                        <span class="nav__name">Gérer mes categories</span>
                    </a>

                    <a href="#" class="nav__link <?= $_SERVER['REQUEST_URI'] == '/' ? 'active-link' : '' ?>">
                        <ion-icon name="pie-chart-outline" class="nav__icon"></ion-icon>
                        <span class="nav__name">Statistiques</span>
                    </a>

                    <a href="#" class="nav__link <?= $_SERVER['REQUEST_URI'] == '/' ? 'active-link' : '' ?>">
                        <ion-icon name="settings-outline" class="nav__icon"></ion-icon>
                        <span class="nav__name">Paramètres</span>
                    </a>
                </div>
            </div>

            <a href="#" class="nav__link">
                <ion-icon name="log-out-outline" class="nav__icon"></ion-icon>
                <span class="nav__name">Déconnexion</span>
            </a>
        </nav>
    </div>
</header>