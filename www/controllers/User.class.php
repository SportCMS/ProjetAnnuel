<?php
    namespace App\controllers;
    use App\core\User as Us;
    use App\core\View;
    use App\models\User as UserModel;
    use App\core\Verificator;

    class User {


        public function login()
        {
            $view = new View("login");
        }
        public function logout()
        {
            echo "logout";
        }
        public function register(){
            $user = new UserModel();

            if(!empty($_POST)) {
                $result = Verificator::checkForm($user->getExamForm(), $_POST);

                print_r($result);
            }


            $view = new View("Register");
            $view->assign("user", $user);
        }


    }