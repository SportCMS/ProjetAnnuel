<?php
    namespace App\migration;
    use App\kernel\migration\Migration;
    use App\kernel\migration\Table;

    $sql = Migration::create(DBPREFIXE.'text',[
        Table::id(),
        Table::newColumn('block_id', 'int')->default('not null'),
        Table::newColumn('content', 'text')->default('not null')

    ]);

    $drop = Migration::drop(DBPREFIXE.'text');