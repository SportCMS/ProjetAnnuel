<?php
    namespace App\migration;
    use App\kernel\migration\Migration;
    use App\kernel\migration\Table;

    $sql = Migration::create(DBPREFIXE.'role',[
        Table::id(),
        Table::newColumn('libelle', 'varchar')->lenght(80)
    ]);

    $drop = Migration::drop(DBPREFIXE.'role');