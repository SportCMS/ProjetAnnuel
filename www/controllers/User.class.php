<?php
    namespace App\controllers;
    use App\core\User as Us;
    use App\core\View;
    use App\models\User as UserModel;
    use App\core\Verificator;
    use main as SendMail;

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

            $view = new View("Register");
            $view->assign("user", $user);
        }

        public function confirmAcc() {
            $user = new UserModel();

            if(!empty($_POST)) {

                $result = Verificator::checkForm($user->getRegisterForm(), $_POST);
                var_dump($result);

                if(empty($result)) {
                    $user->setFirstname($_POST['firstname']);
                    $user->setLastname($_POST['lastname']);
                    $user->setEmail($_POST['email']);
                    $user->setPassword($_POST['password']);
                    $user->generateToken();
    
                    $user->save();

                    echo "Bravo !";
                }
            }
        }
    }