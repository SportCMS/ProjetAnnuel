<?php

namespace App\controllers;

use App\core\Router;
use App\core\Sql;
use App\models\Connexion;
use App\models\User;

use App\querys\QueryLike;

class Landing
{
    public function landingPage()
    {
        $ql = new QueryLike();
        dd($ql->countAllLikesByArticle(1));

        if (isset($_SESSION["role"]) && $_SESSION['role'] == 'user'){
            header('Location: /home');
        }
        if (isset($_SESSION["role"]) && $_SESSION['role'] == 'admin') {
            header('Location: /dashboard');
        }
        return Router::render('front/landing/landingPage.view.php');
    }
}