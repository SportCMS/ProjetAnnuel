<?php
    namespace App\kernel\migration;
    
    class Table{
        private $table;
        public $Type = ['VARCHAR', 'CHAR', 'DATETIME', 'TIME', 'INT'];

        private function __construct($table){
            $this->table = $table;
        }
        /* creer id table */
        public static function id(){
            return new Table(['name' => 'id', 'type' => 'INT', 'lenght' => null, 'unique' => null, 'ai' => 'AUTO_INCREMENT']);
        }
        /* créer une nouvelle column */
        public static function newColumn($name, $type){
            return new Table(['name' => $name, 'type' => strtoupper($type), 'lenght' => null, 'unique' => null, 'ai' => null]);
        }
        /* element column unique */
        public function unique(){
            $this->table['unique'] = 'UNIQUE';
            return $this;
        }
        /* taille valeur column */
        public function lenght($len = null){
            $this->table['lenght'] = '('.$len.')';
            return $this;
        }
        /* création de la réference pour une clé étrangere */
        public static function reference($column, $table){
            return new Table(['column' => $column, 'table' => $table, 'constraint' => null]);
        }
        /* nom de la contrainte */
        public function constraint($name){
            $this->table['constraint'] = $name;
            return $this;
        }
        /* récupérer un column */
        public function getTable(){
            return $this->table;
        }
    }