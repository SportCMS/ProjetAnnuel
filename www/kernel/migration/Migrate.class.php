<?php
    namespace App\kernel\migration;
    use App\core\Db;
    
    class Migrate{

        public static function create($name){
            $file = "migration/" .$name . "_migration" . ".php";
            if(file_exists($file)){
                die ("\e[0;30;41mAttention ! Cette migration existe déjà\e[0m\n");
            }
            $content = "<?php
    namespace App\migration;
    use App\kernel\migration\Migration;
    use App\kernel\migration\Table;

    \$sql = Migration::create(DBPREFIXE.'" . $name . "',[
        Table::id(),
        Table::newColumn('nom', 'type')
    ]);
    
    \$drop = Migration::drop(DBPREFIXE.'" . $name . "');";
            file_put_contents($file, $content);
            echo "\e[0;30;42mMigration créée avec succès !\e[0m\n";
            echo "\e[0;30;42mTemps éxecution : " . round((microtime(true) - EXECB), 4) .  "s\e[0m\n";
        }

        public static function reference($name, $nameTo){
            $file = "migration/" .$name . "_to_" . $nameTo . ".php";
            if(file_exists($file)){
                die("\e[0;30;41mAttention ! Cette reference existe déjà\e[0m\n");
            }
            $content = "<?php
    namespace App\migration;
    use App\kernel\migration\Migration;
    use App\kernel\migration\Table;

    \$sql = Migration::createFk(DBPREFIXE.'" . $name . "',
        Table::reference('id_" . $nameTo ."', DBPREFIXE.'" . $nameTo . "')->constraint('" . $name . "_to_" . $nameTo . "'));
        
    \$drop = Migration::dropConstraint(DBPREFIXE.'" . $name . "','forget_password_to_user');";
        file_put_contents($file, $content);
        echo "\e[0;30;42mReference créée avec succès !\e[0m\n";
        echo "\e[0;30;42mTemps éxecution : " . round((microtime(true) - EXECB), 4) .  "s\e[0m\n";
        }

        public static function toDB(){
            $conn = Db::connect();
            $migrations = array_diff(scandir('migration/'), ['.', '..']);
            $stmts = [];
            foreach($migrations as $migration){
                $table = str_ireplace('.php', '', $migration);
                $table = explode('_', $table);
                if(in_array('migration', $table)){
                    $stmt = $conn->prp("show tables where Tables_in_" . DBNAME . " = ?", [str_ireplace('_migration.php', '', DBPREFIXE . $migration)]);
                    $row = $stmt->fetch();
                    if($row === false){
                        include 'migration/' . $migration;
                        array_unshift($stmts, $sql);
                    }
                }else{
                    $stmt = $conn->prp("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = ? AND CONSTRAINT_NAME = ?", [DBNAME, str_ireplace('.php', '', $migration)]);
                    $row = $stmt->fetch();
                    if($row === false){
                        include 'migration/' . $migration;
                        array_push($stmts, $sql);
                    }
                }
            }
            foreach($stmts as $stmt){
                $conn->prp($stmt);
            }
            echo "\e[0;30;42mMigrations faites avec succès !\e[0m\n";
            echo "\e[0;30;42mTemps éxecution : " . round((microtime(true) - EXECB), 4) .  "s\e[0m\n";
        }

        public static function show(){
            $conn = Db::connect();
            $migrations = array_diff(scandir('migration/'), ['.', '..']);
            foreach($migrations as $migration){
                $table = str_ireplace('.php', '', $migration);
                $table = explode('_', $table);
                $migration = str_ireplace('.php', '', $migration);
                if(in_array('migration', $table)){
                    $stmt = $conn->prp("show tables where Tables_in_" . DBNAME . " = ?", [str_ireplace('_migration', '', DBPREFIXE . $migration)]);
                    $row = $stmt->fetch();
                    echo ($row != false) ? $migration . " => \e[0;30;42mMigrée\e[0m\n" : $migration . " => \e[0;30;41mEn attente de migration\e[0m\n";
                }else{
                    $stmt = $conn->prp("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = ? AND CONSTRAINT_NAME = ?", [DBNAME, $migration]);
                    $row = $stmt->fetch();
                    echo ($row != false) ?  $migration . " => \e[0;30;42mMigrée\e[0m\n" : $migration . " => \e[0;30;41mEn attente de migration\e[0m\n";
                }
            }
            echo "\e[0;30;42mTemps éxecution : " . round((microtime(true) - EXECB), 4) .  "s\e[0m\n";
        }

        public static function purgeDB(){
            echo "\e[0;30;41mAttention !\e[0m\n";
            echo "\e[1;33mVous êtes sur le point de supprimer tout ce qui se trouve sur votre base de donnée.\e[0m\n";
            echo "\e[1;33mToutes vos tables, contraintes et données vont être suprrimé et ne pourrons pas être récupérer.\e[0m\n";
            $op = readline("\e[1;33mConfirmez votre attention en écrivant oui / non : \e[0m");
            if($op == "oui"){
                $conn = Db::connect();
                $migrations = array_diff(scandir('migration/'), ['.', '..']);
                $stmts = [];
                foreach($migrations as $migration){
                    $table = str_ireplace('.php', '', $migration);
                    $table = explode('_', $table);
                    if(in_array('migration', $table)){
                        $stmt = $conn->prp("show tables where Tables_in_" . DBNAME . " = ?", [str_ireplace('_migration.php', '', DBPREFIXE . $migration)]);
                        $row = $stmt->fetch();
                        if($row != false){
                            include 'migration/' . $migration;
                            array_push($stmts, $drop);
                        }
                    }else{
                        $stmt = $conn->prp("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = ? AND CONSTRAINT_NAME = ?", [DBNAME, str_ireplace('.php', '', $migration)]);
                        $row = $stmt->fetch();
                        if($row != false){
                            include 'migration/' . $migration;
                            array_unshift($stmts, $drop);
                        }
                    }
                }
                foreach($stmts as $stmt){
                    $conn->prp($stmt);
                }
                echo "\e[0;30;42mBase de donnée supprimée avec succès !\e[0m\n";
            }else{
                echo "\e[0;30;44mSuppression annulée\e[0m\n";
            }

        }
    }
