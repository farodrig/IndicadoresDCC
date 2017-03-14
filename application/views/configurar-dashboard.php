<!doctype html>
<html class="fixed sidebar-left-collapsed">
<head>
    <?php
    //Para usar head.php debe ser dentro del tag head y debe haberse creado una variable $title.
    include 'partials/head.php'; ?>

    <link rel="stylesheet" href="<?php echo base_url();?>chosen/chosen.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/vendor/bootstrap-colorpicker/css/bootstrap-colorpicker.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/vendor/jquery-datatables-bs3/assets/css/datatables.css">

    <style type="text/css">
        .title{
            font-size: 1.5em;
        }
        .margin-top{
            margin-top: 2%;
        }
        .margin-bottom{
            margin-bottom: 2%;
        }
        .icons {
            padding: 0px;
        }
        div.table-responsive div.row {
            margin-left: 0px;
            margin-right: 0px;
        }
        .chosen-container{
            width: 100% !important;
        }
    </style>

    <script type="text/javascript">
        var types = <?php echo json_encode($types); ?>;
        var aggregation = <?php echo json_encode($aggregation); ?>;
        var metrics = <?php echo json_encode($metrics); ?>;
        var orgs = <?php echo json_encode($orgs); ?>;
        var graphics = <?php echo json_encode($graphics); ?>;
        var values = null;
    </script>
</head>
<body class="loading-overlay-showing" data-loading-overlay="">
<section class="body">

    <?php
    //Para usar header_tmpl.php se debe haber creado la variable $name y $role. Se pueden crear tanto aqui como en el controlador.
    include 'partials/header_tmpl.php'; ?>

    <div class="inner-wrapper">
        <!-- start: sidebar -->
        <?php
        $navData=[['url'=>'config/organizacion', 'name'=>'Configurar áreas y unidades', 'icon'=>'fa fa-th-large'],
            ['url'=>'config/metricas', 'name'=>'Configurar Métricas', 'icon'=>'fa fa-server']];
        include 'partials/navigation.php';
        ?>
        <!-- end: sidebar -->

        <section role="main" class="content-body">
            <header class="page-header">
                <h2>Configuración del Dashboard</h2>

                <div class="right-wrapper pull-right">
                    <ol class="breadcrumbs">
                        <li>
                            <a href="<?php echo base_url();?>inicio">
                                <i class="fa fa-home"></i>
                            </a>
                        </li>
                        <li><span>Configurar</span></li>
                        <li><span>Dashboard</span></li>
                    </ol>
                    <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                </div>
            </header>

            <!-- start: page -->
            <section class="panel panel-transparent">
                <div class="panel-body col-md-10 col-md-offset-1">
                    <div class="panel-body ">
                        <form>
                            <div class="margin-bottom row">
                                <h4 class="text-center margin-bottom"><strong>Configuración de Gráficos del Dashboard</strong></h4>
                            </div>
                            <div class="row margin-top">
                                <div class="col-md-6 text-center">
                                    <label class="control-label title">Organización:</label>
                                </div>
                                <div class="col-md-3">
                                    <select id="org" name="org" data-placeholder="Seleccione area o sub-area..." class="chosen-select">
                                        <option value=""></option>
                                        <?php
                                        foreach ($departments as $dpto){
                                            ?>
                                            <option value="<?php echo(ucwords($dpto['department']->getId()));?>">DCC <?php echo(ucwords($dpto['type']['name'])); ?></option>
                                                <?php
                                                foreach ($dpto['areas'] as $area) {
                                                    ?>
                                                    <option value="<?php echo($area['area']->getId())?>" style="padding-left:20px"><?php echo(ucwords($area['area']->getName()));?></option>
                                                    <?php
                                                    foreach ($area['unidades'] as $unidad) {
                                                        ?>
                                                        <option value="<?php echo($unidad->getId())?>" style="padding-left:40px"><?php echo(ucwords($unidad->getName()));?></option>
                                                        <?php
                                                    }
                                                }?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row margin-top">
                                <div id ="graphicData" class="col-md-12">
                                    <div class="row col-md-12" id="graphicButtons" style="display: none;">
                                        <button id="addGraphic" data-toggle="modal" data-target="#editGraphicModal" data-id="-1" data-title="Añadir Gráfico al Dashboard" class="pull-left" type="button"><i class="fa fa-plus"></i> Añadir Gráfico</button>
                                        <button id="expand-collapse-graphics" class="expanded pull-right" type="button">Expandir Todos</button>
                                    </div>
                                    <div id="graphicSection" class="panel-body col-md-12" style="display: none;">
                                        <div id="graphicTable_wrapper" class="dataTables_wrapper no-footer">
                                            <div class="table-responsive">
                                                <table id="graphicTable" class="table table-bordered table-striped mb-none dataTable no-footer text-center" role="grid" aria-describedby="graphicTable_info">
                                                    <thead>
                                                    <tr>
                                                        <th class="sorting_disabled"></th>
                                                        <th class="sorting text-center" aria-controls="itemTable">Gráfico</th>
                                                        <th class="sorting text-center" aria-controls="itemTable">Año mínimo</th>
                                                        <th class="sorting text-center" aria-controls="itemTable">Año máximo</th>
                                                        <th class="sorting text-center" aria-controls="itemTable">Por año</th>
                                                        <th class="sorting text-center" aria-controls="itemTable">Mostrar</th>
                                                        <th class="sorting text-center" aria-controls="itemTable">Posición</th>
                                                        <th class="sorting_disabled text-center" aria-controls="itemTable">Editar</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="graphicTableContent">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </section>
    </div>
</section>

<div class="modal fade" tabindex="-1" role="dialog" id="editGraphicModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 id="graphicModalTitle" class="modal-title">Editar Configuración del Gráfico</h4>
            </div>
            <div class="modal-body">
                <form id="graphicForm" class="form-horizontal">
                    <input type="hidden" name="graphic">
                    <div class="form-group">
                        <label for="graphicTitle" class="col-sm-3 control-label">Título:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="graphicTitle" name="title" required >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="graphicMinYear" class="col-sm-3 control-label">Año Mínimo:</label>
                        <div class="col-sm-3">
                            <input type="number" class="form-control" id="graphicMinYear" name="minYear" required >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="graphicMaxYear" class="col-sm-3 control-label">Año Máximo:</label>
                        <div class="col-sm-3">
                            <input type="number" class="form-control" id="graphicMaxYear" name="maxYear" required >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="graphicPosition" class="col-sm-3 control-label">Posición:</label>
                        <div class="col-sm-2">
                            <input type="number" class="form-control" id="graphicPosition" name="position" required >
                        </div>
                    </div>
                    <div class="form-group" id="graphByYear">
                        <label for="graphicByYear" class="col-sm-3 control-label">Por Año:</label>
                        <div class="col-sm-1">
                            <input type="checkbox" checked class="form-control" id="graphicByYear" name="byYear">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="graphicDisplay" class="col-sm-3 control-label">Mostrar:</label>
                        <div class="col-sm-1">
                            <input type="checkbox" checked class="form-control" id="graphicDisplay" name="display">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                <button type="button" onclick="editGraphic()" class="btn btn-success">Guardar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="editSerieModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 id="serieModalTitle" class="modal-title">Editar Serie del Gráfico</h4>
            </div>
            <div class="modal-body">
                <form id="serieForm" class="form-horizontal">
                    <input type="hidden" name="graphic">
                    <input type="hidden" name="serie">
                    <div class="form-group">
                        <label for="serieOrg" class="col-sm-3 control-label">Organización:</label>
                        <div class="col-sm-9">
                            <select class="form-control chosen-select no-search" onchange="loadSerieMetric(this.value)" id="serieOrg" name="org" data-placeholder="Seleccione la organización de la métrica a mostrar" required>
                                <option value="" selected></option>
                                <?php
                                foreach ($departments as $dpto){
                                    ?>
                                    <option value="<?php echo(ucwords($dpto['department']->getId()));?>">DCC <?php echo(ucwords($dpto['type']['name'])); ?></option>
                                    <?php
                                    foreach ($dpto['areas'] as $area) {
                                        ?>
                                        <option value="<?php echo($area['area']->getId())?>" style="padding-left:20px"><?php echo(ucwords($area['area']->getName()));?></option>
                                        <?php
                                        foreach ($area['unidades'] as $unidad) {
                                            ?>
                                            <option value="<?php echo($unidad->getId())?>" style="padding-left:40px"><?php echo(ucwords($unidad->getName()));?></option>
                                            <?php
                                        }
                                    }?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="serieMetric" class="col-sm-3 control-label">Métrica:</label>
                        <div class="col-sm-9">
                            <select class="form-control chosen-select no-search" id="serieMetric" name="metric" data-placeholder="Seleccione la métrica a mostrar" required>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="serieType" class="col-sm-3 control-label">Tipo Visualización:</label>
                        <div class="col-sm-9">
                            <select class="form-control chosen-select no-search" id="serieType" name="type" data-placeholder="Seleccione la forma en la que se verá la serie" required>
                                <?php
                                foreach ($types as $id => $type){
                                    echo('<option value="'.$id.'">'.$type->name.'</option>');
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group aggregation">
                        <label for="serieAggregationYear" class="col-sm-3 control-label">Tipo de Agregación para los Años:</label>
                        <div class="col-sm-9">
                            <select class="form-control chosen-select no-search" id="serieAggregationYear" name="aggregation_year" data-placeholder="Seleccione la forma en la que se agregará la serie" required>
                                <?php
                                foreach ($aggregation as $id => $aggr){
                                    echo('<option value="'.$id.'">'.$aggr->name.'</option>');
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group aggregation">
                        <label for="serieAggregationX" class="col-sm-3 control-label">Tipo de Agregación para el eje X:</label>
                        <div class="col-sm-9">
                            <select class="form-control chosen-select no-search" id="serieAggregationX" name="aggregation_x" data-placeholder="Seleccione la forma en la que se agregará la serie" required>
                                <?php
                                foreach ($aggregation as $id => $aggr){
                                    echo('<option value="'.$id.'">'.$aggr->name.'</option>');
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="serieColor" class="col-sm-3 control-label">Color:</label>
                        <div class="col-sm-9">
                            <input type="text" class="colorpicker-default form-control colorpicker-element" id="serieColor" name="color" data-plugin-colorpicker="" >
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                <button type="button" onclick="editSerie()" class="btn btn-success">Guardar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="previewModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 id="previewModalTitle" class="modal-title">Previsualización del Gráfico</h4>
            </div>
            <div class="modal-body loading-overlay-showing">
                <div id="ajaxLoader" style="min-height: 150px; position: relative;" data-loading-overlay-options="{ &quot;startShowing&quot;: true }" data-loading-overlay="" class="">
                    <div class="loading-overlay" style="background-color: rgb(253, 253, 253);">
                        <div class="loader black"></div>
                    </div>
                </div>
                <div id="graphicContainer" class="col-md-12"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<?php include 'partials/footer.php'; ?>
<script src="<?php echo base_url();?>assets/vendor/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
<script src="<?php echo base_url();?>assets/vendor/select2/select2.js"></script>
<script src="<?php echo base_url();?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.js"></script>
<script src="<?php echo base_url();?>assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
<script src="<?php echo base_url();?>chosen/chosen.jquery.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/vendor/jquery-validation/jquery.validate.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/vendor/jquery-validation/additional-methods.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/vendor/highcharts/js/highcharts.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>js/functions.js"></script>

<script type="text/javascript">
    $(function() { $('#serieColor').colorpicker(); });

    $('#serieMetric').on('change', function () {
        var metric = getMetricById(this.value);
        var aggreg = $('.aggregation');
        aggreg.show();
        if (metric.x_name==""){
            aggreg.hide();
        }
    });

    $('.no-search').chosen({"disable_search": true});

    $('#expand-collapse-graphics').on('click', function () {
        expandCollapseAll(this, 'graphicTableContent');
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

    $('#editGraphicModal').on('show.bs.modal', function (e) {
        var org = $('#org').val();
        var id = $(e.relatedTarget).data('id');
        var titulo = $(e.relatedTarget).data('title');
        $('#graphicModalTitle').html(titulo);
        $('input[name=graphic]').val(id);
        $('#graphicByYear').prop("disabled", false);
        $('#graphByYear').show();
        if (graphics[org] === undefined || graphics[org][id] === undefined){
            var title = "";
            var min_year = new Date().getFullYear();
            var max_year = min_year;
            var position = 0;
            var see_x = false;
            var display = false;
            $('#graphByYear').hide();
        }
        else {
            var graphic = graphics[org][id];
            var see_x = graphic.see_x=="1";
            if(!(graphic.series[0]===undefined)){
                var metric = getMetricById(graphic.series[0].metorg);
                if(!(metric===null) && !metric.x_name){
                    see_x = false;
                    $('#graphicByYear').prop("disabled", true);
                }
            }
            var title = graphic.title;
            var min_year = graphic.min_year;
            var max_year = graphic.max_year;
            var position = graphic.position;
            var display = graphic.display=="1";
        }
        $('#graphicTitle').val(title);
        $('#graphicMinYear').val(min_year);
        $('#graphicMaxYear').val(max_year);
        $('#graphicPosition').val(position);
        $('#graphicDisplay').prop('checked', display);
        $('#graphicByYear').prop('checked', !see_x);
    });

    $('#editSerieModal').on('show.bs.modal', function (e) {
        var org = $('#org').val();
        var id = $(e.relatedTarget).data('id');
        var graphic = $(e.relatedTarget).data('graphic');
        var titulo = $(e.relatedTarget).data('title');
        var serieOrg = $(e.relatedTarget).data('org');
        $('#serieModalTitle').html(titulo);
        $('input[name=graphic]').val(graphic);
        var aggreg = $('.aggregation');
        aggreg.show();
        if (graphics[org] === undefined || graphics[org][graphic]===undefined || graphics[org][graphic].series[id]===undefined){
            $('input[name=serie]').val(-1);
            var type = "";
            var aggregYear = 0;
            var aggregX = 0;
            var color = '';
            var metorg = "";
        }
        else {
            var serie = graphics[org][graphic].series[id];
            $('input[name=serie]').val(serie.id);
            var metorg = serie.metorg;
            var type = serie.type;
            var aggregYear = serie.year_aggregation;
            var aggregX = serie.x_aggregation;
            var color = serie.color;
        }

        if (!(graphics[org] === undefined || graphics[org][graphic]===undefined || graphics[org][graphic].series.length <=0) && getMetricById(graphics[org][graphic].series[0].metorg).x_name ==""){
            aggreg.hide();
        }

        org = (serieOrg!=-1 ? serieOrg : org);
        $('#serieOrg option[value="' + org + '"]').prop('selected', true);
        $('#serieOrg').trigger('chosen:updated');
        $('#serieOrg').change();
        $('#serieMetric option[value="' + metorg + '"]').prop('selected', true);
        $('#serieMetric').trigger('chosen:updated');
        $('#serieMetric').change();
        $('#serieType option[value="' + type + '"]').prop('selected', true);
        $('#serieType').trigger('chosen:updated');
        $('#serieAggregationX option[value="' + aggregX + '"]').prop('selected', true);
        $('#serieAggregationX').trigger('chosen:updated');
        $('#serieAggregationYear option[value="' + aggregYear + '"]').prop('selected', true);
        $('#serieAggregationYear').trigger('chosen:updated');
        $('#serieColor').val(color);
    });

    $('#previewModal').on('shown.bs.modal', function (e) {
        var graphic = $(e.relatedTarget).data('graphic');
        $.ajax({url: "<?php echo base_url();?>config/dashboard/values",
                type: "POST",
                async: false,
                dataType: "json",
                cache: false,
                data: {'graphic': graphic},
                success: function(data){
                            values = data['values'];
                        },
                error: function () {
                    $('#previewModal').modal('hide');
                }
        });
        var title = values.title;
        if(values.see_x){
            title += " Periodo (" + values.min_year + " - " + values.max_year + ")";
        }
        $('#previewModalTitle').html(title);
        $('#ajaxLoader').hide();
        var data = createGraphicData(values.series, values.y_unit);
        var options = getGraphicOptions('', values.x_name, values.x_values, values.y_name, values.y_unit, data);
        $('#graphicContainer').highcharts(options);
    });

    $('#previewModal').on('hidden.bs.modal', function (e) {
        $('#ajaxLoader').show();
        $('#graphicContainer').html('');
    });

    $('#org').on('change', function () {
        $('#graphicButtons').hide();
        $('#graphicSection').hide();
        $('#graphicTableContent').html('');
        if (this.value=="")
            return;
        $('#graphicButtons').show();
        if(!(graphics[this.value] === undefined)) {
            loadGraphics(this.value);
            $('#graphicSection').show();
        }
    });

    function loadGraphics(org) {
        var html = "";
        for(var i in graphics[org]){
            var cells = '<tr id="graphic' + i + '" role="row">';
            var hidden = (graphics[org][i].series.length ? '' : 'hidden');
            cells += '<td class="text-center checkDetails"><i onclick="showHideGraphicDetails(this)" data-toggle class="fa fa-plus-square-o text-primary h5 m-none ' + hidden + '" style="cursor: pointer;"></i></td>';
            cells += '<td class="graphicTitle">' + graphics[org][i].title + '</td>';
            cells += '<td class="graphicMinYear">' + graphics[org][i].min_year + '</td>';
            cells += '<td class="graphicMaxYear">' + graphics[org][i].max_year + '</td>';
            var bin = (graphics[org][i].see_x=="1" ? 'No' : 'Si');
            cells += '<td class="graphicState">' + bin + '</td>';
            bin = (graphics[org][i].display=="1" ? 'Si' : 'No');
            cells += '<td class="graphicDisplay">' + bin + '</td>';
            cells += '<td class="graphicPosition">' + graphics[org][i].position + '</td>';
            cells += '<td class="actions"><a class="btn icons" data-toggle="modal" data-title="Editar Gráfico" data-target="#editGraphicModal" data-id="' + i + '"><i class="fa fa-pencil"></i></a>' +
                '<a class="btn icons" onclick="deleteElement(\'graphic\', ' + i + ')"><i class="fa fa-trash-o"></i></a>' +
                '<a class="btn icons" data-toggle="modal" data-title="Añadir Serie" data-target="#editSerieModal" data-graphic="' + i + '" data-org="-1" data-id="-1"><i class="fa fa-plus"></i></a>' +
                '<a class="btn icons" data-toggle="modal" data-target="#previewModal" data-graphic="' + i + '"><i class="fa fa-eye"></i></a></td>';

            html += cells + "</tr>";
        }
        $('#graphicTable').dataTable().fnDestroy();
        $('#graphicTableContent').html(html);
        var datatableInit = function() {
            var $table = $('#graphicTable');
            // initialize
            var datatable = $table.dataTable({
                destroy: true,
                aoColumnDefs: [
                    { 'bSortable': false, 'aTargets': [ 0, 7 ] }
                ],
                aaSorting: [
                    [6, 'asc']
                ],
                bFilter: false,
                paging: false,
                bInfo: false
            });
        };
        datatableInit();
    }

    function loadSeries(id) {
        var html = "";
        var org = $('#org').val();
        var aggre = "";
        html = '<table id="serieTable' + id + '" class="table table-bordered table-striped mb-none dataTable no-footer" role="grid"> \
                    <thead> \
                        <tr > \
                            <th class="sorting_disabled">Organización</th>\
                            <th class="sorting_disabled">Métrica</th> \
                            <th class="sorting_disabled">Tipo de Serie</th>';
        var next =         '<th class="sorting_disabled">Color</th> \
                            <th class="sorting_disabled">Acciones</th> \
                        </tr> \
                    </thead> \
                    <tbody id="serieTableContent' + id + '">';
        var first = true;
        var hasX = seriesHasX(graphics[org][id].series);
        for(var i in graphics[org][id].series){
            var serie = graphics[org][id].series[i];
            var metric = getMetricById(serie.metorg);
            if(metric===null)
                continue;

            var add = "";
            var aggreg = "";
            if (hasX){
                add = '<th class="sorting_disabled">Agregación para Año</th><th class="sorting_disabled">Agregación para X</th>';
                aggreg += '<td class="serieYear">' + (aggregation[serie.year_aggregation].name=="" ? 'No Agrega' : aggregation[serie.year_aggregation].name)+ '</td>';
                aggreg += '<td class="serieX">' + (aggregation[serie.x_aggregation].name=="" ? 'No Agrega' : aggregation[serie.x_aggregation].name)+ '</td>';
            }

            if (first){
                html += add + next;
                first = false;
            }

            var serieOrg = getOrgByMetric(serie.metorg);
            var cells = '<tr id="serie' + serie.id + '" >';
            cells += '<td class="serieOrg">' + orgs[serieOrg] + '</td>';
            cells += '<td class="serieTitle">' + metric.name + '</td>';
            cells += '<td class="serieType">' + types[serie.type].name + '</td>';
            cells += aggreg;
            cells += '<td class="serieColor">' + (serie.color===null ? "Indefinido" : serie.color) + '</td>';
            cells += '<td class="actions"><a class="btn icons" data-toggle="modal" data-title="Editar Serie" data-target="#editSerieModal" data-graphic="' + id + '" data-org="' + serieOrg + '"  data-id="' + i + '"><i class="fa fa-pencil"></i></a>' +
                    '<a class="btn icons" onclick="deleteElement(\'serie\', ' + serie.id + ')"><i class="fa fa-trash-o"></i></a></td>';
            cells += '</tr>';
            html += cells;
        }
        html += '</tbody></table>';
        $('#GraphicMetrics' + id).html(html);
    }

    function loadSerieMetric(org){
        var html = '';
        var graphic = $("input[name='graphic']").val();
        var filtrate = false;
        if (!(graphics[org] === undefined || graphics[org][graphic]===undefined || graphics[org][graphic].series.length <=0)){
            var metric = getMetricById(graphics[org][graphic].series[0].metorg);
            filtrate = (metric.x_name=="");
        }
        for(var id in metrics[org]){
            var metric = metrics[org][id];
            if (filtrate && (metric.x_name!=""))
                continue;
            html += '<option value="' + id + '">' + metric.name + '</option>';
        }
        $('#serieMetric').html(html);
        $('#serieMetric').trigger('chosen:updated');
    }

    function showHideGraphicDetails(element){
        var $this = $(element);
        var row = $this.closest('tr');
        if ( $this.hasClass('fa-minus-square-o')){
            $this.removeClass( 'fa-minus-square-o' ).addClass( 'fa-plus-square-o' );
            if(row.next().hasClass('details'))
                row.next().remove();
        } else {
            var org = $('#org').val();
            $this.removeClass( 'fa-plus-square-o' ).addClass( 'fa-minus-square-o');
            var id = row.attr('id').substring(7);
            var html = '<tr class="details"><td colspan="8">';
            html += '<div id="GraphicMetrics' + id + '"></div>';
            html += '</td></tr>';

            if(graphics[org][id].series.length==0){
                row.after(html);
                return;
            }
            row.after(html);
            loadSeries(id);
        }
    }

    function getMetricById(id) {
        for(var org in metrics){
            if(metrics[org].hasOwnProperty(id)){
                return metrics[org][id];
            }
        }
        return null;
    }

    function getOrgByMetric(id) {
        for(var org in metrics){
            if(metrics[org].hasOwnProperty(id)){
                return org;
            }
        }
        return null;
    }

    function seriesHasX(series) {
        for(var i in series){
            var serie = series[i];
            var metric = getMetricById(serie.metorg);
            if(metric == null || metric.x_name == "")
                continue;
            return true;
        }
        return false;
    }

    function deleteElement(type, id) {
        var retVal = confirm("¿Esta seguro de eliminar este elemento? Se borrará toda la información asociada a este.");
        if(!retVal)
            return;
        var data = {'type': type, 'id': id};
        ajaxCall("<?php echo base_url();?>config/dashboard/delete", data);
    }
    
    function editGraphic() {
        if(!$("#graphicForm").valid()) {
            return;
        }
        var data = {'org': $('#org').val(),
                    'graphic': $('input[name=graphic]').val(),
                    'title': $('#graphicTitle').val(),
                    'minYear': $('#graphicMinYear').val(),
                    'maxYear': $('#graphicMaxYear').val(),
                    'position': $('#graphicPosition').val(),
                    'byYear': ($("#graphicByYear").is(':checked') ? 1 : 0),
                    'display': ($("#graphicDisplay").is(':checked') ? 1 : 0)
        };
        ajaxCall("<?php echo base_url();?>config/dashboard/modify/graphic", data);
        $('#editGraphicModal').modal('hide');
    }

    function editSerie() {
        if(!$("#serieForm").valid() || $('#serieType').val()=="" || $('#serieMetric').val()=="") {
            return;
        }
        if(getMetricById($('#serieMetric').val()).x_name!="" && ($('#serieAggregationYear').val()==0 || $('#serieAggregationX').val()==0)){
            alert("Debe agregar el tipo de agregación");
            return;
        }
        var data = {'graphic': $('input[name=graphic]').val(),
                    'serie': $('input[name=serie]').val(),
                    'metorg': $('#serieMetric').val(),
                    'type': $('#serieType').val(),
                    'aggregYear': $('#serieAggregationYear').val(),
                    'aggregX': $('#serieAggregationX').val(),
                    'color': $('#serieColor').val()
        };
        ajaxCall("<?php echo base_url();?>config/dashboard/modify/serie", data);
        $('#editSerieModal').modal('hide');
    }

    function ajaxCall(url, data) {
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            dataType: "json",
            success:
                function(data){
                    metrics = data['metrics'];
                    graphics = data['graphics'];
                    $("#org").change();
                    if (data['success']){
                        new PNotify({
                            title: 'Éxito!',
                            text: 'Su solicitud ha sido realizada con éxito.',
                            type: 'success'
                        });
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

    var config = {
        '.chosen-select'           : {},
        '.chosen-select-deselect'  : {allow_single_deselect:true},
        '.chosen-select-no-single' : {disable_search_threshold:10},
        '.chosen-select-no-results': {no_results_text:'No se ha encontrado nada.'},
        '.chosen-select-width'     : {width:"95%"}
    };
    
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }

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
                text: 'Ha ocurrido un error con su solicitud.<br>Si el problema persiste comuníquese con el encargado.',
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
