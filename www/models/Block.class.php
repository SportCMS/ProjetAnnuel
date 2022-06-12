<?php

namespace App\models;


use App\core\sql;

class Block extends sql
{
    protected $id;
    protected $page_id;
    protected $title;
    protected $position;

    public function __construct()
    {
        parent::__construct();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setPageId($page_id): void
    {
        $this->page_id = $page_id;
    }

    public function getPageId(): ?int
    {
        return $this->page_id;
    }

    public function setTitle($title): void
    {
        $this->title = $title;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setPosition($position): void
    {
        $this->position = $position;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }
}