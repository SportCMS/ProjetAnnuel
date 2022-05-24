<?php

namespace App\models; 

use App\core\Sql;


class Categorie extends Sql
{
    protected $id;
    protected $name;
    protected $description;
   

    public function __construct()
    {
	parent::__construct();
    }

    public function getId():?int
    {
	return $this->id;
    }

    public function setName($name):void
    {
	$this->name = $name;
    }

    public function getName():?string
    {
	return $this->name;
    }

    public function setDescription($description):void
    {
	$this->description = $description;
    }

    public function getDescription():?string
    {
	return $this->description;
    }


}