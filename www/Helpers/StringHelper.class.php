<?php

namespace App\Helpers;

// sécuriser les chaine de caratère pour eviter les failles XSS


// exemple d'utilisation dans un controller pour verifier les champs
// $helper = new StringHelper();
// $name = $helper->secure($_POST['name']);
// $content = $helper->secure($_POST['content'], 'text'); 

class StringHelper
{
    public function secure($string, $type = null)
    {
        $string = htmlspecialchars(trim(strtolower($string)));
        if ($type != 'text') {
            $chars = ['@', '#', '&', '\"', '(', ')', '§', '°', '_', '<', '>', '¨', '^', '*', '$', '€', '%', '£', '`', '/', '\\', ':', '+', '=', ''];
            $string = str_replace($chars, '', $string);
        }
        return $string;
    }
}