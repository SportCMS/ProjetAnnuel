<?php

namespace App\controllers;

use App\core\Router;
use App\core\Sql;
use App\models\Connexion;
use App\models\User;

use App\querys\QueryUser;

class Landing
{
    public function landingPage()
    {
        $qu = new QueryUser();
        dd($qu->searchUsers("test"));

        if (isset($_SESSION["role"]) && $_SESSION['role'] == 'user'){
            header('Location: /home');
        }
        if (isset($_SESSION["role"]) && $_SESSION['role'] == 'admin') {
            header('Location: /dashboard');
        }
        return Router::render('front/landing/landingPage.view.php');
    }
}