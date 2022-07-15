<?php
    namespace App\migration;
    use App\kernel\migration\Migration;
    use App\kernel\migration\Table;

    $sql = Migration::create(DBPREFIXE.'contact',[
        Table::id(),
        Table::newColumn('message', 'text')->default('not null'),
        Table::newColumn('email', 'varchar')->lenght(255)->default('not null'),
        Table::timestamps()
    ]);

    $drop = Migration::drop(DBPREFIXE.'contact');