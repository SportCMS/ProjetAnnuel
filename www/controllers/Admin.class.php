<?php

    namespace App\controllers;
    use App\core\View;
    use App\models\User as UserModel;
    class Admin
    {
        public function home()
        {
            $firstname = 'simon';
            $view = new View("dashboard", "back");
            $view->assign(['firstname'=> $firstname]);
            
            
            $user = new UserModel();
            
            $view->assign(['user'=> $user]);
        }

        public function memberview()
        {
            $view = new View("adminmember", "back");
            $user = new UserModel();

            $users = $user->getAll();

            $view->assign(["users"=> $users]);
        }

        public function deleteUser()
        {
            $user = new UserModel();
            $user->delete($_GET['id']);
    
            header('Location: /adminmember');
        }

        public function editUserRole()
        {
            $usermanager = new UserModel(); // instancier le manager

            $userdatas = $usermanager->getOneBy(['id' => $_POST['id'] ]); // on récupère les données de l'utilisateur
            $selectedRole = $_POST['role']; // on récupère le role sélectionné
            $user = $userdatas[0]; // on récupère l'utilisateur
            $user->setRole($selectedRole); // on change le role de l'utilisateur
            $user->setUpdatedAt((new \DateTime('now'))->format('Y-m-d H:i:s')); // on change la date de modification
            $user->save(); // on sauvegarde l'utilisateur

            header('Location: /adminmember');
        }
    }