<h1>Tableau de bord</h1>
Welcome <?= $lastname ?><!--Comment faire un  echo-->
<?php if(false):?>
    <h2>Salut</h2><!--Comment faire un if dans une vue-->
<?php endif;?>


<?php $this->includePartial("form", $user->getLogoutBtn());?>