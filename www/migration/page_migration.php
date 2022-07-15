<?php
    namespace App\migration;
    use App\kernel\migration\Migration;
    use App\kernel\migration\Table;

    $sql = Migration::create(DBPREFIXE.'page',[
        Table::id(),
        Table::newColumn('theme_id', 'int'),
        Table::newColumn('title', 'varchar')->lenght(255)->default('not null'),
        Table::newColumn('link', 'varchar')->lenght(255)->default('not null'),
        Table::newColumn('active', 'tinyint'),
        Table::newColumn('type', 'varchar')->lenght(255)->default('not null')

    ]);

    $drop = Migration::drop(DBPREFIXE.'page');