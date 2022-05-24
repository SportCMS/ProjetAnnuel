<h1>Inscrit</h1>
    <?php if(isset($errors)):
        foreach($errors as $error): ?>
            <?= $error . "<br>"?>
        <?php endforeach;
    endif; ?>
    <?php if(isset($success)):?>
        <?= $success . "<br>"?>
    <?php endif; ?>

<?php $this->includePartial("form", $user->getRegisterForm());?>
