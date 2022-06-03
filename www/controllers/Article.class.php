<?php

namespace App\controllers;

use App\core\View;
use App\models\Article as ArticleModel;
use App\core\verificator\VerificatorArticle;
use App\models\Categorie as CategorieModel;
use App\core\Sql;
use App\core\Session;


class Article extends Sql{

	public function articleCreate()
    	{
        $view = new View("article");
        $article = new ArticleModel();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $title = addslashes(htmlspecialchars($_POST['title']));
            $content = addslashes(htmlspecialchars($_POST['content']));
            $category_id = $_POST['category_id'];

            $result = VerificatorArticle::validate($article->getArticleForm(), $_POST);

            if ($result && count($result) > 0) {
                $view->assign(['result' => $result]);
                return;
            }

            $article->setTitle($title);
            $article->setContent($content);
            $article->setCategoryId($category_id);
            $article->setCreatedAt(new \DateTime('now'));
            //$article->setPosition($_POST['position']);
            $article->save();

            header('Location: /articles');
        }
        $view->assign(["article" => $article]);
    	}



	public function detailsArticle()
    	{
        $article = new ArticleModel();
        $category = new CategorieModel();
        $view = new View("detailsArticle");

        //$article_id = $_GET['id'];
        $article_id = isset($_GET['id']) ? $_GET['id'] : ''; // verikfie si existe 

        $articleDatas = $article->getOneBy(['id' => $article_id]);
	var_dump($article);
        $article = $articleDatas[0];
	var_dump($article);

        $categoryDatas = $category->getOneBy(['id' => $article->getCategoryId()]);
        $category = $categoryDatas[0];

        $view->assign(["article" => $article, "category" => $category]);
   	}


	public function allArticle() {
		$view = new View("articles");
		$article = new ArticleModel();

		$all_article = $article->getAll();
		

		$view->assign(["all_article" => $all_article]);

	}

	public function updateArticle() 
    	{
        $manager = new ArticleModel();
        $category = new CategorieModel();

        $view = new View("updateArticle");

        $article_id = isset($_GET['id']) ? $_GET['id'] : ''; // verikfie si existe
        // $article_id = $_GET['id'];
	var_dump($article_id);

        $articleDatas = $manager->getOneBy(['id' => $article_id]);
        $articleObject = $articleDatas[0];

        $categoryDatas = $category->getOneBy(['id' => $articleObject->getCategoryId()]);
        $categoryObject = $categoryDatas[0];

        $params = [
            "id" => $articleObject->getId(),
            "title" => $articleObject->getTitle(),
            "content" => $articleObject->getContent(),
            "selectedValue" => $categoryObject->getId()
        ];

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $title = htmlspecialchars($_POST['title']);
            $content = $_POST['content']; // pas de htmlspecialchars avec le wysiwyg
            $category_id = intval($_POST['category_id']);

            $result = VerificatorArticle::validate($manager->getArticleForm(), $_POST);

            if ($result != null && count($result) > 0) {
                $view->assign(['result' => $result, "article" => $manager, 'params' => $params]);
                return;
            }

            $articleObject->setTitle($title);
            $articleObject->setContent($content);
            $articleObject->setCategoryId($category_id);
            $articleObject->setUpdatedAt((new \DateTime('now'))->format('Y-m-d'));
            $articleObject->save();

            header('Location: /articles');
        }
        $view->assign(["params" => $params, "article" => $manager]);
 	}	

	public function deleteArticle() {

	}
}
