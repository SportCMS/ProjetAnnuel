<?php

namespace App\controllers;

use PDOException;

use App\models\Like as LikeModel;

use App\core\Sql;

class Like extends Sql
{
    public function createLike()
    {
        if (empty($_POST['action']) || empty($_POST['article'])) { 
            echo json_encode(['response' => ['error' => 'Missing informations', 'status' => 400]]); 
        }

        $like = new LikeModel();
        // remplacer par l'id de SESSION user !!!!!
        try {
            $like->toggleLikes(1, $_POST['article']); 
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        $manager = new LikeModel();
        $total_likes = $manager->countAllLikesByArticle($_POST['article']); 
        echo json_encode([
            'count_likes' => $total_likes['likes']
        ]);
    }

    public function getLikes()
    {
        if (empty($_POST['article'])) {
            echo json_encode(['response' => ['error' => 'Missing informations', 'status' => 400]]);
        }
        $manager = new LikeModel();
        $articleLikes = $manager->getUserLikeByArticle(1, $_POST['article']);

        echo json_encode([
            'articleLikes' => count($articleLikes)
        ]);
    }
}
