<?php

    namespace App\controllers;
    use App\core\View;
    use App\models\User as UserModel;
    use App\models\Report as ReportModel;
    use App\core\Router;
    class Admin
    {
        public function dashboard(): void
        {
        // session en dur pour les tests
        // a setter au login si role du user = 'admin
        $_SESSION['role'] = 'admin';

        $reportManager = new ReportModel();
        $reports = $reportManager->getReportNotifications();
        $_SESSION['report'] = count($reports);
        Router::render('admin/home.view.php');
        }

     
  
    }