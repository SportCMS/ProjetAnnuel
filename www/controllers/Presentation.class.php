<?php

namespace App\controllers;

use App\core\Sql;
use App\core\Router;

use App\models\Page as PageModel;
use App\models\Block as BlockModel;
use App\models\Contact;
use App\models\Form;
use App\models\Input;
use App\models\Newsletter;


class Presentation extends Sql
{
    public function indexPresentation()
    {
        $pageManager = new PageModel();
        $page = $pageManager->getOneBy(['link' => $_SERVER['REQUEST_URI']])[0];

        $blockManager = new BlockModel();
        $blocks = $blockManager->getBlockByPosition($page->getId());

        $inputManager = new Input();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            foreach ($blocks as $block) { 
                if (isset($block['formTitle']) && $block['formTitle'] == 'newsletter') {
                    $inputs = $inputManager->getFormInputs($block['formId']);
                    $newsLetter = new Newsletter();
                    foreach ($inputs as $input) {
                        if ($input['type'] == 'submit') {
                            continue;
                        }
                        if (empty($_POST[$input['name']])) { 
                            $alert = 'Veuillez renseigner un email';
                            return Router::render('front/presentation/index.view.php', ['blocks' => $blocks, 'alert' => $alert]);
                        }
                        $secureData = htmlspecialchars(trim($_POST[$input['name']]));
                        if (!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $secureData)) {
                            continue;
                        }
                        $newsLetter->setEmail($secureData);
                    }
                    $newsLetter->save();
                    $alert = 'Vous êtes désormais inscrit à la newsletter';
                    return Router::render('front/presentation/index.view.php', ['blocks' => $blocks, 'alert' => $alert]);
                }

                if (isset($block['formTitle']) && $block['formTitle'] == 'contact') {
                    $inputs = $inputManager->getFormInputs($block['formId']);
                    $contact = new Contact();
                    foreach ($inputs as $input) {
                        if ($input['type'] == 'submit') {
                            continue;
                        }
                        if (empty($_POST[$input['name']])) {
                            $alert = 'Veuillez renseigner tous les champs';
                            return Router::render('front/presentation/index.view.php', ['blocks' => $blocks, 'alert' => $alert]);
                        }
                        $secureData = htmlspecialchars(trim($_POST[$input['name']]));
                        if (preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $secureData)) {
                            $contact->setEmail($secureData);
                        } else {
                            $contact->setMessage($secureData);
                        }
                    }
                    $contact->save();
                    $alert = 'Message envoyé';
                    return Router::render('front/presentation/index.view.php', ['blocks' => $blocks, 'alert' => $alert]);
                }
            }
        }
        return Router::render('front/presentation/index.view.php', ['blocks' => $blocks]);
    }
}