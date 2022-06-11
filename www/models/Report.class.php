<?php

namespace App\models;


use App\core\sql;

class Report extends sql
{
    protected $id;
    protected $comment_id;
    protected $message;
    protected $email;
    protected $has_read;
    protected $created_at;

    public function __construct()
    {
        parent::__construct();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setCommentId($comment_id): void
    {
        $this->comment_id = $comment_id;
    }

    public function getCommentId(): ?int
    {
        return $this->comment_id;
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

    public function getHasRead(): ?int
    {
        return $this->has_read;
    }

    public function setHasRead($has_read): void
    {
        $this->has_read = $has_read;
    }

    public function setCreatedAt($created_at): void
    {
        $this->created_at = $created_at;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }


    public function getReportForm($params = null): array
    {
        return [
            "config" => [
                "method" => "POST",
                "action" => "",
                "class" => "formReport",
                "id" => "formReport",
                "submit" => "valider",
            ],

            "inputs" => [
                "email" => [
                    "value" =>  "",
                    "type" => "text",
                    "id" => "emailReport",
                    "class" => "emailReport",
                    "placeholder" => "Entrer votre email",
                    //"required" => "required",
                    "error" => "Veuillez entrer un email valide",
                ],

                "message" => [
                    "value" =>  "",
                    "type" => "textarea",
                    "id" => "messageReport",
                    "class" => "messageReport",
                    "placeholder" => "Votre message",
                    // "required" => "required",
                    "min" => "10",
                    "max" => "3000",
                    "error" => "Veuillez entrer un message",
                ]
            ]
        ];
    }
}