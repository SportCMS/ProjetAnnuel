<?php
    namespace App\core;
    
    class Session{
        public function __construct(){
            if(session_status() === 1){
                session_start();
            }else{
                echo "Session déjà initialisé";
            }
        }
        public function set($key, $value){//ajouter des données à la session
            $_SESSION[$key] = $value;
        }
        public function get($key){//récupérer les données d'une session 
            if(array_key_exists($key, $_SESSION)){
                return $_SESSION[$key];
            }
            return null;
        }
        public function getAll(){//récupérer toutes les données d'une session 
            return $_SESSION;
        }
        public function clear(){//suppression de la session
            session_unset();
        }
    }