<?php
    namespace App\migration;
    use App\kernel\migration\Migration;
    use App\kernel\migration\Table;

    $sql = Migration::create(DBPREFIXE.'input',[
        Table::id(),
        Table::newColumn('type', 'varchar')->lenght(255)->default('not null'),
        Table::newColumn('js_id', 'varchar')->lenght(255),
        Table::newColumn('js_class', 'varchar')->lenght(255),
        Table::newColumn('placeholder', 'varchar')->lenght(255),
        Table::newColumn('name', 'varchar')->lenght(255),
        Table::newColumn('value', 'varchar')->lenght(255),
        Table::newColumn('label', 'varchar')->lenght(255),
        Table::newColumn('form_id', 'int')
    ]);

    $drop = Migration::drop(DBPREFIXE.'input');