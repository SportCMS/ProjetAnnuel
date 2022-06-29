<?php

namespace App\controllers;

use App\core\Sql;
use App\core\Router;
use App\models\Page as PageModel;
use App\models\Block as BlockModel;
use App\models\Form;
use App\models\Input;

class About extends Sql
{
    public function indexAbout()
    {
        $pageManager = new PageModel();
        $page = $pageManager->getOneBy(['link' => $_SERVER['REQUEST_URI']])[0];

        $blockManager = new BlockModel();
        $blocks = $blockManager->getBlockByPosition($page->getId());

        foreach ($blocks as $block) {
            if (isset($block['formTitle'])) {
                $inputsModel = new Input();
                $inputs = $inputsModel->getFormInputs($block['formId']);
            }
        }
        return Router::render('front/about/index.view.php', ['blocks' => $blocks]);
    }
}