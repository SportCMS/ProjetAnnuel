<?php

    namespace App\Core;
    use App\core\MysqlBuilder;

    class Query{
        private $query;

        public function __construct($class){
            $this->query = new MysqlBuilder($class);
        }

        public function get($id){
            $this->query->select(["*"])
                        ->where("id", $id);
            return $this->query->getQuery()->getObject();
        }

        public function getAll(){
            $this->query->select(["*"]);
            return $this->query->getQuery()->getObject();
        }

        public function getBy($criterias){
            $this->query->select(["*"]);
            foreach($criterias as $key => $criteria){
                $this->query->where($key, $criteria);
            }
            return $this->query->getQuery()->getObject();
        }
    }