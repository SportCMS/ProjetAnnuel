<?php
    namespace App\controllers;
    use App\core\User as Us;
    use App\core\View;
    use App\models\User as UserModel;
    use App\models\Password_reset as PswdRst;
    use App\core\Verificator;
    use App\PHPMailer\PHPMailer;
    use App\PHPMailer\SMTP;
    use App\PHPMailer\Exception;

    class User {


        public function login()
        {
            $view = new View("login");
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
            if(!empty($_POST)) {
                $result = Verificator::checkForm($user->getLoginForm(), $_POST);
            }
            var_dump($result);
            echo "<br>";
            var_dump($_POST);
            $view = new View("Register");
            $view->assign("user", $user);
        }

        /******************************************************/
        /***************PARTIE CHANGER MOT DE PASSE************/
        /******************************************************/
        //Formulaire email user
        public function forgetPswd(){
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
                    $user = $user->getOneBy(["email" => $_POST['email']])[0];
                    if(!empty($user)){
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
                        echo "existe pas";
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
            $pswdRst = $pswdRst->getOneBy(["token" => $_GET["token"]])[0];
            if(!empty($pswdRst)){
                if($pswdRst->getTokenExpiry() > time()){
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
            if(!empty($_POST)) {
                $result = Verificator::checkForm($user->getChangePswdForm(), $_POST);
            }
            var_dump($result);
        }
    }