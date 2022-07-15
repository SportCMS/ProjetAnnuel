<?php
    namespace App\migration;
    use App\kernel\migration\Migration;
    use App\kernel\migration\Table;

    $sql = Migration::create(DBPREFIXE.'report',[
        Table::id(),
        Table::newColumn('comment_id', 'int')->default('not null'),
        Table::newColumn('message', 'text')->default('not null'),
        Table::newColumn('email', 'varchar')->lenght(255)->default('not null'),
        Table::newColumn('has_read', 'tinyint'),
        Table::timestamps()

    ]);

    $drop = Migration::drop(DBPREFIXE.'report');