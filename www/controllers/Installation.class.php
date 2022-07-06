<?php

namespace App\controllers;

use App\core\Router;
use App\core\Sql;
use App\models\User;
use App\models\Theme;
use App\Helpers\Fixtures;

class Installation extends Sql
{
    /**
     * this route allow user to complete the registration after his first login
     * the form provide datas to set the database (global variables) and set the website
     */
    public function completeRegistration()
    {
        // if ($_SESSION['role'] != 'admin') {
        //     header('Location: /home');
        // }
        // if ($_SESSION['role'] == 'admin' && $_SESSION['status'] == 2) {
        //     header('Location: /dashboard');
        // }

        $themeManager = new Theme();
        $themes = $themeManager->getAll();

        $_SESSION['email'] = 'admin@gmail.com';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $alert = [];
            $databaseName = $_POST['databaseName'] ?? null;
            $tablePrefix = $_POST['tablePrefix'] ?? null;
            $domain = $_POST['domain'] ?? null;
            $theme = $_POST['chooseTheme'] ?? null;

            if (
                $databaseName == null || $tablePrefix == null || $domain == null || $theme == null
            ) {
                $alert = ["error", "Veuillez remplir tous les champs"];
                Router::render('admin/installation/completeRegistration.view.php', ['alert', $alert, 'themes' => $themes]);
            }

            if (!preg_match('/^[a-z]*\ +\d*$/i', $domain)) {
                $alert = ["error", "Veuillez renseigner un nom de domaine valide (ex : domain.fr)"];
                Router::render('admin/installation/completeRegistration.view.php', ['alert', $alert, 'themes' => $themes]);
            }

            $userManager = new User();
            $lastuser = $userManager->getOneBy(['email' => $_SESSION['email']])[0];

            $_SESSION['temp_firstname'] = $lastuser->getFirstname();
            $_SESSION['temp_lastname'] = $lastuser->getLastname();
            $_SESSION['temp_email'] = $lastuser->getEmail();
            $_SESSION['temp_password'] = $lastuser->getPassword();
            $_SESSION['temp_status'] = $lastuser->getStatus();
            $_SESSION['temp_role'] = $lastuser->getRole();
            $_SESSION['temp_token'] = $lastuser->getToken();
            $_SESSION['temp_dbName'] = $databaseName;
            $_SESSION['temp_prefix'] = $tablePrefix;
            $_SESSION['temp_domain'] = $domain;
            $_SESSION['temp_theme'] = $theme;

            $this->createDatabase();
            $this->dropDatabase();
            $this->writeDatabaseGlobals();

            header('Location: /loading');
        }
        Router::render('admin/installation/completeRegistration.view.php', ['themes' => $themes]);
    }


    /**
     * During the installation : 
     * the database is created and poplulated,  a new mysql admin
     */
    public function loading()
    {
        //     if ($_SESSION['role'] != 'admin') {
        //         header('Location: /home');
        //     }
        //     if ($_SESSION['role'] == 'admin' && $_SESSION['status'] == 2) {
        //         header('Location: /dashboard');
        //     }

        $this->createTables();
        $fixtures = new Fixtures();

        if ($_SESSION['temp_theme'] == 1)
            $fixtures->loadThemeTwentyFoot();
        if ($_SESSION['temp_theme'] == 2)
            $fixtures->loadThemeTwentyOneSports();

        $user = new User();
        $user->setFirstname($_SESSION['temp_firstname']);
        $user->setLastname($_SESSION['temp_lastname']);
        $user->setEmail($_SESSION['temp_email']);
        $user->setPassword($_SESSION['temp_password']);
        $user->setStatus($_SESSION['temp_status']);
        $user->setRole($_SESSION['temp_role']);
        $user->setStatus(2);
        $user->setSite($_SESSION['temp_theme']);
        $user->save();

        Router::render('admin/installation/loadingPage.view.php');
    }

    /**
     * write new global variables for database connection  and erase the former ones
     * file conf.inc.php
     * @return void
     */
    private function writeDatabaseGlobals()
    {
        $content = file_get_contents('conf.inc.php');
        $content = explode('define', $content);

        $arrayOutput = [];
        foreach ($content as $var) {
            $var = str_replace('(', '', $var);
            $var = str_replace(')', '', $var);
            $var = str_replace(';', '', $var);
            $var = explode(',', $var);
            $arrayOutput[] = $var;
        }

        $newOutput = [];
        for ($i = 0; $i < count($arrayOutput); $i++) {
            if ($arrayOutput[$i][0] == "'DBNAME'")
                array_push($newOutput, ["'DBNAME'", "'" . $_SESSION['temp_dbName'] . "'"]);
            elseif (trim($arrayOutput[$i][0]) == "'DBPREFIXE'")
                array_push($newOutput, ["'DBPREFIXE'", "'" . $_SESSION['temp_prefix'] . "'"]);
            elseif (trim($arrayOutput[$i][0]) == "'DOMAIN'")
                array_push($newOutput, ["'DOMAIN'", "'" . $_SESSION['temp_domain'] . "'"]);
            else
                $newOutput[] = $arrayOutput[$i];
        }

        $content = "";
        $content = "\n";
        $content .= '<?php';
        unset($newOutput[0]);
        foreach ($newOutput as $new) {
            $content .= "\ndefine( {$new[0]}, {$new[1]} );";
        }

        $content = file_put_contents('conf.inc.php', $content);
    }
}