<?php
    namespace App\migration;
    use App\kernel\migration\Migration;
    use App\kernel\migration\Table;

    $sql = Migration::create(DBPREFIXE.'block',[
        Table::id(),
        Table::newColumn('position', 'int'),
        Table::newColumn('title', 'varchar')->lenght(255),
        Table::newColumn('page_id', 'int')->default('null'),
    ]);

    $drop = Migration::drop(DBPREFIXE.'block');