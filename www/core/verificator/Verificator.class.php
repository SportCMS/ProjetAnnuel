<?php
    namespace App\Core\verificator;

    class Verificator
    {
    
        public static function checkForm($config, $data): array
        {
            $errors = [];

            if( count($config["inputs"]) != count($_POST) && count($config["inputs"]) != count($_GET) ){//J'ai changé la vérif des nombres de champs pour les methood get
                return ['Une erreur est survenue'];
            }
    
            foreach ($config["inputs"] as $name=>$input)
            {
                if(!isset($data[$name])){
                    $errors[]="Il manque le champ :".$name;
                }
    
                if(!empty($input["required"]) && empty($data[$name]) ){
                    $errors[]=$name ." ne peut pas être vide";
                }
    
                if($name == "firstname" && self::isHtml($data[$name])){
                    $errors[] = 'Une erreur est survenue';
                }

                if($name == 'lastname' && self::isHtml($data[$name])){
                    $errors[] = 'Une erreur est survenue';
                }

                if($name == "firstname" && !self::maxLen($data[$name])){
                    $errors[] = 'Une erreur est survenue';
                }

                if($name == 'lastname' && !self::maxLen($data[$name])){
                    $errors[] = 'Une erreur est survenue';
                }

                if($input["type"]=="email" &&  !self::checkEmail($data[$name])) {
                    $errors[]=$input["error"];
                }

                if($input["type"]=="file" &&  !self::checkExtention($data[$name], $input["accept"])){
                    $errors[]=$input["error"];
                }
    
                if($input["type"]=="password" &&  !self::checkPwd($data[$name]) && empty($input["confirm"])){
                    $errors[]=$input["error"];
                }

                if( $name == "confirmPassword" && $data[$name]!==$data['password']  ){
                    $errors[]=$input["error"];
                }

                if($input['type']=='captcha' && !self::checkCaptcha($data['g-recaptcha-response'])){
                    $errors[]=$input["error"];
                }

            }

            return $errors;
        }
        
        public static function checkCaptcha($response){
            $userIP = $_SERVER['REMOTE_ADDR'];
            $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . CAPTCHASECRET . '&response=' . $response . '&remoteip=' . $userIP;
            $response = file_get_contents($url);
            $response = json_decode($response);
            return $response->success;
        }

        public static function checkExtention($file, $accepted): bool
        {
            $tbl = explode('.',$file);
            $in = false;
            foreach($accepted as $data){
                if(end($tbl) == $data){
                    $in = true;
                }
            }
            return $in;
        }

        public static function isHtml($str){
            return preg_match("/<[^<]+>/",$str) != 0;
        }
    
        public static function checkEmail($email): bool
        {
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        }
    
        public static function checkPwd($pwd): bool
        {
            return strlen($pwd)>=8
                && strlen($pwd)<=255
                && preg_match("/[0-9]/",$pwd, $result )
                && preg_match("/[A-Z]/",$pwd, $result );
        }

        public static function maxLen($str){
            return strlen($str)>=3
            && strlen($str)<=255;
        }
    }