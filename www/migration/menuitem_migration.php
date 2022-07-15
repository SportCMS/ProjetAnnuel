<?php
    namespace App\migration;
    use App\kernel\migration\Migration;
    use App\kernel\migration\Table;

    $sql = Migration::create(DBPREFIXE.'menuitem',[
        Table::id(),
        Table::newColumn('name', 'varchar')->lenght(255)->default('not null'),
        Table::newColumn('link', 'varchar')->lenght(255)->default('not null'),
        Table::newColumn('js_class', 'varchar')->lenght(255),
        Table::newColumn('js_id', 'varchar')->lenght(255),
        Table::newColumn('article_id', 'int')->default('not null')
    ]);

    $drop = Migration::drop(DBPREFIXE.'menuitem');