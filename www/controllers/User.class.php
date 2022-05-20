<?php
    namespace App\controllers;
    session_start();
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
            $user = new UserModel();
            $user = $user->setId(5);
            $pswdRst = new PswdRst();
            $pswdRst->generateToken();
            $pswdRst->generateTokenExpiry();
            $pswdRst->setUserId($user);
            echo "<pre>";
            var_dump($pswdRst);
            echo "</pre>";
            $pswdRst->save();
        }
        public function register(){
            $user = new UserModel();
<<<<<<< HEAD
            if(!empty($_POST)) {
                $result = Verificator::checkForm($user->getLoginForm(), $_POST);
            }
            var_dump($result);
            echo "<br>";
            var_dump($_POST);
            $view = new View("Register");
            $view->assign("user", $user);
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
            $user = new UserModel();
            if(!empty($_POST)) {
                $result = Verificator::checkForm($user->getForgetPswdForm(), $_POST);
                if(empty($result)){
                    $user = $user->getOneBy(["email" => $_POST['email']]);
                    if(!empty($user)){
                        $user = $user[0]; 
                        $pswdRst = new PswdRst();
                        $pswdRst->generateToken();
                        $pswdRst->generateTokenExpiry();
                        $pswdRst->setUserId($user);
                        
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
                        if($mail->Send()){
                            echo "Email envoyé";
                            $pswdRst->save();
                        }else{
                            echo "Une erreur ";
                        }
                        $mail->smtpClose();
                        
                    }else{
                        echo "email existe pas";
                    }
                }else{
                    $view = new View("forgetpswd");
                    $view->assign("error", "Aie ton email est mal écrit. =,(");
                    $view->assign("user", $user);
                }
            }else{
                $view = new View("forgetpswd");
                $view->assign("error", "Aie un champ a disparue. =,(");
                $view->assign("user", $user);
            }
        }
        //formulaire changement du mot de passe
        public function changePswd(){
            $pswdRst = new PswdRst();
            $user = new UserModel();
            if(!empty($pswdRst->getOneBy(["token" => $_GET["token"]])[0])){
                $pswdRst = $pswdRst->getOneBy(["token" => $_GET["token"]])[0];
                if($pswdRst->getTokenExpiry() > time()){
                    $session = new Session();
                    $session->set("token", $pswdRst->getToken());
                    $view = new View("changepswd");
                    $view->assign("user", $user);
                }else{
                    echo '<p style="color:red;">Le token n\'est plus valide</p>';
                }
            }else{
                echo '<p style="color:red;">Le token n\'existe pas</p>';
            }
        }
        //confirm changement mot de passe
        public function confirmChng(){
            $user = new UserModel();
            $pswdRst = new PswdRst();
            $session = new Session();
            $pswdRst = $pswdRst->getOneBy(["token" => $session->get('token')])[0];
            if(!empty($pswdRst) && $pswdRst->getTokenExpiry() > time()){
                if(!empty($_POST)) {
                    $result = Verificator::checkForm($user->getChangePswdForm(), $_POST);
                    if(empty($result)){
                        $user = $user->setId($pswdRst->getUserId());
                        $user->setPassword($_POST['password']);
                        $user->save();
                        echo "Mot de passe changé";
                    }
                }
            }
        }
    }
=======

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

>>>>>>> c9ef9f6a58322a87dd03c74ca97033e30b17cdf5
