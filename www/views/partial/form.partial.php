<form method="<?= $config["config"]["method"]??"POST" ?>"
      action="<?= $config["config"]["action"]??""?>"
      id="<?= $config["config"]["id"]??""?>"
      class="<?= $config["config"]["class"]??""?>">



    <?php foreach ($config["inputs"] as $name=>$input) :
        switch ($input["type"]):
            case "radio"://crd
                foreach($input['value'] as $key=>$value):?>
                <input name="<?= $name ?>" 
                    type="radio" 
                    id="<?= $input["id"]??"" ?>" 
                    class="<?= $input["class"]??"" ?>" 
                    value="<?= $value??""?>"
                    <?php if(!empty( $input["checked"])) echo ($value === $input['checked'])? 'checked': '';?>>
                <label><?= $key??"Choix"?></label>
                <br>
                <?php 
                endforeach;
                break;//crf?>
        <?php case "checkbox"://ccd
                foreach($input['value'] as $key=>$value):?>
                    <input name="<?= $name ?>" 
                        type="checkbox" 
                        id="<?= $input["id"]??"" ?>" 
                        class="<?= $input["class"]??"" ?>" 
                        value="<?= $value??""?>"
                        <?php if(!empty( $input["checked"])) echo ($value === $input['checked'])? 'checked': '';?>>
                    <label><?= $key??"Choix"?></label>
                    <br>
                <?php 
                endforeach;
                break;//ccf?>
        <?php case "select"://csd?>
                <select name="<?= $name ?>" id="<?= $input["id"]??"" ?>" class="<?= $input["class"]??"" ?>" >
                    <?php foreach($input['value'] as $key=>$value):?>
                        <option value="<?= $value??""?>" <?php if(!empty( $input["selected"])) echo ($value === $input['selected'])? 'selected="selected"': '';?>><?= $key??"Choix"?></option>
                    <?php  endforeach;?>
                </select>
                <br>
                <?php break;//csf?>
        <?php case "textarea"://ctd?>
                <textarea name="<?= $name ?>" class="<?= $input["class"]??"" ?>" id="<?= $input["id"]??"" ?>" placeholder="<?= $input["placeholder"]??"" ?>"></textarea>
                <br>
                <?php break;//ctf?>
        <?php case "file"://cfd?>
            <input 
                type="file" 
                name="<?= $name ?>" 
                class="<?= $input["class"]??"" ?>" 
                id="<?= $input["id"]??"" ?>"
                <?= !empty( $input["multiple"])?'multiple':""  ?>>
            <br>
            <?php break;//cff?>
        <?php case 'captcha'://ccd?>
	        <div class="g-recaptcha" data-sitekey="<?= CAPTCHASITEKEY?>"></div>
            <?php break;//ccf?>
        <?php default:?>
                <input name="<?= $name ?>"
                class="<?= $input["class"]??"" ?>"
                id="<?= $input["id"]??"" ?>"
                placeholder="<?= $input["placeholder"]??"" ?>"
                type="<?= $input["type"]??"text" ?>"
                <?= !empty( $input["required"])?'required="required"':""  ?>
                ><br>
      <?php endswitch;?>        
    <?php endforeach;?>

    <input type="submit" value="<?= $config["config"]["submit"]??"Envoyer"?>">
</form>