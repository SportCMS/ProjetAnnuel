<?php

namespace App\controllers;

use App\models\Report as ReportModel;
use App\models\MenuItem as MenuItemsModel;
use App\models\Page as PageModel;
use App\models\Article as ArticleModel;
use App\models\User as UserModel;
use App\models\Block as BlockModel;
use App\models\Theme as ThemeModel;
use App\models\Connexion as ConnexionModel;
use App\models\Contact as ContactModel;
use App\models\Input as InputModel;
use App\models\Text as TextModel;
use App\models\Form as FormModel;

use App\core\Sql;
use App\core\Router;

use App\Helpers\Fixture;
use App\Helpers\Slugger;

class Admin extends Sql
{
    public function dashboard(): void
    {
        //---------------------
        // $this->createtablesDevTestDatas();
        //$fixtures = new Fixture();
        //$fixtures->loadThemeTwentyFoot();


        $reportManager = new ReportModel();
        $reports = $reportManager->getReportNotifications();
        $_SESSION['report'] = count($reports);

        $connexionManager = new ConnexionModel();
        $connexionData = [
            '01' => $connexionManager->getConnexionByDate(date('Y-m-d'), '1'),
            '03' => $connexionManager->getConnexionByDate(date('Y-m-d'), '3'),
            '05' => $connexionManager->getConnexionByDate(date('Y-m-d'), '5'),
            '07' => $connexionManager->getConnexionByDate(date('Y-m-d'), '7'),
            '10' => $connexionManager->getConnexionByDate(date('Y-m-d'), '10'),
            '15' => $connexionManager->getConnexionByDate(date('Y-m-d'), '15'),
            '18' => $connexionManager->getConnexionByDate(date('Y-m-d'), '18'),
            '20' => $connexionManager->getConnexionByDate(date('Y-m-d'), '20'),
            '23' => $connexionManager->getConnexionByDate(date('Y-m-d'), '23'),
            '25' => $connexionManager->getConnexionByDate(date('Y-m-d'), '25'),
            '27' => $connexionManager->getConnexionByDate(date('Y-m-d'), '27'),
            '30' => $connexionManager->getConnexionByDate(date('Y-m-d'), '30')
        ];

        $userManager = new UserModel();
        $users = $userManager->getAll();
        $monthUsers = $userManager->countMonthUsers();
        $countWeekUsers = $userManager->countWeekUsers();
        $todayUsers = $userManager->countTodayUsers();

        $inscriptionData = [
            '01' => $connexionManager->getInscriptionByDate(date('Y-m-d'), '1', '5'),
            '05' => $connexionManager->getInscriptionByDate(date('Y-m-d'), '5', '10'),
            '10' => $connexionManager->getInscriptionByDate(date('Y-m-d'), '10', '15'),
            '15' => $connexionManager->getInscriptionByDate(date('Y-m-d'), '15', '20'),
            '20' => $connexionManager->getInscriptionByDate(date('Y-m-d'), '20', '25'),
            '25' => $connexionManager->getInscriptionByDate(date('Y-m-d'), '25', '30'),
        ];

        $contact = new ContactModel();
        //Récuperation des contacts
        $contacts = $contact->getAll();
        $_SESSION['contact'] = count($contacts);

        $lastsContacts = $contact->getLastContacts();

        $lastUsers = $userManager->getLastInscriptions();
        // var_dump($lastUsers);

        
        
        Router::render('admin/home.view.php', ['connexionData' => $connexionData,'userStat' => count($users), 'monthUsers' => $monthUsers, 'countWeekUsers' => $countWeekUsers, 'todayUsers' => $todayUsers, 'inscriptionData' => $inscriptionData, 'lastUsers' => $lastUsers, 'lastsContacts' => $lastsContacts]);
    }

    //Pour la barre de recherche
    public function searchUser()
    {
        if ($_POST['user'] == null) {
            echo json_encode(['status' => 'error', 'message' => 'probleme']);
            return;
        }
        $userManager = new UserModel();
        $users = $userManager->searchUsers($_POST['user']);
        echo json_encode(['status' => 'success', 'message' => 'success', 'res' => $users]);
    }

    public function memberview()
    {
        $user = new UserModel();

        $users = $user->getAll();

        Router::render('admin/users/users.view.php', ["users" => $users]);
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

        $page->setUpdatedAt((new \Datetime('now'))->format('Y-m-d H:i:s'));
        $page->save();

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
            $params['route'] = Slugger::sluggify((strtolower($_POST['page_title']))) ?? null;
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

    public function createTextBlock()
    {
        $blockManager = new BlockModel();
        $blockDatas = $blockManager->getOneBy(['id' => intval($_POST['block'])]);
        $block = $blockDatas[0];
        $block->setTitle('Text');
        $block->save();

        $text = new TextModel();
        $text->setBlockId($_POST['block']);
        $text->setContent($_POST['content']);
        $text->save();

        echo json_encode(['success' => 'Bloc de texte enregistré', 'content' => $_POST['content']]);
    }

    public function removeBlock()
    {
        $blockManager = new BlockModel();
        $blockManager->delete($_GET['id']);
        header('Location: /editPage?page=' . $_GET['page']);
    }
    public function createFormInput()
    {
        if (empty($_POST['block'])) {
            echo json_encode(['status' => 'error', 'message' => 'Informations manquantes : block']);
            return;
        }
        if (empty($_POST['type'])) {
            echo json_encode(['status' => 'error', 'message' => 'Informations manquantes : type']);
            return;
        }
        if (empty($_POST['name'])) {
            echo json_encode(['status' => 'error', 'message' => 'Informations manquantes : name']);
            return;
        }
        // create a new Form
        $formManager = new FormModel();
        $exist = $formManager->getOneBy(['block_id' => $_POST['block']])[0] ?? null;

        /*foreach ($formManager->getAll() as $formCheck) {
            if ($formCheck['title'] == $_POST['form']) {
                echo json_encode(['status' => 'error', 'message' => 'Nom de formulaire deja existant']);
                return;
            }
        }*/

        if ($exist == null) {
            $formManager->setBlockId($_POST['block']);
            $formManager->setTitle($_POST['form']);
            $formManager->save();
        }

        $form = $formManager->getOneBy(['title' => $_POST['form']])[0];

        //update block
        $blockManager = new BlockModel();
        $blockDatas = $blockManager->getOneBy(['id' => intval($_POST['block'])]);
        $block = $blockDatas[0];
        $block->setTitle('Form');
        $block->save();

        $input = new InputModel();
        $input->setLabel($_POST['label']);
        $input->setFormId($form->getId());
        $input->setJsId(null);
        $input->setJsclass(null);
        $input->setType($_POST['type']);
        $input->setPlaceholder($_POST['placeholder']);
        $input->setName($_POST['name']);
        $input->setValue(null);
        $input->save();

        echo json_encode(['status' => 'success', 'message' => 'input enregistré']);
    }
}