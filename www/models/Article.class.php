<?php

namespace App\models;


use App\core\sql;

class Article extends sql
{
    protected $id;
    protected $title;
    protected $block_id;
    protected $category_id;
    protected $content;
    protected $position;

    public function __construct()
    {
	    parent::__construct();
    }

    public function getId():?int
    {
	    return $this->id;
    }

    public function setTitle($title):void
    {
	    $this->title = $title;
    }	

    public function getTitle():?string
    {
	    return $this->title;
    }

    public function setBlockId($block_id):void
    {
	    $this->block_id = $block_id;
    }

    public function getBlockId():?int
    {
	    return $this->block_id;
    }

    public function setCategoryId($category_id):void
    {
	    $this->category_id = $category_id;
    }

    public function getCategoryId():?int
    {
	    return $this->category_id;
    }

    public function setContent($content):void
    {
	    $this->content = $content;
    }

    public function getContent():?string
    {
	    return $this->content;
    }

    public function setPosition($position):void
    {
	    $this->position = $position;
    }

    public function getPosition():?int
    {
	    return $this->position;
    }

    // ajout 
    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function setCreatedAt($created_at): void
    {
        $this->created_at = $created_at;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updateddAt;
    }

    public function setUpdatedAt($created_at): void
    {
        $this->created_at = $created_at;
    }

    public function getArticleForm($params = null):array
    {
        
        $category = new Categorie();
        $categories = $category->getAll();

        $datas = [];
        for ($i = 0; $i < count($categories); $i++) {
            $datas[] = [$categories[$i]->getId(),$categories[$i]->getName()];
        }
        //ajout
        return [
            "config" => [
                "method" => "POST",
                "action" => "",
                "class" => "formArticle",
                "id" => "formArticle",
                "submit" => "Enregistrer",
            ],

            "inputs" => [
                "title" => [
                    "value" =>  $params != null ? $params['title'] : "",
                    "type" => "text",
                    "id" => "title",
                    "class" => "title",
                    "placeholder" => "Titre de l'article",
                    //"required" => "required",
                    "error" => "Veuillez entrer un titre",
                ],

                "content" => [
                    "value" => $params != null ? $params['content'] : "",
                    "type" => "textarea",
                    "id" => "content",
                    "class" => "content",
                    "placeholder" => "Contenu de l'article",
                    //"required" => "required",
                    "min" => "10",
                    "max" => "1000",
                    "error" => "Veuillez entrer un contenu",
                ],

                "category_id" => [
                    "type" => "select",
                    "class" => "categories",
                    //"required" => "required",
                    "value" => $datas,
                    "selectedValue" => $params != null ? $params['selectedValue'] : "",
                ],
            ]
        ];
    }
}