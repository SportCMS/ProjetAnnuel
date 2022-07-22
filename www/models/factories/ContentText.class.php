<?php

namespace App\models\factories;

use App\core\sql;
use App\models\factories\ContentInterface;

class ContentText extends sql implements ContentInterface
{
    protected $id;
    protected $content;
    protected $block_id;

    public function __construct()
    {
        parent::__construct();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setContent($content): void
    {
        $this->content = $content;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }


    public function setBlockId($block_id): void
    {
        $this->block_id = $block_id;
    }

    public function getBlockId(): ?int
    {
        return $this->block_id;
    }

    public function __toString()
    {
        return $this->getContent();
    }

    public function showEntity()
    {
        return $this;
    }
}
