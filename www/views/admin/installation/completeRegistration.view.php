<?php ob_start(); ?>

<h1>Merci de complétez votre inscription </h1>
<p>Un peu de patience.. encore une étape avant de commencer à utiliser votre application</p><br><br>

<form action="" method="POST">
    <div class="ligne">
        <p class="encare"> <label>Choisir un nom de base de donnée</label> </p>
        <input type="text" name="databaseName" id="dbName" />
    </div>
    <br>
    <br>
    <div class="ligne">
        <p class="encare"> <label>Choisir un préfixe de tables</label> </p>
        <small>(Pour plus de securité nous vous invitons à choisir un préfixe personnalisé et difficile à deviner)</small><br>
        <input type="text" name="tablePrefix" id="tablePrefix" placeholder="bgfb, bdds, cmsx..." />
    </div>
    <br>
    <br>
    <div class="ligne">
        <p class="encare"> <label>Choisir un thème</label> </p>
        <br>
        <?php foreach ($themes as $theme) : ?>
            <div style="display:flex;width:40%;align-items:center">
                <div style="margin-right:50px">
                    <label for="theme"><?= $theme['name'] ?></label><br>
                    <input type="radio" id="theme" name="chooseTheme" value="<?= $theme['id'] ?>">
                </div>
                <div>
                    <img src="public/assets/img/<?= $theme['image']  ?>" alt="" width="70" height="70"><br>
                    <small><?= $theme['description'] ?></small><br><br>
                </div>
            </div>
        <?php endforeach ?>

    </div>
    <br>
    <br>
    <div class="ligne">
        <p class="encare"> <label>Choisir un port d'accès pour votre base de donnée</label> </p>
        <input type="text" name="port" id="tablePrefix" placeholder="ex : 3306" value="3306" />
    </div>
    <br>
    <br>
    <div class="ligne">
        <p class="encare"> <label>Renseigner votre nom de domaine</label> </p>
        <input type="text" name="domain" id="tablePrefix" value="sportCMS.fr" />
    </div>
    <br>
    <br>
    <small id="msg_alert" style="color:<?= isset($alert) && $alert[0] == 'success' ? 'green' : 'red' ?>">
        <?= isset($alert) ? $alert[1] : '' ?>
    </small><br>
    <button class="button button--form">valider</button>
</form>


<?php $base = ob_get_clean(); ?>
<?php require('./views/front/base/baseSecurity.php'); ?>