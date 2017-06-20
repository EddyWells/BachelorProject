<?php

namespace Models;

use Spot\EntityInterface as Entity;
use Spot\MapperInterface as Mapper;


class Comment extends \Spot\Entity
{
    protected static $table = 'comments';

    public static function fields()
    {
        return [
            'id'        => ['type' => 'integer', 'primary' => true, 'autoincrement' => true],
            'user_id'    => ['type' => 'integer', 'required' => true],
            'project_id'   => ['type' => 'integer', 'required' => true],
            'message' => ['type' => 'text', 'required' => true],
            'date_send' => ['type' => 'datetime', 'value' => new \DateTime()]

        ];
    }
    
    public static function relations(Mapper $mapper, Entity $entity)
    {
        return [
            'user' => $mapper->belongsTo($entity, 'Models\User', 'user_id'),
            'project' => $mapper->belongsTo($entity, 'Models\Project', 'project_id')
        ];
    }
}