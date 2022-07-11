<?php

namespace App\models;


use App\core\Sql;

class Connexion extends Sql
{
    protected $id;
    protected $ip;
    protected $user_id;
    protected $date;

    public function __construct()
    {
        parent::__construct();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setIp($ip): void
    {
        $this->ip = $ip;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setUserId($user_id): void
    {
        $this->user_id = $user_id;
    }

    public function getUserId(): ?string
    {
        return $this->user_id;
    }

    public function setDate($date): void
    {
        $this->date = $date;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }
}