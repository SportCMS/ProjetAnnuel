<?php

namespace App\controllers;

use App\models\Comment as CommentModel;
use App\models\User as UserModel;
use App\models\Report as ReportModel;

use App\core\verificator\VerificatorReport;
use App\core\Sql;
use App\core\Router;

// observer
use App\core\ReportNotification;

class Comment extends Sql
{
	public function commentCreate()
	{
		if (empty($_POST['title']) || empty($_POST['content']) || empty($_POST['article'])) {
			echo json_encode(['response' => ['error' => 'Missing informations', 'status' => 400]]);
			return;
		}

		$comment = new CommentModel();
		$comment->setAuthorId(1);  //remplacer par l'id session user
		$comment->setParentId(null);
		$comment->setArticleId($_POST['article']);
		$comment->setTitle($_POST['title']);
		$comment->setContent($_POST['content']);
		$comment->setCreatedAt((new \DateTime('now'))->format('Y-m-d H:i:s'));
		$comment->save();

		$userManager = new userModel();
		$userDatas = $userManager->getOneBy(['id' => $comment->getAuthorId()]);
		$user = $userDatas[0];

		echo json_encode([
			"success" => 'New Resource created',
			"status" => 201,
			"comment" => [
				"id" => $comment->getId(),
				"title" => $comment->getTitle(),
				"content" => $comment->getContent(),
				"user" => $comment->getAuthorId(),
				"firstname" => $user->getFirstname(),
				"lastname" => $user->getlastname(),
				"article" => $comment->getArticleId(),
				"date" => substr($comment->getCreatedAt(), 0, 10),
				"hour" => substr($comment->getCreatedAt(), -9, 18),

			]
		]);
	}

	public function replyComment()
	{
		if (empty($_POST['replyContent']) || empty($_POST['userId']) || empty($_POST['parentId']) || empty($_POST['articleId'])) {
			echo json_encode(['response' => ['error' => 'Missing informations', 'status' => 400]]);
			return;
		}

		$comment = new CommentModel();
		$comment->setAuthorId(1);  //remplacer par l'id session user !!  et créer la SESSION !
		$comment->setParentId($_POST['parentId']);
		$comment->setArticleId($_POST['articleId']);
		$comment->setContent($_POST['replyContent']);
		$comment->setCreatedAt((new \DateTime('now'))->format('Y-m-d H:i:s'));
		$comment->save();

		$userManager = new userModel();
		$userDatas = $userManager->getOneBy(['id' => $comment->getAuthorId()]);
		$user = $userDatas[0];

		echo json_encode([
			"success" => 'Votre commentaire est en ligne',
			"status" => 201,
			"comment" => [
				"id" => $comment->getId(),
				"content" => $comment->getContent(),
				"user" => $comment->getAuthorId(),
				"firstname" => $user->getFirstname(),
				"lastname" => $user->getlastname(),
				"article" => $comment->getArticleId(),
				"date" => substr($comment->getCreatedAt(), 0, 10),
				"hour" => substr($comment->getCreatedAt(), -9, 18),
				"parent" => $comment->getParentId(),
			]
		]);
	}

	public function reportComment() 
    {
        $reportManager = new ReportModel();
        $commentManager = new CommentModel();
        $userManager = new UserModel();

        $commentDatas = $commentManager->getOneBy(['id' => $_GET['id']]); // récupère le commentaire en question
        $comment = $commentDatas[0];
        $userDatas = $userManager->getOneBy(['id' => $comment->getAuthorId()]); // récupère l'utilisateur qui a posté le commentaire
        $user = $userDatas[0];

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = htmlspecialchars($_POST['email']); 
            $message = htmlspecialchars($_POST['message']);

            $result = VerificatorReport::validate($reportManager->getReportForm(), $_POST);

            if ($result && count($result) > 0) {
                Router::render('admin/reports/reports.view.php',[
                    'result' => $result,
                    "reportManager" => $reportManager,
                    "comment" => $comment,
                    "author" => $user
                ]);
                return;
            }

			$users = new UserModel();
			$users = $users->getAllAdmin();

			$reportNotify = new ReportNotification();


			$comment = "Un report viens d'etre fait avec le message " . $message;
			dd($users);
            $report = new ReportModel();
            $report->setCommentId($_GET['id']);
            $report->setEmail($email);
            $report->setMessage($message);
	    	$report->setHasRead(0);
            $report->setCreatedAt((new \Datetime('now'))->format('Y-m-d H:i:s'));
            $report->save();

			foreach($users as $user){
				$user->update($reportNotify, $comment);
			}

            header('Location:/articles');
        }

        Router::render('front/report/report.view.php',[
            "reportManager" => $reportManager,
            "comment" => $comment,
            "author" => $user
        ]);
    }

    public function getReports()
    {
        $reportManager = new ReportModel();
        $reports = $reportManager->getBy(['has_read' => 0]); // récupère tous les reports non lus

        $_SESSION['report'] = 0; 

        foreach ($reports as $report) {
            $report->setHasRead(1);
            $report->save();
        }


        Router::render('admin/report/reports.view.php', ['reports' => $reports]);
    }
}
