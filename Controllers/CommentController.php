<?php

namespace Controllers;

use Models\Project;
use Models\UserProject;

class CommentController
{
  public function initDatabase()
      {
        $spot = spot()->mapper('Models\Comment');
        $spot->migrate();
      }

   public function newComment()
      {

        $arg = filter_input_array(INPUT_POST);
        $message = $arg['comment'];
        $idProject = $arg['idP'];
        $user = $_SESSION['id'];

        $commentMapper = spot()->mapper('Models\Comment');
        $newComment = $commentMapper->create([
          'user_id'      => $user,
          'project_id'     => $idProject,
          'message' => $message
        ]);

        header("Location: index.php?page=project&id=" . $idProject);
      }
}