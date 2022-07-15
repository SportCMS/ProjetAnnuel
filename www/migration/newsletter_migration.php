<?php
    namespace App\migration;
    use App\kernel\migration\Migration;
    use App\kernel\migration\Table;

    $sql = Migration::create(DBPREFIXE.'newsletter',[
        Table::id(),
        Table::newColumn('email', 'varchar')->lenght(255)->default('not null')
    ]);

    $drop = Migration::drop(DBPREFIXE.'newsletter');