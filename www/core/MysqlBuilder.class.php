<?php

    namespace App\core;
    use App\core\QueryBuilder;
    use App\core\Db;
    
    class MysqlBuilder implements QueryBuilder
    {
        private $query;
        private $pdo;
        private $table;
        private $class;

        public function __construct($class){
            $this->pdo = Db::connect();
            $table = explode("\\", $class);
            $this->table = DBPREFIXE . strtolower(end($table));
            $this->class = $class; 
        }

        public function setTable($table){
            $this->table = $table;
            return $this;
        }

        private function reset()
        {
            $this->query = new \stdClass();
        }

        /********************/
        /*******SELECT*******/
        /********************/
        public function select(array $columns): QueryBuilder
        {
            $this->reset();
            $this->query->base = "SELECT " . implode(", ", $columns) . " FROM " . $this->table;
            return $this;
        }

        /********************/
        /*******WHERE********/
        /********************/
        public function where(string $column, string $value, string $operator = "="): QueryBuilder
        {
            $this->query->where[] = $column . " " . $operator . " " . "'" . $value . "'";
            return $this;
        }

        /********************/
        /******OR WHERE******/
        /********************/
        public function orwhere(string $column, string $value, string $operator = "="): QueryBuilder
        {
            $this->query->orwhere[] = " OR " . $column . " " . $operator . " " . "'" . $value . "'";
            return $this;
        }

        /********************/
        /*****RIGHT JOIN*****/
        /********************/
        public function rightJoin(string $table, string $fk, string $pk): QueryBuilder 
        {
            $this->query->join[] = " RIGHT JOIN " . $table . " ON " . $pk . " = " . $fk;
            return $this;
        }

        /********************/
        /******ORDER BY******/
        /********************/
        public function orderBy(string $column, string $scale = ""): QueryBuilder 
        {
            if(!empty($scale)){
                $scale = " " . $scale;
            }
            $this->query->orderby[] = $column . $scale;
            return $this;
        }

        /********************/
        /*******LIMIT********/
        /********************/
        public function limit(int $from, int $offset): QueryBuilder
        {
            $this->query->limit = " LIMIT " . $from . ", " . $offset;
            return $this;
        }

        public function getQuery(): QueryBuilder
        {
            $query = $this->query;

            $sql = $query->base;

            if (!empty($query->join)) {
                $sql .= implode(' ', $query->join);
            }

            if (!empty($query->where)) {
                $sql .= " WHERE " . implode(' AND ', $query->where);
            }

            if (!empty($query->orwhere)) {
                $sql .= implode($query->orwhere);
            }

            if (!empty($query->orderby)) {
                $sql .= " ORDER BY " .  implode(", ", $query->orderby);
            }

            if (isset($query->limit)) {
                $sql .= $query->limit;
            }

            $sql .= ";";

            $this->query->sql = $sql;
            return $this;
        }
        /** RESULT **/
        public function getResult(){
            $stmt = $this->pdo->prp($this->query->sql);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        public function getObject(){
            $val = [];
            $stmt = $this->pdo->prp($this->query->sql);
            while($row = $stmt->fetchObject($this->class)){
                array_push($val, $row); 
            }
            return $val;
        }
    }