<?php

use App\models\MenuItem;
?>

<header id="site-header">
    <div class="container" style="display: flex; justify-content: space-between; align-items:center">
        <div class="">
            <?php if(isset($_SESSION['email'])):?>
                        <span style="padding: 10px; background-color: red; color: white; border: solid 1px grey; border-radius: 50%;">
                            <?= ucfirst(substr($_SESSION['firstname'],0,1)).ucfirst(substr($_SESSION['lastname'],0,1));?>
                        </span>
                        <?php endif;?>
        </div>

        <div>

            <!-- lien de redirection au dashboard si admin : a placer et styliser correctement, GO les fronts -->
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') : ?>
                <a href="/dashboard">Revenir au dashboard</a>
            <?php endif ?>
            <button id="menu-button"></button>
            <nav id="site-nav">

                <ul>
                    <?php foreach ((new MenuItem())->getAllByPosition() as $link) : ?>
                        <li><a href="<?= $link['link'] ?>"><?= $link['name'] ?></a></li>
                    <?php endforeach ?>



                    <?php if (isset($_SESSION['role'])) : ?>
                        <?= $_SESSION['role'] == 'user'
                            ? '<li id="logout"><a href="/logout" class="button">Déconnexion</a></li>'
                            : '<li id="logout"><a href="#" class="button">Déconnexion</a></li>' ?>
                    <?php else : ?>
                        <li id="se_connecter"><a href="/login" class="button">Se connecter</a></li>
                    <?php endif ?>
                </ul>

            </nav>
        </div>
    </div>
</header>