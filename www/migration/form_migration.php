<?php
    namespace App\migration;
    use App\kernel\migration\Migration;
    use App\kernel\migration\Table;

    $sql = Migration::create(DBPREFIXE.'form',[
        Table::id(),
        Table::newColumn('block_id', 'int')->default('not null'),
        Table::newColumn('title', 'varchar')->lenght(255)->default('not null')
    ]);

    $drop = Migration::drop(DBPREFIXE.'form');