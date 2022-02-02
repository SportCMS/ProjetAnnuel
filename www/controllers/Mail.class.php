<?php
    namespace App\controllers;
    //Namespace à utiliser 
    use App\PHPMailer\PHPMailer;
    use App\PHPMailer\SMTP;
    use App\PHPMailer\Exception;

    class Mail
    {
        public function main(){
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
            $mail->addAddress("ketrazz.amh@gmail.com");
            $mail->Subject = "Test";
            $mail->Body = 'Bonjour ceci est le lien de notre site <a href="www.charenton.fr/">ici</a>';
            if($mail->Send()){
                echo "Email envoyé";
            }else{
                echo "Une erreur ";
            }
            $mail->smtpClose();
        }

    }