<?php
    namespace App\core;
    class View{

        private $view;
        private $template;
        private $data = [];

        public function __construct($view, $template = 'front')
        {
            $this->setView($view);
            $this->setTemplate($template);
        }
        public function setView($view):void{
            $this->view = strtolower($view);
        }
        public function setTemplate($template):void{
            $this->template = strtolower($template);
        }

        // public function assign($key, $value):void
        // {
        //     $this->data[$key] = $value;
        // }

        public function assign(array $array): void
        {
            foreach ($array as $key => $value) {
                $this->data[$key] = $value;
            }
         }

         
        public function includePartial($partial, $config): void
        {
            if(!file_exists("views/partial/".$partial.".partial.php")){
                die("Le partial ".$partial." n'existe pas");
            }
            include "views/partial/".$partial.".partial.php";
        }
        public function __destruct(){
            extract($this->data);
            include "views/" . $this->template . ".tpl.php";
        }

    }