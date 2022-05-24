<?php

namespace App\controllers;

use App\core\View;
use App\models\Article as ArticleModel;
use App\core\verificator\VerificatorArticle;
use App\models\Categorie as CategorieModel;
use App\core\Sql;
use App\core\Session;


class Article extends Sql{

	public function articleCreate() {
		$view = new View("article");
		$article = new ArticleModel();
		$view->assign("article", $article);


		// $categorie = new CategorieModel();
		// echo '<pre>';
		// print_r($categorie->getAll());
		// echo '</pre>';
		// die();

	
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			//var_dump($_POST);
			//die();
			$title = addslashes(htmlspecialchars($_POST['title']));
			$content = addslashes(htmlspecialchars($_POST['content']));
			$category_id = $_POST['category_id'];
			
			$result = VerificatorArticle::validate($article->getArticleForm(), $_POST);



			if(count($result) > 0) {
				
				$view->assign('result', $result);
				return;
			}

			$article->setTitle($title);
			$article->setContent($content);
			$article->setCategoryId($category_id);
			//$article->setPosition($_POST['position']);
			$article->save();

			$article_data = [
				'id' => $article->getId(),
			];

			$article_id = $article->getOneBy($article_data);

			$object = $article_id[0];
			$id = $object->id;

			header('Location: /allArticle');
		}
	}

	public function detailsArticle() {
		$article = new ArticleModel();
		$view = new View("detailsarticle");

		$article_id = $_GET['article_id'];
		
		$article_details = $article->getOneBy(['id' => $article_id]);
		$object = $article_details[0];

		$id = $object->id;
		$title = $object->title;
		$content = $object->content;
		$category_id = $object->category_id;
	}

	public function allArticle() {
		$view = new View("articles");
		$article = new ArticleModel();

		$all_article = $article->getAll();
		

		$view->assign("all_article", $all_article);

	}

	public function updateArticle() {

	}

	public function deleteArticle() {

	}
}
