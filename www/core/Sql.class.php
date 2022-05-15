<?php
    namespace App\core;
    abstract class Sql{
        private $pdo;
        private $table;
        public function __construct(){
            try{
                $this->pdo = new \PDO(DBDRIVER.":host=".DBHOST.";port=".DBPORT.";dbname=".DBNAME , DBUSER , DBPWD
                , [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING]);
            }catch(\Exception $e){
                die('Error sql' . $e->getMessage());
            }
            $getCalledClass = explode('\\', strtolower(get_called_class()));//Récupérer la classe appelé, (Pour insert dans la bonne base)
            $this->table = DBPREFIXE . end($getCalledClass);//création du nom de la table avec son préfix
        }
        public function setId(?int $id):self
        {
            $sql = 'SELECT * FROM ' . $this->table . ' WHERE id=:id';
            $queryPrp = $this->pdo->prepare($sql);
            $queryPrp->execute(['id' => $id]);
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
            $queryPrp = $this->pdo->prepare($sql);
            $queryPrp->execute($colums);
        }

        public function getOneBy($entrie)
        {
            $val = [];
            $sql = 'SELECT * FROM ' . $this->table . ' WHERE ' . array_keys($entrie)[0] . '=:' . array_keys($entrie)[0];
            $queryPrp = $this->pdo->prepare($sql);
            $queryPrp->execute($entrie);
            while($row = $queryPrp->fetchObject()){
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
            $queryPrp = $this->pdo->prepare($sql);
            $queryPrp->execute($entrie);
            while($row = $queryPrp->fetchObject()){
                array_push($val, $row); 
            }
            return $val;
        }

        public function activateAccount(?int $id){
            $sql = 'UPDATE ' . $this->table . ' SET status=1 WHERE id=:id';
            $queryPrp = $this->pdo->prepare($sql);
            $queryPrp->execute(['id' => $id]);
        }

        public function uniqueMailVerification($email){
            $sql = 'SELECT count(email) FROM ' . $this->table . " WHERE email=:email";
            $queryPrp = $this->pdo->prepare($sql);

            if($queryPrp->execute(['email' => $email])){
                $object = $queryPrp->fetch();
                return $object;
            }
            return false;
        }
    }