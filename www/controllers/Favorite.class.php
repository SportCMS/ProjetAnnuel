<?php

namespace App\controllers;

use App\core\Sql;
use App\models\Favorite as FavoriteModel;
use PDOException;

class Favorite extends Sql
{
    public function createFavorite()
    {
        if (empty($_POST['action']) || empty($_POST['article'])) {
            echo json_encode(['response' => ['error' => 'Missing informations', 'status' => 400]]);
        }

        $favorite = new FavoriteModel();
        // remplacer par l'id de SESSION user !!!!!
        try {
            $favorite->toggleFavorites(1, $_POST['article']);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        $manager = new FavoriteModel();
        $total_favorites = $manager->countAllFavoritesByArticle($_POST['article']);
        echo json_encode([
            'count_favorites' => $total_favorites['favorites']
        ]);
    }

    public function getFavorites(){
        if (empty($_POST['article'])) {
            echo json_encode(['response' => ['error' => 'Missing informations', 'status' => 400]]);
        }
        $manager = new FavoriteModel();
        $articleFavorites = $manager->getUserFavoriteByArticle(1, $_POST['article']);

        echo json_encode([
            'articleLikes' => count($articleFavorites)
        ]);
    }
}