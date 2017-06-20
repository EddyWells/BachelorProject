<?php

namespace Controllers;

use Models\Project;
use Models\UserProject;

class InvitationController 
{
  public function initDatabase()
    {
      $spot = spot()->mapper('Models\Invitation');
      $spot->migrate();
    }

 public function newInvitation()
    {
      $arg = filter_input_array(INPUT_POST);
      $project = $arg['idP'];
      $mail = $arg['email'];
      $sender = $_SESSION['id'];

      $userMapper = spot()->mapper('Models\User');
      $user = $userMapper->first(['email' => $mail]);

      $invMapper = spot()->mapper('Models\Invitation');
      $newInvitation = $invMapper->create([
        'sender_id'      => $sender,
        'receiver_id'     => $user->id,
        'project_id' => $project
      ]);

      header("Location: index.php?page=project&id=" . $project);
    }

  public function accept()
  {
    $arg = filter_input_array(INPUT_GET);
    $idInv = $arg['id'];

    $invMapper = spot()->mapper('Models\Invitation');
    $invitation = $invMapper->get($idInv);
    $idP = $invitation->project->id;

    $userProjectMapper = spot()->mapper('Models\UserProject');
    $newUserProject = $userProjectMapper->create([
      'user_id'      => $invitation->receiver->id,
      'project_id'   => $idP  
    ]);

    $invMapper->delete(['id' => $idInv]);

    header("Location: index.php?page=project&id=" . $idP);
  }

  public function refuse()
  {
    $arg = filter_input_array(INPUT_GET);
    $idInv = $arg['id'];

    $invMapper = spot()->mapper('Models\Invitation');
    $invMapper->delete(['id' => $idInv]);

    header("Location: index.php");
  }

}
