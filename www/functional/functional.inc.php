<?php
    use App\core\Router;
    //use App\core\Sql as sql;

    /* FONCTION DD : ARRET DU PROGRAMME ET AFFICHE LA VALEUR D'UNE VARIABLE AU CHOIX */
    function dd($value = null){
        echo '<pre style="background-color: black; color: #1DF133;">';
        var_dump($value);
        echo '</pre>';
        die();
    }

    /* FONCTION ABORT : RETOURNE LE BON CODE ERREUR ET LA BONNE PAGE*/
    function abort($code){
        http_response_code($code);
        $file = 'front/errors/' . $code .'.view.php';
        if(file_exists( "views/" . $file )){
            Router::render($file);
        }
        die();
    }