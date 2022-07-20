<?php
    namespace App\querys;
    use App\core\Query;

    use App\models\Password_reset;

    class QueryPassword_reset extends Query{
        public function __construct(){
            parent::__construct(Password_reset::class);
        }
    }