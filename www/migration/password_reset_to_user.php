<?php
    namespace App\migration;
    use App\kernel\migration\Migration;
    use App\kernel\migration\Table;

    $sql = Migration::createFk(DBPREFIXE.'password_reset',
        Table::reference('id_user', DBPREFIXE.'user')->constraint('password_reset_to_user'));
    
    $drop = Migration::dropConstraint(DBPREFIXE.'password_reset','password_reset_to_user');