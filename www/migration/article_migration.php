<?php
    namespace App\migration;
    use App\kernel\migration\Migration;
    use App\kernel\migration\Table;

    $sql = Migration::create(DBPREFIXE.'article',[
        Table::id(),
        Table::newColumn('libelle', 'varchar')->lenght(255)
    ]);

    $drop = Migration::drop(DBPREFIXE.'article');