<?php ob_start(); ?>


<form action="" method="post" enctype="multipart/form-data" style="width:60%;margin:0 auto;">
    <h1>Créer une categorie</h1>
    <label style="margin-top:10px;display:block">Ajouter un titre</label><br>
    <input type="text" name="title" required style="width:85%;height:30px;padding:3px 5px" /><br>

    <label style="margin-top:10px;display:block">Ajouter une courte description</label><br>
    <textarea name="description" required style="width:85%;min-height:100px;padding:3px 5px"></textarea>

    <label style="margin-top:10px;display:block">Choisir une photo</label><br>
    <input type="file" name="image" id="fileToUpload" style="width:85%;min-height:30px;padding:3px 5px" /><br>

    <button style="width:85%;margin-top:20px;padding:7px 30px">Valider</button>
    <small style="color:red"><?= isset($error) ? $error : "" ?></small>
</form>

<?php $content = ob_get_clean(); ?>
<?php require('./views/admin/base/base.php'); ?>