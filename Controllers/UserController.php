<?php

namespace Controllers;

use Models\User;
use Spot\Exception;


class UserController
{

  public function initDatabase()
      {
        $spot = spot()->mapper('Models\User');
        $spot->migrate();
      }
  
  public function inscription()
        {
          $arg = filter_input_array(INPUT_POST);
          $email = $arg['mailI'];
          $name = $arg['nameI'];
          $pw1 = $arg['pw1'];
          $pw2 = $arg['pw2'];

          if($pw1 === $pw2){
            try {
            $userMapper = spot()->mapper('Models\User');
            $myNewUser = $userMapper->create([
              'name'      => $name,
              'email'     => $email,
              'password'  => $pw
            ]);
            } catch (Exception $e){
              header("Location: views/login.php");
            }

            $_SESSION['id'] = $myNewUser->id;
            $_SESSION['name'] = $myNewUser->name;
            header("Location: index.php");
          } else{
            header("Location: views/login.php");
          }
        }



  public function login(){
      $arg = filter_input_array(INPUT_POST);
      $email = $arg['email'];
      $pw = $arg['password'];

      $userMapper = spot()->mapper('Models\User');
      $user = $userMapper->first(['email' => $email]);
      if ($user){
        if($user->password === $pw){
          $_SESSION['id'] = $user->id;
          $_SESSION['name'] = $user->name;
          }
        }
        header("Location: index.php");   
    }

   public function logout(){
      session_destroy();
      header('Location: views/login.php');
    }

  public static function countActivity($idUser){
      $userMapper = spot()->mapper('Models\User');
      $user = $userMapper->get($idUser);
      $comp = count($user->tasks->where(['progression' => 0]));
      $user = $userMapper->get($idUser);
      $comp2 = count($user->tasks->where(['complete' => false]));
      $count = array(count($user->projects->where(['active' => 1])), $comp , $comp2);
      return $count;
    }

  public static function loadRecentProjects($idUser){
      $userMapper = spot()->mapper('Models\User');
      $user = $userMapper->get($idUser);
      $projects = $user->projects
            ->order(['date_created' => 'ASC'])
            ->limit(4);
      foreach ($projects as $project) {
        if(count($project->users) > 1){
          $class = "fa fa-users";
        } else {
          $class = "fa fa-user";
        }
        echo '<div class="col-md-3 text-center">
                <a href="index.php?page=project&id=' . $project->id . '">
                  <button type="button" class="btn btn-primary btn-circle btn-xl"><i class="' . $class . '"></i>
                  </button>
                </a>
                <h4>' . $project->title . '</h4>
              </div>';
      }
    }

  public static function loadActiveProjectsDl($idUser){
          $userMapper = spot()->mapper('Models\User');
          $user = $userMapper->get($idUser);
          $projects = $user->projects->where(['active' => 1, 'date_end !=' => null])->order(['date_end' => 'ASC']);
          
          foreach ($projects as $project) {
            if($project->creator_id == $idUser){
              $panel = 'panel panel-danger';
            } else {
              $panel = 'panel panel-info';
            }
            if(count($project->users)>1){
              $fa = 'fa fa-users';
            } else {
              $fa = 'fa fa-user';
              $panel = 'panel panel-warning';
            }
            $date = $project->date_end;
            if($date != null){
              $date = '<div class="pull-right">' . $project->date_end->format('Y-m-d') . '</div>';
            }
            $nbT = count($project->tasks);
            echo  '<div class="col-lg-4 clearfix">
                    <a style="color: inherit; text-decoration : none;" href="index.php?page=project&id=' . $project->id . '">
                        <div class="' . $panel . '">
                            <div class="panel-heading" style="text-size:24px;"><h4 ><div class="' . $fa . '"></div> '
                                  . $project->title . $date . 
                            '</h4></div>
                            <div class="panel-body">
                                <p>' . $project->description . '</p>
                            </div>
                            <div class="panel-footer">'
                              . $nbT . ' tâches en cours
                              <div class="pull-right">
                                
                              </div>
                            </div>
                        </div></a>
                    </div>';
          }
    }

    public static function loadActiveProjects($idUser){
          $userMapper = spot()->mapper('Models\User');
          $user = $userMapper->get($idUser);
          $projects = $user->projects->where(['active' => 1, 'date_end' => null]);
          
          foreach ($projects as $project) {
            if($project->creator_id == $idUser){
              $panel = 'panel panel-danger';
            } else {
              $panel = 'panel panel-info';
            }
            if(count($project->users)>1){
              $fa = 'fa fa-users';
            } else {
              $fa = 'fa fa-user';
              $panel = 'panel panel-warning';
            }
            $date = $project->date_end;
            if($date != null){
              $date = '<div class="pull-right">' . $project->date_end->format('Y-m-d') . '</div>';
            }
            $nbT = count($project->tasks);
            echo  '<div class="col-lg-4 clearfix">
                    <a style="color: inherit; text-decoration : none;" href="index.php?page=project&id=' . $project->id . '">
                        <div class="' . $panel . '">
                            <div class="panel-heading" style="text-size:24px;"><h4 ><div class="' . $fa . '"></div> '
                                  . $project->title . $date . 
                            '</h4></div>
                            <div class="panel-body">
                                <p>' . $project->description . '</p>
                            </div>
                            <div class="panel-footer">'
                              . $nbT . ' tâches en cours
                              <div class="pull-right">
                                
                              </div>
                            </div>
                        </div></a>
                    </div>';
          }
    }


  public static function loadActiveTasks($idUser){
          $userMapper = spot()->mapper('Models\User');
          $user = $userMapper->get($idUser);
          $i = 1;
          foreach ($user->tasks as $task) {
            $p = $task->progression;
            if($p === 0){
              $p = $p . 0;
            }
            echo '<tr>
                    <td class="col-md-1">' . $i . '</td>
                    <td><a href="index.php?page=task&id=' . $task->id . '&p=' . $task->project->id .'" style="display: block; height: 100%; width: 100%; color: inherit; text-decoration:  none;">' . $task->title . '</a></td>
                    <td><a href="index.php?page=project&id=' . $task->project->id .'" style="display: block; height: 100%; width: 100%; color: inherit; text-decoration:  none;">' . $task->project->title . '</a></td>
                    <td>
                    <span class="pull-left text-muted" style="margin-right: 5px;">'. $p .'% Complete </span>
                      <div class="progress" style="margin: 0;">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="'. $p .'" aria-valuemin="0" aria-valuemax="100" style="width:'. $p .'%">
                        </div>
                      </div>
                    </td>
                  </tr>';
            $i++;        
          }
    }

  public static function invitationsByUser($idUser){
          $userMapper = spot()->mapper('Models\User');
          $user = $userMapper->get($idUser);
          foreach ($user->invitations as $invitation) {
            echo '<li class="right clearfix">
                    <span class="pull-right">
                      <a href="index.php?c=invitation&t=accept&id=' . $invitation->id . '"><button type="button" class="btn btn-success btn-circle"><i class="fa fa-check"></i></button></a>
                      <a href="index.php?c=invitation&t=refuse&id=' . $invitation->id . '"><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-times"></i></button></a>
                    </span>
                    <div class="chat-body clearfix">
                      <p><strong>' . $invitation->sender->name . '</strong> vous invite à participer au projet <strong>' . $invitation->project->title . '</strong></p>
                    </div>
                  </li>';
          }
    }
}