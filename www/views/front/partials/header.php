<?php

use App\models\MenuItem;
?>

<header id="site-header">
    <div class="container" style="display: flex; justify-content: space-between; align-items:center">
        <div>
            <?php if(isset($_SESSION['email'])):?>
                <a href="user-profile" style="text-decoration:none; font-weight: 700;">
                    <span style="padding: 10px; background-color: blueviolet; color: white; border: solid 1px grey; border-radius: 50%;">
                        <?= ucfirst(substr($_SESSION['firstname'],0,1)).ucfirst(substr($_SESSION['lastname'],0,1));?>
                    </span>
                </a>
            <?php endif;?>
            <button id="menu-button"></button>
        </div>

        <nav id="site-nav">
            <ul>
                <?php foreach ((new MenuItem())->getAllByPosition() as $link) : ?>
                    <li><a href="<?= strtolower($link['link']) ?>"><?= $link['name'] ?></a></li>
                <?php endforeach ?>
            </ul>
        </nav>
        <?php if (isset($_SESSION['role'])) : ?>
                    <?= $_SESSION['role'] == 'user'
                        ? '<a id="logout" href="/logout" class="button" style="background-color: #2A4365;">Déconnexion</a>'
                        : '<a id="logout" href="#" class="button" style="background-color: #2A4365;">Déconnexion</a>' ?>
         <?php else : ?>
            <a id="se_connecter" href="/login" class="button"  style="background-color: #2A4365;">Se connecter</a>
        <?php endif ?>
    </div>
</header>