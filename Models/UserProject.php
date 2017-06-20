<?php

namespace Models;

use Spot\EntityInterface as Entity;
use Spot\MapperInterface as Mapper;


class UserProject extends \Spot\Entity
{
    protected static $table = 'user_project';
    public static function fields()
    {
        return [
            'id'        => ['type' => 'integer', 'primary' => true, 'autoincrement' => true],
            'user_id'    => ['type' => 'integer', 'required' => true, 'unique' => 'user_project'],
            'project_id'   => ['type' => 'integer', 'required' => true, 'unique' => 'user_project']
        ];
    }
    public static function relations(Mapper $mapper, Entity $entity)
    {
        return [
            'user' => $mapper->belongsTo($entity, 'Models\User', 'user_id'),
            'project'  => $mapper->belongsTo($entity, 'Models\Project', 'project_id')
        ];
    }
}