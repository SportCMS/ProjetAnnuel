<?php
    namespace App\migration;
    use App\kernel\migration\Migration;
    use App\kernel\migration\Table;

    $sql = Migration::create(DBPREFIXE.'article',[
        Table::id(),
        Table::newColumn('block_id', 'int')->default('null'),
        Table::newColumn('category_id', 'int'),
        Table::newColumn('title', 'varchar')->lenght(255),
        Table::newColumn('slug', 'varchar')->lenght(255),
        Table::newColumn('content', 'text'),
        Table::newColumn('position', 'int')->default('null'),
        Table::newColumn('image', 'varchar')->lenght(255)->default('null'),
        Table::timestamps()
    ]);

    $drop = Migration::drop(DBPREFIXE.'article');