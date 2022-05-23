<?php
    namespace App\controllers;
    use App\core\User as Us;
    use App\core\View;
    use App\models\User as UserModel;
    use App\models\Password_reset as PswdRst;
    use App\core\Verificator;
    use App\core\Sql;
    use App\PHPMailer\PHPMailer;
    use App\PHPMailer\SMTP;
    use App\PHPMailer\Exception;
    use App\core\Session;
    use App\controllers\Mail;

    class User {
        /* Session test*/
        public function login()
        {
            $view = new View("Login");
            $user = new UserModel();
            $view->assign("user", $user);
            if(empty($_POST)){
                die();
            }
            $errors = Verificator::checkForm($user->getLoginForm(), $_POST);
            if(!empty($errors)){
                $view->assign('errors', $errors);
                die();
            }
            if(!isset($user->getOneBy(['email' => $_POST['email']])[0])){
                $view->assign('errors', ["Votre email ou mot de passe est invalide"]);
                die();
            }
            $user = $user->getOneBy(['email' => $_POST['email']])[0];

            if(!password_verify($_POST['password'], $user->getPassword())){
                $view->assign('errors', ["Votre email ou mot de passe est invalide"]);
                die();
            }
            $status = $user->getStatus();
            
            if($status == 0){
                $view->assign('errors', ["Votre compte n'est pas encore actif"]);
                die();
            }
            $session = new Session();
            $session->set('email', $_POST['email']);
            header("Location: dashboard");
        }
        public function logout()
        {
            $user = new UserModel();
            $user = $user->setId(5);
            $pswdRst = new PswdRst();
            $pswdRst->generateToken();
            $pswdRst->generateTokenExpiry();
            $pswdRst->setIdUser($user);
            echo "<pre>";
            var_dump($pswdRst);
            echo "</pre>";
            $pswdRst->save();
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
            $view = new View("forgetpswd");
            $view->assign("user", $user);
        }
        //envoie mail utilisateur ou redirection vers formulaire 
        public function sendPswdRst(){
            $view = new View("forgetpswd");
            $user = new UserModel();
            $view->assign("user", $user);
            if(empty($_POST)){
                $view->assign("error", "Aie un champ a disparue. =,(");
                die();
            }
            $result = Verificator::checkForm($user->getForgetPswdForm(), $_POST);
            if(!empty($result)){
                $view->assign("error", "Aie ton email est mal écrit. =,(");
                die();
            }
            $user = $user->getOneBy(["email" => $_POST['email']]);
            if(empty($user)){
                $view->assign("error", "L'email n'existe pas. =,(");
                die();
            }
            $user = $user[0]; 
            $pswdRst = new PswdRst();
            $pswdRst->generateToken();
            $pswdRst->generateTokenExpiry();
            $pswdRst->setIdUser($user);
            
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = MAILHOST;
            $mail->SMTPAuth = "true";
            $mail->SMTPSecure = MAILENCRYPT;
            $mail->Port = MAILPORT;
            $mail->Username = MAILUSER;
            $mail->Password = MAILPSWD;
            $mail->iSHtml(true);
            $mail->setFrom(MAILUSER);
            $mail->addAddress($_POST['email']);
            $mail->Subject = "Test";
            $mail->Body = '<h1 style="color:blue">SPORTCMS</h1>
                <p>
                    Nous avons bien reçus votre demande de changement de mot de passe.
                </p>
                <div>
                    Changez de mot de passe en cliquant <a href="http://127.0.0.1:81/changePassword?token=' . $pswdRst->getToken() . '">ici</a>
                </div>
            ';
            if(!$mail->Send()){
                die("Vous rencontrer une erreur lors de l'envoie de mail");
            }
            $mail->smtpClose();
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
            $view = new View("changepswd");
            $view->assign("user", $user);
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
            $user = $user->setId($pswdRst->getIdUser());
            $user->setPassword($_POST['password']);
            $user->save();
            echo "Mot de passe changé";
        }
        /*****REGISTER*****/
        public function register(){$user = new UserModel();
            $view = new View("register");
            $view->assign("user", $user);
            /* Si post vide alors on affiche le formulaire */
            if(empty($_POST)){
                die();
            }
    
            $errors = Verificator::checkForm($user->getRegisterForm(), $_POST);
            /* si des erreurs sont présentes on renvois sur la vue avec les erreurs */
            if(!empty($errors)){
                $view->assign("errors", $errors);
                die();
            }
            $firstname = strip_tags($_POST['firstname']);
            $lastname = strip_tags($_POST['lastname']);
            /* si l'email est trouvé en base retour vue avec erreur */
            if(isset($user->getOneBy(['email' => $_POST['email']])[0])){
                $view->assign("errors",  ["L'utilisateur existe"]);
                die();
            }
    
            $user->setFirstname($firstname);
            $user->setLastname($lastname);
            $user->setEmail($_POST['email']);
            $user->setPassword($_POST['password']);
            $user->generateToken();
    
            $user->save();
    
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = MAILHOST;
            $mail->SMTPAuth = "true";
            $mail->SMTPSecure = MAILENCRYPT;
            $mail->Port = MAILPORT;
            $mail->Username = MAILUSER;
            $mail->Password = MAILPSWD;
            $mail->iSHtml(true);
            $mail->setFrom(MAILUSER);
            $mail->addAddress($_POST['email']);
            $mail->Subject = "Confirmation inscription SportCMS";
            $mail->Body = "
            Bonjour " . $user->getFirstname() .
            " <br><br>Nous avons bien reçu vos informations. <br>
            Afin de valider votre compte merci de cliquer sur le lien suivant <a href='http://localhost:81/confirmaccount?token=".$user->getToken()."'>Ici</a> <br><br>
            Cordialement,<br>
            <a href=''>L'Equipe de SportCMS</a>
            ";
            if(!$mail->Send()){
                die("Vous rencontrer une erreur lors de l'envoie de mail");
            }
            $mail->smtpClose();
            $view->assign("success", "Un e-mail de confirmation vous a été envoyé pour valider votre compte !");
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
    }