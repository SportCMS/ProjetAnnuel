<?php

namespace App\models\factories;


use App\core\sql;
use App\models\factories\ContentInterface;

class ContentForm extends sql implements ContentInterface
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

    public function __toString()
    {
        return $this->getTitle();
    }

    public function showEntity()
    {
        return $this;
    }
}
