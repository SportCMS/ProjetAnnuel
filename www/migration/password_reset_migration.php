<?php
    namespace App\migration;
    use App\kernel\migration\Migration;
    use App\kernel\migration\Table;

    $sql = Migration::create(DBPREFIXE.'password_reset',[
        Table::id(),
        Table::newColumn('token', 'char')->lenght(128),
        Table::newColumn('tokenExpiry', 'int')
    ]);

    $drop = Migration::drop(DBPREFIXE.'password_reset');