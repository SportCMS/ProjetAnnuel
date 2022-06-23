<form method="<?= $config["config"]["method"] ?? "POST" ?>" action="<?= $config["config"]["action"] ?? "" ?>" id="<?= $config["config"]["id"] ?? "" ?>" class="<?= $config["config"]["class"] ?? "" ?>">

    <?php foreach ($config["inputs"] as $name => $input) :
        switch ($input["type"]):

                /**********************************
             * ********* RADIO ***************
             **********************************/
            case "radio": //crd
                foreach ($input['value'] as $key => $value) : ?>
                    <input name="<?= $name ?>" type="radio" id="<?= $input["id"] ?? "" ?>" class="<?= $input["class"] ?? "" ?>" value="<?= $value ?? "" ?>" <?php if (!empty($input["checked"])) echo ($value === $input['checked']) ? 'checked' : ''; ?>>
                    <label><?= $key ?? "Choix" ?></label>
                    <br>
                <?php
                endforeach;
                break; //crf
                ?>

                <?php
            case "checkbox": //ccd
                /**********************************
                 * ********* CHECKBOX ***************
                 **********************************/
                foreach ($input['value'] as $key => $value) : ?>
                    <input name="<?= $name ?>" type="checkbox" id="<?= $input["id"] ?? "" ?>" class="<?= $input["class"] ?? "" ?>" value="<?= $value ?? "" ?>" <?php if (!empty($input["checked"])) echo ($value === $input['checked']) ? 'checked' : ''; ?>>
                    <label><?= $key ?? "Choix" ?></label>
                    <br>
                <?php
                endforeach;
                break; //ccf
                ?>


            <?php
            case "select": //csd
            ?>
                <!--
                     /**********************************
                    * ********* SELECT ***************
                    **********************************/
                -->
                <select name="<?= $name ?>" id="<?= $input["id"] ?? "" ?>" class="<?= $input["class"] ?? "" ?>">
                    <option value="">Veuillez choisir</option>
                    <?php foreach ($input['value'] as $value) : ?>
                        <?php if ($input["selectedValue"] == $value[0]) : ?>
                            <option value="<?= $value[0] ?>" selected><?= $value[1]  ?></option>
                        <?php else : ?>
                            <option value="<?= $value[0] ?>"><?= $value[1]  ?></option>
                        <?php endif ?>
                    <?php endforeach; ?>
                </select>
                <br>
                <?php break; ?>



            <?php
            case "textarea": //ctd
            ?>
                <!--
                     /**********************************
                    * ********* TEXTAREA ***************
                    **********************************/
                -->
                <textarea name="<?= $name ?>" class="<?= $input["class"] ?? "" ?>" id="<?= $input["id"] ?? "" ?>" placeholder="<?= $input["placeholder"] ?? "" ?>"><?= $input["value"] ?? '' ?></textarea>
                <br>
                <?php break; //ctf
                ?>

            <?php
            case "file": //cfd
            ?>
                <input type="file" name="<?= $name ?>" class="<?= $input["class"] ?? "" ?>" id="<?= $input["id"] ?? "" ?>" <?= !empty($input["multiple"]) ? 'multiple' : ""  ?>>
                <br>
                <?php break; //cff
                ?>


            <?php
            case 'captcha': //ccd
            ?>
                <!--
                     /**********************************
                    * ********* RECAPTCHA ***************
                    **********************************/
                -->
                <div class="g-recaptcha" data-sitekey="<?= CAPTCHASITEKEY ?>"></div>
                <?php break; //ccf
                ?>
            <?php
            default: ?>
                <input name="<?= $name ?>" class="<?= $input["class"] ?? "" ?>" id="<?= $input["id"] ?? "" ?>" placeholder="<?= $input["placeholder"] ?? "" ?>" type="<?= $input["type"] ?? "text" ?>" value="<?= $input["value"] ?? "" ?>" <?= !empty($input["required"]) ? 'required="required"' : ""  ?>><br>
        <?php endswitch; ?>
    <?php endforeach; ?>

    <input type="submit" value="<?= $config["config"]["submit"] ?? "Envoyer" ?>">


</form>