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
            case 'email': //ccd
                ?>
                <div class="logo__input">
                    <label><?= $input["label"] ?? "" ?></label>
                    <input name="<?= $name ?>" class="<?= $input["class"] ?? "" ?>" id="<?= $input["id"] ?? "" ?>" placeholder="<?= $input["placeholder"] ?? "" ?>" type="<?= $input["type"] ?? "text" ?>" value="<?= $input["value"] ?? "" ?>" <?= !empty($input["required"]) ? 'required="required"' : ""  ?><?= !empty($input["disabled"]) ? 'disabled' : ""  ?> <?= !empty($input["pattern"]) ? 'pattern='.$input['pattern'] : ""  ?> <?= !empty($input["minlength"]) ? 'minlength='.$input['minlength'] : "" ?> <?= !empty($input["maxlength"]) ? 'maxlength='.$input['maxlength'] : ""  ?>><br>
                </div>
                <?php break; 
                ?>

                <?php
            case 'password': 
                 ?>
                <div class="logo__input">
                <label><?= $input["label"] ?? "" ?></label>
                <input name="<?= $name ?>" class="<?= $input["class"] ?? "" ?>" id="<?= $input["id"] ?? "" ?>" placeholder="<?= $input["placeholder"] ?? "" ?>" type="<?= $input["type"] ?? "text" ?>" value="<?= $input["value"] ?? "" ?>" <?= !empty($input["required"]) ? 'required="required"' : ""  ?><?= !empty($input["pattern"]) ? 'pattern='.$input['pattern'] : ""  ?> <?= !empty($input["minlength"]) ? 'minlength='.$input['minlength'] : "" ?> <?= !empty($input["maxlength"]) ? 'maxlength='.$input['maxlength'] : ""  ?>><br>
                </div>
                <?php break; 
                ?>

            <?php
            case "select": //csd
            ?>
                <!--
                     /**********************************
                    * ********* SELECT ***************
                    **********************************/
                -->
                <select name="<?= $name ?>" id="<?= $input["id"] ?? "" ?>" class="<?= $input["class"] ?? "" ?>"<?= !empty($input["required"]) ? 'required="required"' : ""  ?>>
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
                <div class="logo__input">
                    <label><?= $input["label"] ?? "" ?></label>
                    <input name="<?= $name ?>" class="<?= $input["class"] ?? "" ?>" id="<?= $input["id"] ?? "" ?>" placeholder="<?= $input["placeholder"] ?? "" ?>" type="<?= $input["type"] ?? "text" ?>" value="<?= $input["value"] ?? "" ?>" <?= !empty($input["required"]) ? 'required="required"' : ""  ?><?= !empty($input["disabled"]) ? 'disabled' : ""  ?><?= !empty($input["pattern"]) ? 'pattern='.$input['pattern'] : ""  ?><?= !empty($input["minlength"]) ? 'minlength='.$input['minlength'] : "" ?> <?= !empty($input["maxlength"]) ? 'maxlength='.$input['maxlength'] : ""  ?>><br>
                </div>
        <?php endswitch; ?>
    <?php endforeach; ?>

    <input type="submit" value="<?= $config["config"]["submit"] ?? "Envoyer" ?>">

    <?php if($_SERVER['REQUEST_URI'] == '/inscription') :?>
        <p class="link">D??j?? un compte ? <a href="/login">Connectez-vous !</a></p>
    <?php endif?>
    <?php if($_SERVER['REQUEST_URI'] == '/admin-inscription') :?>
        <p class="link">D??j?? un compte ? <a href="/admin-login">Connectez-vous !</a></p> 
    <?php endif?>

    <?php if($_SERVER['REQUEST_URI'] == '/login') :?>
        <p class="link">Pas de compte ? <a href="/inscription">Inscrivez-vous !</a></p>
        <small class="link">Mot de passe oubli?? ? <a href="/forgetPassword">Cliquez ici !</a></small> 
    <?php endif?>
    <?php if($_SERVER['REQUEST_URI'] == '/admin-login') :?>
        <p class="link">Pas de compte ? <a href="/admin-inscription">Inscrivez-vous !</a></p>
        <small class="link">Mot de passe oubli?? ? <a href="/forgetPassword">Cliquez ici !</a></small> 
    <?php endif?>



</form>