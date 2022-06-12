<?php

<<<<<<< HEAD
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
=======
namespace App\controllers;

use App\core\Sql;
use Exception;
use App\core\View;

use App\models\Page as PageModel;
use App\models\Theme as ThemeModel;
use App\models\Block as BlockModel;


class Admin extends Sql
{
    public function home(): void
    {
        $firstname = 'jean';
        $view = new View("dashboard", "back");

        $pageManager = new PageModel();
        $pages = $pageManager->getAll();
        $view->assign([
            'firstname' => $firstname,
            'pages' => $pages
        ]);
    }

    public function addPage(): void
    {
        $view = new View("admin/addPage", 'back');
        $themeManager = new ThemeModel();
        $params = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (!$_POST['page_title'] || !$_POST['page_role'] || !$_POST['type']) {
                throw new \Exception('missing parameters');
            }
            $params['route'] = strtolower($_POST['page_title']) ?? null;
            $params['role'] = strtolower($_POST['page_role']) ?? null;
            $params['model'] = strtolower($_POST['type']) ?? null;
            $params['action'] = 'index' .  ucfirst($params['model']) ?? null;

            $pageManager = new PageModel();

            $currentPages = $pageManager->getAll();

            foreach ($currentPages as $currentPage) {
                if ($currentPage['type'] == $params['model']) {
                    $message = 'Page dÃ©ja existante';
                    $view->assign(["message" => $message]);
                    return;
                }
            }

            $pageManager->setTitle($params['route']);
            $pageManager->setType($params['model']);
            $pageManager->setLink('/' . $params['route']);
            $pageManager->setThemeId(1); // remplacer par la suite par l'id_theme en SESSION
            $pageManager->save();

            $pageData = $pageManager->getOneBy(['title' => $pageManager->getTitle()]);
            $page = $pageData[0];

            $block = new BlockModel();
            $block->setPageId($page->getId());
            $block->setPosition(1); // create a default position
            $block->setTitle($_POST['page_title']);
            $block->save();

            $this->writeRoute($params);

            header('Location: /dashboard');
>>>>>>> feature/slug
        }
    }

    # delete a page 
    public function deletePageAdmin(): void
    {
        $page = new PageModel();
        $block = new BlockModel();

        $page->deletePage($_GET['page']);
        $block->deleteBlock($_GET['id']);

        $this->eraseRoute($_GET['page']);

        header('Location: /dashboard');
    }


    /**
     * A FAIRE SAMEDI
     *
     */
    public function editPage()
    {
        $pageModel = new PageModel();
        $view = new View("admin/editPage", 'back');

        $data = $pageModel->getOneBy(['title' => $_GET['page']]);
        $page = $data[0];
    }

    /**
     * A FAIRE SAMEDI
     *
     */
    public function editMenu()
    {
        $view = new View("admin/editMenu", 'back');
    }

    # write route in route.yaml
    private function writeRoute(array $params): void
    {
        $content = file_get_contents('routes.yml');
        $content .= "\n\n/" . strtolower($params['route']) . ':';
        $content .= "\n  controller: " . $params['model'];
        $content .= "\n  action: " . $params['action'];
        $content .= "\n  role: [" . $params['role'] . "]";
        file_put_contents('routes.yml', $content);

        $file = fopen('views/' . strtolower($params['route']) . '.view.php', 'a');
        fwrite($file, '<h1>' . ucfirst($params['route']) . '</h1>');
        fclose($file);
    }

    private function eraseRoute(string $route): void
    {
        $content = file_get_contents('routes.yml');
        $arrayContent = explode('/', $content);

        $output = [];
        for ($i = 0; $i < count($arrayContent); $i++) {
            if (strstr($arrayContent[$i], $route) == false && $arrayContent[$i] != '') {
                $output[] = '/' . $arrayContent[$i];
            }
        }

        $content = file_get_contents('routes.yml');
        $content = '';
        for ($i = 0; $i < count($output); $i++) {
            $content .= $output[$i];
        }
        file_put_contents('routes.yml', $content);

        unlink('views/' . strtolower($route) . '.view.php');
    }
}