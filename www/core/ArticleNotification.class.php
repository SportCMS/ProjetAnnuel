<?php
    namespace App\core;

    use App\core\Observer;

    use App\core\Mail;

    class ArticleNotification implements Observer{
        public function alert($email, $message){
            $mail = new Mail();

            $mail->sendTo($email);
            $mail->subject("Un article viens d'être publié !");
            $mail->message($message);
            $mail->send();
        }
    }