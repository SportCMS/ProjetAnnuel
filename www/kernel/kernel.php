<?php
    namespace App\kernel;
    use App\kernel\migration\Migrate;
    use App\kernel\controller\Controller;
    
    if(is_null(USER)){
        die("\e[0;30;41mAttention ! Merci de définir votre nom d'utilisateur dans le fichier .env\e[0m\n");
    }
    define('EXECB', microtime(true));
    $argv[1] = (isset($argv[1])) ? $argv[1] : null;
    switch($argv[1]){
        case 'migrate':
            $argv[2] = (isset($argv[2])) ? $argv[2] : null;
            switch($argv[2]){
                case 'create':
                    if(!isset($argv[3])){
                        echo "\e[0;30;41mAttention ! Pour créer une migration vous devez renseigner son nom\e[0m\n";
                        die("\e[1;34mphp mvc migrate create <nom migration>\e[0m\n");
                    }
                    Migrate::create($argv[3]);
                    break;
                case 'reference':
                    if(!isset($argv[3]) and !isset($argv[4])){
                        echo "\e[0;30;41mVeuillez renseigner les tables qui doivent se faire reference dans l'ordre\e[0m\n";
                        die("\e[1;34mphp mvc migrate reference <nom table> <nom reference>\e[0m\n");
                    }
                    Migrate::reference($argv[3], $argv[4]);
                    break;
                case 'toDB':
                    Migrate::toDB();
                    break;
                case 'show':
                    Migrate::show();
                    break;
                case 'purgeDB':
                    Migrate::purgeDB();
                    break;
                default:
                    echo "\e[0;30;43mOption inconnue ou nul\e[0m\n";
                    echo "\e[1;34mphp mvc migrate [option] <args>
    -- create <nom migration>
    -- reference <nom migration>
    -- toDB
    -- show\e[0m\n";
            }
            break;
        case 'controller':
            $argv[2] = (isset($argv[2])) ? $argv[2] : null;
            switch($argv[2]){
                case 'create':
                    if(!isset($argv[3])){
                        echo "\e[0;30;41mAttention ! Pour créer un controller vous devez renseigner son nom\e[0m\n";
                        die("\e[1;34mphp mvc controller create <nom controller>\e[0m\n");
                    }
                    Controller::create($argv[3]);
                    break;
                default:
                echo "\e[0;30;43mOption inconnue ou non donnée\e[0m\n";
                echo "\e[1;34mphp mvc controller [option] <args>
    -- create <nom controller>\e[0m\n";
            }
            break;
        default:
            echo "\e[0;30;43mCommande inconnue\e[0m\n";
            echo "\e[1;34mphp mvc [commande] [option] <args>
    -- migrate [option] <args>
    -- controller [option] <args>\e[0m\n";
    }