<?php

namespace Controllers;

use Models\Project;
use Models\UserProject;

class ProjectController 
{

    public function initDatabase()
        {
          $spot = spot()->mapper('Models\Project');
          $spot->migrate();
        }

    public function newProject()
        {

          $arg = filter_input_array(INPUT_POST);
          $proj = $arg['pTitle'];
          $desc = $arg['pDesc'];
          if($arg['pDate'] != null){
            $date = $arg['pDate'];
            $date = new \DateTime($date);
          } else {
            $date = null;
          }

          $projectMapper = spot()->mapper('Models\Project');
          $newProject = $projectMapper->create([
            'title'      => $proj,
            'description'     => $desc,
            'creator_id' => $_SESSION['id'],
            'date_end' => $date,
            'active' => 1
          ]);

          $userProjectMapper = spot()->mapper('Models\UserProject');
          $newUserProject = $userProjectMapper->create([
            'user_id'      => $_SESSION['id'],
            'project_id'     => $newProject->id
          ]);

          header("Location: index.php?page=project&id=" . $newProject->id);
        }


  public static function loadProjectInfos($idProject)
        {

          $projectMapper = spot()->mapper('Models\Project');
          $project = $projectMapper->first(['id' => $idProject]);

          $date = $project->date_end;
          if($date != null){
            $date = $project->date_end->format('Y-m-d');
          }
          $listM = array();
          foreach ($project->users as $member) {
            if($member->id != $project->creator_id){
              array_push($listM, $member->name);
            } else {
              array_unshift($listM, $member->name);
            }
          }
          $infosP = array("title" => $project->title, "desc" => $project->description, "date" => $date, "chef" => $project->creator->name, "members" => $listM);
          
          return $infosP;
        }

  public static function loadProjectTasks($idProject){
      $projectMapper = spot()->mapper('Models\Project');
      $project = $projectMapper->first(['id' => $idProject]);

      $i = 1;
      foreach ($project->tasks as $task) {
        $prog = $task->progression;
        if ($prog == 0){
          $class = "danger";
        } elseif ($prog < 100) {
          $class = "warning";
        } else {
          $class = "success";
        }
        if($task->user_id != null){
          $assign = $task->user_assigned->name;
        } else {
          $assign = "@notAssigned";
        }
        echo '<tr class="'. $class . '">
                <td>' . $i . '</td>
                <td><a href="index.php?page=task&id=' . $task->id . '&p=' . $task->project->id .'" style="display: block; height: 100%; width: 100%; color: inherit; text-decoration:  none;">' . $task->title . '</a></td>
                <td>' . $prog .'%</td>
                <td>' . $assign .'</td>
              </tr>';
        $i++;
      }
    }

  public static function loadProjectComments($idProject){
      $projectMapper = spot()->mapper('Models\Project');
      $project = $projectMapper->first(['id' => $idProject]);

      $comments = $project->comments->order(['date_send' => 'DESC'])->limit(10);
      foreach ($comments as $comment) {
        echo '<li>
                <div class="chat-body clearfix">
                  <div class="header">
                    <strong class="primary-font">' . $comment->user->name . '</strong>
                      <small class="pull-right text-muted">
                        <i class="fa fa-clock-o fa-fw"></i>' . $comment->date_send->format("Y-m-d H:i:s") .
                      '</small>
                  </div>
                  <p>' . $comment->message . '</p>
                </div>
              </li>';
      }
  }

  public static function checkProjectAdmin($idProject,$idUser){
      $projectMapper = spot()->mapper('Models\Project');
      $project = $projectMapper->first(['id' => $idProject]);
      if($project->creator_id == $idUser){
        return true;
      } else {
        return false;
      }
  }
}