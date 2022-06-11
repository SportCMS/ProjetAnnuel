<?php

    namespace App\controllers;
    use App\core\View;
    use App\models\User as UserModel;
    use App\models\Report as ReportModel;
    class Admin
    {
        public function home()
        {
            // $firstname []= 'simon';
            $view = new View("dashboard", "back");
    
            $reportManager = new ReportModel();
            $reports = $reportManager->getReportNotifications();
            $_SESSION['report'] = count($reports);
            // $view->assign(['firstname' => $firstname]);
            
            
            // $user = new UserModel();
            
            // $view->assign(["user" => $user]);
        }
    }