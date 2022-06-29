<?php

namespace App\models;


use App\core\sql;

class Input extends sql
{
    protected $id;
    protected $form_id;
    protected $js_id;
    protected $js_class;
    protected $placeholder;
    protected $name;
    protected $value;
    protected $label;
    protected $type;

    public function __construct()
    {
        parent::__construct();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setLabel($label): void
    {
        $this->label = $label;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }


    public function setValue($value): void
    {
        $this->value = $value;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }


    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }


    public function setPlaceholder($placeholder): void
    {
        $this->placeholder = $placeholder;
    }

    public function getPlaceholder(): ?string
    {
        return $this->placeholder;
    }

    public function setJsclass($js_class): void
    {
        $this->js_class = $js_class;
    }

    public function getJsclass(): ?string
    {
        return $this->js_class;
    }


    public function setJsId($js_id): void
    {
        $this->js_id = $js_id;
    }

    public function getJsId(): ?string
    {
        return $this->js_id;
    }


    public function setFormId($form_id): void
    {
        $this->form_id = $form_id;
    }

    public function getFormId(): ?string
    {
        return $this->form_id;
    }

    public function setType($type): void
    {
        $this->type = $type;
    }

    public function getType(): ?string
    {
        return $this->type;
    }
}