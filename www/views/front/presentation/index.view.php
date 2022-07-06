<?php

use App\models\Input;

ob_start(); ?>

<h1 style="text-align:center">Presentation</h1>

<?php if (isset($blocks)) : ?>
    <?php foreach ($blocks as $block) : ?>
        <div style="min-height:50px; padding:20px; margin:50px 0;text-align:center">
            <?php if (isset($block['content'])) :  ?>
                <?= $block['content'] ?>
            <?php endif ?>

            <?php if (isset($block['formTitle'])) : ?>
                <form action="" method="POST">
                    <h1><?= $block['formTitle'] ?></h1>
                <?php endif ?>

                <?php $inputManager = new Input();
                if ($inputs = $inputManager->getFormInputs($block['formId'])) ?>
                <?php foreach ($inputs as $input) : ?>
                    <div class="ligne">
                        <?php if ($input['type'] != 'submit') : ?>
                            <p class="encare">
                                <label><?= $input['label'] ?></label>
                            </p>
                        <?php endif ?>
                        <br>
                        <input style="padding:6px 50px" type="<?= $input['type'] ?>" name="<?= $input['name'] ?>" placeholder="<?= $input['placeholder'] ?>" required>
                    </div>
                    <br>
                <?php endforeach ?>

                <?php if (isset($block['formTitle'])) : ?>
                </form>
            <?php endif ?>

        <?php endforeach ?>
    <?php endif ?>
    <span><?= isset($alert) ? $alert : '' ?></span>

    <?php $base = ob_get_clean(); ?>
    <?php require('./views/front/base/base.php'); ?>