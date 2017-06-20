<?php

namespace Models;

use Spot\EntityInterface as Entity;
use Spot\MapperInterface as Mapper;

class User extends \Spot\Entity
{
    protected static $table = 'users';

    public static function fields()
    {
      return [
        'id'        => ['type' => 'integer', 'primary' => true, 'autoincrement' => true],
        'name'      => ['type' => 'string', 'required' => true, 'unique' => true],
        'email'     => ['type' => 'string', 'required' => true, 'unique' => true],
        'password'  => ['type' => 'string', 'required' => true],
        'admin'     => ['type' => 'boolean', 'default' => false, 'value' => false]
      ];
    }

    public static function relations(Mapper $mapper, Entity $entity)
    {
        return [
            'projects' => $mapper->hasManyThrough($entity, 'Models\Project', 'Models\UserProject', 'project_id', 'user_id'),
            'tasks' => $mapper->hasMany($entity, 'Models\Task', 'user_id'),
            'invitations' => $mapper->hasMany($entity, 'Models\Invitation', 'receiver_id')
        ];
    }
}