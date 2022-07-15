<?php
    namespace App\migration;
    use App\kernel\migration\Migration;
    use App\kernel\migration\Table;

    $sql = Migration::create(DBPREFIXE.'card',[
        Table::id(),
        Table::newColumn('block_id', 'int'),
        Table::newColumn('title', 'varchar')->lenght(255),
        Table::newColumn('content', 'text'),
        Table::newColumn('image', 'varchar')->lenght(255),
        Table::newColumn('link', 'varchar')->lenght(255)
    ]);

    $drop = Migration::drop(DBPREFIXE.'card');