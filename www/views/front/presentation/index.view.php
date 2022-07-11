<?php

use App\models\Input;

ob_start(); ?>

<section class="mainuser">
    <h1 style="text-align:center">Presentation</h1>

    <?php if (isset($blocks)) : ?>
    <?php foreach ($blocks as $block) : ?>
    <div style="min-height:50px; padding:20px; margin:50px 0;text-align:center">
        <?php if (isset($block['content'])) :  ?>
        <?= $block['content'] ?>
        <?php endif ?>

        <?php if (isset($block['formTitle'])) : ?>
            <h1><?= $block['formTitle'] ?></h1>
            <form class="form_builder" action="" method="POST">
            <?php endif ?>

            <?php $inputManager = new Input();
                if ($inputs = $inputManager->getFormInputs($block['formId'])) ?>
            <?php foreach ($inputs as $input) : ?>
                <?php if ($input['type'] != 'submit') : ?>
                    <div class="logo__input">
                        <label><?= $input['label'] ?></label>
                        
                        <input class="form_input" type="<?= $input['type'] ?>" name="<?= $input['name'] ?>"
                            placeholder="<?= $input['placeholder'] ?>" required>
                    </div>
                    <br>
                <?php endif ?>
                <?php if ($input['type'] == 'submit') : ?>
                    <input type="<?= $input['type'] ?>" name="<?= $input['name'] ?>"
                                placeholder="<?= $input['placeholder'] ?>" required>
                <?php endif ?>
            <?php endforeach ?>

            <?php if (isset($block['formTitle'])) : ?>
        </form>
        <?php endif ?>

        <?php endforeach ?>
        <?php endif ?>
        <span><?= isset($alert) ? $alert : '' ?></span>
</section>

<?php $base = ob_get_clean(); ?>
<?php require('./views/front/base/base.php'); ?>