<?php
    namespace App\core;

    use App\core\Observer;

    use App\core\Mail;

    class ReportNotification implements Observer{
        public function alert($email, $message){
            $mail = new Mail();

            $mail->sendTo($email);
            $mail->subject("Nouveau report fait");
            $mail->message($message);
            $mail->send();
        }
    }