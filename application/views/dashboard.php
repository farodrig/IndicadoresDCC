<html class="fixed sidebar-left-collapsed">
<head>
    <?php
    $title = "Dashboard";
    include 'partials/head.php';
    ?>
    <link rel="stylesheet" href="<?php echo base_url();?>assets/vendor/select2/select2.css" />
    <link rel="stylesheet" href="<?php echo base_url();?>assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
    <link rel="stylesheet" href="<?php echo base_url();?>assets/vendor/bootstrap-colorpicker/css/bootstrap-colorpicker.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/vendor/morris/morris.css" />
    <link rel="stylesheet" href="<?php echo base_url();?>assets/vendor/chartist/chartist.css" />
    <style type="text/css">
        div.table-responsive div.row {
            margin-left: 0px;
            margin-right: 0px;
        }

        .margin-top{
            margin-top: 2%;
        }

        .margin-left{
            margin-left: 1%;
        }

        .fa-square-o {
            border-radius: 20%;
            height: 0.7em;
            width: 0.75em;
        }
    </style>
    <script type="text/javascript">
        var graphics = <?php echo json_encode($graphics); ?>;
        var tables = [];
    </script>
</head>
<body>
<section class="body">

    <?php include 'partials/header_tmpl.php'; ?>

    <div class="inner-wrapper">
        <!-- start: sidebar -->
        <?php
        $navData=[];
        if($add_data)
            $navData[] = ['url'=>'formAgregarDato?org='.$org, 'name'=>'Modificar Datos', 'icon'=>'fa fa-plus-square'];

        include 'partials/navigation.php';
        ?>
        <!-- end: sidebar -->

        <section role="main" class="content-body">
            <header class="page-header">
                <h2>Dashboard</h2>

                <div class="right-wrapper pull-right">
                    <ol class="breadcrumbs">
                        <li>
                            <a href="<?php echo base_url();?>inicio">
                                <i class="fa fa-home"></i>
                            </a>
                        </li>
                        <?php
                        for($i=sizeof($route);$i>0;$i--)
                            echo "<li><span>".$route[$i]."</span></li>";
                        ?>
                    </ol>
                    <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                </div>
            </header>
            <!-- start: page -->
            <?php
            if($show_button){
                if($show_all){
                    ?>
                    <button class="btn btn-primary" onclick="changePage(0)">Ver gráficos seleccionados</button>
                <?php }
                else { ?>
                    <button class="btn btn-primary" onclick="changePage(1)">Ver todos los gráficos</button>
                <?php } ?>
                <hr>
            <?php } ?>

            <?php if(sizeof($graphics)==0){ ?>
                <h2> No hay gráficos configurados para mostrar. </h2>
            <?php } ?>

            <?php foreach ($graphics as $graphic):
                $title = $graphic->title;
                if($graphic->ver_x){
                    $title .= " Periodo (".$graphic->min_year." - ".$graphic->max_year.")";
                }?>
                <div class='panel margin-top'>
                    <?php echo form_open("export"); ?>
                    <div class='panel-heading'>
                        <input type="hidden" name="graphic" value="<?php echo $graphic->id;?>">
                        <input class="all" " type="hidden" name="all" value="0">
                        <h2 class='panel-title'><?php echo ($title); ?>
                            <button id="exportAll" class="btn btn-primary pull-right margin-left exportAll" type="submit">Exportar Métricas</button>
                            <button class="btn btn-primary pull-right margin-left" type="submit">Exportar Tabla</button>
                        </h2>
                    </div>
                    <div class='panel-body'>
                        <div id="graphic<?php echo $graphic->id;?>" class="col-md-6"></div>
                        <div id="graphicTable_wrapper" class="dataTables_wrapper no-footer col-md-6">
                            <div class="table-responsive">
                                <table id="graphicTable<?php echo $graphic->id;?>" class="table table-bordered table-striped mb-none dataTable no-footer" role="grid" aria-describedby="graphicTable_info">
                                    <thead>
                                        <tr>
                                            <th class="sorting text-center">Serie</th>
                                            <th class="sorting text-center"><?php echo $graphic->x_name;?></th>
                                            <th class="sorting text-center">Valor</th>
                                            <th class="sorting text-center">Esperado</th>
                                            <th class="sorting text-center">Meta</th>
                                        </tr>
                                    </thead>
                                    <tbody id="graphicTableContent<?php echo $graphic->id;?>">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            <?php endforeach; ?>
            <!-- end: page -->
        </section>
    </div>

</section>

<?php include 'partials/footer.php'; ?>

<!-- Specific Page Vendor -->
<script src="<?php echo base_url();?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.js"></script>
<script src="<?php echo base_url();?>assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
<script src="<?php echo base_url();?>assets/vendor/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>

<script src="<?php echo base_url();?>assets/vendor/highcharts/js/highcharts.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/vendor/highcharts/js/modules/exporting.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>js/functions.js"></script>


<script type="text/javascript">
    
    $('.exportAll').on('click', function () {
        $('.all').val("1");
    });

    $(window).resize( function () {
        for(var i in tables){
            var table = tables[i];
            table.columns.adjust();
        }
    } );

    for(var i in graphics){
        var graphic = graphics[i];
        loadGraphic(graphic);
        tables.push(loadTable(graphic.id, graphic.series));
    }
    
    function changePage(all) {
        window.location.href = "<?php echo base_url();?>dashboard?org=<?php echo $org;?>&all=" + all;
    }

    function loadGraphic(graphic) {
        var data = createGraphicData(graphic.series, graphic.y_unit);
        var options = getGraphicOptions('', graphic.x_name, graphic.x_values, graphic.y_name, graphic.y_unit, data);
        $('#graphic' + graphic.id).highcharts(options);
    }

    function loadTable(graphic, series) {
        var html = "";
        var c = series.length;
        var first = 1;
        if(c==1){
            $('#graphicTable' + graphic + ' tr').children().first().remove();
            first = 0;
        }
        for(var i in series){
            var serie = series[i];
            var graphSerie = $('#graphic'+graphic).highcharts().get(serie.id);
            for(var j in serie.values){
                var value = serie.values[j];
                if(value.expected===undefined)
                    continue;
                cells = '<tr>';

                if(c!=1){
                    cells += '<td class="text-center"><i class="fa fa-square-o fa-lg" aria-hidden="true" style="background: ' + graphSerie.color + ';"></i></td>';
                }
                cells += '<td>' + value.x + '</td>';
                cells += '<td>' + (value.value === null ? "" : value.value) + '</td>';
                cells += '<td>' + (value.expected === null ? "" : value.expected) + '</td>';
                cells += '<td>' + (value.target === null ? "" : value.target) + '</td>';
                html += cells + "</tr>";
            }
        }
        $('#graphicTable' + graphic).dataTable().fnDestroy();
        $('#graphicTableContent' + graphic).html(html);
        $('.colorpicker-component').colorpicker();
        var datatable;
        var datatableInit = function() {
            var $table = $('#graphicTable' + graphic);

            // initialize
            datatable = $table.DataTable({
                destroy: true,
                aoColumnDefs: [
                    { bSortable: false, aTargets: [ 0 ] }
                ],
                aaSorting: [
                    [first, 'asc']
                ],
                scrollY: 360,
                bFilter: false,
                paging: false,
                bInfo: false
            });
        };
        datatableInit();
        return datatable;
    }
</script>
</body>
</html>
