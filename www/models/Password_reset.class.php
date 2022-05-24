<?php
    namespace App\models;
    use App\core\Sql;
    //retirer le coté pluriels de mes dossiers
    class Password_reset extends Sql{
        /** 
         *
         * @var int
         * 
         */
        protected $id;
        /** 
         *
         * @var string
         * 
         */
        protected $token;
        /** 
         *
         * @var string
         * 
         */
        protected $tokenExpiry;
        /** 
         *
         * @var int
         * 
         */
        protected $id_user;

        public function __construct(){
            parent::__construct();
        }

        public function getId():?int
        {
            return $this->id;
        }
        //token
        public function getToken():?string
        {
            return $this->token;
        }
        public function generateToken():void
        {
            $length = 32;
            $this->token = bin2hex(random_bytes($length));
        }
        //tokenExpiry (YYYY-MM-DD hh:mm:ss)
        public function getTokenExpiry():?string
        {
            return $this->tokenExpiry;
        }
        public function generateTokenExpiry():void
        {
            $this->tokenExpiry = time() + (60*15);
        }
        //test
        public function getIdUser():?int
        {
            return $this->id_user;
        }
        public function setIdUser(?user $user):void
        {
            $this->id_user = $user->getId();
        }
        public function save(): void
        {
            parent::save();
        }
        public function getRstPswdForm(): array
        {
            return [
                "config"=>[
                    "method"=>"POST",
                    "action"=>"",
                    "id"=>"formRstPswd",
                    "class"=>"formRstPswd",
                    "submit"=>"S'inscrire"
                ],
                "inputs"=>[
                    "email"=>[
                        "placeholder"=>"Votre email ...",
                        "type"=>"email",
                        "id"=>"emailRegister",
                        "class"=>"formRegister",
                        "required"=>true,
                        "error"=>"Email incorrect",
                        "unicity"=>true,
                        "errorUnicity"=>"Un compte existe déjà avec cet email"
                    ]
                ]
            ];
        }
    }