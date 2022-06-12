<?php

namespace App\models;


use App\core\sql;

class Page extends sql
{
    protected $id;
    protected $title;
    protected $theme_id;
    protected $link;
    protected $type;


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

    public function setThemeId($theme_id): void
    {
        $this->theme_id = $theme_id;
    }

    public function getThemeId(): ?string
    {
        return $this->theme_id;
    }
    public function setLink($link): void
    {
        $this->link = $link;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function getType(): ?string
    {
        return $this->type;
    }
    public function setType($type): void
    {
        $this->type = $type;
    }
}
