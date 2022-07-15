<?php
    namespace App\models;
    use App\core\Sql;
    //retirer le coté pluriels de mes dossiers
    class User extends Sql{
        protected $id; // ?int  ?int
        protected $firstname = null;// ?string (?string)
        protected $lastname = null;// ?string (?string)
        protected $email;// string (string)
        protected $role;// string(string)
        protected $status = 0;// int (int)
        protected $password;// string (string)
        protected $token = null;// ?string (?string)
        protected $updated_at;// ?string (?string)
        protected $site;
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

        //role
        public function getRole():string
        {
            return $this->role;
        }
        public function setRole(string $role):void
        {
            $this->role = $role;
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
  
        public function getUpdatedAt():string
        {
            return $this->updated_at;
        }
        public function setUpdatedAt(string $updated_at):void
        {
            $this->updated_at = $updated_at;
        }

        public function getSite(): ?int
        {
            return $this->site;
        }
        public function setSite(int $site): void
        {
            $this->site = $site;
        }

        public function getRegisterForm(): array
        {
            return [
                "config"=>[
                    "method"=>"POST",
                    "action"=>"",
                    "id"=>"formRegister",
                    "class"=>"form_builder",
                    "submit"=>"S'inscrire"
                ],
                "inputs"=>[
                    "firstname"=>[
                        "label"=>"Prénom",
                        "placeholder"=>"Votre prénom ...",
                        "type"=>"text",
                        "id"=>"firstnameRegister",
                        "class"=>"form_input",
                        "minlength"=>2,
                        "maxlength"=>25,
                        "error"=>" Votre prénom doit faire entre 2 et 25 caractères",
                        "required"=>true,
                    ],
                    "lastname"=>[
                        "label"=>"Nom",
                        "placeholder"=>"Votre nom ...",
                        "type"=>"text",
                        "id"=>"lastnameRegister",
                        "class"=>"form_input",
                        "minlength"=>2,
                        "maxlength"=>100,
                        "error"=>" Votre nom doit faire entre 2 et 100 caractères",
                        "required"=>true,
                    ],
                    "email"=>[
                        "label"=>"Email",
                        "placeholder"=>"Votre email ...",
                        "type"=>"email",
                        "id"=>"emailRegister",
                        "class"=>"form_input",
                        "required"=>true,
                        "error"=>"Email incorrect",
                        "unicity"=>true,
                        "errorUnicity"=>"Un compte existe déjà avec cet email",
                        "pattern"=>"[a-z0-9-.]+@[a-z0-9-.]+.[a-z]{2,3}"
                    ],
                    "password"=>[
                        "label"=>"Mot de passe",
                        "placeholder"=>"Votre mot de passe ...",
                        "type"=>"password",
                        "id"=>"pwdRegister",
                        "class"=>"form_input",
                        "required"=>true,
                        "error"=>"Votre mot de passe doit faire au min 8 caratères avec une majuscule et un chiffre",
                        "minlength"=>8,
                        "pattern"=>"(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                    ],
                    "passwordConfirm"=>[
                        "label"=>"Confirmation du mot de passe",
                        "placeholder"=>"Confirmation ...",
                        "type"=>"password",
                        "id"=>"pwdConfirmRegister",
                        "class"=>"form_input",
                        "required"=>true,
                        "error"=>"Votre confirmation de mot de passe ne correspond pas",
                        "confirm"=>"password",
                        "minlength"=>8,
                        "pattern"=>"(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                    ]
                ]
            ];
        }
        public function getLoginForm(): array
        {
            return [
                "config"=>[
                    "method"=>"POST",
                    "action"=>"/login",
                    "id"=>"formLogin",
                    "class"=>"form_builder",
                    "submit"=>"Se connecter"
                ],
                "inputs"=>[
                    "email"=>[
                        "label"=>"Votre email",
                        "placeholder"=>"Votre email ...",
                        "type"=>"email",
                        "id"=>"emailRegister",
                        "class"=>"form_input",
                        "error"=>"Email incorrect",
                        "required"=>true,
                    ],
                    "password"=>[
                        "label"=>"Votre mot de passe",
                        "placeholder"=>"Votre mot de passe ...",
                        "type"=>"password",
                        "id"=>"pwdRegister",
                        "class"=>"form_input",
                        "error"=>"Password incorrect",
                        "required"=>true,
                        "error"=>"Email ou mot de passe incorrect",
                        "minlength"=>8,
                        "pattern"=>"(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                    ]
                ]
            ];
        }
        /* formulaire de d'envoie de l'email pour change mot de passe */
        public function getForgetPswdForm(){
            return ["config"=>[
                "method"=>"POST",
                "action"=>"forgetPassword",
                "id"=>"formRstPswd",
                "class"=>"form_builder",
                "submit"=>"Récuperer mot de passe"
                ],
                "inputs"=>[
                    "email"=>[
                        "placeholder"=>"Votre email ...",
                        "type"=>"email",
                        "id"=>"emailRegister",
                        "class"=>"form_input",
                        "required"=>true,
                        "error"=>"Email incorrect",
                        "unicity"=>true,
                        "errorUnicity"=>"Un compte existe déjà avec cet email",
                        "pattern"=>"[a-z0-9-.]+@[a-z0-9-.]+.[a-z]{2,3}"
                    ]
                ]
            ];
        }


        // public function getLogoutBtn(){
        //     return ["config"=>[
        //         "method"=>"POST",
        //         "action"=>"/logout",
        //         "id"=>"logou__btn",
        //         "class"=>"logout",
        //         "submit"=>"Se déconnecter"
        //     ]];
        // }
        public function getChangePswdForm($token){
            return ["config"=>[
                "method"=>"POST",
                "action"=>"changePassword?token=" . $token,
                "id"=>"formChangePswd",
                "class"=>"form_builder",
                "submit"=>"Changer de mot de passe"
                ],
                "inputs"=>[
                    "password"=>[
                        "placeholder"=>"Votre mot de passe ...",
                        "type"=>"password",
                        "id"=>"changePswd",
                        "class"=>"form_input",
                        "required"=>true,
                        "error"=>"mot de passe incorrect",
                        "minlength"=>8,
                        "pattern"=>"(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                    ],
                    "confirmPassword"=>[
                        "placeholder"=>"Confirmez votre mot de pase ...",
                        "type"=>"password",
                        "id"=>"changePswd",
                        "class"=>"form_input",
                        "required"=>true,
                        "error"=>"Pas le meme mot de passe",
                        "minlength"=>8,
                        "pattern"=>"(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                    ]
                ]
            ];
        }

        public function getUserProfileForm(){
            return [

                "config" => [
                    "method" => "POST",
                    "action" => "",
                    "id" => "form_userprofile",
                    "class" => "form_builder",
                    "submit" => "Sauvegarder"
                ],
                "inputs" => [
                    "firstname" => [
                        "type" => "text",
                        "label" => "Votre prénom",
                        "id" => "firstname",
                        "class" => "form_input",
                        "placeholder" => "Saisissez un prénom..",
                        "value" => $this->getFirstname() ?? "",
                        "minlength"=>2,
                        "maxlength"=>25,
                        "required"=>true
                    ],
                    "lastname" => [
                        "type" => "text",
                        "label" => "Votre nom",
                        "id" => "lastname",
                        "class" => "form_input",
                        "placeholder" => "Saisissez un nom..",
                        "value" => $this->getLastname() ?? "",
                        "minlength"=>2,
                        "maxlength"=>100,
                        "required"=>true
                    ],
                    "email" => [
                        "type" => "email",
                        "label" => "Votre email (inchangeable)",
                        "id" => "email",
                        "class" => "form_input",
                        "placeholder" => "nom@gmail.com",
                        "value" => $this->getEmail() ?? '',
                        "error" => "Votre email doit faire entre 8 et 320 caractères",
                        "required" => true,
                        "disabled" => 'disabled',
                        "pattern"=>"[a-z0-9-.]+@[a-z0-9-.]+.[a-z]{2,3}"
                    ],
                ]
            ];
        }


        public function getUserPwdForm(){
            return [

                "config" => [
                    "method" => "POST",
                    "action" => "",
                    "id" => "form_userprofile",
                    "class" => "form_builder",
                    "submit" => "Valider"
                ],
                "inputs" => [
                    
                    "oldPassword" => [
                        "type" => "password",
                        "label" => "Votre mot de passe actuel",
                        "id" => "pwd",
                        "class" => "form_input",
                        "placeholder" => "",
                        "error" => "L'ancien mot de passe est incorrecte",
                        "required" => true,
                        "pattern"=>"(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                    ],
                    "password" => [
                        "label" => "Votre nouveau mot de passe",
                        "type" => "password",
                        "id" => "pwd",
                        "class" => "form_input",
                        "placeholder" => "",
                        "error" => "Votre mot de passe doit faire au minimum 8 caractères et doit contenir minimum un chiffre et une majuscule",
                        "required" => true,
                        "pattern"=>"(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                    ],
                    "passwordConfirm" => [
                        "label" => "Confirmation",
                        "type" => "password",
                        "confirm" => "pwd",
                        "id" => "passwordConfirm",
                        "class" => "form_input",
                        "placeholder" => "",
                        "error" => "Votre mot de mot de passe de confirmation ne correspond pas",
                        "required" => true,
                        "pattern"=>"(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                    ],
                ]
            ];
        }
    }