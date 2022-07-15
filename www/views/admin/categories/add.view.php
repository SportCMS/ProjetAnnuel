<?php ob_start(); ?>

<?php if(!empty($errors)): ?>
	<?php foreach ($errors as $error): ?>
		<div class="alert">
			<p class="errormsg"><ion-icon name="close-circle-outline"></ion-icon><?= $error ?></p>
		</div>
	<?php endforeach; ?>
<?php endif; ?>	
<div class="form_container">
<h1>Créer une categorie</h1>
<form class="form_builder" action="" method="post" enctype="multipart/form-data">
    <div class="logo__input">
    <label>Ajouter un titre</label><br>
    <input class="form_input" type="text" name="title" required style="width:85%;height:30px;padding:3px 5px" /><br>
    </div>
    <div class="logo__input">
    <label>Ajouter une courte description</label><br>
    <textarea class="form_input" name="description" required style="width:85%;min-height:100px;padding:3px 5px"></textarea>
    </div>
    <div class="logo__input">
    <label>Choisir une photo</label><br>
    <input class="form_input" type="file" name="image" id="fileToUpload" style="width:85%;min-height:30px;padding:3px 5px" /><br>
    </div>
    <button>Valider</button>
    <small style="color:red"><?= isset($error) ? $error : "" ?></small>
</form>
</div>
<?php $content = ob_get_clean(); ?>
<?php require('./views/admin/base/base.php'); ?>