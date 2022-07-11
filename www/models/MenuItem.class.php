<?php

namespace App\models;


use App\core\Sql;

class MenuItem extends Sql
{
    protected $id;
    protected $name;
    protected $link;
    protected $js_class;
    protected $js_Id;
    protected $position;


    public function __construct()
    {
        parent::__construct();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setLink($link): void
    {
        $this->link = $link;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function getJsClass(): ?string
    {
        return $this->js_class;
    }
    public function setJsClass($js_class): void
    {
        $this->js_class = $js_class;
    }

    public function getJsId(): ?string
    {
        return $this->js_Id;
    }
    public function setJsId($js_Id): void
    {
        $this->js_Id = $js_Id;
    }

    public function setPosition($position): void
    {
        $this->position = $position;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }
}