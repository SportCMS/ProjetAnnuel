<?php
    namespace App\core;
    use App\core\Db;
    use PDO;

    abstract class Sql{
        private $pdo;
        private $table;
        private $prefix = DBPREFIXE;
        private $base = DBNAME;
        
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
            JOIN `{$this->prefix}user`as u
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
            JOIN `{$this->prefix}user`as u
            ON u.id = c.author_id
            WHERE c.article_id = ?
            AND c.parent_id IS NOT NULL
            ORDER BY c.created_at DESC";
    
            $queryPrp = $this->pdo->prp($sql, [$id]);
            return $queryPrp->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getUserLikeByArticle($user_id, $article_id)
        {
            $sql = "SELECT l.id as 'like' FROM `{$this->prefix}like` as l
            JOIN `{$this->prefix}user`as u
            ON l.user_id = u.id
            JOIN `{$this->prefix}article`as a
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
                $sql = "INSERT INTO {$this->prefix}like (user_id, article_id) VALUES (?, ?)";
                $this->pdo->prp($sql, [$user_id, $article_id]);
            } else {
                $sql = "DELETE FROM {$this->table} WHERE user_id = ? AND article_id = ?";
                $this->pdo->prp($sql, [$user_id, $article_id]);
            }
        }

        public function countAllLikesByArticle($article_id)
        {
        $sql = "SELECT count(l.id) as 'likes' FROM `{$this->prefix}like` as l
        JOIN `{$this->prefix}article`as a
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
            $sql = "TRUNCATE TABLE {$this->prefix}" . $table;
            $queryPrp = $this->pdo->prp($sql, []);

        }

        public function getBlockByPosition($pageId)
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

        public function createBlock($position, $title, $page_id)
        {
            $sql = "INSERT INTO {$this->table} (position, title, page_id) VALUES (?, ?, ?)";
            $this->pdo->prp($sql, [$position, $title, $page_id]);
        }

        public function getFormInputs($formId)
        {
            $sql = "SELECT * FROM {$this->prefix}input as i
                    LEFT JOIN {$this->prefix}form as f ON i.form_id = f.id
                    WHERE form_id = ?";
            $queryPrp = $this->pdo->prp($sql, [$formId]);

            return $queryPrp->fetchAll(PDO::FETCH_ASSOC);
        }
        public function dropDatabase(): void
    {
        $sql = "DROP DATABASE {$this->base};";
        $queryPrp = $this->pdo->prp($sql);
    }

    public function createDatabase()
    {
        $sql = "CREATE DATABASE {$_SESSION['temp_dbName']} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
        $queryPrp = $this->pdo->prp($sql);
    }
    public function createDatabaseTestDatas()
    {
        $sql = "CREATE DATABASE `mvcdocker2` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
        $queryPrp = $this->pdo->prp($sql);
    }
    public function createtablesDevTestDatas()
    {
        $sql = "CREATE TABLE {$this->prefix}article (id int(11) NOT NULL AUTO_INCREMENT,block_id int(11) DEFAULT NULL,category_id int(11) NOT NULL,title varchar(255) NOT NULL,slug varchar(255) NOT NULL,content text NOT NULL,position int(11) DEFAULT NULL,image varchar(255) NULL, created_at datetime DEFAULT CURRENT_TIMESTAMP,updated_at datetime DEFAULT NULL, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $sql .= "CREATE TABLE `{$this->prefix}block` (`id` int(11) NOT NULL AUTO_INCREMENT,`position` int(11) NOT NULL,`title` varchar(255) CHARACTER SET utf8 NOT NULL,`page_id` int(11) NOT NULL, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $sql .= "CREATE TABLE `{$this->prefix}card` (`id` int(11) NOT NULL AUTO_INCREMENT,`block_id` int(11) NOT NULL,`title` varchar(255) NOT NULL,`content` text NOT NULL,`image` varchar(255) NOT NULL,`link` varchar(255) NOT NULL, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $sql .= "CREATE TABLE `{$this->prefix}categorie` (`id` int(11) NOT NULL AUTO_INCREMENT,`name` varchar(255) NOT NULL,`description` text,`image` varchar(255) DEFAULT NULL,`slug` varchar(255) NOT NULL,`created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,`updated_at` datetime DEFAULT NULL, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $sql .= "CREATE TABLE `{$this->prefix}comment` (`id` int(11) NOT NULL AUTO_INCREMENT,`parent_id` int(11) DEFAULT NULL,`author_id` int(11) NOT NULL,`article_id` int(11) NOT NULL,`title` varchar(255) DEFAULT NULL,`content` text NOT NULL,`created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $sql .= "CREATE TABLE `{$this->prefix}connexion` (`id` int(11) NOT NULL AUTO_INCREMENT,`ip` varchar(255) NOT NULL,`user_id` int(11) DEFAULT NULL,`date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $sql .= "CREATE TABLE `{$this->prefix}contact` (`id` int(11) NOT NULL AUTO_INCREMENT,`message` text NOT NULL,`email` varchar(255) NOT NULL,`created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $sql .= "CREATE TABLE `{$this->prefix}form` (`id` int(11) NOT NULL AUTO_INCREMENT,`block_id` int(11) NOT NULL,`title` varchar(255) NOT NULL, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $sql .= "CREATE TABLE `{$this->prefix}input` (`id` int(11) NOT NULL AUTO_INCREMENT,`type` varchar(255) NOT NULL,`js_id` varchar(255) DEFAULT NULL,`js_class` varchar(255) DEFAULT NULL,`placeholder` varchar(255) DEFAULT NULL, `name` varchar(255) DEFAULT NULL,`value` varchar(255) DEFAULT NULL,`label` varchar(255) DEFAULT NULL,`form_id` int(11) DEFAULT NULL, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $sql .= "CREATE TABLE `{$this->prefix}like` (`id` int(11) NOT NULL AUTO_INCREMENT,`user_id` int(11) NOT NULL,`article_id` int(11) NOT NULL, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $sql .= "CREATE TABLE `{$this->prefix}menuitem` (`id` int(11) NOT NULL AUTO_INCREMENT,`name` varchar(255) NOT NULL,`link` varchar(255) NOT NULL,`js_class` varchar(255) DEFAULT NULL,`js_id` varchar(255) DEFAULT NULL,`position` int(11) NOT NULL, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $sql .= "CREATE TABLE `{$this->prefix}page` (`id` int(11) NOT NULL AUTO_INCREMENT,`theme_id` int(11) DEFAULT NULL,`title` varchar(255) NOT NULL,`link` varchar(255) NOT NULL,`active` tinyint(1) DEFAULT NULL,`type` varchar(255) NOT NULL, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $sql .= "CREATE TABLE `{$this->prefix}report` (`id` int(11) NOT NULL AUTO_INCREMENT,`comment_id` int(11) NOT NULL,`message` text NOT NULL,`email` varchar(255) NOT NULL,`has_read` tinyint(1) NOT NULL,`created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $sql .= "CREATE TABLE `{$this->prefix}text` (`id` int(11) NOT NULL AUTO_INCREMENT,`block_id` int(11) NOT NULL,`content` text NOT NULL, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $sql .= "CREATE TABLE `{$this->prefix}theme` (`id` int(11) NOT NULL AUTO_INCREMENT,`name` varchar(255) NOT NULL,`description` text NOT NULL,`domain` varchar(255) NOT NULL,`image` varchar(255) NULL, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $sql .= "CREATE TABLE `{$this->prefix}user` ( `id` int(11) NOT NULL AUTO_INCREMENT,`firstname` varchar(255) DEFAULT NULL,`lastname` varchar(255) DEFAULT NULL,`email` varchar(255) NOT NULL,`status` int(11) NOT NULL,`password` varchar(255) NOT NULL,`role` varchar(255) DEFAULT NULL, `token` varchar(255) DEFAULT NULL,`created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,`updated_at` datetime DEFAULT NULL, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $queryPrp = $this->pdo->prp($sql);
    }
    public function createTables()
    {
        $sql = "CREATE TABLE {$_SESSION['temp_prefix']}article (id int(11) NOT NULL AUTO_INCREMENT,block_id int(11) DEFAULT NULL,category_id int(11) NOT NULL,title varchar(255) NOT NULL,slug varchar(255) NOT NULL,content text NOT NULL,position int(11) DEFAULT NULL,image varchar(255) NULL, created_at datetime DEFAULT CURRENT_TIMESTAMP,updated_at datetime DEFAULT NULL, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
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
        $sql .= "CREATE TABLE `{$_SESSION['temp_prefix']}user` ( `id` int(11) NOT NULL AUTO_INCREMENT,`firstname` varchar(255) DEFAULT NULL,`lastname` varchar(255) DEFAULT NULL,`email` varchar(255) NOT NULL,`status` int(11) NOT NULL,`password` varchar(255) NOT NULL,`role` varchar(255) DEFAULT NULL, `token` varchar(255) DEFAULT NULL,`created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,`updated_at` datetime DEFAULT NULL, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $queryPrp = $this->pdo->prp($sql);
    }

    public function getConnexionByDate(string $date, string $startRange)
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

    public function countMonthUsers()
    {
        $date = (new \DateTime('now'))->format('Y-m');
        $sql = "SELECT count(id) as 'count' FROM {$this->prefix}user
        WHERE created_at BETWEEN '{$date}-1' AND '{$date}-31' ";
        $queryPrp = $this->pdo->prp($sql, []);
        return $queryPrp->fetch();
    }

    public function countWeekUsers()
    {
        $week = date('W');
        $sql = "SELECT count(id) as 'count' FROM {$this->prefix}user
        WHERE WEEK(created_at) = {$week}";
        $queryPrp = $this->pdo->prp($sql, []);
        return $queryPrp->fetch();
    }

    public function countTodayUsers()
    {
        $year = (new \DateTime('now'))->format('Y');
        $month = (new \DateTime('now'))->format('m');
        $day = (new \DateTime('now'))->format('d');
        $sql = "SELECT count(id) as 'count' FROM {$this->prefix}user
        WHERE date_format(created_at, '%Y') = {$year}
        AND date_format(created_at, '%m') = {$month}
        AND date_format(created_at, '%d') = {$day}";
        $queryPrp = $this->pdo->prp($sql, []);
        return $queryPrp->fetch();
    }

    public function getInscriptionByDate(string $date, string $startRange, string $endRange)
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

    public function getLastInscriptions()
    {
        $sql = "SELECT * FROM {$this->prefix}user ORDER BY created_at DESC LIMIT 5";
        $queryPrp = $this->pdo->prp($sql, []);
        return $queryPrp->fetchAll();
    }

    public function getLastContacts()
    {
        $sql = "SELECT * FROM {$this->prefix}contact ORDER BY created_at DESC LIMIT 3";
        $queryPrp = $this->pdo->prp($sql, []);
        return $queryPrp->fetchAll();
    }

}