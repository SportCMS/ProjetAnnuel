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
        public function getRegisterForm(): array
        {
            return [
                "config"=>[
                    "method"=>"POST",
                    "action"=>"",
                    "id"=>"formRegister",
                    "class"=>"formRegister",
                    "submit"=>"S'inscrire"
                ],
                "inputs"=>[
                    "firstname"=>[
                        "label"=>"Prénom",
                        "placeholder"=>"Votre prénom ...",
                        "type"=>"text",
                        "id"=>"firstnameRegister",
                        "class"=>"formRegister",
                        "min"=>2,
                        "max"=>25,
                        "error"=>" Votre prénom doit faire entre 2 et 25 caractères",
                    ],
                    "lastname"=>[
                        "label"=>"Nom",
                        "placeholder"=>"Votre nom ...",
                        "type"=>"text",
                        "id"=>"lastnameRegister",
                        "class"=>"formRegister",
                        "min"=>2,
                        "max"=>100,
                        "error"=>" Votre nom doit faire entre 2 et 100 caractères",
                    ],
                    "email"=>[
                        "label"=>"Email",
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
                        "label"=>"Mot de passe",
                        "placeholder"=>"Votre mot de passe ...",
                        "type"=>"password",
                        "id"=>"pwdRegister",
                        "class"=>"formRegister",
                        "required"=>true,
                        "error"=>"Votre mot de passe doit faire au min 8 caratères avec une majuscule et un chiffre"
                    ],
                    "passwordConfirm"=>[
                        "label"=>"Confirmation du mot de passe",
                        "placeholder"=>"Confirmation ...",
                        "type"=>"password",
                        "id"=>"pwdConfirmRegister",
                        "class"=>"formRegister",
                        "required"=>true,
                        "error"=>"Votre confirmation de mot de passe ne correspond pas",
                        "confirm"=>"password"
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
                        "error"=>"Mot de passe invalide"
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


        // public function getLogoutBtn(){
        //     return ["config"=>[
        //         "method"=>"POST",
        //         "action"=>"/logout",
        //         "id"=>"logou__btn",
        //         "class"=>"logout",
        //         "submit"=>"Se déconnecter"
        //     ]];
        // }
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

        public function getUserProfileForm(){
            return [

                "config" => [
                    "method" => "POST",
                    "action" => "",
                    "id" => "form_userprofile",
                    "class" => "form_userprofile",
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
                    ],
                    "lastname" => [
                        "type" => "text",
                        "label" => "Votre nom",
                        "id" => "lastname",
                        "class" => "form_input",
                        "placeholder" => "Saisissez un nom..",
                        "value" => $this->getLastname() ?? "",
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
                        "disabled" => 'disabled'
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
                    "class" => "form_userprofile",
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
                        "required" => true
                    ],
                    "password" => [
                        "label" => "Votre nouveau mot de passe",
                        "type" => "password",
                        "id" => "pwd",
                        "class" => "form_input",
                        "placeholder" => "",
                        "error" => "Votre mot de passe doit faire au minimum 8 caractères et doit contenir minimum un chiffre et une majuscule",
                        "required" => true
                    ],
                    "passwordConfirm" => [
                        "label" => "Confirmation",
                        "type" => "password",
                        "confirm" => "pwd",
                        "id" => "passwordConfirm",
                        "class" => "form_input",
                        "placeholder" => "",
                        "error" => "Votre mot de mot de passe de confirmation ne correspond pas",
                        "required" => true
                    ],
                ]
            ];
        }
    }