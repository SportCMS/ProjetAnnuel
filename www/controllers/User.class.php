<?php
    namespace App\controllers;
    use App\core\User as Us;
    use App\core\View;
    use App\models\User as UserModel;
    use App\core\Verificator;

    class User {


        public function login()
        {
            $user = new UserModel();

            if(!empty($_POST)) {
                $result = Verificator::checkForm($user->getLoginForm(), $_POST);

                print_r($result);
            }


            $view = new View("Login");
            $view->assign("user", $user);
        }
        public function logout()
        {
            echo "logout";
        }
        public function register(){
            $user = new UserModel();

            if(!empty($_POST)) {
                $result = Verificator::checkForm($user->getExamForm(), $_POST);
            }


            $view = new View("Register");
            $view->assign("user", $user);
        }



        public function connection(){
            echo "HAHAHA";
            $user = new UserModel();
            $password = $_POST['password'];
            $email = $_POST['email'];
            $user->setPassword($password);
            $user->setEmail($email);
            print_r($user);

            
        }


        public function forgetPswd(){
            $user = new UserModel();
            $user = $user->getBy(['email' => 'ayman.bedda@gmail.com', 'id' => 5]);
            //$user = $user->getOneBy(['email' => 'ayman.bedda@gmail.com']);
            var_dump($user);
            die();
            $view = new View("forgetpswd");
            $view->assign("user", $user);
        }
    }