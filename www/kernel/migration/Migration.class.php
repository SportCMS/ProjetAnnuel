<?php
    namespace App\kernel\migration;

    class Migration{
        public static function create($name = null, $columns = null){
            if(is_null($name) and is_null($columns)){
                die('Attention une erreur est présente dans la création des migrations');
            }
            $sql = 'CREATE TABLE ' . $name . '(';
            foreach($columns as $column){
                if(!in_array($column->getTable()['type'], $column->Type)){
                    die("\e[0;30;41mAttention ! la migration vers la base de donnée a été arrété car une erreur de type est présente sur la migration : " . $name  . "\e[0m\n");
                }
                $data = array_diff($column->getTable(), [null]);
                $sql  .= implode($data, ' ') . ', ';
            }
            $sql .= 'PRIMARY KEY(id))';
            return $sql;
        }
        public static function createFk($name, $column){
            if(is_null($name) and is_null($column)){
                die('Attention une erreur est présente dans la création des migrations');
            }
            $column = $column->getTable();
            return "ALTER TABLE " . $name . " ADD " . $column['column'] . " INT; 
            ALTER TABLE " . $name . " ADD CONSTRAINT " . $column['constraint'] . " FOREIGN KEY (" . $column['column'] . ") REFERENCES " . $column['table'] . "(id);";  
        }
        public static function drop($name){
            if(is_null($name)){
                die('Attention une erreur est présente dans la création des migrations');
            }
            return "DROP TABLE " . $name;
        }
        public static function dropConstraint($name, $constraint){
            if(is_null($name) and is_null($constraint)){
                die('Attention une erreur est présente dans la création des migrations');
            }
            return "ALTER TABLE " . $name . " DROP CONSTRAINT " . $constraint;
        }
    }