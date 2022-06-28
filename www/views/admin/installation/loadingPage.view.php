<?php ob_start(); ?>
<div class="c">
    <div></div>
    <div></div>
    <div></div>
</div>
<br>
<h1 style="margin-top:15px">Veuillez patienter pendant l'installation</h1>
<br>
<small style="text-align:center;display:block" id="msgLoading"></small>
<br>
<?php $base = ob_get_clean(); ?>
<?php require('./views/front/base/baseSecurity.php'); ?>