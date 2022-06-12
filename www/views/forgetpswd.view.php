
<img class="logo__login" src="../public/assets/images/SportCMS.png" alt="logo SPORT-CMS">

<?php
    if(isset($error)):
    ?>
        <p style="color: red;font-size: 0.8em;"><?=$error?></p>
    <?php
    endif;
?>
<?php $this->includePartial("form", $user->getForgetPswdForm());?>