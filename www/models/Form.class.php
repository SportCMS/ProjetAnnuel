<?php

namespace App\models;


use App\core\Sql;

class Form extends Sql
{
    protected $id;
    protected $block_id;
    protected $title;

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

    public function setBlockId($block_id): void
    {
        $this->block_id = $block_id;
    }

    public function getBlockId(): ?string
    {
        return $this->block_id;
    }
}