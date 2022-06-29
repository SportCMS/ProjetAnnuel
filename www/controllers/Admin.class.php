<?php

    namespace App\controllers;

    use App\models\Report as ReportModel;
    use App\models\MenuItem as MenuItemsModel;
    use App\models\Page as PageModel;
    use App\models\Article as ArticleModel;
    use App\models\User as UserModel;
    use App\models\Block as BlockModel;
    use App\models\Theme as ThemeModel;
    
    use App\core\Sql;
    use App\core\Router;

    use App\Helpers\Fixture;
    use App\Helpers\Slugger;

    class Admin extends Sql
    {
        public function dashboard(): void
        {
             //scenario installeur
            //1 - creer base mvcdocker2
            //2 - créer tables
             //$this->createtablesDevTestDatas();
            //3 - fixtures
             $fixtures = new Fixture();
             $fixtures->loadThemeTwentyFoot();
            //4 - aller sur l'installation : /installation
            //5 - valider le formulaire
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

    // gestion menu 
    public function editMenu()
    {
        $itemsManager = new MenuItemsModel();
        $items = $itemsManager->getAllByPosition();

        $pagesManager = new PageModel();
        $pages = $pagesManager->getAll();

        Router::render('admin/editMenu.view.php', [
            'items' => $items,
            'pages' => $pages
        ]);
    }

    public function addItem()
    {
        $item = new MenuItemsModel();
        $count = count($item->getAllByPosition());

        $item->setLink("/{$_POST['route']}");
        $item->setName($_POST['name']);
        $item->setPosition($count + 1);
        $item->save();

        echo json_encode(['id' => $count + 1]);
    }
    
    public function moveItemPosition(): void
    {
        $blockManager = new MenuItemsModel();
        var_dump($blockManager);
        
        foreach($_POST as $key => $value) {
            $blockManager->updateItemPosition($key, $value);
        }
        echo json_encode(['data' => $_POST, 'objet' => $blockManager]);
    }

        public function memberview()
        {
            $user = new UserModel();

            $users = $user->getAll();

            Router::render('admin/adminmember.view.php', ["users"=> $users]);
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
    

    

    public function editPage()
    {
        $pageModel = new PageModel();
        $block = new BlockModel();

        $data = $pageModel->getOneBy(['title' => $_GET['page']]);
        $page = $data[0];

        $blocksPage = $block->getBlockByPosition($page->getId());

        $positionBlocks = count($blocksPage);
        $position = $positionBlocks + 1;

        if (!$page) {
            header('Location: /gerer-mes-pages');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $block->createBlock($position, 'untitled', $page->getId());

            header('Location: /editPage?page=' . $_GET['page']);
        }
        Router::render('admin/page/editPage.view.php', ['blocksPage' => $blocksPage]);
    }

    public function deletePageAdmin(): void
    {
        $page = new PageModel();
        $menuItem = new MenuItemsModel();
        $item = $menuItem->getOneBy(['link' => '/' . $_GET['page']])[0] ?? null;

        if ($item != null) {
            $item->delete($item->getId());
        }
            $page->deletePage($_GET['page']);
            $this->eraseRoute($_GET['page']);
        

        header('Location: /gerer-mes-pages');
    }

    public function addPage()
    {
        $themeManager = new ThemeModel();
        $pageManager = new PageModel();
        $pages = $pageManager->getAll();
        $params = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (!$_POST['page_title'] || !$_POST['page_role'] || !$_POST['type']) {
                throw new \Exception('missing parameters');
            }
            $params['route'] = Slugger::sluggify(($_POST['page_title'])) ?? null;
            $params['role'] = strtolower($_POST['page_role']) ?? null;
            $params['model'] = strtolower($_POST['type']) ?? null;
            $params['action'] = 'index' .  ucfirst($params['model']) ?? null;

            $pageManager = new PageModel();

            $currentPages = $pageManager->getAll();
            foreach ($currentPages as $currentPage) {
                if ($currentPage['type'] == $params['model']) {
                    $message = 'Page déja existante';
                    Router::render('admin/page/addPage.view.php', ['pages' => $pages, "message" => $message]);
                    return false;
                }
            }
            $pageManager->setTitle($params['route']);
            $pageManager->setType($params['model']);
            $pageManager->setLink('/' . Slugger::sluggify($params['route']));
            $pageManager->setThemeId(1); // remplacer par la suite par l'id_theme en SESSION
            $pageManager->save();

            $pageData = $pageManager->getOneBy(['title' => $pageManager->getTitle()]);
            $page = $pageData[0];

            $block = new BlockModel();
            $block->setPageId($page->getId());
            $block->setPosition(1);
            $block->setTitle($_POST['page_title']);
            $block->save();

            $this->writeRoute($params);

            unset($_SESSION['flash']);
            header('Location: /gerer-mes-pages');
        }
        Router::render('admin/page/addPage.view.php', ['pages' => $pages]);
    }
}