<?php

    namespace App\controllers;
    use App\core\View;
    use App\models\User as UserModel;
    class Admin
    {
        public function home()
        {
            $firstname = 'simon';
            $view = new View("dashboard", "back");
            $view->assign('firstname', $firstname);
            
            
            $user = new UserModel();
            
            $view->assign("user", $user);
        }
    }