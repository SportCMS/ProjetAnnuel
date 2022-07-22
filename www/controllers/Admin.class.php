<?php

namespace App\controllers;

use App\core\Sql;
use App\core\Router;
use App\models\Form;
use App\models\Text;
use App\models\User;
use App\models\Input;
use App\models\Contact;
use App\Helpers\Slugger;
use App\models\Connexion;
use App\models\Page;
use App\models\Block;
use App\models\Theme;
use App\models\Report;
use App\models\Article;
use App\models\MenuItem;

/*  DESIGN PATTERN FACTORY */
use App\models\factories\BlockContentFactory;
use App\models\factories\ContentText;
use App\models\factories\ContentForm;


//Query builder
use App\querys\QueryUser;

class Admin extends Sql
{
    # dashboard main page
    public function dashboard(): void
    {

        $qu = new QueryUser();
        
        $reportManager = new Report();
        // recuperer les signalements
        $reports = $reportManager->getReportNotifications();

        $userManager = new User();
        $users = $userManager->getAll();
        // inscriptions du mois
        $monthUsers = $qu->countMonthUsers();
        //inscriptions cette semaine
        $countWeekUsers = $qu->countWeekUsers();
        // inscriptions aujourd'hui
        $todayUsers = $qu->countTodayUsers();

        $contact = new Contact();
        $contacts = $contact->getAll();
        // derniers messages de contact
        $lastsContacts = $contact->getLastContacts();

        $_SESSION['contact'] = count($contacts);
        $_SESSION['report'] = count($reports);

        $connexionManager = new Connexion();
        // stats connexions au site du mois
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

        // stats inscriptions du mois
        $inscriptionData = [
            '01' => $connexionManager->getInscriptionByDate(date('Y-m-d'), '1', '5'),
            '05' => $connexionManager->getInscriptionByDate(date('Y-m-d'), '5', '10'),
            '10' => $connexionManager->getInscriptionByDate(date('Y-m-d'), '10', '15'),
            '15' => $connexionManager->getInscriptionByDate(date('Y-m-d'), '15', '20'),
            '20' => $connexionManager->getInscriptionByDate(date('Y-m-d'), '20', '25'),
            '25' => $connexionManager->getInscriptionByDate(date('Y-m-d'), '25', '30'),
        ];

        // derniers inscrits
        $lastUsers = $qu->getLastInscriptions();

        Router::render('admin/home.view.php', [
            'userStat' => count($users),
            'monthUsers' => $monthUsers,
            'countWeekUsers' => $countWeekUsers,
            'todayUsers' => $todayUsers,
            'connexionData' => $connexionData,
            'inscriptionData' => $inscriptionData,
            'lastUsers' => $lastUsers,
            'lastsContacts' => $lastsContacts
        ]);
    }

    /**
     * ajoute une nouvelle page au site
     *
     * @return void
     */
    public function addPage()
    {
        $themeManager = new Theme();
        $pageManager = new Page();
        $pages = $pageManager->getAll();
        $params = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (!$_POST['page_title'] || !$_POST['page_role'] || !$_POST['type']) {
                throw new \Exception('missing parameters');
            }

            // on cree un tableau de donnée de la page
            $params['route'] = Slugger::sluggify(($_POST['page_title'])) ?? null;
            $params['role'] = strtolower($_POST['page_role']) ?? null;
            $params['model'] = strtolower($_POST['type']) ?? null;
            $params['action'] = 'index' .  ucfirst($params['model']) ?? null;

            $pageManager = new Page();
            // on recupère les pages du theme
            $currentPages = $pageManager->getAll();

            // on boucle les pages du theme
            foreach ($currentPages as $currentPage) {
                // si la nouvelle page existe deja en base
                if ($currentPage['type'] == $params['model']) {
                    // erreur 
                    $message = 'Page déja existante';
                    Router::render('admin/page/addPage.view.php', ['pages' => $pages, "message" => $message]);
                    return false;
                }
            }
            // sinon on la crée
            $pageManager->setTitle($params['route']);
            $pageManager->setType($params['model']);
            $pageManager->setLink('/' . Slugger::sluggify($params['route']));
            $pageManager->setThemeId($_SESSION['theme']);
            $pageManager->save();

            // on ecrit une nouvelle route
            $this->writeRoute($params);

            unset($_SESSION['flash']);
            header('Location: /gerer-mes-pages');
        }
        Router::render('admin/page/addPage.view.php', ['pages' => $pages]);
    }

    /**
     * Supprime une page , admin access
     *
     * @return void
     */
    public function deletePageAdmin(): void
    {
        $page = new Page();
        $menuItem = new MenuItem();
        $item = $menuItem->getOneBy(['name' => ucfirst($_GET['page'])])[0] ?? null;

        if ($item != null) {
            $item->delete($item->getId());
        }
        // suppression de la page
        $page->deletePage($_GET['page']);
        // suppression de la route dans le yaml
        $this->eraseRoute($_GET['page']);
        // redirection index pages admin
        header('Location: /gerer-mes-pages');
    }


    /**
     * Modifier une page
     *
     * @return void
     */
    public function editPage()
    {
        $pageModel = new Page();
        $block = new Block();

        $data = $pageModel->getOneBy(['title' => $_GET['page']]);
        $page = $data[0] ?? null;

        $blocksPage = $block->getBlockByPosition($page->getId());

        // on compte les block de la page
        $positionBlocks = count($blocksPage);
        // on crée une nouvelle position
        $position = $positionBlocks + 1;

        if ($page == null) {
            // si la page n'existe pas, redirection index
            header('Location: /gerer-mes-pages');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // creation d'un block
            $block->createBlock($position, 'untitled', $page->getId());
            // redirection page d'edition
            header('Location: /editPage?page=' . $_GET['page']);
        }
        // on modifie la date d update de la page
        $page->setUpdatedAt((new \Datetime('now'))->format('Y-m-d H:i:s'));
        $page->save();

        Router::render('admin/page/editPage.view.php', ['blocksPage' => $blocksPage]);
    }


    /**
     * creation d'un block de type texte
     * json response
     * @return void
     */
    public function createTextBlock()
    {
        // on recupère le block en cours de construction
        $blockManager = new Block();
        $blockDatas = $blockManager->getOneBy(['id' => intval($_POST['block'])]);
        $block = $blockDatas[0];
        // on lui met un titre texte
        $block->setTitle('Text');
        $block->save();


        /* ------------------------------------ DESIGN PATTERN FACTORY */ 
        // on crée un nouveau module texte
        (new BlockContentFactory)
                ->create('text')
                ->setBlockId($_POST['block'])
                ->setContent($_POST['content'])
                ->save();

        // envoi de la reponse au client
        echo json_encode(['success' => 'Bloc de texte enregistré', 'content' => $_POST['content']]);
    }


    /**
     * suppression d'un block
     *
     * @return void
     */
    public function removeBlock()
    {
        $blockManager = new Block();
        $blockManager->delete($_GET['id']);
        header('Location: /editPage?page=' . $_GET['page']);
    }

    /**
     *  créer un input de formulaire pour un block
     *  json response
     * @return void
     */
    public function createFormInput()
    {
        // reponse json au client encas d'erreur
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
         /* ------------------------------------ DESIGN PATTERN FACTORY */ 
        $formManager = (new BlockContentFactory)->create('form');
        $exist = $formManager->getOneBy(['block_id' => $_POST['block']])[0] ?? null;

        // foreach ($formManager->getAll() as $formCheck) {
        //     if ($formCheck['title'] == $_POST['form']) {
        //         echo json_encode(['status' => 'error', 'message' => 'Nom de formulaire deja existant']);
        //         return;
        //     }
        // }

        // on verifie la presence d'un formulaire de meme nom en base
        if ($exist == null) {
            // creation du form

         /* ------------------------------------ DESIGN PATTERN FACTORY */ 
            $formManager
                ->setBlockId($_POST['block'])
                ->setTitle($_POST['form'])
                ->save();
        }

        $form = $formManager->getOneBy(['title' => $_POST['form']])[0];

        //update du block, avec le titre 'form'
        $blockManager = new Block();
        $blockDatas = $blockManager->getOneBy(['id' => intval($_POST['block'])]);
        $block = $blockDatas[0];
        $block->setTitle('Form');
        $block->save();

        // creation de l'input
        $input = new Input();
        $input->setLabel($_POST['label']);
        $input->setFormId($form->getId());
        $input->setJsId(null);
        $input->setJsclass(null);
        $input->setType($_POST['type']);
        $input->setPlaceholder($_POST['placeholder']);
        $input->setName($_POST['name']);
        $input->setValue(null);
        $input->save();

        // reponse succes au client
        echo json_encode(['status' => 'success', 'message' => 'input enregistré']);
    }


    public function editMenu()
    {
        $itemsManager = new MenuItem();
        // on recupère les items
        $items = $itemsManager->getAllByPosition();

        $pagesManager = new Page();
        $pages = $pagesManager->getAll();

        Router::render('admin/editMenu.view.php', [
            'items' => $items,
            'pages' => $pages
        ]);
    }

    /**
     * Permet d'ajouter un li au menu du site
     *
     * @return void
     */
    public function addItem()
    {
        $item = new MenuItem();
        // on compte les items
        $count = count($item->getAllByPosition());

        // on crée l'item menu
        $item->setLink("/{$_POST['route']}");
        $item->setName($_POST['name']);
        // on attribue la position p+1
        $item->setPosition($count + 1);
        $item->save();

        // reponse au client
        echo json_encode(['id' => $count + 1,]);
    }

    /**
     * Modifie la position des ITEMS (li) du menu du site
     *
     * @return void
     */
    public function moveItemPosition(): void
    {
        $blockManager = new MenuItem();

        foreach ($_POST as $key => $value) {
            // update position
            $blockManager->updateItemPosition($key, $value);
        }
    }

    /**
     * Modifie la position des BLOCKS d'une page
     *
     * @return void
     */
    public function saveItemPosition(): void
    {
        $blockManager = new Block();
        foreach ($_POST as $key => $value) {
            // update position
            $blockManager->updateBlockItemPosition($key, $value);
        }
    }

    # write route in route.yaml
    private function writeRoute(array $params): void
    {
        // ouvre le fichier et recupère le contenu
        $content = file_get_contents('routes.yml');
        // concatenation de la chaine 
        $content .= "\n\n/" . strtolower($params['route']) . ':';
        $content .= "\n  controller: " . $params['model'];
        $content .= "\n  action: " . $params['action'];
        $content .= "\n  role: [" . $params['role'] . "]";
        // on réécrit la nouvelle chaine dans le fichier
        file_put_contents('routes.yml', $content);
    }

    /**
     * supprime une route dans le fichier routes.yaml
     *
     * @param string $route
     * @return void
     */
    private function eraseRoute(string $route): void
    {
        // recupère le contenu
        $content = file_get_contents('routes.yml');
        // explode en tableau délimoité par les slashs
        $arrayContent = explode('/', $content);

        $output = [];
        for ($i = 1; $i < count($arrayContent); $i++) {
            // si la chaine existe et contient un '/'
            if (strstr($arrayContent[$i], $route) == false && $arrayContent[$i] != '') {
                // on ajoute la chaine au tableau
                $output[] = '/' . $arrayContent[$i];
            }
        }

        $content = file_get_contents('routes.yml');
        // on set le contenu à null
        $content = '';
        for ($i = 0; $i < count($output); $i++) {
            // on concatene le contenu du tableau dans $content
            $content .= $output[$i];
        }
        // on réécrit le fichier yaml
        file_put_contents('routes.yml', $content);
    }

    /**
     * Liste les articles coté dashboard avec pagination
     *
     * @return void
     */
    public function indexArticle()
    {
        $article = new Article();
        $blockManager = new Block();
        $blocks = $blockManager->getAllByPosition();

        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $countArticles = count($article->getAll());
        // articles par page
        $per_page = 8;
        // arrondi à l'entier superieur
        $pages = ceil($countArticles / $per_page);
        // determine la premiere page
        $first = ($currentPage * $per_page) - $per_page;

        // recupère les articles pagninés 
        $all_article = $article->getAllPaginated($first, $per_page);

        Router::render('admin/article/articles.view.php', [
            'all_article' => $all_article,
            'blocks' => $blocks,
            'pages' => $pages,
            'currentPage' => $currentPage
        ]);
    }

    /**
     * Affiche les messages de contact coté admin
     *
     * @return void
     */
    public function indexContact()
    {
        $contact = new Contact();
        $contacts = $contact->getAll();

        Router::render('admin/contact/contactPage.view.php', ['contacts' => $contacts]);
    }

    /**
     * Route de suppression des messages de contact
     *
     * @return void
     */
    public function deleteContact()
    {
        $contactManager = new Contact();
        $contactManager->delete($_GET['id']);
        $_SESSION['contact'] -= 1;
        header('Location: /gerer-mes-messages');
    }

    /**
     * update du role user
     *
     * @return void
     */
    public function editUserRole()
    {
        $usermanager = new User(); // instancier le manager

        $userdatas = $usermanager->getOneBy(['id' => $_POST['id']]); // on récupère les données de l'utilisateur
        $selectedRole = $_POST['role']; // on récupère le role sélectionné
        $user = $userdatas[0]; // on récupère l'utilisateur

        $user->setRole(strtolower($selectedRole)); // on change le role de l'utilisateur
        $user->setUpdatedAt((new \DateTime('now'))->format('Y-m-d H:i:s')); // on change la date de modification
        $user->save(); // on sauvegarde l'utilisateur

        header('Location: /voir-les-utilisateurs');
    }

    /**
     * Affiche les utilisateurs inscrits coté admin
     *
     * @return void
     */
    public function memberview()
    {
        $user = new User();

        $users = $user->getAll();

        Router::render('admin/users/users.view.php', ["users" => $users]);
    }

    /**
     * Route de suppression d'un utilisateur coté admin
     *
     * @return void
     */
    public function deleteUser()
    {
        $user = new User();
        $user->delete($_GET['id']);

        header('Location: /voir-les-utilisateurs');
    }

    /**
     * Rechercher un utilisateur (search barre header)
     *
     * @return void
     */
    public function searchUser()
    {
        if ($_POST['user'] == null) { 
            // response d'erreur retourné au client
            echo json_encode(['status' => 'error', 'message' => 'probleme']);
            return;
        }
        $qu = new QueryUser();
        //$userManager = new User();
        // search the query expression
        $users = $qu->searchUsers($_POST['user']); // on recupère les utilisateurs correspondants à la recherche
        // retourne une reponse json de succès
        echo json_encode(['status' => 'success', 'message' => 'success', 'res' => $users]);
    }
}