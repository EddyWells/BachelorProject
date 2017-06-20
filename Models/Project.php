<?php

namespace Models;

use Spot\EntityInterface as Entity;
use Spot\MapperInterface as Mapper;

class Project extends \Spot\Entity
{
    protected static $table = 'projects';

    public static function fields()
    {
      return [
        'id'            => ['type' => 'integer', 'primary' => true, 'autoincrement' => true],
        'title'         => ['type' => 'string', 'required' => true],
        'description'   => ['type' => 'text', 'required' => true],
        'creator_id'    => ['type' => 'integer', 'required' => true],
        'date_created'  => ['type' => 'datetime', 'value' => new \DateTime()],
        'date_end'      => ['type' => 'date', 'default' => null],
        'active'        => ['type' => 'boolean', 'default' => true]
      ];
    }

    public static function relations(Mapper $mapper, Entity $entity)
    {
        return [
            'creator' => $mapper->belongsTo($entity, 'Models\User', 'creator_id'),
            'users' => $mapper->hasManyThrough($entity, 'Models\User', 'Models\UserProject', 'user_id', 'project_id'),
            'tasks' => $mapper->hasMany($entity, 'Models\Task', 'project_id'),
            'comments' => $mapper->hasMany($entity, 'Models\Comment', 'project_id')
        ];

    }
}