<?php

namespace App\controllers;

use App\core\View;
use App\models\Article as ArticleModel;
use App\core\verificator\VerificatorArticle;
use App\models\Categorie as CategorieModel;
use App\models\Comment as CommentModel;
use App\models\Like as LikeModel;


//tester le drag and drop
use App\models\Block as BlockModel;
use App\models\User as UserModel;
use App\core\Sql;
use App\core\Session;
use App\helpers\Slugger;


class Article extends Sql
{
	public function articleCreate()
	{
		$view = new View("article");
		$article = new ArticleModel();

		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$title = addslashes(htmlspecialchars($_POST['title']));
			$content = $_POST['content'];
			$category_id = $_POST['category_id'];

			$result = VerificatorArticle::validate($article->getArticleForm(), $_POST);

			if ($result && count($result) > 0) {
				$view->assign(['result' => $result, "article" => $article]);
				return;
			}

			$article->setTitle($title);
			$article->setSlug(Slugger::sluggify($_POST['title']));
			$article->setContent($content);
			$article->setCategoryId($category_id);
			$article->setCreatedAt((new \DateTime('now'))->format('Y-m-d H:i:s'));
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
		$commentManager = new CommentModel();
		$likeManager = new LikeModel();
<<<<<<< HEAD
		$view = new View("detailsarticle", "empty");
		$article_id = $_GET['id'];
=======
		$view = new View("detailsarticle");
		$article_id = $_GET['slug'];
>>>>>>> feature/slug


		$articleDatas = $article->getOneBy(['slug' => $article_id]);
		$article = $articleDatas[0];

		$like = count($likeManager->getUserLikeByArticle(1, $article_id)); // remplacer par l'id user id de session 
		$total_likes = $likeManager->countAllLikesByArticle($article->getId());

		$categoryDatas = $category->getOneBy(['id' => $article->getCategoryId()]);
		$category = $categoryDatas[0];

		$comments = $commentManager->getCommentsByArticle($article->getId());
		$replies = $commentManager->getRepliesByComment($article->getId());
		$countComments = $commentManager->countComments($article->getId());

		if (count($comments) > 0) {
			$view->assign(['comments' => $comments]);
		}
		if (count($replies) > 0) {
			$view->assign(['replies' => $replies]);
		}
		$view->assign([
			"article" => $article,
			"category" => $category,
			'countComments' => $countComments,
			'like' => $like,
			'total_likes' => $total_likes['likes']
		]);
	}

	public function indexArticle()
	{
		$view = new View("articles");
		$article = new ArticleModel();


		$all_article = $article->getAll();

		$view->assign([
			"all_article" => $all_article,
		]);
	}

	public function updateArticle()
	{
		$manager = new ArticleModel();
		$category = new CategorieModel();

		$view = new View("updateArticle");

		$article_id = $_GET['slug'];

		$articleDatas = $manager->getOneBy(['slug' => $article_id]);
		$articleObject = $articleDatas[0];

		$categoryDatas = $category->getOneBy(['id' => $articleObject->getCategoryId()]);
		$categoryObject = $categoryDatas[0];

		$params = [
			// "id" => $articleObject->getId(),
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

	public function deleteArticle()
	{
		$article = new ArticleModel();
		$comment = new CommentModel();

		$comment->deleteComments($_GET['id']);
		$article->delete($_GET['id']);

		header('Location: /articles');
	}

	
}
	


