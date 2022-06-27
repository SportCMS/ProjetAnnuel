<?php

    namespace App\controllers;

    use App\models\User as UserModel;
    use App\models\Password_reset as PswdRst;

    use App\core\verificator\Verificator;
    use App\core\Session;
    use App\core\Mail;
    use App\core\Router;

    class User {
        /* Session test*/
        public function login()
        {
            $user = new UserModel();
            Router::render('front/security/login.view.php',["user" => $user]);
            if(empty($_POST)){
                die();
            }
            $errors = Verificator::checkForm($user->getLoginForm(), $_POST);
            if(!empty($errors)){
                Router::render('front/security/login.view.php',['errors' => $errors]);
                die();
            }
            if(!isset($user->getOneBy(['email' => $_POST['email']])[0])){
                Router::render('front/security/login.view.php',['errors' => ["Votre email ou mot de passe est invalide"]]);
                die();
            }
            $user = $user->getOneBy(['email' => $_POST['email']])[0];

            if(!password_verify($_POST['password'], $user->getPassword())){
                Router::render('front/security/login.view.php',['errors' => ["Votre email ou mot de passe est invalide"]]);
                die();
            }
            $status = $user->getStatus();
            
            if($status == 0){
                Router::render('front/security/login.view.php',['errors' => ["Votre compte n'est pas encore actif"]]);
                die();
            }
            $session = new Session();
            $session->set('email', $_POST['email']);
            header("Location: dashboard");
        }
        public function logout()
        {

            session_start ();

            session_unset ();

            session_destroy ();

            header ('Location: login');
        }

        public function redi(){
            $test = "le test est concluant";
            $session = new Session();
            //header('Location: /forgetPassword');
        }
        /******************************************************/
        /***************PARTIE CHANGER MOT DE PASSE************/
        /******************************************************/
        //Formulaire email user
        public function forgetPswd(){
            if(isset($test)){
                echo $test;
                die();
            }
            $user = new UserModel();
            Router::render('front/security/forgetpwsd.view.php',["user" => $user]);
        }
        //envoie mail utilisateur ou redirection vers formulaire 
        public function sendPswdRst(){
            $user = new UserModel();
            Router::render('front/security/forgetpwsd.view.php',["user" => $user]);
            if(empty($_POST)){
                Router::render('front/security/forgetpwsd.view.php',["error" => "Aie un champ a disparue. =,("]);
                die();
            }
            $result = Verificator::checkForm($user->getForgetPswdForm(), $_POST);
            if(!empty($result)){
                Router::render('front/security/forgetpwsd.view.php',["error" => "Aie ton email est mal écrit. =,("]);
                die();
            }
            $user = $user->getOneBy(["email" => $_POST['email']]);
            if(empty($user)){
                Router::render('front/security/forgetpwsd.view.php',["error" => "L'email n'existe pas. =,("]);
                die();
            }
            $user = $user[0]; 
            $pswdRst = new PswdRst();
            $pswdRst->generateToken();
            $pswdRst->generateTokenExpiry();
            $pswdRst->setIdUser($user);
            
            $mail = new Mail();
            $mail->sendTo($_POST['email']);
            $mail->subject("Il est l'heure de changer de mot de passe");
            $mail->message('<h1 style="color:blue">SPORTCMS</h1>
            <p>
                Nous avons bien reçus votre demande de changement de mot de passe.
            </p>
            <div>
                Changez de mot de passe en cliquant <a href="http://127.0.0.1:81/changePassword?token=' . $pswdRst->getToken() . '">ici</a>
            </div>');
            if(!$mail->send()){
                die("Vous rencontrer une erreur lors de l'envoie de mail");
            }
            
            $pswdRst->save();
            echo "Vous allez recevoir un mail pour modifier votre mail";
        }

        //formulaire changement du mot de passeEmail envoyé
        public function changePswd(){
            $pswdRst = new PswdRst();
            $user = new UserModel();
            if(empty($pswdRst->getOneBy(["token" => $_GET["token"]])[0])){
                die('<p style="color:red;">Le token n\'existe pas</p>');
            }
            $pswdRst = $pswdRst->getOneBy(["token" => $_GET["token"]])[0];
            if($pswdRst->getTokenExpiry() < time()){
                die('<p style="color:red;">Le token n\'est plus valide</p>');
            }
            $session = new Session();
            $session->set("token", $pswdRst->getToken());
            Router::render('front/security/changepswd.view.php',["user" => $user]);
        }

        //confirm changement mot de passe
        public function confirmChng(){
            $user = new UserModel();
            $pswdRst = new PswdRst();
            $session = new Session();
            $pswdRst = $pswdRst->getOneBy(["token" => $session->get('token')])[0];
            if(empty($pswdRst) && $pswdRst->getTokenExpiry() < time()){
                die("Le token n'existe pas ou est expiré");
            }
            if(empty($_POST)){
                die("Attention Vous n'avez pas remplie les champs");
            }
            $result = Verificator::checkForm($user->getChangePswdForm(), $_POST);

            if(!empty($result)){
                die("HOO! des erreurs sont présentent dans le formulaire");
            }
            $user->setId($pswdRst->getIdUser());
            $user->setPassword($_POST['password']);
            $user->save();
            echo "Mot de passe changé";
        }
        
        /*****REGISTER*****/
        // public function register(){
        //     $user = new UserModel();
        //     //Router::render('front/security/register.view.php',["user" => $user]);
        //     /* Si post vide alors on affiche le formulaire */
        //     if(empty($_POST)){
        //         die();
        //     }
    
        //     $errors = Verificator::checkForm($user->getRegisterForm(), $_POST);
        //     /* si des erreurs sont présentes on renvois sur la vue avec les erreurs */
        //     if(!empty($errors)){
        //         Router::render('front/security/register.view.php',["errors" => $errors]);
        //         die();
        //     }
        //     $firstname = strip_tags($_POST['firstname']);
        //     $lastname = strip_tags($_POST['lastname']);
        //     /* si l'email est trouvé en base retour vue avec erreur */
        //     if(isset($user->getOneBy(['email' => $_POST['email']])[0])){
        //         Router::render('front/security/register.view.php',["errors" => ["L'utilisateur existe"]]);
        //         die();
        //     }

        //     if($_POST['password'] !== $_POST['passwordConfirm']) {
        //         echo "Vos mots de passe ne correspondent pas !!!";
        //         die();
        //     }
    
        //     $user->setFirstname($firstname);
        //     $user->setLastname($lastname);
        //     $user->setEmail($_POST['email']);
        //     $user->setPassword($_POST['password']);
        //     $user->generateToken();
            
        //     $user->setRole('User');

        //     $user->save();

        //     $mail = new Mail();
        //     $mail->sendTo($_POST['email']);
        //     $mail->subject("Confirmation inscription SportCMS");
        //     $mail->message("
        //     Bonjour " . $user->getFirstname() .
        //     " <br><br>Nous avons bien reçu vos informations. <br>
        //     Afin de valider votre compte merci de cliquer sur le lien suivant <a href='http://localhost:81/confirmaccount?token=".$user->getToken()."'>Ici</a> <br><br>
        //     Cordialement,<br>
        //     <a href=''>L'Equipe de SportCMS</a>");
        //     if(!$mail->send()){
        //         die("Vous rencontrer une erreur lors de l'envoie de mail");
        //     }
        //     Router::render('front/security/register.view.php',["success" => "Un e-mail de confirmation vous a été envoyé pour valider votre compte !"]);
        // }
        
        public function register (){
            $user = new UserModel();
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $errors = Verificator::checkForm($user->getRegisterForm(), $_POST);
                
                if(count($errors) > 0){
                    Router::render('front/security/register.view.php',["user" => $user, "errors" => $errors]);
                     
                }
                
                $firstname = strip_tags($_POST['firstname']);
                $lastname = strip_tags($_POST['lastname']);
                $email = strip_tags($_POST['email']);
                
                
                
                if(isset($user->getOneBy(['email' => $_POST['email']])[0])){
                    $errors = [];
                    $errors['errors'] = "l'utilisateur exites déjà"; 
                    Router::render('front/security/register.view.php',["user" => $user, "errors" => $errors]);
                }

                $password = strip_tags($_POST['password']);
                $passwordConfirm = strip_tags($_POST['passwordConfirm']);

                if ($password !== $passwordConfirm) {
                    $errors['badPassword'] = "Les mots de passe doivent correspondre";
                    // *user permet de réafficher le formulaire
                    Router::render('front/security/register.view.php', ["user" => $user, "errors" => $errors]);
                }

                $user->setFirstname($firstname);
                $user->setLastname($lastname);
                $user->setEmail($email);
                $user->setPassword($password);
                $user->generateToken();
            
                //if form user register, assign role and create user email template
                if ($_SERVER['REQUEST_URI'] == '/inscription') {

                $user->setRole('user');
                
                $user->save();
                
                $mailBody = "
                        Bonjour {$user->getFirstname()} <br><br>Validez votre compte<br>
                        Afin de valider votre compte merci de cliquer sur le lien suivant <a href='http://localhost:81/confirmation-inscription?token=" . $user->getToken() . "'>Ici</a> <br><br>
                        Cordialement,<br> <a href=''>L'Equipe de SportCMS</a>";
                }

                // if admin register assign admin role and create admin email template
                if ($_SERVER['REQUEST_URI'] == '/admin-inscription') {
                
                $user->setRole('admin');
                
                $user->save();

                $mailBody = "
                        Bonjour {$user->getFirstname()}<br><br>Validez votre compte admin<br>
                        Afin de valider votre compte administrateur merci de cliquer sur le lien suivant <a href='http://localhost:81/confirmation-inscription?token=" . $user->getToken() . "'>Ici</a> <br><br>
                        Cordialement,<br> <a href=''>L'Equipe de SportCMS</a>";
                }

                // send email
                $mail = new Mail();
                $mail->sendTo($_POST['email']);
                $mail->subject("Confirmation inscription SportCMS");
                $mail->message($mailBody);
                
                if (!$mail->send()) {
                    // pas de die svp
                    die("Vous rencontrer une erreur lors de l'envoie de mail");
                }
                
                $_SESSION['success'] = "Un e-mail de confirmation vous a été envoyé pour valider votre compte !";
                header('Location:' . $_SERVER['REQUEST_URI']);
                
                }
                // quand tu arrive pour la premier fois pas de POST
                Router::render('front/security/register.view.php', ["user" => $user]);
        }




                           
                


    

        public function confirmaccount() {
            $user = new UserModel();
            
            if(!isset($user->getOneBy(['token' => $_GET['token']])[0])){
                die();
            }
            $user = $user->getOneBy(['token' => $_GET['token']])[0];

            if($user->getStatus() == 0){
                $user->setStatus(1);
                $user->save();
            }
            header('Location: /login');
        }

        // /* Gestion des rôles */ 
        // public function isAdmin(){
        //     $user = new UserModel();
        //     $foundAdmin = isset($_SESSION['email']) ? $user->getOneBy(['email' => $_SESSION['email']])[0] : null;

        //     if(!empty($foundAdmin)){
        //         die("Admin non trouvé");
        //     }

        //     if($foundAdmin->getRole() == "Admin"){
        //         return true;
        //     }
        // }

        // public function isCoach(){
        //     $user = new UserModel();
        //     $foundCoach = isset($_SESSION['email']) ? $user->getOneBy(['email' => $_SESSION['email']])[0] : null;
            
        //     if(empty($foundCoach)){
        //         die("Coach non trouvé");
        //     }

        //     if($foundCoach->getRole() == "Coach"){
        //         return true;
        //     }
        // }

        // public function isSubcriber(){
        //     $user = new UserModel();
        //     $foundSub = isset($_SESSION['email']) ? $user->getOneBy(['email' => $_SESSION['email']])[0] : null;

        //     if(empty($foundSub)){
        //         die("Subscriber non trouvé");
        //     }
        
        //     if($foundSub->getRole() == "Subscriber"){
        //         return true;
        //     }
        // }

        // public function isUser(){
        //     $user = new UserModel();
        //     $foundUser = isset($_SESSION['email']) ? $user->getOneBy(['email' => $_SESSION['email']])[0] : null;

        //     if(empty($foundUser)){
        //         die("User non trouvé");
        //     }
        
        //     if($foundUser->getRole() == "User"){
        //         return true;
        //     }
        // }
    }