<?php

namespace App\Core\verificator;


class VerificatorReport
{
    public static function validate($config, array $data): array
    {
        if (count($config["inputs"]) != count($_POST) && count($config["inputs"]) != count($_GET)) {
            return ['Une erreur est survenue'];
        }

        $errors = [];
        if (!$data['message'])
            $errors['message'] =  'Renseigner un message svp';

        if (!$data['email'])
            $errors['email'] =  'Renseigner un email valide svp';

        if (strlen($data['email']) > 100)
            $errors['email'] =  'La taille max de l\'email est de 100 caractères';

        return $errors;
    }
}