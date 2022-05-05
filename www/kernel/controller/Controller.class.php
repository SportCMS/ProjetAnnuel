<?php
    namespace App\kernel\controller;

    class Controller{
        public static function create($name){
            $name = ucfirst(strtolower($name));
            $file = "controllers/" . $name . ".class.php";
            if(file_exists($file)){
                die ("\e[0;30;41mAttention ! Ce controller existe déjà\e[0m\n");
            }
            $content = "<?php
    namespace App\controllers;
    use App\core\View;

    class " . $name . "
    {
        public function main()
        {
            //Votre code
        }
    }";
            file_put_contents($file, $content);
            echo "\e[0;30;42mController crée avec succès !\e[0m\n\n";
            echo "\e[0;30;42mTemps éxecution : " . round((microtime(true) - EXECB), 4) .  "s\e[0m\n";
        }
    }
