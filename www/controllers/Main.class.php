<?php

    namespace App\controllers;
    
    use App\core\Router;
    use App\core\Sql;
    use App\models\Connexion;
    use App\models\User;

    class Main extends Sql {
        public function home()
        {
            $ip = $_SERVER['REMOTE_ADDR']; // Récupère l'adresse IP de l'utilisateur  
            $connexionManager = new Connexion(); // Instancie un nouvel objet Connexion
            $userManager = new User(); // Instancie un nouvel objet User  
    
            if (isset($_SESSION['email'])) {    // Si l'utilisateur est connecté
                $userDatas = $userManager->getOneBy(['email' => $_SESSION['email']]); // get user datas from db
                $user =  $userDatas[0] ?? null;
                if ($user != null) { // Si l'utilisateur existe dans la base de données
                    $connexionManager->setUserId($user->getId());
                }
            }
    
            $datas = $connexionManager->getBy(['ip' => $ip, 'user_id' => $user->getId()]); // get connexion datas from db
            $existingConnexion = end($datas) ?? null; // On prend le dernier élément de la liste

            // Si l'utilisateur a déjà fait une connexion aujourd'hui 
            if ($existingConnexion != null && (new \Datetime($existingConnexion->getDate()))->format('Y-m-d') == date('Y-m-d')) {
                return Router::render('front/home/home.view.php'); 
            }
            //Sinon, on l'enregistre dans la base de données
            $connexionManager->setIp($ip);  // On set l'adresse IP de l'utilisateur
            $connexionManager->save();
    
            Router::render('front/home/home.view.php'); 
        }

        
    }