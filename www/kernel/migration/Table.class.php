<?php
    namespace App\kernel\migration;
    
    class Table{
        private $table;
        public $Type = ['VARCHAR', 'CHAR', 'DATETIME', 'TIME', 'INT', 'BOOLEAN', 'timestamp'];

        private function __construct($table){
            $this->table = $table;
        }
        /* creer id table */
        public static function id(){
            return new Table(['name' => 'id', 'type' => 'INT', 'lenght' => null, 'unique' => null, 'ai' => 'AUTO_INCREMENT']);
        }
        /* créer une nouvelle column */
        public static function newColumn($name, $type){
            return new Table(['name' => $name, 'type' => strtoupper($type), 'lenght' => null, 'unique' => null, 'ai' => null, 'default' => 'NOT NULL']);
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
        /* création d'un "timestamps" poour une table */
        public static function timestamps(){
            return new Table(['name' => 'createAt', 'type' => 'timestamp', 'default' => 'DEFAULT CURRENT_TIMESTAMP' , 'separator' => ',', 'name2' => 'updateAt', 'type2' => 'timestamp', 'update' => 'ON UPDATE CURRENT_TIMESTAMP']);
        }
        /* valeur par défault de la table */
        public function default($value = 'NOT NULL'){
            $this->table['default'] = 'DEFAULT ' . $value;
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