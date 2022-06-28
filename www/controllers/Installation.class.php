<?php

namespace App\controllers;

use App\core\Router;
use App\core\Sql;
use App\models\User;
use App\models\Theme;
use App\Helpers\Fixtures;

class Installation extends Sql
{
    public function completeRegistration()
    {

        $themeManager = new Theme();
        $themes = $themeManager->getAll();
        Router::render('admin/installation/completeRegistration.view.php', ['themes' => $themes]);
    }


    public function loading()
    {
        Router::render('admin/installation/loadingPage.view.php');
    }

}