<?php

namespace App\controllers;

use App\core\Sql;
use App\core\Router;


class Presentation extends Sql
{
    public function indexPresentation()
    {
        return Router::render('front/presentation/index.view.php');
    }
}