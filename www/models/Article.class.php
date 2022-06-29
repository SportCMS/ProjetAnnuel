<?php

namespace App\models;


use App\core\sql;

class Article extends sql
{
    protected $id;
    protected $title;
    protected $category_id;
    protected $content;
    protected $position;
    protected $created_at;
    protected $updated_at;
    protected $slug;
    protected $image;

    public function __construct()
    {
        parent::__construct();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setTitle($title): void
    {
        $this->title = $title;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }


    public function setCategoryId($category_id): void
    {
        $this->category_id = $category_id;
    }

    public function getCategoryId(): ?int
    {
        return $this->category_id;
    }

    public function setContent($content): void
    {
        $this->content = $content;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }


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

    public function setPosition($position): void
    {
        $this->position = $position;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setSlug($slug): void
    {
        $this->slug = $slug;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setImage($image): void
    {
        $this->image = $image;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function getArticleForm($params = null): array
    {
        $category = new Categorie();
        $categories = $category->getAll();

        $datas = [];
        for ($i = 0; $i < count($categories); $i++) {
            $datas[] = [$categories[$i]['id'], $categories[$i]['name']];
        }

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
                    "id" => "editor",
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
