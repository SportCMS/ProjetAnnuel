<?php

namespace App\controllers;

use App\core\Sql;
use App\core\Router;


class Error
{
    public function notFoundError()
    {
        return Router::render('admin/errors/404.view.php');
    }
}