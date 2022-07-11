<?php

namespace App\models;


use App\core\Sql;

class Contact extends Sql
{

    protected $message;
    protected $email;
    protected $created_at;

    public function __construct()
    {
        parent::__construct();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setMessage($message): void
    {
        $this->message = $message;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setCreatedAt($created_at): void
    {
        $this->created_at = $created_at;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }
}