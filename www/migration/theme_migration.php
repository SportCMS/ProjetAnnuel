<?php
    namespace App\migration;
    use App\kernel\migration\Migration;
    use App\kernel\migration\Table;

    $sql = Migration::create(DBPREFIXE.'text',[
        Table::id(),
        Table::newColumn('name', 'varchar')->lenght(255)->default('not null'),
        Table::newColumn('description', 'text')->default('not null'),
        Table::newColumn('domain', 'varchar')->lenght(255)->default('not null'),
        Table::newColumn('image', 'varchar')->lenght(255)

    ]);

    $drop = Migration::drop(DBPREFIXE.'text');