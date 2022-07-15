<?php

namespace App\controllers;

use App\models\Categorie as CategorieModel;
use App\models\Page as PageModel;
use App\models\Article as ArticleModel;
use App\core\Sql;
use App\Helpers\Slugger;
use App\core\Router;


class Categorie extends Sql
{
    public function indexCategories()
    {
        $categoryManager = new CategorieModel();
        $categories = $categoryManager->getAll();

        Router::render('admin/categories/categories.view.php', ['categories' => $categories]);
    }

    public function categoryPage()
    {
        $slug = str_replace('/', '', $_SERVER['REQUEST_URI']);
        $articleManager = new ArticleModel();
        $categoryManager = new CategorieModel();

        $categoryDatas = $categoryManager->getOneBy(['slug' => $slug]);
        $category = $categoryDatas[0];
        $articles = $articleManager->getArticlesByCategory($slug);

        Router::render('front/categorie/category.view.php', [
            'articles' => $articles,
            'category' => $category
        ]);
    }

    public function addCategory()
    {
        $category = new CategorieModel();
        $pageManager = new PageModel();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (empty($_POST['title'] || empty($_POST['description']))) {
                $error = "l'upload de l'image à échoué";
                return;
            }

            $target_dir = "public/uploads/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));


            /* CORRECTIONs */
            if(empty($_FILES['image']['name'])){
                Router::render('admin/categories/add.view.php',['errors' => ['Vous devez mettre une image']]);
            }
            
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check == false) {
                $error = "l'upload de l'image à échoué";
                return;
            }

            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $error = "l'upload de l'image à échoué";
                return;
            }

            $category->setName(htmlspecialchars($_POST['title']));
            $category->setDescription(htmlspecialchars($_POST['description']));
            $category->setSlug(Slugger::sluggify($_POST['title']));
            $category->setImage($_FILES["image"]["name"]);
            $category->save();

            $params['route'] = strtolower(Slugger::sluggify($_POST['title'])) ?? null;
            $params['role'] = 'public' ?? null;
            $params['model'] = 'categorie' ?? null;
            $params['action'] = 'categoryPage';

            $this->writeRoute($params);
            header('Location:gerer-mes-categories');
        }
        Router::render('admin/categories/add.view.php');
    }

    private function writeRoute(array $params): void
    {
        $content = file_get_contents('routes.yml');                                     // /ma-categorie-geniale :
        $content .= "\n\n/" . strtolower($params['route']) . ':';                       // controller : categories
        $content .= "\n  controller: " . strtolower($params['model']);                              // action : indexCategories
        $content .= "\n  action: " . $params['action'];                                 // role : [public]
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

        unlink('views/admin/' . strtolower($route) . '.view.php');
    }

    public function deleteCategory()
    {
        $category = new CategorieModel();
        $category->delete($_GET['id']);
        
        
        $route = str_replace('/', '', $_SERVER['REQUEST_URI']);
        $this->eraseRoute($route);

        header('Location: /gerer-mes-categories');
    }
}