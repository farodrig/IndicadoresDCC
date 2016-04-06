<!doctype html>
<html class="fixed sidebar-left-collapsed">
<head>
    <?php
    //Para usar head.php debe ser dentro del tag head y debe haberse creado una variable $title.
    include 'partials/head.php'; ?>

    <link rel="stylesheet" href="<?php echo base_url();?>chosen/chosen.css">

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
    </style>

    <script type="text/javascript">
        var types = <?php echo json_encode($types); ?>;
        var aggregation = <?php echo json_encode($aggregation); ?>;
        var metrics = <?php echo json_encode($metrics); ?>;
        var orgs = <?php echo json_encode($orgs); ?>;
        var graphics = {"2":[{'title': 'titulo', 'max_year':2015, 'min_year':2010, 'ver_x': true, 'metrics':[]}]};
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
        $navData=[['url'=>'inicio', 'name'=>'U-Dashboard', 'icon'=>'fa fa-home'],
            ['url'=>'careaunidad', 'name'=>'Configurar áreas y unidades', 'icon'=>'fa fa-th-large'],
            ['url'=>'cmetrica', 'name'=>'Configurar Métricas', 'icon'=>'fa fa-server']];
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
                <div class="panel-body col-md-8 col-md-offset-2">
                    <div class="panel-body ">
                        <form>
                            <div class="margin-bottom row">
                                <h4 class="text-center margin-bottom"><strong>Configuración de Gráficos del Dashboard</strong></h4>
                            </div>
                            <div class="row margin-top">
                                <div class="col-md-6 text-center">
                                    <label class="control-label title">Organización:</label>
                                </div>
                                <div class="col-md-6">
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
                                                <table id="graphicTable" class="table table-bordered table-striped mb-none dataTable no-footer" role="grid" aria-describedby="graphicTable_info">
                                                    <thead>
                                                    <tr role="row">
                                                        <th class="sorting_disabled"></th>
                                                        <th class="sorting" aria-controls="itemTable">Gráfico</th>
                                                        <th class="sorting" aria-controls="itemTable">Año mínimo</th>
                                                        <th class="sorting" aria-controls="itemTable">Año máximo</th>
                                                        <th class="sorting" aria-controls="itemTable">Por año</th>
                                                        <th class="sorting_disabled" aria-controls="itemTable">Editar</th>
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

<?php include 'partials/footer.php'; ?>

<script src="<?php echo base_url();?>assets/vendor/select2/select2.js"></script>
<script src="<?php echo base_url();?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.js"></script>
<script src="<?php echo base_url();?>assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
<script src="<?php echo base_url();?>chosen/chosen.jquery.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/vendor/jquery-validation/jquery.validate.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/vendor/jquery-validation/additional-methods.js" type="text/javascript"></script>

<script type="text/javascript">

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

    $('#org').on('change', function () {
        $('#graphicButtons').hide();
        $('#graphicSection').hide();
        $('#graphicTableContent').html('');
        if (this.value=="")
            return;
        $('#graphicButtons').show();
        if(!(graphics[this.value] === undefined)) {
            loadGraphics();
            $('#graphicSection').show();
        }
    });

    function loadGraphics() {
        html = "";
        org = $('#org').val();
        for(i in graphics[org]){
            cells = '<tr id="graphic' + i + '" role="row">';
            cells += '<td class="text-center checkDetails"><i onclick="showHideGraphicDetails(this)" data-toggle class="fa fa-plus-square-o text-primary h5 m-none" style="cursor: pointer;"></i></td>';
            cells += '<td class="graphicTitle">' + graphics[org][i].title + '</td>';
            cells += '<td class="graphicMinYear">' + graphics[org][i].min_year + '</td>';
            cells += '<td class="graphicMaxYear">' + graphics[org][i].max_year + '</td>';
            if (graphics[org][i].ver_x){
                cells += '<td class="graphicState"> Si </td>';
            }
            else{
                cells += '<td class="graphicState"> No </td>';
            }
            cells += '<td class="actions"><a class="btn icons" data-toggle="modal" data-title="Editar Gráfico" data-target="#editGraphicModal" data-id="' + i + '"><i class="fa fa-pencil"></i></a>' +
                '<a class="btn icons" onclick="deleteElement(\'graphic\', ' + i + ')"><i class="fa fa-trash-o"></i></a>' +
                '<a class="btn icons" data-toggle="modal" data-title="Añadir Métrica" data-target="#editMetricModal" data-graphic="' + i + '" data-id="-1"><i class="fa fa-plus"></i></a></td>';
            html += cells + "</tr>";
        }
        $('#graphicTable').dataTable().fnDestroy();
        $('#graphicTableContent').html(html);
        var datatableInit = function() {
            var $table = $('#goalTable');
            // initialize
            var datatable = $table.dataTable({
                destroy: true,
                aoColumnDefs: [
                    { 'bSortable': false, 'aTargets': [ 0, 5 ] }
                ],
                aaSorting: [
                    [1, 'asc']
                ],
                bFilter: false,
                paging: false,
                bInfo: false,
            });
        };
        datatableInit();
    }

    function showHideGraphicDetails(element){
        var $this = $(element);
        var row = $this.closest('tr');
        if ( $this.hasClass('fa-minus-square-o')){
            $this.removeClass( 'fa-minus-square-o' ).addClass( 'fa-plus-square-o' );
            if(row.next().hasClass('details'))
                row.next().remove();
        } else {
            org = $('#org').val();
            $this.removeClass( 'fa-plus-square-o' ).addClass( 'fa-minus-square-o');
            id = row.attr('id').substring(7);
            html = '<tr class="details"><td colspan="6">';
            html += '<div id="GraphicMetrics' + id + '"></div>';
            html += '</td></tr>';

            if(graphics[org][id].metrics.length==0){
                row.after(html);
                return;
            }
            row.after(html);
            loadMetrics(id);
        }
    }

    function loadMetrics(id) {

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
                        fodas = data['fodas'];
                        items = data['items'];
                        strategies = data['strategies'];
                        goals = data['goals'];
                        actions = data['actions'];
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
                text: 'Ha ocurrido un error con su solicitud.<br>Los nombres de Áreas y Unidades solo puede tener letras, tildes y espacios.',
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