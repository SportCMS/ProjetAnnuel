<?php
    namespace App\migration;
    use App\kernel\migration\Migration;
    use App\kernel\migration\Table;

    $sql = Migration::create(DBPREFIXE.'admin',[
        Table::id(),
        Table::newColumn('pseudo', 'varchar')->lenght(255),
        Table::newColumn('libelle', 'varchar')->lenght(255),
    ]);

    $drop = Migration::drop(DBPREFIXE.'admin');