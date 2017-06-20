<?php

namespace Controllers;

use Models\Task;
use Models\UserProject;


class TaskController
{
  
  public function initDatabase()
      {
        $spot = spot()->mapper('Models\Task');
        $spot->migrate();
      }

  public function newTask()
      {

        $arg = filter_input_array(INPUT_POST);
        $task = $arg['tTitle'];
        $desc = $arg['tDesc'];
        $member = $arg['tMember'];
        $idProject = $arg['pId'];

        $userMapper = spot()->mapper('Models\User');
        $user = $userMapper->first(['name' => $member]);

        if($user){
          $idUser = $user->id;
        } else {
          $idUser= null;
        }

        $taskMapper = spot()->mapper('Models\Task');
        $newTask = $taskMapper->create([
          'title'      => $task,
          'description'     => $desc,
          'project_id' => $idProject,
          'user_id' => $idUser,
          'progression' => 0,
          'complete' => 0
        ]);

        header("Location: index.php?page=project&id=" . $idProject);
      }

  public static function loadTaskInfos($idTask)
      {

        $taskMapper = spot()->mapper('Models\Task');
        $task = $taskMapper->first(['id' => $idTask]);
        $pTitle = $task->project->title;
        $pId = $task->project->id;
        if ($task->user_id != null){
          $user = $task->user_assigned->name;
        } else {
          $user = "Non assignée";
        }
        $listM = array();
        foreach ($task->project->users as $member) {
          array_push($listM, $member->name);
        }

        $infosT = array("title" => $task->title, "desc" => $task->description, "user" => $user, "prog" => $task->progression, "members" => $listM, "pTitle" => $pTitle, "pId" => $pId);
          
        return $infosT;
      }
}