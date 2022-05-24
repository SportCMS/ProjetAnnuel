<?php
    namespace App\controllers;
    use App\core\View;

    class Main {
        public function home()
        {
            $view = new View("home");
        }
    }