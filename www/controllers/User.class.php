<?php

    namespace App\controllers;

    use App\models\User as UserModel;
    use App\models\Password_reset as PswdRst;

    use App\core\verificator\VerificatorPwd;
    use App\core\verificator\Verificator;
    
    use App\core\Session;
    use App\core\Mail;
    use App\core\Router;

    class User {
        /* Session test*/
        public function login()
        {
            $user = new UserModel();
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
        
                $errors = Verificator::checkForm($user->getLoginForm(), $_POST);
                if(count($errors) > 0){
                    Router::render('front/security/login.view.php',["user" => $user, 'errors' => $errors]);
                    return;
                }
                if(!isset($user->getOneBy(['email' => $_POST['email']])[0])){
                    $errors = [];
                    $errors[] = "Email ou mot de passe incorrect";
                    Router::render('front/security/login.view.php',["user" => $user, 'errors' => $errors]);
                    return;
                }
                $user = $user->getOneBy(['email' => $_POST['email']])[0];

                if(!password_verify($_POST['password'], $user->getPassword())){
                    Router::render('front/security/login.view.php',["user" => $user,'errors' => $errors]);
                    return;
                }
                $status = $user->getStatus();
                
                if($status == 0){
                    $errors = [];
                    $errors[] = "Veuillez activer votre compte via l'email que vous avez reçu !";
                    Router::render('front/security/login.view.php',["user" => $user, 'errors' => $errors]);
                    return;
                }

                $_SESSION['email'] = $_POST['email'];
                $_SESSION['firstname'] = $user->getFirstname();
                $_SESSION['lastname'] = $user->getLastname();
                $_SESSION['id'] = $user->getId();
                $_SESSION['role'] = $user->getRole();
                if ($user->getSite() != null) {
                    $_SESSION['site'] = $user->getSite();
                }

                //Si user, on redirige vers home
                if(isset($_SESSION['role'])){
                    if ($_SESSION['role'] == 'user') {
                        header("Location: /home");
                    }
                    //Tant que le statut != 2, on rediride vers installation
                    if ($_SESSION['role'] == 'admin' && $user->getStatus() == 1) {
                        header("Location: /installation");
                    }

                    if ($_SESSION['role'] == 'admin' && $user->getStatus() == 2) {
                        header("Location: /dashboard");
                    }
                }

            }
            $errors = [];
            if(isset($_SESSION['error'])){
                $errors[] = $_SESSION['error'];
                unset($_SESSION['error']);
            }
            Router::render('front/security/login.view.php',["user" => $user, 'errors' => $errors]);
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
            $user = new UserModel();
            if(empty($_POST)){
                return Router::render('front/security/forgetpswd.view.php',["user" => $user]);
            }
            $result = Verificator::checkForm($user->getForgetPswdForm(), $_POST);
            if(!empty($result)){
                return Router::render('front/security/forgetpswd.view.php',["user" => $user, "errors" => ["Champ mal renseigné"]]);
            }
            $user = $user->getOneBy(["email" => $_POST['email']]);
            if(empty($user)){
                $user = new UserModel();
                return Router::render('front/security/forgetpswd.view.php',["user" => $user, "errors" => ["Email introuvable"]]);
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
                abort(500);
            }
            
            $pswdRst->save();
            $_SESSION['success'] = "Vous allez recevoir un mail pour modifier votre mot de passe !";
            Router::render('front/security/forgetpswd.view.php',["user" => $user]);
        }
        //envoie mail utilisateur ou redirection vers formulaire 
        public function sendPswdRst(){
            $user = new UserModel();
            if(empty($_POST)){
                return Router::render('front/security/forgetpswd.view.php',["user" => $user, "errors" => ["Une erreur est survenue"]]);
            }
            $result = Verificator::checkForm($user->getForgetPswdForm(), $_POST);
            if(!empty($result)){
                
            }
            $user = $user->getOneBy(["email" => $_POST['email']]);
            if(empty($user)){
                
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
            $_SESSION['success'] = "Vous allez recevoir un mail pour modifier votre mot de passe !";
            Router::render('front/security/forgetpswd.view.php',["user" => $user]);
        }

        //formulaire changement du mot de passeEmail envoyé
        public function changePswd(){
            $user = new UserModel();
            if(!isset($_GET["token"])){
                return Router::render('front/security/changepswd.view.php',["user" => $user, "errors" => ["Une erreur est survenue"]]);
            }
            $token = $_GET["token"];
            $pswdRst = new PswdRst();
            if(empty($pswdRst->getOneBy(["token" => $token])[0])){
                return Router::render('front/security/changepswd.view.php',["user" => $user, "token" => $token, "errors" => ["Une erreur est survenue"]]);
            }
            $pswdRst = $pswdRst->getOneBy(["token" => $token])[0];
            if($pswdRst->getTokenExpiry() < time()){
                return Router::render('front/security/changepswd.view.php',["user" => $user, "token" => $token, "errors" => ["Votre récupération de mot de passe a expiré"]]);
            }
            if(empty($_POST)){
                return Router::render('front/security/changepswd.view.php',["user" => $user, "token" => $token]);
            }
            $result = Verificator::checkForm($user->getChangePswdForm($token), $_POST);
            if(!empty($result)){
                return Router::render('front/security/changepswd.view.php',["user" => $user, "token" => $token, "errors" => $result]);
            }
            $user = $user->setId($pswdRst->getIdUser());
            $user->setPassword($_POST['password']);
            $user->save();
            $_SESSION['success'] = "Votre mot de passe a bien été modifié !";
            $pswdRst->deleteL();
            Router::render('front/security/changepswd.view.php',["user" => $user, "token" => $token]);
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
        
        public function register(){
            $user = new UserModel();
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $errors = Verificator::checkForm($user->getRegisterForm(), $_POST);
                
                if(count($errors) > 0){
                    return Router::render('front/security/register.view.php',["user" => $user, "errors" => $errors]);
                }
                
                $firstname = htmlspecialchars($_POST['firstname']);
                $lastname = htmlspecialchars($_POST['lastname']);
                $email = htmlspecialchars($_POST['email']);
                
                
                
                if(isset($user->getOneBy(['email' => $_POST['email']])[0])){
                    $errors = [];
                    $errors[] = "L'utilisateur existe déjà"; 
                    return Router::render('front/security/register.view.php',["user" => $user, "errors" => $errors]);
                }

                $password = htmlspecialchars($_POST['password']);
                $passwordConfirm = htmlspecialchars($_POST['passwordConfirm']);

                if ($password !== $passwordConfirm) {
                    $errors[] = "Les mots de passe doivent correspondre";
                    // *user permet de réafficher le formulaire
                    return Router::render('front/security/register.view.php', ["user" => $user, "errors" => $errors]);
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
                    abort(500);
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
                redirect("/login", ["error" => "Une erreur est survenue lors de la vérification de votre compte !"]);
            }
            $user = $user->getOneBy(['token' => $_GET['token']])[0];

            if($user->getStatus() == 0){
                $user->setStatus(1);
                $user->save();
            }
            header('Location: /login');
        }


        public function changePwd(){
            $user = new UserModel();
            //Récupérer les infos du USER grâce à la session
            if (isset($_SESSION['email'])){
                $user = $user->getOneBy(['email' => $_SESSION['email']])[0];
            }else{
                header('Location:/non-autorise');
            }

            $status = $user->getStatus();
            
            if($_SERVER['REQUEST_METHOD'] == 'POST'){

                $errors = VerificatorPwd::checkForm($user->getUserPwdForm(), $_POST);
                if(count($errors) > 0){
                    Router::render('front/security/user_profilPwd.view.php', ["user" => $user, "errors" => $errors]);
                    return;
                }

                //Vérification Ancien mot de passe

                $password = htmlspecialchars($_POST['password']);
                $passwordConfirm = htmlspecialchars($_POST['passwordConfirm']);
                $oldPassword = htmlspecialchars($_POST['oldPassword']);
                if(!password_verify($oldPassword, $user->getPassword()) && $status == 1){
                    $errors=[];
                    $errors[] = "Ancien mot de passe n'est pas bon";
                    Router::render('front/security/user_profilPwd.view.php', ["user" => $user, "errors" => $errors]);
                    return;
                }   

                if ($password === $oldPassword){

                    $errors=[];
                    $errors[] = "Le nouveau mot de passe ne doit pas être similaire à l'ancien";
                    Router::render('front/security/user_profilPwd.view.php', ["user" => $user, "errors" => $errors]);
                    return;
                }


                if($password !== $passwordConfirm){
                    $errors=[];
                    $errors[] = "Vos mots de passe ne correspondent pas !!!";
                    Router::render('front/security/user_profilPwd.view.php', ["user" => $user, "errors" => $errors]);
                    return;
                }

                //Vérification du nouveau password
                    $user->setPassword($password);
                    $user->save();
                    $infos=[];
                    $infos[] = "Votre mot de passe a été modifié";
                    Router::render('front/security/user_profilPwd.view.php', ["user" => $user, "infos" => $infos]);
                    return;
                
            }
            Router::render('front/security/user_profilPwd.view.php', ["user" => $user]);
        }

        public function getUserProfile(){
            $user = new UserModel();
            if(isset($_SESSION['email'])){
                $user = $user->getOneBy(['email' => $_SESSION['email']])[0];
            }else{
                header('Location:/non-autorise');
            }
            
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                //Sécurité sur le champs email
                if(isset($_POST['email']) && $_POST['email'] != $user->getEmail()){
                    $errors[]="Une erreur est survenue, veuillez réessayer.";
                    return Router::render('front/security/user_profile.view.php', ["user" => $user, "errors" => $errors]);
                }
                //Changement du prénom
                if($_POST['firstname'] != $user->getFirstname()){
                    $user->setFirstname(htmlspecialchars($_POST['firstname']));
                    $user->save();

                    $infos[] = "Votre prénom a bien été modifié !";
                }
                //Changement du nom
                if($_POST['lastname'] != $user->getLastname()){
                    $user->setLastname(htmlspecialchars($_POST['lastname']));
                    $user->save();

                    $infos[] = "Votre nom a bien été modifié !";
                }
                if(isset($infos)){
                    return Router::render('front/security/user_profile.view.php', ["user" => $user, "infos" => $infos]);
                }
            }
            // quand tu arrive pour la premier fois pas de POST
            Router::render('front/security/user_profile.view.php', ["user" => $user]);
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