<?php
    namespace App\controllers;
    use App\core\User as Us;
    use App\core\View;
    use App\models\User as UserModel;
    use App\core\Verificator;
    use App\core\Sql;

    class User {


        public function login()
        {
            
            $view = new View("Login");
            $config = UserModel::getLoginForm();
            $user = new UserModel();
            $view->assign("user", $user);
            if($_SERVER["REQUEST_METHOD"] == "POST") {
                $result = Verificator::checkForm($user->getLoginForm(), $_POST);

                if(!empty($result)){
                    $view ->assign('result',$result);
                }else{
                    if(!empty($_POST)){
                        
                        $user_form = $user->getOneBy(['email' => $_POST['email']]);
                        $object=$user_form[0];

                        !is_null($user_form) ? $pwd = $object->password : '';
                        !is_null($user_form) ? $email = $object->email : '';

                        $token=$object->token;

                        $pwd_user = isset($pwd) ? $pwd : '';
                        $email_user = isset($email) ? $email : '';
                        //$pwd_verif = password_verify($_POST['password'],$pwd_user);

                        if($email_user === $_POST['email'] && $pwd_user === $_POST['password'] && $token == null){
                            header("Location: dashboard");
                            var_dump("yeeees");
                        }elseif(!$pwd_verif){
                            $result[]="Votre mot de passe est incorrect";
                            $view->assign('result',$result);
                            var_dump("fail mdp");
                            var_dump($_POST['password']);
                            var_dump($pwd_user);
                            var_dump($pwd_verif);
                        }elseif($token !== null){
                            $result[] = "Veuillez activer votre compte";
                            $view->assign('result',$result);
                            var_dump("fail_token");
                        }
                    }
                    else{}
                }

            }
            $view->assign("config",$config);


            
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