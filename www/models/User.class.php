<?php
    namespace App\models;
    use App\core\Sql;
    //retirer le coté pluriels de mes dossiers
    class User extends Sql{
        protected $id; // ?int  ?int
        protected $firstname = null;// ?string (?string)
        protected $lastname = null;// ?string (?string)
        protected $email;// string (string)
        protected $status = 0;// int (int)
        protected $password;// string (string)
        protected $token = null;// ?string (?string)
        //Créer getter / setter
        public function __construct(){
            //echo 'créa user';
            parent::__construct();
        }
        //id
        public function getId():?int
        {
            return $this->id;
        }
        //firstname
        public function getFirstname():?string
        {
            return $this->firstname;
        }
        public function setFirstname(?string $firstname):void
        {
            $this->firstname = ucwords(strtolower(trim($firstname)));
        }
        //lastname
        public function getLastname():?string
        {
            return $this->lastname;
        }
        public function setLastname(?string $lastname):void
        {
            $this->lastname = strtoupper(trim($lastname));
        }
        //email
        public function getEmail():?string
        {
            return $this->email;
        }
        public function setEmail(?string $email):void
        {
            $this->email = $email;
        }
        //status
        public function getStatus():int
        {
            return $this->status;
        }
        public function setStatus(int $status):void
        {
            $this->status = $status;
        }
        //password
        public function getPassword():string
        {
            return $this->password;
        }
        public function setPassword(string $password):void
        {
            $this->password = password_hash($password, PASSWORD_DEFAULT);
        }
        //password
        public function getToken():?string
        {
            return $this->token;
        }
        public function generateToken():void
        {
            $bytes = random_bytes(128);
            $this->token = substr(str_shuffle(bin2hex($bytes)), 0, 255);
        }
        public function save(): void
        {
            parent::save();
        }
        public function getRegisterForm(): array
        {
            return [
                "config"=>[
                    "method"=>"POST",
                    "action"=>"/confirmAcc",
                    "id"=>"formRegister",
                    "class"=>"formRegister",
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
                    ],
                    "password"=>[
                        "placeholder"=>"Votre mot de passe ...",
                        "type"=>"password",
                        "id"=>"pwdRegister",
                        "class"=>"formRegister",
                        "required"=>true,
                        "error"=>"Votre mot de passe doit faire au min 8 caratères avec une majuscule et un chiffre"
                    ],
                    "passwordConfirm"=>[
                        "placeholder"=>"Confirmation ...",
                        "type"=>"password",
                        "id"=>"pwdConfirmRegister",
                        "class"=>"formRegister",
                        "required"=>true,
                        "error"=>"Votre confirmation de mot de passe ne correspond pas",
                        "confirm"=>"password"
                    ],
                    "firstname"=>[
                        "placeholder"=>"Votre prénom ...",
                        "type"=>"text",
                        "id"=>"firstnameRegister",
                        "class"=>"formRegister",
                        "min"=>2,
                        "max"=>25,
                        "error"=>" Votre prénom doit faire entre 2 et 25 caractères",
                    ],
                    "lastname"=>[
                        "placeholder"=>"Votre nom ...",
                        "type"=>"text",
                        "id"=>"lastnameRegister",
                        "class"=>"formRegister",
                        "min"=>2,
                        "max"=>100,
                        "error"=>" Votre nom doit faire entre 2 et 100 caractères",
                    ]
                ]
            ];
        }
        public function getLoginForm(): array
        {
            return [
                "config"=>[
                    "method"=>"POST",
                    "action"=>"",
                    "id"=>"formLogin",
                    "class"=>"formLogin",
                    "submit"=>"Se connecter"
                ],
                "inputs"=>[
                    "email"=>[
                        "placeholder"=>"Votre email ...",
                        "type"=>"email",
                        "id"=>"emailRegister",
                        "class"=>"formRegister",
                        "error"=>"Email incorrect",
                        "required"=>true,
                    ],
                    "password"=>[
                        "placeholder"=>"Votre mot de passe ...",
                        "type"=>"password",
                        "id"=>"pwdRegister",
                        "class"=>"formRegister",
                        "error"=>"Password incorrect",
                        "required"=>true,
                    ]
                ]
            ];
        }
        /* formulaire de d'envoie de l'email pour change mot de passe */
        public function getForgetPswdForm(){
            return ["config"=>[
                "method"=>"POST",
                "action"=>"sendPasswordReset",
                "id"=>"formRstPswd",
                "class"=>"formRstPswd",
                "submit"=>"Récuperer mot de passe"
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
        public function getChangePswdForm(){
            return ["config"=>[
                "method"=>"POST",
                "action"=>"confirmChange",
                "id"=>"formChangePswd",
                "class"=>"formChangePswd",
                "submit"=>"Changer de mot de passe"
                ],
                "inputs"=>[
                    "password"=>[
                        "placeholder"=>"Votre mot de passe ...",
                        "type"=>"password",
                        "id"=>"changePswd",
                        "class"=>"changePswd",
                        "required"=>true,
                        "error"=>"mot de passe incorrect"
                    ],
                    "confirmPassword"=>[
                        "placeholder"=>"Confirmez votre mot de pase ...",
                        "type"=>"password",
                        "id"=>"changePswd",
                        "class"=>"changePswd",
                        "required"=>true,
                        "error"=>"Pas le meme mot de passe"
                    ]
                ]
            ];
        }
    }