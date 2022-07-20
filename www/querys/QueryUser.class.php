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

        public function countTodayUsers():array
        {
            $year = (new \DateTime('now'))->format('Y');
            $month = (new \DateTime('now'))->format('m');
            $day = (new \DateTime('now'))->format('d');
            $this->query
                ->select(["count(id) as 'count'"])
                ->where("date_format(created_at, '%Y')", '{' . $year . '}')
                ->where("date_format(created_at, '%m')", '{' . $month . '}')
                ->where("date_format(created_at, '%d')", '{' . $day . '}');
            
            return $this->query->getQuery()->getResult();
        }

        public function countWeekUsers()
        {
            $week = date('W');
            $this->query
                ->select(["count(id) as 'count'"])
                ->where("WEEK(created_at)", '{' . $week . '}');

            return $this->query->getQuery()->getResult();
        }

        public function countMonthUsers():array
        {
            $date = (new \DateTime('now'))->format('Y-m');
            $this->query
                ->select(["count(id) as 'count'"])
                ->where("created_at", '{' . $date . '}-1\'' . ' AND ' . '\'{' . $date . '}-31', 'BETWEEN');
            
            return $this->query->getQuery()->getResult();
        }

        public function getLastInscriptions()
        {
            $this->query
                ->select(["*"])
                ->orderBy("created_at", "DESC")
                ->limit(5, 5);
            
            return $this->query->getQuery()->getAllResult();
        }
    }