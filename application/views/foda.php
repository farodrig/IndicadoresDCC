<!doctype html>
<html class="fixed sidebar-left-collapsed">
<head>
    <?php
    //Para usar head.php debe ser dentro del tag head y debe haberse creado una variable $title.
    include 'partials/head.php'; ?>

    <link rel="stylesheet" href="<?php echo base_url();?>chosen/chosen.css">
    <link href="<?php echo base_url();?>assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" rel="stylesheet">

    <style type="text/css">
        .title{
            font-size: 1.5em;
        }

        .margin-top{
            margin-top: 2%;
        }

        .margin-bottom{
            margin-bottom: 5%;
        }

        form label, td label, .description{
            font-size: 1.2em;
        }

        .no-validated{
            color: red;
        }

        .validated{
            color: green;
        }

        .table > tbody > tr > td {
            vertical-align: middle;
        }

        div.table-responsive div.row {
            margin-left: 0px;
            margin-right: 0px;
        }

        .tooltip.top .tooltip-inner {
            background-color:white;
            color: darkgrey;
        }
        .icons {
            padding: 0px;
        }
        .chosen-container
        {
            width: 100% !important;
        }
    </style>

    <script type="text/javascript">
        var years = <?php echo json_encode($years);?>;
        var fodas = <?php echo json_encode($fodas); ?>;
        var items = <?php echo json_encode($items); ?>;
        var strategies = <?php echo json_encode($strategies); ?>;
        var goals = <?php echo json_encode($goals); ?>;
        var actions = <?php echo json_encode($actions); ?>;
        var types = <?php echo json_encode($types); ?>;
        var priorities = <?php echo json_encode($priorities); ?>;
        var users = <?php echo json_encode($users); ?>;
        var estados = <?php echo json_encode($status); ?>;
        var permits = <?php echo json_encode($permits); ?>;
    </script>
</head>
<body>
<section class="body">

    <?php
    //Para usar header_tmpl.php se debe haber creado la variable $name y $role. Se pueden crear tanto aqui como en el controlador.
    include 'partials/header_tmpl.php'; ?>

    <div class="inner-wrapper">
        <!-- start: sidebar -->
        <?php
        $navData=[];
        include 'partials/navigation.php';
        ?>
        <!-- end: sidebar -->

        <section role="main" class="content-body">
            <header class="page-header">
                <h2>FODAs de la Organización</h2>

                <div class="right-wrapper pull-right">
                    <ol class="breadcrumbs">
                        <li>
                            <a href="<?php echo base_url();?>inicio">
                                <i class="fa fa-home"></i>
                            </a>
                        </li>
                        <li><span>Ver</span></li>
                        <li><span>FODAs</span></li>
                    </ol>

                    <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>

                </div>
            </header>

            <!-- start: page -->
            <section class="panel panel-transparent">
                <div class="form-group">
                    <div class="col-md-2 text-center">
                        <label class="control-label title">Año:</label>
                    </div>
                    <div class="col-md-2">
                        <select id="year" name="year" data-placeholder="Seleccione año..." class="chosen-select" style="width:200px;" onchange ="validate_year('year')">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="col-md-4 col-md-offset-2">
                        <div class="col-md-6">
                            <label class="control-label title">Organización:</label>
                        </div>
                        <div class="col-md-6">
                            <select id="org" name="org" data-placeholder="Seleccione area o sub-area..." class="chosen-select">
                                <option value=""></option>
                                <?php
                                foreach ($departments as $dpto){
                                ?>
                                <optgroup label="<?php echo(ucwords($dpto['type']['name'])); ?>">
                                    <?php
                                    foreach ($dpto['areas'] as $area) {
                                        ?>
                                        <option value="<?php echo($area['area']->getId())?>"><?php echo(ucwords($area['area']->getName()));?></option>
                                        <?php
                                        foreach ($area['unidades'] as $unidad) {
                                            ?>
                                            <option value="<?php echo($unidad->getId())?>" style="padding-left:30px"><?php echo(ucwords($unidad->getName()));?></option>
                                        <?php
                                        }
                                    }?>
                                </optgroup>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="panel-body row">
                        <div class="row margin-bottom">
                            <div class="foda">
                                <h4 class="text-center">FODA
                                    <small id="foda_valid"></small>
                                    <a class="btn editFoda hidden" data-toggle="modal" data-target="#editFodaModal" ><i class="fa fa-pencil"></i></a>
                                    <button type="button" id="validateFoda" onclick="validateElement('foda', null)" class="btn btn-success pull-right" style="display: none;">Validar</button>
                                </h4>
                                <div class="form-group">
                                    <label class="col-sm-2">Comentario:</label>
                                    <label class="col-sm-10 text-left" id="foda_comment"></label>
                                </div>
                            </div>
                            <div id ="itemData">
                                <div id="itemSection" class="panel-body col-md-12">
                                    <div id="itemTable_wrapper" class="dataTables_wrapper no-footer">
                                        <div id="itemButtons" class="row" style="display: none;">
                                            <div class="col-md-12">
                                                <button id="addItem" data-toggle="modal" data-target="#editItemModal" data-id="-1" data-title="Añadir Item al FODA" class="pull-left" type="button"><i class="fa fa-plus"></i> Añadir Item</button>
                                                <button id="expand-collapse-items" class="expanded pull-right" type="button">Expandir Todos</button>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table id="itemTable" style="display: none;" class="table table-bordered table-striped mb-none dataTable no-footer text-center" role="grid" aria-describedby="itemTable_info">
                                                <thead>
                                                <tr>
                                                    <th class="sorting_disabled text-center"></th>
                                                    <th class="sorting text-center">ID</th>
                                                    <th class="sorting text-center" aria-controls="itemTable">Item</th>
                                                    <th class="sorting text-center" aria-controls="itemTable">Tipo</th>
                                                    <th class="sorting text-center" aria-controls="itemTable">Prioridad</th>
                                                    <th class="sorting_disabled text-center" aria-controls="itemTable">Editar</th>
                                                </tr>
                                                </thead>
                                                <tbody id="itemTableContent">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row margin-top">
                            <div class="strategy">
                                <h4 class="text-center">Plan Estratégico
                                    <small id="strategy_valid"></small>
                                    <a class="btn editStrategy hidden" data-toggle="modal" data-target="#editStrategyModal" ><i class="fa fa-pencil"></i></a>
                                    <button type="button" id="validateStrategy" onclick="validateElement('strategy', null)" class="btn btn-success pull-right" style="display: none;">Validar</button>
                                </h4>
                                <div class="strategy-data">
                                    <div class="form-group">
                                        <div class="col-sm-10" id="strategy_descrip"></div>
                                    </div>
                                    <div class="form-group margin-top">
                                        <div class="col-sm-2">Comentario:</div>
                                        <div class="col-sm-10">
                                            <p id="strategy_comment"></p>
                                        </div>
                                    </div>
                                    <div class="form-group margin-top">
                                        <div class="col-sm-2">Fecha de Término:</div>
                                        <div class="col-sm-1" id="strategy_deadline"></div>
                                        <div class="col-sm-2 text-right">Estado Actual:</div>
                                        <div class="col-sm-1" id="strategy_status"></div>
                                    </div>
                                    <div class="form-group margin-top">
                                        <div class="col-sm-2">Colaboradores:</div>
                                        <div class="col-sm-10" id="strategy_collaborators"></div>
                                    </div>
                                </div>
                            </div>
                            <div id ="goalData" class="margin-top">
                                <div id="goalSection" class="col-md-12">
                                    <div id="goalTable_wrapper" class="dataTables_wrapper no-footer">
                                        <div id="goalButtons" class="row" style="display: none">
                                            <div class="col-md-12">
                                                <button id="addGoal" data-toggle="modal" data-target="#editGoalModal" data-id="-1" data-title="Añadir Objetivo" class="pull-left" type="button"><i class="fa fa-plus"></i> Añadir Objetivo</button>
                                                <button id="expand-collapse-goals" class="expanded pull-right" type="button">Expandir Todos</button>
                                            </div>
                                        </div>
                                        <div class="table-responsive" >
                                            <table id="goalTable"  style="display: none;" class="table table-bordered table-striped mb-none dataTable no-footer text-center" role="grid" aria-describedby="goalTable_info">
                                                <thead>
                                                <tr>
                                                    <th class="sorting_disabled text-center"></th>
                                                    <th class="sorting text-center">ID</th>
                                                    <th class="sorting text-center" aria-controls="goalTable">Objetivo</th>
                                                    <th class="sorting text-center" aria-controls="goalTable">Encargado</th>
                                                    <th class="sorting text-center" aria-controls="goalTable">Término</th>
                                                    <th class="sorting text-center" aria-controls="goalTable">Estado</th>
                                                    <th class="sorting_disabled text-center" aria-controls="goalTable">Editar</th>
                                                </tr>
                                                </thead>
                                                <tbody id="goalTableContent">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </section>
    </div>
</section>

<div class="modal fade" tabindex="-1" role="dialog" id="editFodaModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Editar FODA</h4>
            </div>
            <div class="modal-body">
                <form id="fodaForm" class="form-horizontal">
                    <input type="hidden" name="foda">
                    <div class="form-group">
                        <label for="fodaComment" class="col-sm-2 control-label">Comentario:</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="fodaComment" name="comment"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                <button type="button" onclick="editFoda()" class="btn btn-success">Guardar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="editStrategyModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Editar Plan Estratégico</h4>
            </div>
            <div class="modal-body">
                <form id="strategyForm" class="form-horizontal">
                    <input type="hidden" name="strategy">
                    <div class="form-group">
                        <label for="strategyStatus" class="col-sm-3 control-label">Estado Actual:</label>
                        <div class="col-sm-9">
                            <select class="form-control chosen-select no-search" id="strategyStatus" name="status">
                                <?php
                                foreach ($status as $id=>$name){
                                    echo('<option value="'.$id.'">'.$name.'</option>');
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="strategyDescription" class="col-sm-3 control-label">Descripción:</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="strategyDescription" name="description" required></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="strategyComment" class="col-sm-3 control-label">Comentario:</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="strategyComment" name="comment"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="strategyDeadline" class="col-sm-3 control-label">Fecha límite:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control datepicker" id="strategyDeadline" data-provide="datepicker" name="deadline">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="strategyCollaborators" class="col-sm-3 control-label">Colaboradores:</label>
                        <div class="col-sm-9">
                            <select class="form-control chosen-select" multiple="" id="strategyCollaborators" name="collaborators[]" data-placeholder="Añada a los colaboradores del Plan Estratégico">
                                <option value=""></option>
                                <?php
                                foreach ($users as $id => $user){
                                    echo('<option value="'.$id.'">'.$user->name.'</option>');
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                <button type="button" onclick="editStrategy()" class="btn btn-success">Guardar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="editItemModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="itemModalTitle"></h4>
            </div>
            <div class="modal-body">
                <form id="itemForm" class="form-horizontal">
                    <input type="hidden" name="item">
                    <div class="form-group">
                        <label for="itemTitle" class="col-sm-3 control-label">Título:</label>
                        <div class="col-sm-9">
                            <input type="text" maxlength="250" class="form-control" id="itemTitle" name="title" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="itemType" class="col-sm-3 control-label">Tipo de Item:</label>
                        <div class="col-sm-9">
                            <select class="form-control chosen-select no-search" id="itemType" name="type">
                                <?php
                                foreach ($types as $type){
                                    echo('<option value="'.$type->id.'">'.$type->name.'</option>');
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="itemPriority" class="col-sm-3 control-label">Prioridad:</label>
                        <div class="col-sm-9">
                            <select class="form-control chosen-select no-search" id="itemPriority" name="priority">
                                <?php
                                foreach ($priorities as $priority){
                                    echo('<option value="'.$priority->id.'">'.$priority->name.'</option>');
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="itemDescription" class="col-sm-3 control-label">Descripción</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="itemDescription" name="description"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="itemGoals" class="col-sm-3 control-label">Objetivos:</label>
                        <div class="col-sm-9">
                            <select class="form-control chosen-select" multiple="" id="itemGoals" name="goals[]" data-placeholder="Añada los objetivos asociados al item del FODA.">
                                <option value=""></option>
                                <?php
                                foreach ($goals as $org){
                                    foreach ($org as $year){
                                        foreach ($year as $goal){
                                            echo('<option value="'.$goal->id.'"> O'.$goal->id." - ".$goal->title.'</option>');
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                <button type="button" onclick="editItem()" class="btn btn-success">Guardar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="editGoalModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="goalModalTitle"></h4>
            </div>
            <div class="modal-body">
                <form id="goalForm" class="form-horizontal">
                    <input type="hidden" id="modalGoalId" name="goal">
                    <div class="form-group">
                        <label for="goalTitle" class="col-sm-3 control-label">Título:</label>
                        <div class="col-sm-9">
                            <input type="text" maxlength="250" class="form-control" id="goalTitle" name="title" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="goalStatus" class="col-sm-3 control-label">Estado Actual:</label>
                        <div class="col-sm-9">
                            <select class="form-control chosen-select no-search" id="goalStatus" name="status">
                                <?php
                                foreach ($status as $id=>$name){
                                    echo('<option value="'.$id.'">'.$name.'</option>');
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="goalUser" class="col-sm-3 control-label">Encargado:</label>
                        <div class="col-sm-9">
                            <select class="form-control chosen-select" id="goalUser" name="goalUser" data-placeholder="Añada al encagado del objetivo" required>
                                <option value=""></option>
                                <?php
                                foreach ($users as $id => $user){
                                    echo('<option value="'.$id.'">'.$user->name.'</option>');
                                }
                                ?>
                            </select>
                            <label id="errorGoalUser" style="display: none" class="error" for="goalUser">This field is required.</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="goalDescription" class="col-sm-3 control-label">Descripción:</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="goalDescription" name="description"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="goalComment" class="col-sm-3 control-label">Comentario:</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="goalComment" name="comment"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="goalDeadline" class="col-sm-3 control-label">Fecha límite:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control datepicker" id="goalDeadline" data-provide="datepicker" name="deadline">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="goalItems" class="col-sm-3 control-label">Items del FODA:</label>
                        <div class="col-sm-9">
                            <select class="form-control chosen-select" multiple="" id="goalItems" name="items[]" data-placeholder="Añada los items del FODA asociados al objetivo.">
                                <option value=""></option>
                                <?php
                                foreach ($items as $org){
                                    foreach ($org as $year){
                                        foreach ($year as $item){
                                            echo('<option value="'.$item->id.'"> I'.$item->id." - ".$item->title.'</option>');
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                <button type="button" onclick="editGoal()" class="btn btn-success">Guardar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="editActionModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="actionModalTitle"></h4>
            </div>
            <div class="modal-body">
                <form id="actionForm" class="form-horizontal">
                    <input type="hidden" name="goal">
                    <input type="hidden" name="action">
                    <div class="form-group">
                        <label for="actionTitle" class="col-sm-3 control-label">Título:</label>
                        <div class="col-sm-9">
                            <input type="text" maxlength="250" class="form-control" id="actionTitle" name="title" required >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="actionStatus" class="col-sm-3 control-label">Estado Actual:</label>
                        <div class="col-sm-9">
                            <select class="form-control chosen-select no-search" id="actionStatus" name="status">
                                <?php
                                foreach ($status as $id=>$name){
                                    echo('<option value="'.$id.'">'.$name.'</option>');
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="actionUser" class="col-sm-3 control-label">Encargado:</label>
                        <div class="col-sm-9">
                            <select class="form-control chosen-select" id="actionUser" name="actionUser" data-placeholder="Añada al encagado de la acción" required>
                                <option value=""></option>
                                <?php
                                foreach ($users as $id => $user){
                                    echo('<option value="'.$id.'">'.$user->name.'</option>');
                                }
                                ?>
                            </select>
                            <label id="errorActionUser" style="display: none" class="error" for="actionUser">This field is required.</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="actionCurrent" class="col-sm-3 control-label">Resultado Actual:</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="actionCurrent" name="current"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="actionExpected" class="col-sm-3 control-label">Resultado Esperado:</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="actionExpected" name="expected" required></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                <button type="button" onclick="editAction()" class="btn btn-success">Guardar</button>
            </div>
        </div>
    </div>
</div>

<?php include 'partials/footer.php'; ?>

<script src="<?php echo base_url();?>assets/vendor/select2/select2.js"></script>
<script src="<?php echo base_url();?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.js"></script>
<script src="<?php echo base_url();?>assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
<script src="<?php echo base_url();?>chosen/chosen.jquery.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/vendor/jquery-validation/jquery.validate.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/vendor/jquery-validation/additional-methods.js" type="text/javascript"></script>

<script type="text/javascript">

    $(".datepicker").datepicker({
        format: "dd-mm-yyyy",
        language: "es",
    });
    $('.no-search').chosen({"disable_search": true});
    $('.datepicker').datepicker().on('show.bs.modal', function(event) {
        event.stopPropagation();
    });



    $('#editFodaModal').on('show.bs.modal', function (e) {
        year = $('#year').val();
        org = $('#org').val();
        if (fodas[org] === undefined || fodas[org][year] === undefined){
            comment = "";
            id = -1;
        }
        else {
            comment = fodas[org][year].comment;
            id = fodas[org][year].id;
        }
        $('input[name=foda]').val(id);
        $('#fodaComment').val(comment);
    });

    $('#editStrategyModal').on('show.bs.modal', function (e) {
        year = $('#year').val();
        org = $('#org').val();
        var stra = -1;
        if (strategies[org] === undefined || strategies[org][year] === undefined){
            descrip = "";
            comment = "";
            status = "";
            collaborators = [];
        }
        else {
            strategy = strategies[org][year]['strategy'];
            descrip = strategy.description;
            comment = strategy.comment;
            status = strategy.status;
            collaborators = strategies[org][year]['collaborators'];
            stra = strategy.id;
        }
        for(id in users){
            option = $('#strategyCollaborators option[value="' + id + '"]');
            if(collaborators[id]=== undefined)
                option.prop('selected', false);
            else
                option.prop('selected', true);
        }
        $('input[name=strategy]').val(stra);
        $('#strategyCollaborators').trigger('chosen:updated');
        $('#strategyStatus option:contains("' + status + '")').prop('selected', true);
        $('#strategyStatus').trigger('chosen:updated');
        $('#strategyDescription').val(descrip);
        $('#strategyComment').val(comment);
    });

    $('#editStrategyModal').on('shown.bs.modal', function (e) {
        year = $('#year').val();
        org = $('#org').val();
        if (strategies[org] === undefined || strategies[org][year] === undefined){
            $('#strategyDeadline').datepicker('update', new Date());
            return;
        }
        deadline = strategies[org][year].strategy.deadline.split('-');
        $('#strategyDeadline').datepicker('update', new Date(deadline[2], deadline[1] - 1, deadline[0]));
    });

    $('#editGoalModal').on('show.bs.modal', function (e) {
        year = $('#year').val();
        org = $('#org').val();
        id = $(e.relatedTarget).data('id');
        titulo = $(e.relatedTarget).data('title');
        if(e.relatedTarget===undefined){
            id = $('#modalGoalId').val();
            titulo = "Editar Objetivo";
        }
        $('#goalModalTitle').html(titulo);
        $('input[name=goal]').val(id);
        if (goals[org] === undefined || goals[org][year] === undefined || goals[org][year][id] === undefined){
            descrip = "";
            comment = "";
            status = "";
            user = "";
            title="";
            elementos = [];
        }
        else {
            goal = goals[org][year][id];
            descrip = goal.description;
            comment = goal.comment;
            status = goal.status;
            user = goal.userInCharge;
            title = goal.title;
            elementos = goal.items;
        }
        $("#goalItems option").show();
        for(orgId in items){
            for (yearItem in items[orgId]){
                for(id in items[orgId][yearItem]){
                    if (orgId!=org || yearItem!=year){
                        $('#goalItems option[value="' + items[orgId][yearItem][id].id + '"]').hide().prop('selected', false);
                        continue;
                    }
                    option = $('#goalItems option[value="' + items[orgId][yearItem][id].id + '"]');
                    option.prop('selected', elementos.indexOf(items[orgId][yearItem][id].id) != -1);
                }
            }
        }
        $('#errorGoalUser').hide();
        $('#goalItems').trigger('chosen:updated');
        $('#goalUser option[value="' + user + '"]').prop('selected', true);
        $('#goalUser').trigger('chosen:updated');
        $('#goalStatus option:contains("' + status + '")').prop('selected', true);
        $('#goalStatus').trigger('chosen:updated');
        $('#goalTitle').val(title);
        $('#goalDescription').val(descrip);
        $('#goalComment').val(comment);
    });

    $('#editGoalModal').on('shown.bs.modal', function (e) {
        year = $('#year').val();
        org = $('#org').val();
        id = $(e.relatedTarget).data('id');
        if (goals[org] === undefined || goals[org][year] === undefined || goals[org][year][id] === undefined){
            $('#goalDeadline').datepicker('update', new Date());
            return;
        }
        deadline = goals[org][year][id].deadline.split('-');
        $('#goalDeadline').datepicker('update', new Date(deadline[2], deadline[1] - 1, deadline[0]));
    });

    $('#editItemModal').on('show.bs.modal', function (e) {
        year = $('#year').val();
        org = $('#org').val();
        id = $(e.relatedTarget).data('id');
        titulo = $(e.relatedTarget).data('title');
        $('#itemModalTitle').html(titulo);
        $('input[name=item]').val(id);
        item = getItemById(id);
        if (item==""){
            descrip = "";
            title = "";
            type = "";
            priority = "";
            objetivos = [];
        }
        else{
            descrip = item.description;
            title = item.title;
            type = item.type;
            priority = item.priority;
            objetivos = item.goals;
        }
        $("#itemGoals option").show();
        for(orgId in goals){
            for (yearGoal in goals[orgId]){
                for(id in goals[orgId][yearGoal]){
                    if (orgId!=org || yearGoal!=year){
                        $('#itemGoals option[value="' + id + '"]').hide().prop('selected', false);
                        continue;
                    }
                    option = $('#itemGoals option[value="' + id + '"]');
                    option.prop('selected', objetivos.indexOf(id) != -1);
                }
            }
        }
        $('#itemGoals').trigger('chosen:updated');
        $('#itemType option[value="' + type + '"]').prop('selected', true);
        $('#itemType').trigger('chosen:updated');
        $('#itemPriority option[value="' + priority + '"]').prop('selected', true);
        $('#itemPriority').trigger('chosen:updated');
        $('#itemDescription').val(descrip);
        $('#itemTitle').val(title);
    });

    $('#editActionModal').on('show.bs.modal', function (e) {
        year = $('#year').val();
        org = $('#org').val();
        id = $(e.relatedTarget).data('id');
        goal = $(e.relatedTarget).data('goal');
        titulo = $(e.relatedTarget).data('title');
        $('#actionModalTitle').html(titulo);
        $('input[name=goal]').val(goal);
        $('input[name=action]').val(id);
        if (actions[goal] === undefined || actions[goal][id] === undefined){
            current = "";
            expected = "";
            status = "";
            user = "";
            title="";
        }
        else {
            action = actions[goal][id];
            expected = action.expected_result;
            current = action.current_result;
            status = action.status;
            user = action.userInCharge;
            title = action.title;
        }
        $('#errorActionUser').hide();
        $('#actionUser option[value="' + user + '"]').prop('selected', true);
        $('#actionUser').trigger('chosen:updated');
        $('#actionStatus option:contains("' + status + '")').prop('selected', true);
        $('#actionStatus').trigger('chosen:updated');
        $('#actionTitle').val(title);
        $('#actionExpected').val(expected);
        $('#actionCurrent').val(current);
    });

    $('#expand-collapse-items').on('click', function () {
        expandCollapseAll(this, 'itemTableContent');
    });

    $('#expand-collapse-goals').on('click', function () {
        expandCollapseAll(this, 'goalTableContent');
    });

    function expandCollapseAll(element, id) {
        var $this = $(element);
        if($this.hasClass('expanded')) {
            $this.removeClass('expanded').addClass('collapsed');
            $this.html('Colapsar todos');
            $('#'+id).find('i.fa-plus-square-o').click();
        }
        else{
            $this.removeClass('collapsed').addClass('expanded');
            $this.html('Expandir todos');
            $('#'+id).find('i.fa-minus-square-o').click();
        }
    }

    function getItemById(id){
        var year = $('#year').val();
        var org = $('#org').val();
        if (items === undefined || items[org] === undefined || items[org][year] === undefined)
            return "";
        for(var i in items[org][year]){
            if (items[org][year][i].id==id)
                return items[org][year][i];
        }
        return "";
    }
    
    function editFoda() {
        if(!$("#fodaForm").valid()) {
            return;
        }
        var data = {'org': $('#org').val(),
                    'year': $('#year').val(),
                    'foda': $('input[name=foda]').val(),
                    'comment': $('#fodaComment').val()
        };
        ajaxCall("<?php echo base_url();?>fodaStrategy/modify/foda", data);
        $('#editFodaModal').modal('hide');
    }

    function editItem() {
        if(!$("#itemForm").valid()) {
            return;
        }
        var data = {'org': $('#org').val(),
                    'year': $('#year').val(),
                    'item': $('input[name=item]').val(),
                    'title': $('#itemTitle').val(),
                    'type': $('#itemType').val(),
                    'description': $('#itemDescription').val(),
                    'priority': $('#itemPriority').val(),
                    'goals[]': $('#itemGoals').val()};
        ajaxCall("<?php echo base_url();?>fodaStrategy/add/item", data);
        $('#editItemModal').modal('hide');
    }

    function editStrategy() {
        if(!$("#strategyForm").valid()) {
            return;
        }
        var data = {'org': $('#org').val(),
                    'year': $('#year').val(),
                    'strategy': $('input[name=strategy]').val(),
                    'status': $('#strategyStatus').val(),
                    'description': $('#strategyDescription').val(),
                    'comment': $('#strategyComment').val(),
                    'deadline': $('#strategyDeadline').val(),
                    'collaborators[]': $('#strategyCollaborators').val()};
        ajaxCall("<?php echo base_url();?>fodaStrategy/modify/strategy", data);
        $('#editStrategyModal').modal('hide');
    }

    function editGoal() {
        if(!$("#goalForm").valid()) {
            return;
        }

        if($('#goalUser').val() == ""){
            $('#errorGoalUser').show();
            return;
        }

        var data = {'org': $('#org').val(),
            'year': $('#year').val(),
            'goal': $('#modalGoalId').val(),
            'title': $('#goalTitle').val(),
            'status': $('#goalStatus').val(),
            'description': $('#goalDescription').val(),
            'comment': $('#goalComment').val(),
            'goalUser': $('#goalUser').val(),
            'deadline': $('#goalDeadline').val(),
            'items[]': $('#goalItems').val()
        };
        ajaxCall("<?php echo base_url();?>fodaStrategy/add/goal", data);
        $('#editGoalModal').modal('hide');
    }

    function editAction() {
        if(!$("#actionForm").valid()) {
            return;
        }

        if($('#actionUser').val() == ""){
            $('#errorActionUser').show();
            return;
        }

        var data = {'org' : $('#org').val(),
                    'goal': $('input[name=goal]').val(),
                    'action': $('input[name=action]').val(),
                    'title': $('#actionTitle').val(),
                    'status': $('#actionStatus').val(),
                    'current': $('#actionCurrent').val(),
                    'expected': $('#actionExpected').val(),
                    'actionUser': $('#actionUser').val()
        };
        ajaxCall("<?php echo base_url();?>fodaStrategy/add/action", data);
        $('#editActionModal').modal('hide');
    }

    function deleteElement(type, id) {
        var retVal = confirm("¿Esta seguro de eliminar este elemento? Se borrará toda la información asociada a este.");
        if(!retVal)
            return;
        var data = {'type': type, 'id': id, 'org':$('#org').val()};
        ajaxCall("<?php echo base_url();?>fodaStrategy/delete", data);
    }

    function validateElement(type, id) {
        var retVal = confirm("¿Está seguro que desea validar este elemento?");
        if(!retVal)
            return;
        var org = $('#org').val();
        var year = $('#year').val();
        if(id===null){
            if(type=='foda')
                id = fodas[org][year].id;
            else if(type=='strategy')
                id = strategies[org][year].strategy.id;
        }
        var data = {'type': type, 'id': id, 'org':org};
        ajaxCall("<?php echo base_url();?>fodaStrategy/validate", data);
    }

    function ajaxCall(url, data) {
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            dataType: "json",
            cache:false,
            success:
                function(data){
                    if (data['success']){
                        new PNotify({
                            title: 'Éxito!',
                            text: 'Su solicitud ha sido realizada con éxito.',
                            type: 'success'
                        });
                        strategies = data['strategies'];
                        goals = data['goals'];
                        actions = data['actions'];
                        fodas = data['fodas'];
                        items = data['items'];
                        $("#year").change();
                    }
                    else {
                        new PNotify({
                            title: 'Error!',
                            text: 'Ha ocurrido un error. El servidor no logró realizar su solicitud',
                            type: 'error'
                        });
                    }
                },
            error:
                function(xhr, textStatus, errorThrown){
                    new PNotify({
                        title: 'Error!',
                        text: 'Ha ocurrido un error. No se logró conectar con el servidor. Intentelo más tarde',
                        type: 'error'
                    });
                }
        });
    }

    function loadFoda(){
        year = $('#year').val();
        org = $('#org').val();
        if(fodas[org][year]===undefined)
            return;
        $("#foda_comment").html(fodas[org][year].comment);
        $('#foda_valid').removeClass('validated');
        $('#foda_valid').removeClass('no-validated');
        $('#validateFoda').hide();
        if(fodas[org][year].validated==1) {
            $('#foda_valid').html('(Validado)');
            $('#foda_valid').addClass('validated');
        }
        else{
            if (permits[org].validate)
                $('#validateFoda').show();
            $('#foda_valid').html('(No validado)');
            $('#foda_valid').addClass('no-validated');
        }
        loadItems();
    }
    
    function loadItems(){
        var html = "";
        var year = $('#year').val();
        var org = $('#org').val();
        if(items[org]===undefined || items[org][year]===undefined)
            return;
        for(i = 0; i<items[org][year].length; i++){
            cells = '<tr id="item' + items[org][year][i].id + '" >';
            cells += '<td class="text-center checkDetails"><i onclick="showHideItemDetails(this)" data-toggle class="fa fa-plus-square-o text-primary h5 m-none" style="cursor: pointer;"></i></td>';
            cells += '<td>I' + items[org][year][i].id + '</td>';
            cells += '<td class="itemTitle">' + items[org][year][i].title + '</td>';
            cells += '<td class="itemType">' + types[items[org][year][i].type-1].name + '</td>';
            cells += '<td class="itemPriority">' + priorities[items[org][year][i].priority-1].name + '</td>';
            cells += '<td class="actions">';
            if(permits[org].edit) {
                cells += '<a class="btn icons" data-toggle="modal" data-title="Editar Item del FODA" data-target="#editItemModal" data-id="' + items[org][year][i].id + '"><i class="fa fa-pencil"></i></a>' +
                    '<a class="btn icons" onclick="deleteElement(\'item\', ' + items[org][year][i].id + ')"><i class="fa fa-trash-o"></i></a>';
            }
            else{
                cells += 'Sin permisos';
            }
            cells += '</td>';
            html += cells + "</tr>";
        }
        $('#itemTable').dataTable().fnDestroy();
        $('#itemTableContent').html(html);
        var datatableInit = function() {
            var $table = $('#itemTable');

            // initialize
            var datatable = $table.dataTable({
                destroy: true,
                aoColumnDefs: [
                    { 'bSortable': false, 'aTargets': [ 0, 5 ] }
                ],
                aaSorting: [
                    [3, 'desc']
                ],
                bFilter: false,
                paging: false,
                bInfo: false,
            });
        };
        datatableInit();
    }    

    function loadStrategy() {
        year = $('#year').val();
        org = $('#org').val();
        strategy = strategies[org][year].strategy;
        collaborators = strategies[org][year].collaborators;
        $('#strategy_valid').removeClass('validated');
        $('#strategy_valid').removeClass('no-validated');
        $('#validateStrategy').hide();
        if(strategy.validated==1) {
            $('#strategy_valid').html('(Validado)');
            $('#strategy_valid').addClass('validated');
        }
        else{
            if (permits[org].validate)
                $('#validateStrategy').show();
            $('#strategy_valid').html('(No validado)');
            $('#strategy_valid').addClass('no-validated');
        }
        $('#strategy_descrip').html(strategy.description);
        $('#strategy_comment').html(strategy.comment);
        $('#strategy_deadline').html(strategy.deadline);
        $('#strategy_status').html(strategy.status);

        colls = "<div>";
        for(id in collaborators){
            colls += collaborators[id].name + ', ';
        }
        colls = colls.substring(0, colls.length-2);
        colls += '</div>';
        $('#strategy_collaborators').html(colls);
        loadGoals();
    }

    function loadGoals() {
        var html = "";
        var year = $('#year').val();
        var org = $('#org').val();
        if(goals[org] === undefined || goals[org][year]===undefined)
            return;
        for(var i in goals[org][year]){
            var cells = '<tr id="goal' + i + '" >';
            cells += '<td class="text-center checkDetails"><i onclick="showHideGoalDetails(this)" data-toggle class="fa fa-plus-square-o text-primary h5 m-none" style="cursor: pointer;"></i></td>';
            cells += '<td>O' + i + '</td>';
            var validate = "";
            if (goals[org][year][i].validated==1){
                var valText = '<p class="validated">(Validado)</p>';
            }
            else{
                var valText = '<p class="no-validated">(No Validado)</p>';
                if (permits[org].validate)
                    validate = '<a class="btn icons" onclick="validateElement(\'goal\', ' + i + ')" data-toggle="tooltip" title="Validar Objetivo"><i class="fa fa-check"></i></a>';
            }
            cells += '<td class="goalTitle">' + goals[org][year][i].title + valText + '</td>';
            cells += '<td class="goalUser">' + users[goals[org][year][i].userInCharge].name + '</td>';
            cells += '<td class="goalDeadline">' + goals[org][year][i].deadline + '</td>';
            cells += '<td class="goalState">' + goals[org][year][i].status + '</td>';

            cells += '<td class="actions">';
            if (permits[org].edit) {
                cells += '<a class="btn icons" data-toggle="modal" data-title="Editar Objetivo" data-target="#editGoalModal" data-id="' + i + '"><i class="fa fa-pencil"></i></a>' +
                    '<a class="btn icons" onclick="deleteElement(\'goal\', ' + i + ')"><i class="fa fa-trash-o"></i></a>' +
                    '<a class="btn icons" data-toggle="modal" data-title="Añadir Acción" data-target="#editActionModal" data-goal="' + i + '" data-id="-1"><i class="fa fa-plus"></i></a>';
            }
            else{
                cells += "Sin Permisos";
            }
            html += cells + validate + "</td></tr>";
        }
        $('#goalTable').dataTable().fnDestroy();
        $('#goalTableContent').html(html);
        var datatableInit = function() {
            var $table = $('#goalTable');
            // initialize
            var datatable = $table.dataTable({
                destroy: true,
                aoColumnDefs: [
                    { 'bSortable': false, 'aTargets': [ 0, 6 ] }
                ],
                aaSorting: [
                    [1, 'asc']
                ],
                bFilter: false,
                paging: false,
                bInfo: false
            });
        };
        datatableInit();
    }

    function loadActions(goal) {
        var html = "";
        var year = $('#year').val();
        var org = $('#org').val();
        if(actions[goal]===undefined)
            return;
        html = '<table id="actionTable' + goal + '" class="table table-bordered table-striped mb-none dataTable no-footer" role="grid"> \
                        <thead> \
                            <tr > \
                                <th class="sorting_disabled">Acción</th> \
                                <th class="sorting_disabled">Encargado</th> \
                                <th class="sorting_disabled">Estado</th> \
                                <th class="sorting_disabled">Editar</th> \
                            </tr> \
                        </thead> \
                        <tbody id="actionTableContent' + goal + '">';
        for(var i in actions[goal]){
            var action = actions[goal][i];
            var cells = '<tr id="action' + i + '" >';
            cells += '<td class="actionTitle">' + action.title + '</td>';
            cells += '<td class="actionUser">' + users[action.userInCharge].name + '</td>';
            cells += '<td class="actionState">' + action.status + '</td>';
            cells += '<td class="actions">';
            if(permits[org].edit) {
                cells += '<a class="btn icons" data-toggle="modal" data-title="Editar Acción" data-target="#editActionModal" data-goal="' + action.goal + '" data-id="' + i + '"><i class="fa fa-pencil"></i></a>' +
                    '<a class="btn icons" onclick="deleteElement(\'action\', ' + i + ')"><i class="fa fa-trash-o"></i></a></td>';
            }
            else{
                cells += "Sin Permisos";
            }
            cells += '</tr>';
            cells += '<tr class="details"><td colspan="5">' ;
            cells += '<label>Resultado Actual: </label><p class="actionCurrResult">' + action.current_result + '</p>';
            cells += '<label>Resultado Esperado: </label><p class="actionExpResult">' + action.expected_result + '</p>';
            cells += '</td></tr>';
            html += cells;
        }
        html += '</tbody></table>';
        $('#goalActions' + goal).html(html);
    }

    function cleanStrategy() {
        $('#strategy_valid').removeClass('validated');
        $('#strategy_valid').removeClass('no-validated');
        $('#strategy_valid').html('');
        $('a.editStrategy').addClass('hidden');
        $('#strategy_descrip').html('');
        $('#strategy_comment').html('');
        $('#strategy_deadline').html('');
        $('#strategy_status').html('');
        $('#strategy_collaborators').html('');
        $('#goalTableContent').html("");
        $('#goalButtons').hide();
        $('#goalSection').hide();
        $('#addGoal').hide();
        $('#goalTable').hide();
    }

    function cleanFoda() {
        $("#foda_comment").html("");
        $('#foda_valid').removeClass('validated');
        $('#foda_valid').removeClass('no-validated');
        $('a.editFoda').addClass('hidden');
        $('#foda_valid').html('');
        $('#itemTableContent').html("");
        $('#itemButtons').hide();
        $('#itemSection').hide();
        $('#itemTable').hide();
        $('#addItem').hide();
    }

    function showHideItemDetails(element){
        var $this = $(element);
        var row = $this.closest('tr');
        if ( $this.hasClass('fa-minus-square-o')){
            $this.removeClass( 'fa-minus-square-o' ).addClass( 'fa-plus-square-o' );
            if(row.next().hasClass('details'))
                row.next().remove();
        } else {
            $this.removeClass( 'fa-plus-square-o' ).addClass( 'fa-minus-square-o');
            item = getItemById(row.attr('id').substring(4));
            html = '<tr class="details"><td colspan="4"><label>Descripción: </label><p class="itemDescription">' + item.description + '</p></td>';
            html += '<td colspan="2"><label>Objetivos: </label><p class="itemGoals">';
            if (item.goals.length==0){
                html += 'No hay Objetivos Estratégicos asociados.'
            }
            else {
                for (i in item.goals) {
                    html += 'G' + item.goals[i] + ", ";
                }
                html = html.substring(0, html.length - 2);
            }
            html += '</p></td></tr>';
            row.after(html);
        }
    }

    function showHideGoalDetails(element){
        var $this = $(element);
        var row = $this.closest('tr');
        if ( $this.hasClass('fa-minus-square-o')){
            $this.removeClass( 'fa-minus-square-o' ).addClass( 'fa-plus-square-o' );
            if(row.next().hasClass('details'))
                row.next().remove();
        } else {
            year = $('#year').val();
            org = $('#org').val();
            $this.removeClass( 'fa-plus-square-o' ).addClass( 'fa-minus-square-o');
            id = row.attr('id').substring(4);
            goal = goals[org][year][id];
            html = '<tr class="details"><td colspan="8">';
            html += '<div class="col-sm-8">';
            html += '<label>Descripción: </label><p class="goalDescription">' + goal.description + '</p>';
            html += '<label>Comentario: </label><p class="goalComment">' + goal.comment + '</p>';
            html += '</div><div class="col-sm-4"';
            html += '<label>Fecha de Creación: </label><p class="goalTimestamp">' + goal.timestamp + '</p>';
            html += '<label>Items: </label><p class="GoalItems">';
            if (goal.items.length==0){
                html += 'No hay Items del FODA asociados.'
            }
            else {
                for (i in goal.items) {
                    html += 'I' + goal.items[i] + ", ";
                }
                html = html.substring(0, html.length - 2);
            }
            html += '</p></div>';
            html += '<div id="goalActions' + id + '"></div>';
            html += '</td></tr>';

            if(actions[id].length==0){
                row.after(html);
                return;
            }
            row.after(html);
            loadActions(id);
        }
    }

    for(var i = 0; i<years.length; i++){
        $('#year').append('<option>' + years[i] + '</option>');
    }

    var config = {
        '.chosen-select'           : {},
        '.chosen-select-deselect'  : {allow_single_deselect:true},
        '.chosen-select-no-single' : {disable_search_threshold:10},
        '.chosen-select-no-results': {no_results_text:'No se ha encontrado nada.'},
        '.chosen-select-width'     : {width:"95%"}
    }
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }

    $('#year').on('chosen:no_results', function(e,params) {
        var value = $('.chosen-search > input:nth-child(1)').val();
        if(value.length==4 && (!isNaN(parseFloat(value)) && isFinite(value))){
            $('#year').append($("<option>" , {
                text: value,
                value: value
            }));
            $('#year option[value="'.concat(value,'"]')).attr("selected", "selected");
            $('#year').trigger('chosen:updated');
            $("#year").change();
        }
    });

    function validate_year(id){
        var opt = document.getElementById(id).value;
        return changeOnValidation(document.getElementById(id), ((!isNaN(parseFloat(opt)) && isFinite(opt)) && opt.length ==4 && opt>=1980));
    }

    function changeOnValidation(elem, validator){
        if(validator){
            elem.style.borderColor="green";
            return true;
        }
        else{
            elem.style.borderColor="red";
            elem.focus();
            return false;
        }
    }

    $("#year").change(function(e){
        var org = $("#org").val();
        cleanStrategy();
        cleanFoda();

        if (!validate_year('year') || parseInt(org)<0 || isNaN(parseInt(org))) {
            return;
        }
        if(permits[org].edit) {
            $('a.editFoda').removeClass('hidden');
            $('a.editStrategy').removeClass('hidden');
        }
        $('input[name=year]').val(this.value);
        $('input[name=org]').val(org);
        if(!(fodas[org] === undefined || fodas[org][this.value] === undefined)) {
            loadFoda();
            $('#itemSection').show();
            $('#itemButtons').show();
            if(permits[org].edit){
                $('#addItem').show();
            }
        }
        if(!(strategies[org] === undefined || strategies[org][this.value] === undefined)) {
            loadStrategy();
            $('#goalSection').show();
            $('#goalButtons').show();
            if(permits[org].edit){
                $('#addGoal').show();
            }
        }
        if (!(items[org]===undefined || items[org][this.value]===undefined)){
            $('#itemTable').show();
        }
        if (!(goals[org] === undefined || goals[org][this.value]===undefined)){
            $('#goalTable').show();
        }
    });

    $('#org').on('change', function () {
       $('#year').change();
    });

    function notify(success) {
        if (success==1){
            new PNotify({
                title: 'Éxito!',
                text: 'Su solicitud ha sido realizada con éxito.',
                type: 'success'
            });
        }
        if (success==0){
            new PNotify({
                title: 'Error!',
                text: 'Ha ocurrido un error con su solicitud.<br>Los nombres de los elementos solo puede tener letras, tildes, números y espacios.',
                type: 'error'
            });
        }
    }

    notify(<?php echo($success);?>);

    function optionHTML(optionArray, selected){
        var optHTML = "";
        for(var i = 0; i<optionArray.length; i++){
            if (selected==optionArray[i].id || selected==optionArray[i].name)
                optHTML += '<option value="'+ optionArray[i].id +'" selected>' + optionArray[i].name + '</option>';
            else
                optHTML += '<option value="'+ optionArray[i].id +'">' + optionArray[i].name + '</option>';
        }
        return optHTML;
    }
</script>
</body>
</html>
