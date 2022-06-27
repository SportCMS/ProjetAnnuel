<?php
namespace App\controllers;

use App\PHPMailer\PHPMailer;

class Mail
{
    public function main($email, $subject, $body){
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
        $mail->addAddress("$email");
        $mail->Subject = $subject;
        $mail->Body = $body;
        if($mail->Send()){
            echo "Email envoyé";
        }else{
            echo "Une erreur ";
        }
        $mail->smtpClose();
    }
}