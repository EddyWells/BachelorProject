<div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Vos Projets actifs
                            <button type="button" class="btn btn-outline btn-warning pull-right" style="margin: 3px;">Individuel</button>
                            <button type="button" class="btn btn-outline btn-info pull-right" style="margin: 3px;">Membre</button>
                            <button type="button" class="btn btn-outline btn-danger pull-right" style="margin: 3px;">Chef</button>
                        </h1>
                    </div>
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Projets avec deadline</a>
                            </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse in">
                            <div class="panel-body">
                            <?php
                                $userClass = "Controllers\\UserController";
                                $userClass::loadActiveProjectsDl($_SESSION['id']);
                            ?>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">Projets sans deadline</a>
                            </h4>
                        </div>
                        <div id="collapseTwo" class="panel-collapse collapse">
                            <div class="panel-body">
                            <?= $userClass::loadActiveProjects($_SESSION['id']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
</div>