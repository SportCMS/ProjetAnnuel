<?php
    namespace App\core;
    use App\core\Db;
    use PDO;

    abstract class Sql{
        private $pdo;
        private $table;
        private $prefix = DBPREFIXE;
        
        public function __construct()
        {
            $this->pdo = Db::connect(); // singleton
            $getCalledClass = explode('\\', strtolower(get_called_class())); //Récupérer la classe appelé, (Pour insert dans la bonne base)
            $this->table = $this->prefix . end($getCalledClass); //création du nom de la table avec son préfix
        }

        public function setId(?int $id):self
        {
            $sql = 'SELECT * FROM ' . $this->table . ' WHERE id=:id';
            $queryPrp = $this->pdo->prp($sql, ['id' => $id]);
            return $queryPrp->fetchObject(get_called_class());
        }
        protected function save():void
        {
            $colums = get_object_vars($this);//récupération des données de l'objet
            $varToExplode = get_class_vars(get_class());//récupération des noms de variable de la classe sql
            $colums = array_diff_key($colums, $varToExplode);//suppréssion de la variable pdo des donnée de la class principales
            if($colums['id'] === null){
                $colums = array_diff($colums, [$colums['id']]);
                $sql = 'INSERT INTO ' . $this->table  . ' (' . implode(',', array_keys($colums)) . ') VALUES (:'. implode(',:', array_keys($colums)) . ')';
            }else{
                $update = [];
                foreach($colums as $key => $value){
                    $update[] = $key . '=:' . $key;
                }
                $sql = "UPDATE ".$this->table." SET ". implode(',', $update) . " WHERE id=:id";
            }
            $this->pdo->prp($sql, $colums);
        }
        


        public function getOneBy($entrie)
        {
            $val = [];
            $sql = 'SELECT * FROM ' . $this->table . ' WHERE ' . array_keys($entrie)[0] . '=:' . array_keys($entrie)[0];
            $queryPrp = $this->pdo->prp($sql, $entrie);
            while($row = $queryPrp->fetchObject(get_called_class())){
                array_push($val, $row); 
            }
            return $val;
        }
        
        public function getBy($entrie)
        {
            $val = [];
            $sql = 'SELECT * FROM ' . $this->table . ' WHERE ';
            foreach($entrie as $key=>$data){
                if (end($entrie) != $data){
                    $sql .= $key . '=:' . $key . ' and ';
                }else{
                    $sql .= $key . '=:' . $key;
                }
            }
            $queryPrp = $this->pdo->prp($sql, $entrie);
            while($row = $queryPrp->fetchObject(get_called_class())){
                array_push($val, $row); 
            }
            return $val;
        }

        public function getAll()
        {
            
            $sql = 'SELECT * FROM ' . $this->table;
            $queryPrp = $this->pdo->prp($sql);
            
            return $queryPrp->fetchAll(\PDO::FETCH_ASSOC);
        }

        
        public function delete($id)
        {
            $sql = "DELETE FROM {$this->table} WHERE id = ?";
            $this->pdo->prp($sql, [$id]);
        }

        // creation de la fonctionnalité commentaires
        public function getCommentsByArticle($id)
        {
            $sql = "SELECT c.id as 'idComment', c.parent_id as 'parent', c.author_id as 'author', c.title, c.content, c.created_at,u.firstname, u.lastname, u.id as 'idUser'
            FROM {$this->table} as c
            JOIN `bgfb_user`as u
            ON u.id = c.author_id
            WHERE c.article_id = ?
            AND c.parent_id IS NULL
            ORDER BY c.created_at DESC";
    
            $queryPrp = $this->pdo->prp($sql, [$id]);
            return $queryPrp->fetchAll(PDO::FETCH_ASSOC);
        }
    
        public function countComments($id)
        {
            $sql = "SELECT count(id) as 'count' FROM {$this->table}
            WHERE article_id = ?";
            $queryPrp = $this->pdo->prp($sql, [$id]);
            return $queryPrp->fetch();
        }
    
    
        public function getRepliesByComment($id)
        {
            $sql = "SELECT c.id as 'idComment', c.parent_id as 'parent', c.author_id as 'author', c.title, c.content, c.created_at,u.firstname, u.lastname, u.id as 'idUser'
            FROM {$this->table} as c
            JOIN `bgfb_user`as u
            ON u.id = c.author_id
            WHERE c.article_id = ?
            AND c.parent_id IS NOT NULL
            ORDER BY c.created_at DESC";
    
            $queryPrp = $this->pdo->prp($sql, [$id]);
            return $queryPrp->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getUserLikeByArticle($user_id, $article_id)
        {
            $sql = "SELECT l.id as 'like' FROM `bgfb_like` as l
            JOIN `bgfb_user`as u
            ON l.user_id = u.id
            JOIN `bgfb_article`as a
            ON l.article_id = a.id
            WHERE u.id = ?
            AND a.id = ?";
            $queryPrp = $this->pdo->prp($sql, [$user_id, $article_id]);
            return $queryPrp->fetchAll(PDO::FETCH_ASSOC);
        }
    

        public function toggleLikes($user_id, $article_id)
        {
            $likes = $this->getUserLikeByArticle($user_id, $article_id);
    
    
            if (count($likes) == 0) {
                $sql = "INSERT INTO bgfb_like (user_id, article_id) VALUES (?, ?)";
                $this->pdo->prp($sql, [$user_id, $article_id]);
            } else {
                $sql = "DELETE FROM {$this->table} WHERE user_id = ? AND article_id = ?";
                $this->pdo->prp($sql, [$user_id, $article_id]);
            }
        }

        public function countAllLikesByArticle($article_id)
        {
        $sql = "SELECT count(l.id) as 'likes' FROM `bgfb_like` as l
        JOIN `bgfb_article`as a
        ON l.article_id = a.id
        AND a.id = ?";
        $queryPrp = $this->pdo->prp($sql, [$article_id]);
        return $queryPrp->fetch();
        }


        // requete recupération des notifications pour la modération
        public function getReportNotifications()
        {
            $sql = "SELECT * FROM " . $this->table . " as r WHERE r.has_read = 0 ";
            $queryPrp = $this->pdo->prp($sql, []);
            return $queryPrp->fetchAll(PDO::FETCH_ASSOC);
        }

        // requete appelé dans admin
        public function deletePage($page)
        {
            $sql = "DELETE FROM {$this->table} WHERE title = ?";
            $this->pdo->prp($sql, [$page]);
        }

        public function deleteBlock($page)
        {
            $sql = "DELETE FROM {$this->table} WHERE page_id = ?";
            $this->pdo->prp($sql, [$page]);
        }   
        
        public function deleteComments($id)
        {
            $sql = "DELETE FROM {$this->table} WHERE article_id = ?";
            $this->pdo->prp($sql, [$id]);
        }

        // geestion de positions
        public function getAllByPosition()
        {
            $sql = 'SELECT * FROM ' . $this->table . ' ORDER BY position ';
            $queryPrp = $this->pdo->prp($sql);

            return $queryPrp->fetchAll(PDO::FETCH_ASSOC);
        }

        public function updateItemPosition($position, $block_id)
        {
            $sql = "UPDATE " . $this->table . " SET position = ?  WHERE id= ?";
            $queryPrp = $this->pdo->prp($sql, [$block_id, $position]);
        }

        // fixtures
        public function truncate($table)
        {
            $sql = "TRUNCATE TABLE bgfb_" . $table;
            $queryPrp = $this->pdo->prp($sql, []);
        }

        public function getBlockByPosition($pageId)
        {
            $sql = "SELECT b.id as blockId, f.id as formId, b.position, b.title, b.page_id, s.id as textId, s.block_id, s.content, f.title  as formTitle
                    FROM {$this->prefix}blocks as b 
                    LEFT JOIN {$this->prefix}texts as s ON s.block_id = b.id
                    LEFT JOIN {$this->prefix}forms as f ON f.block_id = b.id
                    WHERE page_id = ? 
                    ORDER BY position";
            $queryPrp = $this->pdo->prp($sql, [$pageId]);

            return $queryPrp->fetchAll(PDO::FETCH_ASSOC);
        }

        public function createBlock($position, $title, $page_id)
        {
            $sql = "INSERT INTO {$this->table} (position, title, page_id) VALUES (?, ?, ?)";
            $this->pdo->prp($sql, [$position, $title, $page_id]);
        }
    }