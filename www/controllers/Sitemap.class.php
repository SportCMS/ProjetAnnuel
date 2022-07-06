<?php

namespace App\controllers;

use App\core\Sql;
use App\core\Router;
use App\models\Article;
use App\models\Page;
use App\models\Block as BlockModel;
use App\models\Categorie;
use App\models\Form;
use App\models\Input;

class Sitemap extends Sql
{
    public function index()
    {
        $pages = (new Page())->getAll();
        $categories = (new Categorie())->getAll();
        $articles = (new Article())->getAll();

        return Router::render('front/sitemap/index.view.php', [
            'pages' => $pages,
            'categories' => $categories,
            'articles' => $articles,
            'domain' => DOMAIN
        ]);
    }
}