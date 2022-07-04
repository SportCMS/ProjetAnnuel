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
    protected $created_at;
    protected $updated_at;

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

    public function getThemeId(): ?int
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
    public function setCreatedAt($created_at): void
    {
        $this->created_at = $created_at;
    }
    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }
    public function setUpdatedAt($updated_at): void
    {
        $this->updated_at = $updated_at;
    }
    public function getUpdatedAt(): ?string
    {
        return $this->updated_at;
    }
}
