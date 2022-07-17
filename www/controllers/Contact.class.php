<?php

namespace App\controllers;

use App\core\Sql;
use App\core\Router;
use App\models\Contact as ModelsContact;

class Contact extends Sql
{
    public function indexContact()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $message = isset($_POST['message']) ? $_POST['message'] : null;

            if (null == $message) {
                $alert = array('error', 'Veuillez renseigner un contenu');
                return Router::render('front/contact/index.view.php', ['alert' => $alert]);
            }
            $contact = new ModelsContact();
            $contact->setMessage($message);
            $contact->setEmail($_SESSION['email'] ?? 'test@gmail.com');
            $contact->save();
            $alert = array('success', 'Message envoyé');

            Router::render('front/contact/index.view.php', ['alert' => $alert]);
        }
        Router::render('front/contact/index.view.php');
    }
}
