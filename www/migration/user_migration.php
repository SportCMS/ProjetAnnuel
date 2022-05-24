<?php
    namespace App\migration;
    use App\kernel\migration\Migration;
    use App\kernel\migration\Table;

    $sql = Migration::create(DBPREFIXE.'user',[
        Table::id(),
        Table::newColumn('email', 'varchar')->lenght(320)->unique(),
        Table::newColumn('password', 'varchar')->lenght(255),
        Table::newColumn('firstname', 'varchar')->lenght(25)->default('null'),
        Table::newColumn('lastname', 'varchar')->lenght(100)->default('null'),
        Table::newColumn('status', 'boolean')->default(0),
        Table::newColumn('token', 'char')->lenght(255)->default('null'),
        Table::timestamps()
    ]);

    $drop = Migration::drop(DBPREFIXE.'user');