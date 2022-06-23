<?php

namespace App\controllers;

use App\models\Article as ArticleModel;
use App\models\Categorie as CategorieModel;
use App\models\Comment as CommentModel;
use App\models\Like as LikeModel;

use App\core\verificator\VerificatorArticle;
use App\core\Router;

use App\core\Sql;

use App\helpers\Slugger;

class Article extends Sql
{
	public function indexArticle()
    {
        $article = new ArticleModel();

        $all_article = $article->getAll();

        Router::render("front/article/articles.view.php", [
            "all_article" => $all_article        
		]);
    }

	public function articleCreate()
    {
        $article = new ArticleModel();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $title = addslashes(htmlspecialchars($_POST['title']));
            $content = $_POST['content'];
            $category_id = $_POST['category_id'];

            $result = VerificatorArticle::validate($article->getArticleForm(), $_POST);

            if ($result && count($result) > 0) {
                Router::render('admin/article/articleCreate.view.php', [
                    'result' => $result,
                    "article" => $article
                ]);

                return;
            }

            $article->setTitle($title);
            $article->setSlug(Slugger::sluggify($_POST['title']));
            $article->setContent($content);
            $article->setCategoryId($category_id);
            $article->setCreatedAt((new \DateTime('now'))->format('Y-m-d H:i:s'));
            //$article->setPosition($_POST['position']);
            $article->save();

            header('Location: /gerer-mes-articles');
        }
        Router::render('admin/article/articleCreate.view.php', [
            "article" => $article,
            'form' => $article->getArticleForm(null),
        ]);
    }

	public function showArticle()
    {
        $articleManager = new ArticleModel();
        $category = new CategorieModel();
        $commentManager = new CommentModel();
        $likeManager = new LikeModel();
        $article_id = $_GET['slug'];

        $articleDatas = $articleManager->getOneBy(['slug' => $article_id]);
        $article = $articleDatas[0];

        $like = count($likeManager->getUserLikeByArticle(1, $article_id)); // remplacer par l'id user id de session 
        $total_likes = $likeManager->countAllLikesByArticle($article->getId());

        $categoryDatas = $category->getOneBy(['id' => $article->getCategoryId()]);
        $category = $categoryDatas[0];

        $comments = $commentManager->getCommentsByArticle($article->getId());
        $replies = $commentManager->getRepliesByComment($article->getId());
        $countComments = $commentManager->countComments($article->getId());

        Router::render("front/article/article.view.php", [
            "article" => $article,
            "category" => $category,
            'countComments' => count($countComments) > 0 ? $countComments : null,
            'replies' => count($replies) > 0 ? $replies : null,
            'like' => $like,
            'total_likes' => $total_likes['likes'],
            'comments' => $comments

        ]);
    }

	public function updateArticle()
    {
        $manager = new ArticleModel();
        $category = new CategorieModel();

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
                Router::render("admin/article/updateArticle.view.php", [
                    'result' => $result,
                    "article" => $manager,
                    'params' => $params
                ]);
            }

            $articleObject->setTitle($title);
            $articleObject->setContent($content);
            $articleObject->setCategoryId($category_id);
            $articleObject->setUpdatedAt((new \DateTime('now'))->format('Y-m-d'));
            $articleObject->save();

            header('Location: /gerer-mes-articles');
        }
        Router::render("admin/article/updateArticle.view.php", [
            "article" => $manager,
            'params' => $params
        ]);
    }

	public function deleteArticle()
    {
        $article = new ArticleModel();
        $comment = new CommentModel();

        $comment->deleteComments($_GET['id']);
        $article->delete($_GET['id']);

        header('Location: /gerer-mes-articles');
	}
	
}
	


