<?php
    namespace App\querys;
    use App\core\Query;

    class QueryUser extends Query{
        public function __construct(){
            parent::__construct(User::class);
        }
    }