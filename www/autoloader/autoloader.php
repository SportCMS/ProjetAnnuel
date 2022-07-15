<?php
    function myAutoloader($class)
    {
        $class = str_ireplace('App\\',  '',$class);//On supprime "App\" de App\exemple\class.class.php
        $class = str_ireplace('\\', '/', $class);// 
        $class .= '.class.php';
        if(file_exists($class)){
            include $class;//On utilise include car plus rapide, et on a déjà vérifier son existance
        }else{
            die("la classe existe pas : " . $class . "\n");
        }
    }

    spl_autoload_register('\myAutoloader');