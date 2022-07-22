<?php

namespace App\models\factories;

use App\models\factories\ContentInterface;
use App\models\factories\ContentText;
use App\models\factories\ContentForm;
use App\models\User;

class BlockContentFactory
{
    public function create(string $modelName)
    {
        $className = 'App\models\factories\Content' . ucfirst($modelName);
        $entity = new $className;

        if (get_class($entity) != $className) {
            throw new \Exception('Invalid entity');
        }
        return $entity;
    }
}
