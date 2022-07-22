<?php
    namespace App\querys;
    use App\core\Query;

    use App\models\Like;

    class QueryLike extends Query{
        public function __construct(){
            parent::__construct(Like::class);
        }

        public function countAllLikesByArticle(int $article_id):array
        {
            $this->query
                ->select(["count(" . DBPREFIXE . "like.id) as 'likes'"])
                ->join("article", "id", "article_id")
                ->where(DBPREFIXE . "article.id", $article_id);
                
            return $this->query->getQuery()->getResult();
        }
    }