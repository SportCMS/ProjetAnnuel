<?php
    namespace App\migration;
    use App\kernel\migration\Migration;
    use App\kernel\migration\Table;

    $sql = Migration::create(DBPREFIXE.'like',[
        Table::id(),
        Table::newColumn('user_id', 'int')->default('not null'),
        Table::newColumn('article_id', 'int')->default('not null')
    ]);

    $drop = Migration::drop(DBPREFIXE.'like');