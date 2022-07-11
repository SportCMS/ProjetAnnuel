<?php

    namespace App\core;

    interface QueryBuilder
    {
        public function select(array $columns): QueryBuilder;

        public function where(string $column, string $value, string $operator = "="): QueryBuilder;

        public function orwhere(string $column, string $value, string $operator = "="): QueryBuilder;

        public function orderBy(string $column, string $scale = ""): QueryBuilder;

        public function rightJoin(string $table, string $fk, string $pk): QueryBuilder;
        
        public function limit(int $from, int $offset): QueryBuilder;

        public function getQuery(): QueryBuilder;

        public function getResult();

        public function getObject();
    }