<div id="page-wrapper">
<?php
$taskClass = "Controllers\\TaskController";
$projectClass = "Controllers\\ProjectController";

$get = filter_input_array(INPUT_GET);

$infos = $taskClass::loadTaskInfos($get['id']);
$admin = $projectClass::checkProjectAdmin($get['p'],$_SESSION['id']);

?>
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?= $infos['title'] ?></h1>
         </div>
    </div>
    <div class="row">
        <div class="col-lg-8">
            <div class="well">
                <h4>Description</h4>
                <p><?= $infos['desc'] ?></p>
            </div>
            <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-tasks fa-fw"></i> Progression de la tâche
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div>
                                <span class="pull-left text-muted" style="margin-right: 10px;"><?= $infos['prog'] ?>% Complete</span>
                                    <div class="progress progress-striped active">
                                        <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="<?= $infos['prog'] ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $infos['prog'] ?>%">
                                            <span class="sr-only"><?= $infos['prog'] ?>% Complete (danger)</span>
                                        </div>
                                    </div>
                            </div>
                            <hr>
                            <form class="form-group" action="index.php?c=task&t=newTask" method='POST' id="newTask">
                                <label>Changer la progression: </label>
                                <input name="progression" type="number" min="0" max="100" step="10" value="<?= $infos['prog'] ?>">
                                <br>
                                <button type="button" class="btn btn-outline btn-primary">Changer</button>
                            </form>
                        </div>
                        <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
                     <div>
            <a href="index.php?page=project&id=<?= $infos['pId'] ?>">
                <button type="button" class="btn btn-primary btn-lg" style="margin: 3px;"><i class="fa fa-step-backward"> <?= $infos['pTitle'] ?></i>
                </button>
            </a>
        </div>
         </div>

         <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-user fa-fw"></i> Membre assigné
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <p class="text-primary"><?= $infos['user'] ?></p>
                        </div>
                        <!-- /.panel-body -->
                        <?php
                            if ($admin){
                        ?>
                        <div class="panel-footer">
                            <form class="form-inline" action="index.php?c=invitation&t=newInvitation" method="post">
                                <select name="tMember" style="width: 75%;">
                                    <option selected="selected">Ne pas assigner</option>
                                    <?php
                                    foreach ($infos['members'] as $m) {
                                        echo  '<option>' . $m . '</option>';
                                    }
                                    ?>
                                    </select>
                                <input type="hidden" name="idP" value="<?= $get['id'] ?>">
                                <button class="btn btn-warning btn-sm" id="btn-chat" type="submit">Assigner</button>
                            </form>
                        </div>
                        <?php } ?>
                    </div>
                    <!-- /.panel -->
                    <div class="chat-panel panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-comments fa-fw"></i> Chat du projet
                            <div class="btn-group pull-right">
                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-chevron-down"></i>
                                </button>
                                <ul class="dropdown-menu slidedown">
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-refresh fa-fw"></i> Refresh
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-check-circle fa-fw"></i> Available
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-times fa-fw"></i> Busy
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-clock-o fa-fw"></i> Away
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-sign-out fa-fw"></i> Sign Out
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <ul class="chat">
                            <?= $projectClass::loadProjectComments($get['p']); ?>
                            </ul>
                        </div>
                        <!-- /.panel-body -->
                        <div class="panel-footer">
                            <form class="form-inline" action="index.php?c=comment&t=newComment" method="post">
                                <input id="btn-input" type="text" class="form-control input-sm" name="comment" placeholder="Entrez votre message ici..."  style="width: 80%;" required />
                                <input type="hidden" name="idP" value="<?= $get['id'] ?>">
                                <button class="btn btn-warning btn-sm" id="btn-chat" type="submit">Envoyer</button>
                            </form>
                        </div>
                        <!-- /.panel-footer -->
                    </div>
                    <!-- /.panel .chat-panel -->
                </div>
                <!-- /.col-lg-4 -->
    </div>
</div>