<?php
    namespace App\migration;
    use App\kernel\migration\Migration;
    use App\kernel\migration\Table;

    $sql = Migration::create(DBPREFIXE.'categorie',[
        Table::id(),
        Table::newColumn('name', 'varchar')->lenght(255)->default('not null'),
        Table::newColumn('description', 'text'),
        Table::newColumn('image', 'varchar')->lenght(255),
        Table::newColumn('slug', 'varchar')->lenght(255)->default('not null'),
        Table::timestamps()
    ]);

    $drop = Migration::drop(DBPREFIXE.'categorie');