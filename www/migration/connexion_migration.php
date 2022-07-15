<?php
    namespace App\migration;
    use App\kernel\migration\Migration;
    use App\kernel\migration\Table;

    $sql = Migration::create(DBPREFIXE.'connexion',[
        Table::id(),
        Table::newColumn('ip', 'varchar')->lenght(255)->default('not null'),
        Table::newColumn('user_id', 'int'),
        Table::newColumn('date', 'datetime')->default('not null')
    ]);

    $drop = Migration::drop(DBPREFIXE.'connexion');