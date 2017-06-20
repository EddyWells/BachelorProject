<?php

namespace Models;

use Spot\EntityInterface as Entity;
use Spot\MapperInterface as Mapper;


class Invitation extends \Spot\Entity
{
    protected static $table = 'invitations';
    
    public static function fields()
    {
        return [
            'id'        => ['type' => 'integer', 'primary' => true, 'autoincrement' => true],
            'sender_id'    => ['type' => 'integer', 'required' => true, 'unique' => 'sender_receiver_project'],
            'receiver_id'   => ['type' => 'integer', 'required' => true, 'unique' => 'sender_receiver_project'],
            'project_id'   => ['type' => 'integer', 'required' => true, 'unique' => 'sender_receiver_project']

        ];
    }

    public static function relations(Mapper $mapper, Entity $entity)
    {
        return [
            'sender' => $mapper->belongsTo($entity, 'Models\User', 'sender_id'),
            'receiver'  => $mapper->belongsTo($entity, 'Models\User', 'receiver_id'),
            'project' => $mapper->belongsTo($entity, 'Models\Project', 'project_id')
        ];
    }
}