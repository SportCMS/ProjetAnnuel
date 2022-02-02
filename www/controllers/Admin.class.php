<?php

    namespace App\controllers;
    use App\core\View;
    class Admin
    {
        public function home()
        {
            $firstname = 'simon';
            $view = new View("dashboard", "back");
            $view->assign('firstname', $firstname);
        }
    }