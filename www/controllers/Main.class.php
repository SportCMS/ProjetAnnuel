<?php

    namespace App\controllers;
    
    use App\core\Router;
    use App\core\Sql;
    use App\models\Connexion;
    use App\models\User;

    class Main extends Sql {
        public function home()
        {
            $ip = $_SERVER['REMOTE_ADDR'];
            $connexionManager = new Connexion();
            $userManager = new User();
    
            if (isset($_SESSION['email'])) {
                $userDatas = $userManager->getOneBy(['email' => 'johndoe@gmail.com']);
                $user =  $userDatas[0] ?? null;
                if ($user != null) {
                    $connexionManager->setUserId($user->getId());
                }
            }
    
            $datas = $connexionManager->getOneBy(['ip' => $ip]);
            $existingConnexion = $datas[0] ?? null;
    
            // on compte une meme connexion/ip par jour
            if ($existingConnexion != null && (new \Datetime($existingConnexion->getDate()))->format('Y-m-d') == date('Y-m-d')) {
                return Router::render('front/home/home.view.php');
            }
    
            $connexionManager->setIp($ip);
            $connexionManager->save();
    
            Router::render('front/home/home.view.php');
        }

        
    }