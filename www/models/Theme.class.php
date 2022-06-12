<?php

namespace App\models;

use App\core\sql;

class Theme extends sql
{
    protected $id;
    protected $name;
    protected $description;
    protected $domain;

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

    public function setDescription($description): void
    {
        $this->description = $description;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDomain($domain): void
    {
        $this->domain = $domain;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }
}