<?php

namespace Models;

use Spot\EntityInterface as Entity;
use Spot\MapperInterface as Mapper;

class Task extends \Spot\Entity
{
    protected static $table = 'tasks';

    public static function fields()
    {
      return [
        'id'            => ['type' => 'integer', 'primary' => true, 'autoincrement' => true],
        'title'         => ['type' => 'string', 'required' => true],
        'description'   => ['type' => 'text', 'required' => true],
        'user_id'       => ['type' => 'integer', 'default' => null],
        'project_id'    => ['type' => 'integer', 'required' => true],
        'progression'   => ['type' => 'integer', 'default' => 0],
        'complete'      => ['type' => 'boolean', 'default' => false]
      ];
    }

    public static function relations(Mapper $mapper, Entity $entity)
    {
        return [
            'user_assigned' => $mapper->belongsTo($entity, 'Models\User', 'user_id'),
            'project' => $mapper->belongsTo($entity, 'Models\Project', 'project_id')
        ];

    }
}