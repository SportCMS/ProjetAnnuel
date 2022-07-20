<?php
    namespace App\querys;
    use App\core\Query;

    use App\models\User;

    class QueryUser extends Query{
        public function __construct(){
            parent::__construct(User::class);
        }
        public function searchUsers($search){
            $search = "%" . $search . "%";
            $this->query
                ->select(["*"])
                ->where("firstname", $search, 'LIKE')
                ->orwhere("lastname", $search, 'LIKE');
            return $this->query->getQuery()->getResult();
        }
    }