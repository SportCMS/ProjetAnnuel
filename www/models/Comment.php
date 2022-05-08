<?php

namespace App\models;
use App\core\Sql;

class Comment extends Sql{
    
     
    protected $id; 

    protected $user_name; 

    protected $content; 

   // protected $articleId;
   // a faire quand on ajoute la model Article

    protected $date;


    public function __construct(){
	parent::__construct();
    }

    public function getId():?int
    {
	return $this->id;
    }

    public function setUserName($user_name):void
    {
	$this->user_name = strtolower($user_name);
    }

    public function getUserName():?string
    {
	return $this->user_name;
    }

    public function setContent($content):void
    {
	$this->content = $content;
    }

    public function getContent():?string
    {
	return $this->content;
    }

//     public function setArticle(?article $articleId):void
//     {
// 	$this->articleId = $articleId->$articleId;
//     }

    public function setDate():void
    {
	$this->date = date('Y-m-d H:i:s');
    }

    public function getDate():?string
    {
	return $this->date;
    }


}
