<?php
    namespace App\controllers;
    use App\core\Router;

    class Main {
        public function home()
        {
            Router::render('front/home/home.view.php');
        }
    }