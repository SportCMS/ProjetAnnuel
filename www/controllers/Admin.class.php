<?php

    namespace App\controllers;
    use App\core\View;
    use App\models\User as UserModel;
    use App\models\Report as ReportModel;
    use App\core\Router;
    use App\models\Theme as ThemeModel;
    use App\models\Page as PageModel;
    use App\models\Article as ArticleModel;
    use App\core\Sql;



    class Admin extends Sql
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

        public function indexArticle()
    	{
        $article = new ArticleModel();

        $all_article = $article->getAll();

        Router::render("admin/article/articles.view.php", [
            "all_article" => $all_article,
            
        ]);
    	}

        public function addPage(): void
        {
        $themeManager = new ThemeModel();
        $pageManager = new PageModel();
        $pages = $pageManager->getAll();

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
                    $message = 'Page déja existante';
                    Router::render('admin/addPage.view.php', ["message" => $message]);
                }
            }

            $pageManager->setTitle($params['route']);
            $pageManager->setType($params['model']);
            $pageManager->setLink('/' . $params['route']);
            $pageManager->setThemeId(1); // remplacer par la suite par l'id_theme en SESSION
            $pageManager->save();

            $pageData = $pageManager->getOneBy(['title' => $pageManager->getTitle()]);
            $page = $pageData[0];

          
            $this->writeRoute($params);

            header('Location: /dashboard');
        }
        Router::render('admin/addPage.view.php', ['pages' => $pages]);
    }



    # delete a page 
    public function deletePageAdmin(): void
    {
        $page = new PageModel();
        

        $page->deletePage($_GET['page']);
        $this->eraseRoute($_GET['page']);

        header('Location: /gerer-mes-pages');
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

       
    }
}