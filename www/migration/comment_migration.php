<?php
    namespace App\migration;
    use App\kernel\migration\Migration;
    use App\kernel\migration\Table;

    $sql = Migration::create(DBPREFIXE.'comment',[
        Table::id(),
        Table::newColumn('parent_id', 'int'),
        Table::newColumn('author_id', 'int')->default('not null'),
        Table::newColumn('article_id', 'int')->default('not null'),
        Table::newColumn('title', 'varchar')->lenght(255),
        Table::newColumn('content', 'text')->default('not null'),
        Table::timestamps()
    ]);

    $drop = Migration::drop(DBPREFIXE.'comment');