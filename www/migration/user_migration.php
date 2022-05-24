<?php
    namespace App\migration;
    use App\kernel\migration\Migration;
    use App\kernel\migration\Table;

    $sql = Migration::create(DBPREFIXE.'user',[
        Table::id(),
        Table::newColumn('lastname', 'varchar')->lenght(255),
        Table::newColumn('name', 'varchar')->lenght(255),
        Table::newColumn('email', 'varchar')->lenght(255)->unique()
    ]);

    $drop = Migration::drop(DBPREFIXE.'user');