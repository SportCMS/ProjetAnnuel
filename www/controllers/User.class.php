<?php
    namespace App\controllers;
    session_start();
    use App\core\User as Us;
    use App\core\View;
    use App\models\User as UserModel;
    use App\core\Verificator;
    use App\core\Sql;
    use App\controllers\Mail;

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

            $view = new View("register");
            $view->assign("user", $user);

            if(!empty($_POST)) {

                $errors = Verificator::checkForm($user->getRegisterForm(), $_POST);
                var_dump($errors);

                if(empty($errors)) {
                    $firstname = addslashes(htmlspecialchars($_POST['firstname']));
                    $lastname = addslashes(htmlspecialchars($_POST['lastname']));
                    $email = addslashes($_POST['email']);
                    $pwd = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $pwd_confirm = password_hash($_POST['passwordConfirm'], PASSWORD_DEFAULT);

                    //$user_info = $user->getOneBy(['email' => $email]);
                    //$object = $user_info[0];

                    //$user_email = $object->email;

                    $user_email = $user->uniqueMailVerification($email);
                    //var_dump($user_email[0]);

                    if($user_email[0] != 0){
                        echo "Votre email existe déjà !!!";
                    }
                    elseif(password_verify($_POST['password'] ,$pwd) !== password_verify($_POST['passwordConfirm'], $pwd_confirm)) {
                        echo "Vos mots de passe ne correspondent pas !!!";
                    }
                    else {
                        $user->setFirstname($firstname);
                        $user->setLastname($lastname);
                        $user->setEmail($email);
                        $user->setPassword($pwd);
                        $user->generateToken();
        
                        $user->save();

                        $token = $user->getToken();

                        $mail_confirm = new Mail();
                        $mail_confirm->main(
                            $email, 
                            "Confirmation inscription SportCMS", 
                            "
                            Bonjour " . $user->getFirstname() .
                            " <br><br>Nous avons bien reçu vos informations. <br>
                            Afin de valider votre compte merci de cliquer sur le lien suivant <a href='http://localhost:81/confirmaccount?token=".$token."'>Ici</a> <br><br>
                            Cordialement,<br>
                            <a href=''>L'Equipe de SportCMS</a>
                            "
                        );

                        $_SESSION['flash']['success'] = "Un e-mail de confirmation vous a été envoyé pour valider votre compte !";
                        //header('Location: login');
                        exit();
                    }
                }
            }
        }

        public function confirmaccount() {
            $user = new UserModel();

            $view = new View("confirmaccount");

            $token = $_GET['token'];

            $user_info = $user->getOneBy(['token' => $token]);
            $object = $user_info[0];

            $user_token = $object->token;
            $user_id = $object->id;

            if($token === $user_token){
                $user->activateAccount($user_id);
            }
            else {
                echo "Token non valide !";
            }
                
        }
    }

