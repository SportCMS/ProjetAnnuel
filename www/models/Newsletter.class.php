<?php

namespace App\models;

use App\core\sql;

class Newsletter extends sql
{
    protected $id;
    protected $email;

    public function __construct()
    {
        parent::__construct();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }
}