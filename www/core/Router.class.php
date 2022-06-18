<?php

namespace App\core;

class Router
{
    // réunit la fonction assign de base et le compact, amélioration
    public static function render(string $vue, array $array = null): void
    {
        if ($array != null) {
            foreach ($array as $key => $value) {
                $data[$key] = $value;
            }
            extract($data);
        }
        include  'views/' . $vue;
        return;
    }

    public static function includePartial($partial, $config): void
    {
        if (!file_exists("views/admin/" . $partial . ".partial.php")) {
            die("Le partial " . $partial . " n'existe pas");
        }
        include "views/admin/" . $partial . ".partial.php";
    }
}