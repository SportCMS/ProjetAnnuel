<?php
    /* FONCTION ABORT : RETOURNE LE BON CODE ERREUR ET LA BONNE PAGE*/
    function abort($code){
        http_response_code($code);
        if(file_exists($file = 'views/errors/' . $code .'.view.php')){
            Router::render($file);
        }
        die();
    }

    /* FONCTION DD : ARRET DU PROGRAMME ET AFFICHE LA VALEUR D'UNE VARIABLE AU CHOIX */
    function dd($value = null){
        echo '<pre style="background-color: black; color: #1DF133;">';
        var_dump($value);
        echo '</pre>';
        die();
    }