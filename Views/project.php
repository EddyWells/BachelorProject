<div id="page-wrapper">
<?php
$projectClass = "Controllers\\ProjectController";

$get = filter_input_array(INPUT_GET);

$infos = $projectClass::loadProjectInfos($get['id']);
$admin = $projectClass::checkProjectAdmin($get['id'],$_SESSION['id']);
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
                            <i class="fa fa-tasks fa-fw"></i> Liste des tâches
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr class="text-center">
                                            <th>#</th>
                                            <th>Tâche</th>
                                            <th>Progression</th>
                                            <th>Membre Assigné</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?= $projectClass::loadProjectTasks($get['id']) ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
            </div>
            <?php
                if($admin){
            ?>
            <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bolt fa-fw"></i> Créer une nouvelle tâche
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                             <form action="index.php?c=task&t=newTask" method='POST' id="newTask">
                                <div class="form-group">
                                    <input type="hidden" name="pId" value="<?= $get['id'] ?>">
                                    <label for="tTitle" style="margin:10px;">Titre de la tâche<i style="color: red;">*</i></label>
                                    <input type="text" name="tTitle" class="form-control" required/>
                                    <label for="tDesc" style="margin:10px;">Description de la tâche<i style="color: red;">*</i></label>
                                    <br>
                                    <textarea class="form-control" rows="5" name="tDesc" required></textarea>
                                    <label for="tMember" style="margin:10px;">Membre assigné </label>
                                    <select name="tMember">
                                    <option selected="selected">Ne pas assigner</option>
                                    <?php
                                    foreach ($infos['members'] as $m) {
                                        echo  '<option>' . $m . '</option>';
                                    }
                                    ?>
                                    </select>
                                    <br>
                                    <div class="pull-right">
                                        <input type="submit" value="Créer la tâche" class="btn btn-default " /> 
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
            <?php } ?>
         </div>
         <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-users fa-fw"></i> Liste des membres
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <p class="text-danger">Chef de projet: <?= $infos['chef'] ?></p>
                            <?php if(count($infos['members']) > 1 ){
                                echo '<p class="text-primary">Membres: ';
                                foreach ($infos['members'] as $m) {
                                    if($m != $infos['chef']){
                                        echo  $m . ' | ';
                                    }
                                }
                                echo '</p>';
                                } ?>
                        </div>
                        <!-- /.panel-body -->
                        <?php
                            if($admin){
                        ?>
                        <div class="panel-footer">
                            <form class="form-inline" action="index.php?c=invitation&t=newInvitation" method="post">
                                <input id="btn-input" type="email" class="form-control input-sm" name="email" placeholder="E-mail"  style="width: 80%;" required />
                                <input type="hidden" name="idP" value="<?= $get['id'] ?>">
                                <button class="btn btn-warning btn-sm" id="btn-chat" type="submit">Inviter</button>
                            </form>
                        </div>
                        <?php
                            }
                        ?>
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
                               <?= $projectClass::loadProjectComments($get['id']); ?>
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