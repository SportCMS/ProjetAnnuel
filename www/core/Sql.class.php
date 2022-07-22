<?php

namespace App\core;

use PDO;
use App\core\Db;
use PDOStatement;

abstract class Sql extends Db
{
    private $pdo;
    private $table;
    private $prefix = DBPREFIXE;
    private $base = DBNAME;

    public function __construct()
    {
        $this->pdo = Db::connect(); // singleton
        $getCalledClass = explode('\\', strtolower(get_called_class())); //Récupérer la classe appelé, (Pour insert dans la bonne base)
        $this->table = $this->prefix . end($getCalledClass) ; //création du nom de la table avec son préfix
    }

    /**
     * drop a database
     *
     * @return void
     */
    public function dropDatabase(): void
    {
        $sql = "DROP DATABASE {$this->base};";
        $queryPrp = $this->pdo->prp($sql);
    }

    /**
     * creer une base de donnee avec le nom renseigné durant l'installation,
     * La variable est accessible depuis la session
     *
     * @param $_SESSION['temp_dbName']
     * @return void
     */
    public function createDatabase(): void
    {
        $sql = "CREATE DATABASE {$_SESSION['temp_dbName']} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
        $queryPrp = $this->pdo->prp($sql);
    }


    /**
     * creation des tables du site, le prefix est renseigné durant l'installation et accessible depuis la session
     *
     * @param $_SESSION['temp_prefix']
     * @return void
     */
    public function createTables(): void
    {

        $sql = "CREATE TABLE `{$_SESSION['temp_prefix']}newsletter` (`id` int(11) NOT NULL AUTO_INCREMENT,`email` varchar(255) NOT NULL, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $sql .= "CREATE TABLE `{$_SESSION['temp_prefix']}article` (`id` int(11) NOT NULL AUTO_INCREMENT,`block_id` int(11) DEFAULT NULL,`category_id` int(11) NOT NULL,`title` varchar(255) NOT NULL,`slug` varchar(255) NOT NULL,`content` text NOT NULL,`position` int(11) DEFAULT NULL,`image` varchar(255) NULL, `created_at` datetime DEFAULT CURRENT_TIMESTAMP,`updated_at` datetime DEFAULT NULL, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $sql .= "CREATE TABLE `{$_SESSION['temp_prefix']}block` (`id` int(11) NOT NULL AUTO_INCREMENT,`position` int(11) NOT NULL,`title` varchar(255) CHARACTER SET utf8 NOT NULL,`page_id` int(11) NOT NULL, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $sql .= "CREATE TABLE `{$_SESSION['temp_prefix']}card` (`id` int(11) NOT NULL AUTO_INCREMENT,`block_id` int(11) NOT NULL,`title` varchar(255) NOT NULL,`content` text NOT NULL,`image` varchar(255) NOT NULL,`link` varchar(255) NOT NULL, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $sql .= "CREATE TABLE `{$_SESSION['temp_prefix']}categorie` (`id` int(11) NOT NULL AUTO_INCREMENT,`name` varchar(255) NOT NULL,`description` text,`image` varchar(255) DEFAULT NULL,`slug` varchar(255) NOT NULL,`created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,`updated_at` datetime DEFAULT NULL, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $sql .= "CREATE TABLE `{$_SESSION['temp_prefix']}comment` (`id` int(11) NOT NULL AUTO_INCREMENT,`parent_id` int(11) DEFAULT NULL,`author_id` int(11) NOT NULL,`article_id` int(11) NOT NULL,`title` varchar(255) DEFAULT NULL,`content` text NOT NULL,`created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $sql .= "CREATE TABLE `{$_SESSION['temp_prefix']}connexion` (`id` int(11) NOT NULL AUTO_INCREMENT,`ip` varchar(255) NOT NULL,`user_id` int(11) DEFAULT NULL,`date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $sql .= "CREATE TABLE `{$_SESSION['temp_prefix']}contact` (`id` int(11) NOT NULL AUTO_INCREMENT,`message` text NOT NULL,`email` varchar(255) NOT NULL,`created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $sql .= "CREATE TABLE `{$_SESSION['temp_prefix']}form` (`id` int(11) NOT NULL AUTO_INCREMENT,`block_id` int(11) NOT NULL,`title` varchar(255) NOT NULL, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $sql .= "CREATE TABLE `{$_SESSION['temp_prefix']}input` (`id` int(11) NOT NULL AUTO_INCREMENT,`type` varchar(255) NOT NULL,`js_id` varchar(255) DEFAULT NULL,`js_class` varchar(255) DEFAULT NULL,`placeholder` varchar(255) DEFAULT NULL, `name` varchar(255) DEFAULT NULL,`value` varchar(255) DEFAULT NULL,`label` varchar(255) DEFAULT NULL,`form_id` int(11) DEFAULT NULL, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $sql .= "CREATE TABLE `{$_SESSION['temp_prefix']}like` (`id` int(11) NOT NULL AUTO_INCREMENT,`user_id` int(11) NOT NULL,`article_id` int(11) NOT NULL, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $sql .= "CREATE TABLE `{$_SESSION['temp_prefix']}menuitem` (`id` int(11) NOT NULL AUTO_INCREMENT,`name` varchar(255) NOT NULL,`link` varchar(255) NOT NULL,`js_class` varchar(255) DEFAULT NULL,`js_id` varchar(255) DEFAULT NULL,`position` int(11) NOT NULL, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $sql .= "CREATE TABLE `{$_SESSION['temp_prefix']}page` (`id` int(11) NOT NULL AUTO_INCREMENT,`theme_id` int(11) DEFAULT NULL,`title` varchar(255) NOT NULL,`link` varchar(255) NOT NULL,`active` tinyint(1) DEFAULT NULL,`type` varchar(255) NOT NULL, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $sql .= "CREATE TABLE `{$_SESSION['temp_prefix']}report` (`id` int(11) NOT NULL AUTO_INCREMENT,`comment_id` int(11) NOT NULL,`message` text NOT NULL,`email` varchar(255) NOT NULL,`has_read` tinyint(1) NOT NULL,`created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $sql .= "CREATE TABLE `{$_SESSION['temp_prefix']}text` (`id` int(11) NOT NULL AUTO_INCREMENT,`block_id` int(11) NOT NULL,`content` text NOT NULL, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $sql .= "CREATE TABLE `{$_SESSION['temp_prefix']}theme` (`id` int(11) NOT NULL AUTO_INCREMENT,`name` varchar(255) NOT NULL,`description` text NOT NULL,`domain` varchar(255) NOT NULL,`image` varchar(255) NULL, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $sql .= "CREATE TABLE `{$_SESSION['temp_prefix']}user` ( `id` int(11) NOT NULL AUTO_INCREMENT,`firstname` varchar(255) DEFAULT NULL,`lastname` varchar(255) DEFAULT NULL,`email` varchar(255) NOT NULL,`status` int(11) NOT NULL,`password` varchar(255) NOT NULL,`role` varchar(255) DEFAULT NULL, `token` varchar(255) DEFAULT NULL, `site` int DEFAULT NULL, `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,`updated_at` datetime DEFAULT NULL, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $queryPrp = $this->pdo->prp($sql);
    }


    public function setId(?int $id): self
    {
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE id=:id';
        $queryPrp = $this->pdo->prp($sql, ['id' => $id]);
        return $queryPrp->fetchObject(get_called_class());
    }

    protected function save(): void
    {
        $colums = get_object_vars($this); //récupération des données de l'objet
        $varToExplode = get_class_vars(get_class()); //récupération des noms de variable de la classe sql
        $colums = array_diff_key($colums, $varToExplode); //suppréssion de la variable pdo des donnée de la class principales
        if ($colums['id'] == null) {
            $colums = array_diff($colums, [$colums['id']]);
            $sql = 'INSERT INTO ' . $this->table  . ' (' . implode(',', array_keys($colums)) . ') VALUES (:' . implode(',:', array_keys($colums)) . ')';
        } else {
            $update = [];
            foreach ($colums as $key => $value) {
                $update[] = $key . '=:' . $key;
            }
            $sql = "UPDATE " . $this->table . " SET " . implode(',', $update) . " WHERE id=:id";
        }
        $this->pdo->prp($sql, $colums);
    }

    public function deleteL(){
        $colums = get_object_vars($this);
        if(!is_null($colums["id"])){
            $sql = "DELETE FROM " . $this->table . " WHERE id=:id";
            $this->pdo->prp($sql, ["id" => $colums["id"]]);
            return true;
        }
        return false;
    }

    public function getOneBy($entrie)
    {
        $val = [];
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE ' . array_keys($entrie)[0] . '=:' . array_keys($entrie)[0];
        $queryPrp = $this->pdo->prp($sql, $entrie);
        while ($row = $queryPrp->fetchObject(get_called_class())) {
            array_push($val, $row);
        }
        return $val;
    }

    public function getBy($entrie)
    {
        $val = [];
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE ';
        foreach ($entrie as $key => $data) {
            if (end($entrie) != $data) {
                $sql .= $key . '=:' . $key . ' and ';
            } else {
                $sql .= $key . '=:' . $key;
            }
        }
        $queryPrp = $this->pdo->prp($sql, $entrie);
        while ($row = $queryPrp->fetchObject(get_called_class())) {
            array_push($val, $row);
        }
        return $val;
    }

    public function getAll()
    {
        $sql = 'SELECT * FROM ' . $this->table;
        $queryPrp = $this->pdo->prp($sql);

        return $queryPrp->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getAllModel()
    {
        $sql = 'SELECT * FROM ' . $this->table;
        $queryPrp = $this->pdo->prp($sql);

        $val = [];
        while($row = $queryPrp->fetchObject(get_called_class())){
            array_push($val, $row); 
        }

        return $val;
    }

    public function getAllAdmin()
    {
        $sql = 'SELECT * FROM ' . $this->table . 'WHERE role="admin"';
        $queryPrp = $this->pdo->prp($sql);

        $val = [];
        while($row = $queryPrp->fetchObject(get_called_class())){
            array_push($val, $row); 
        }

        return $val;
    }

    /**
     * récupère les données d'un modele selon un range definit en parametres, pour realiser une pagination
     *
     * @param integer $first
     * @param integer $per_page
     * @return array
     */
    public function getAllPaginated(int $first, int  $per_page): array
    {
        $sql = "SELECT * FROM  {$this->table} ORDER BY created_at DESC LIMIT {$first}, {$per_page}";

        $queryPrp = $this->pdo->prp($sql);

        return $queryPrp->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Recupere les données d'un modele par ordre de position
     * utilisé pour les items du <nav> : MenuItem class
     *
     * @return array
     */
    public function getAllByPosition(): array
    {
        $sql = 'SELECT * FROM ' . $this->table . ' ORDER BY position ';
        $queryPrp = $this->pdo->prp($sql);

        return $queryPrp->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Recupère les blocks d'une page et les renvoi par ordre de position
     *
     * @param integer $pageId
     * @return array
     */
    public function getBlockByPosition(int $pageId): array
    {
        $sql = "SELECT b.id as blockId, f.id as formId, b.position, b.title, b.page_id, s.id as textId, s.block_id, s.content, f.title  as formTitle
                FROM {$this->prefix}block as b 
                LEFT JOIN {$this->prefix}text as s ON s.block_id = b.id
                LEFT JOIN {$this->prefix}form as f ON f.block_id = b.id
                WHERE page_id = ? 
                ORDER BY position";
        $queryPrp = $this->pdo->prp($sql, [$pageId]);

        return $queryPrp->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Recupère les input d'un formulaire
     *
     * @param integer $formId
     * @return array
     */
    public function getFormInputs(int $formId): array
    {
        $sql = "SELECT * FROM {$this->prefix}input as i
                LEFT JOIN {$this->prefix}forms as f ON i.form_id = f.id
                WHERE form_id = ?";
        $queryPrp = $this->pdo->prp($sql, [$formId]);

        return $queryPrp->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete(int $id): void
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $this->pdo->prp($sql, [$id]);
    }

    public function deleteComments(int $id): void
    {
        $sql = "DELETE FROM {$this->table} WHERE article_id = ?";
        $this->pdo->prp($sql, [$id]);
    }

    public function deletePage(string $page): void
    {
        $sql = "DELETE FROM {$this->table} WHERE title = ?";
        $this->pdo->prp($sql, [$page]);
    }

    /**
     * Recupère tous les commentaires parents d'un article
     *
     * @param [type] $id
     * @return array
     */
    public function getCommentsByArticle(int $id): array
    {
        $sql = "SELECT c.id as 'idComment', c.parent_id as 'parent', c.author_id as 'author', c.title, c.content, c.created_at,u.firstname, u.lastname, u.id as 'idUser'
        FROM {$this->table} as c
        JOIN {$this->prefix}user as u
        ON u.id = c.author_id
        WHERE c.article_id = ?
        AND c.parent_id IS NULL
        ORDER BY c.created_at DESC";

        $queryPrp = $this->pdo->prp($sql, [$id]);
        return $queryPrp->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Compte le nombre de commentaires d'un article
     *
     * @param [type] $id
     * @return array
     */
    public function countComments(int $id):array
    {
        $sql = "SELECT count(id) as 'count' FROM {$this->table}
        WHERE article_id = ?";
        $queryPrp = $this->pdo->prp($sql, [$id]);
        return $queryPrp->fetch();
    }

    /**
     * Compte le nombre de connexions au site
     *
     * @param string $date
     * @param string $startRange
     * @return array
     */
    public function getConnexionByDate(string $date, string $startRange):array
    {
        $year = (new \DateTime($date))->format('Y');
        $month = (new \DateTime($date))->format('m');
        $sql = "SELECT count(id) as 'count' FROM {$this->prefix}connexion
        WHERE date_format(date, '%Y') = {$year}
        AND date_format(date, '%m') = {$month}
        AND date_format(date, '%d') = {$startRange}";
        $queryPrp = $this->pdo->prp($sql, []);
        return $queryPrp->fetch();
    }

    /**
     * Compte le nombre d'inscriptions à une date donnée
     *
     * @param string $date
     * @param string $startRange
     * @param string $endRange
     * @return array
     */
    public function getInscriptionByDate(string $date, string $startRange, string $endRange):array
    {
        $year = (new \DateTime($date))->format('Y');
        $month = (new \DateTime($date))->format('m');
        $sql = "SELECT count(id) as 'count' FROM {$this->prefix}user
        WHERE date_format(created_at, '%Y') = {$year}
        AND date_format(created_at, '%m') = {$month}
        AND date_format(created_at, '%d') BETWEEN {$startRange} AND {$endRange}";
        $queryPrp = $this->pdo->prp($sql, []);
        return $queryPrp->fetch();
    }

    /**
     * Recupère les 3 derniers messages de contact postés sur le site
     *
     * @return void
     */
    public function getLastContacts()
    {
        $sql = "SELECT * FROM {$this->prefix}contact ORDER BY created_at DESC LIMIT 3";
        $queryPrp = $this->pdo->prp($sql, []);
        return $queryPrp->fetchAll();
    }

    /**
     * Recupère les reponses de commentaires pour un article donné
     * reponses = les commentaires dont le parent n'est pas null
     *
     * @param integer $id
     * @return array
     */
    public function getRepliesByComment(int $id): array
    {
        $sql = "SELECT c.id as 'idComment', c.parent_id as 'parent', c.author_id as 'author', c.title, c.content, c.created_at,u.firstname, u.lastname, u.id as 'idUser'
        FROM {$this->table} as c
        JOIN {$this->prefix}user as u
        ON u.id = c.author_id
        WHERE c.article_id = ?
        AND c.parent_id IS NOT NULL
        ORDER BY c.created_at DESC";

        $queryPrp = $this->pdo->prp($sql, [$id]);
        return $queryPrp->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ajoute ou supprime un like de commentaire article
     *
     * @param integer $user_id
     * @param integer $article_id
     * @return void
     */
    public function toggleLikes(int $user_id, int $article_id): void
    {
        $likes = $this->getUserLikeByArticle($user_id, $article_id);

        if (count($likes) == 0) {
            $sql = "INSERT INTO {$this->prefix}like (user_id, article_id) VALUES (?, ?)";
            $this->pdo->prp($sql, [$user_id, $article_id]);
        } else {
            $sql = "DELETE FROM {$this->table} WHERE user_id = ? AND article_id = ?";
            $this->pdo->prp($sql, [$user_id, $article_id]);
        }
    }


    /**
     * Recupère le nombre de like pour chaque article et par utilisateurs
     *
     * @param integer $user_id
     * @param integer $article_id
     * @return array
     */
    public function getUserLikeByArticle(int $user_id, string $article_id): array
    {
        $sql = "SELECT l.id as 'like' FROM {$this->prefix}like as l
        JOIN {$this->prefix}user as u
        ON l.user_id = u.id
        JOIN {$this->prefix}article as a
        ON l.article_id = a.id
        WHERE u.id = ?
        AND a.id = ?";
        $queryPrp = $this->pdo->prp($sql, [$user_id, $article_id]);
        return $queryPrp->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Compte le nombre de likes pour un article donné
     *
     * @param [type] $article_id
     * @return array
     */
    public function countAllLikesByArticle(int $article_id):array
    {
        $sql = "SELECT count(l.id) as 'likes' FROM {$this->prefix}like as l
        JOIN {$this->prefix}article as a
        ON l.article_id = a.id
        AND a.id = ?";
        $queryPrp = $this->pdo->prp($sql, [$article_id]);
        return $queryPrp->fetch();
    }

    /**
     * update l'ordre d'affichage des blocks sur la page en modifiant la position
     *
     * @param integer $position
     * @param integer $block_id
     * @return void
     */
    public function updateItemPosition(int $position, int $block_id)
    {
        $sql = "UPDATE " . $this->table . " SET position = ?  WHERE id= ?";
        $queryPrp = $this->pdo->prp($sql, [$block_id, $position]);
    }

    /**
     * update l'odre d'affichage des items d'un block de page en modifiant la position
     *
     * @param [type] $position
     * @param [type] $block_id
     * @return void
     */
    public function updateBlockItemPosition(int $position, int $block_id)
    {
        $sql = "UPDATE " . $this->table . " SET position = ?  WHERE id= ?";
        $queryPrp = $this->pdo->prp($sql, [$block_id, $position]);
    }

    /**
     * recupère toutes les signalements non lus par l'admin
     *
     * @return array
     */
    public function getReportNotifications():array
    {
        $sql = "SELECT * FROM " . $this->table . " as r WHERE r.has_read = 0 ";
        $queryPrp = $this->pdo->prp($sql, []);
        return $queryPrp->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Recupères les articles d'une category donnée
     *
     * @param string $slug
     * @return array
     */
    public function getArticlesByCategory(string $slug): array
    {
        $sql = "SELECT r.title, r.image as artImage, r.slug as slugArticle, r.content, r.created_at, c.slug as slugCategory
        FROM  {$this->prefix}article as r 
        JOIN {$this->prefix}categorie as c
        ON r.category_id = c.id
        WHERE c.slug = ?";
        $queryPrp = $this->pdo->prp($sql, [$slug]);
        return $queryPrp->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Vide une table
     *
     * @param string $table
     * @return void
     */
    public function truncate(string $table)
    {
        $sql = "TRUNCATE TABLE {$this->prefix}" . $table;
        $queryPrp = $this->pdo->prp($sql, []);
    }


    /**
     * Crée un block de contenu sur une page
     *
     * @param integer $position
     * @param string $title
     * @param integer $page_id
     * @return void
     */
    public function createBlock(int $position, string $title, int  $page_id)
    {
        $sql = "INSERT INTO {$this->table} (position, title, page_id) VALUES (?, ?, ?)";
        $this->pdo->prp($sql, [$position, $title, $page_id]);
    }
}