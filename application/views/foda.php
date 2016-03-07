<!doctype html>
<html class="fixed sidebar-left-collapsed">
<head>
    <?php
    //Para usar head.php debe ser dentro del tag head y debe haberse creado una variable $title.
    include 'partials/head.php'; ?>

    <style type="text/css">

        .titulo{
            font-size: 15px;
            padding-bottom: 20px;
            padding-top: 10px;
        }

        .ticket{
            color: lightgrey;
            text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;
        }

        #fodaForm{
            margin-top: 2%;
        }

        form label, td label, .description{
            font-size: 1.2em;
        }

        .description{
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .Element{
            margin-top: 2%;
        }
    </style>

    <script type="text/javascript">

        var element = 0;

        var fodas = <?php echo json_encode($fodas); ?>;
        var priorities = <?php echo json_encode($priorities); ?>;
        var types = <?php echo json_encode($types); ?>;
        var greenTicket = -1;

        var prioritiesHTML = "";
        for(var i = 0; i<priorities.length; i++){
            prioritiesHTML += '<option value="'+ priorities[i].id +'">' + priorities[i].name + '</option>'
        }
        var typesHTML = "";
        for(var i = 0; i<types.length; i++){
            typesHTML += '<option value="'+ types[i].id +'">' + types[i].name + '</option>'
        }
        function changeColor(id){
            if (greenTicket!=id){
                if (greenTicket!=-1){
                    $('#ticket'+greenTicket).css('color', 'lightgrey');
                }
                greenTicket = id;
                $('#org').val(greenTicket);
                $('#ticket'+id).css('color', 'forestgreen');
            }
            else{
                $('#ticket'+id).css('color', 'lightgrey');
                $('#org').val(null);
                greenTicket = -1;
            }
            $('#year').trigger('change');
        }

        function loadItems(itemsByType, itemsByPriority){
            var html = "";
            var active = true;
            var priorClass = ['default', 'danger', 'warning', 'success'];
            for(var i = 0; i<itemsByType.length; i++) {
                extra = "";
                if (active) {
                    extra = 'active';
                    active = false;
                }
                html += '<div id="' + types[i].name.toLowerCase() + '" class="scrollable-content tab-pane ' + extra + '" tabindex="0" style="padding: 15px; right: -15px;"> \
                    <div class="table-responsive"> \
                    <table class="table mb-none"> \
                    <tbody>';
                var first = true;
                for (var j = 0; j < itemsByType[i].length; j++) {
                    var extra = "";
                    if (first) {
                        extra = 'style="border-top: 0px;"';
                        first = false;
                    }
                    var descrip = "No hay una descripción disponible";
                    var comment = "No hay un comentario disponible";
                    if (itemsByType[i][j].description != ""){
                        descrip = itemsByType[i][j].description;
                    }
                    if (itemsByType[i][j].comment != ""){
                        comment = itemsByType[i][j].comment;
                    }
                    html += '<tr> \
                        <td ' + extra + '>' + (j + 1) + '</td> \
                        <td ' + extra + '> \
                        <label>Prioridad: </label>  <label class="text-' + priorClass[itemsByType[i][j].priority] + '">' + priorities[itemsByType[i][j].priority-1].name + '</label> \
                        <p class="description">' + descrip + '</p> \
                    <label>Comentario:</label> <p style="display: inline">' + comment + '</p> \
                    </td> \
                    </tr>';
                }
                html += "</tbody> \
                    </table> \
                    </div> \
                    </div>";
            }
            var counter = 1;
            var first = true;
            for(var i = 0; i<itemsByPriority.length; i++){
                html += '<div id="prioridad" class="scrollable-content tab-pane" tabindex="0" style="padding: 15px; right: -15px;"> \
                    <div class="table-responsive"> \
                    <table class="table mb-none"> \
                    <tbody>';
                for (var j = 0; j < itemsByPriority[i].length; j++) {
                    var extra = "";
                    if (first) {
                        extra = 'style="border-top: 0px;"';
                        first = false;
                    }
                    var descrip = "No hay una descripción disponible";
                    var comment = "No hay un comentario disponible";
                    if (itemsByPriority[i][j].description != "") {
                        descrip = itemsByPriority[i][j].description;
                    }
                    if (itemsByPriority[i][j].comment != "") {
                        comment = itemsByPriority[i][j].comment;
                    }
                    html += '<tr> \
                        <td ' + extra + '>' + counter + '</td> \
                        <td ' + extra + '> \
                            <label>Prioridad: </label>  <label class="text-' + priorClass[i+1] + '">' + priorities[i].name + '</label> \
                            <p class="description">' + descrip + '</p> \
                            <label>Comentario:</label> <p style="display: inline">' + comment + '</p> \
                        </td> \
                    </tr>';
                    counter++;
                }
            }
            $('#itemContent').html(html);
        }

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
            ['url'=>'cmetrica', 'name'=>'Configurar Métricas', 'icon'=>'fa fa-server'],
            ['url'=>'cdashboardUnidad', 'name'=>'Configurar Dashboard', 'icon'=>'fa fa-bar-chart'],
            ['url'=>'foda/config', 'name'=>'Configurar FODAs', 'icon'=>'fa fa-pencil']];
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
                <div class="panel-body">
                    <?php
                    $c = 0;
                    foreach ($departments as $dpto){
                        $c++;
                        $counter = 0;
                        $kind = $dpto['type']['name'];
                        $color = $dpto['type']['color'];
                        if($kind=="Operación"){
                            $color_button = "btn-warning";
                        }
                        else{
                            $color_button = "btn-success";
                        }
                        ?>
                        <section class="panel col-md-6">
                            <h2 style="text-align:center;"><?php echo(ucwords($kind));?></h2>
                            <hr>

                            <header class="panel panel-heading" style="background-color: transparent">
                                <div class="row" onclick="changeColor(<?php echo($dpto['department']->getId())?>)">
                                    <div class="btn btn-block btn-primary panel-body">
                                        <h2 class="panel-title">
                                            <div class="btn-group-horizontal text-center">
                                                <label style="color:white" class="text-center">DCC</label>
                                                <i class="ticket pull-right glyphicon glyphicon-ok" id="<?php echo('ticket'.$dpto['department']->getId())?>"></i>
                                            </div>
                                        </h2>
                                    </div>
                                </div>
                            </header>

                            <?php
                            foreach ($dpto['areas'] as $area){
                                if ($counter % 2 == 0 && $counter!=0)
                                    echo ('</div>');
                                if ($counter % 2 == 0)
                                    echo ('<div class ="row">');
                                ?>
                                <div class="col-md-6">
                                    <section class="panel panel-info">
                                        <header class="btn btn-block panel-heading" style="background-color: <?php echo($color);?>" onclick="changeColor(<?php echo($area['area']->getId())?>)">
                                            <h2 class="<?php echo($color_button)?> panel-title text-center">
                                                <div class="btn-group-horizontal">
                                                    <label><?php echo(ucwords($area['area']->getName()));?></label>
                                                    <i class="ticket pull-right glyphicon glyphicon-ok" id="<?php echo('ticket'.$area['area']->getId())?>"></i>
                                                </div>
                                            </h2>
                                        </header>
                                        <div class="panel-body">
                                            <div class="btn-group-vertical col-md-12">
                                                <?php
                                                foreach ($area['unidades'] as $unidad){
                                                    ?>
                                                    <div class="btn btn-default text-center" onclick="changeColor(<?php echo($unidad->getId())?>)">
                                                        <label><?php echo(ucwords($unidad->getName()));?></label>
                                                        <i class="ticket pull-right glyphicon glyphicon-ok" id="<?php echo('ticket'.$unidad->getId())?>"></i>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                                <?php
                                $counter++;
                                if($counter==count($dpto['areas'])){
                                    echo ('</div>');
                                }
                            }
                            ?>
                        </section>
                        <?php
                    }
                    ?>
                </div>
            </section>
            <form id="fodaForm" class="form-horizontal mb-lg " action="<?php echo base_url();?>foda/add" method="post">
                <input type="hidden" id="org" name="org">
                <div class="form-group">
                    <label for="year" class="col-sm-2 control-label">Año:</label>
                    <div class="col-sm-1">
                        <select class="form-control" id="year" name="year">
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="fodaComment" class="col-sm-2 control-label">Comentario:</label>
                    <div class="col-sm-2">
                        <textarea class="form-control" id="fodaComment" name="fodaComment" rows="4"></textarea>
                    </div>
                </div>
            </form>
            <div id="itemSection" class="col-md-9" hidden>
                <div class="tabs tabs-primary">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a id="fortLink" href="#fortalezas" data-toggle="tab" aria-expanded="true"><i class="glyphicon glyphicon-thumbs-up"></i> Fortalezas</a>
                        </li>
                        <li class="">
                            <a href="#oportunidades" data-toggle="tab" aria-expanded="false"><i class="fa fa-star"></i> Oportunidades</a>
                        </li>
                        <li class="">
                            <a href="#debilidades" data-toggle="tab" aria-expanded="false"><i class="glyphicon glyphicon-thumbs-down"></i> Debilidades</a>
                        </li>
                        <li class="">
                            <a href="#amenazas" data-toggle="tab" aria-expanded="false"><i class="glyphicon glyphicon-warning-sign"></i> Amenazas</a>
                        </li>
                        <li class="">
                            <a href="#prioridad" data-toggle="tab" aria-expanded="false"><i class="glyphicon glyphicon-flag"></i> Más Importantes</a>
                        </li>
                    </ul>
                    <div id="itemContent" class="tab-content scrollable has-scrollbar" data-plugin-scrollable="" style="height: 350px;">
                    </div>
                </div>
            </div>
        </section>
    </div>
</section>

<?php include 'partials/footer.php'; ?>

<script type="text/javascript">
    //agrega comentario que exista previamente en la base de datos
    $("#year").change(function(e){
        if (greenTicket==-1 || fodas[greenTicket] === undefined || fodas[greenTicket][this.value] === undefined ) {
            $("#fodaComment").val("");
            $('#itemSection').hide();
            return;
        }
        $("#fodaComment").val(fodas[greenTicket][this.value].comment);
        $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>foda/items",
            data: {id: $('#org').val(),
                   year: $('#year').val()},
            dataType: "json",
            cache:false,
            success:
                function(data){
                    $('#itemSection').show();
                    console.log(data['itemsByPriority']);
                    loadItems(data['itemsByType'], data['itemsByPriority']);
                },
            error:
                function(xhr, textStatus, errorThrown){
                    $('#itemSection').hide();
                }
        });
    });



    //Carga de los años
    for(var i = new Date().getFullYear()+1; i>=2000; i--){
        if (i==new Date().getFullYear()){
            $('#year').append('<option selected>' + i + '</option>');
            continue;
        }
        $('#year').append('<option>' + i + '</option>');
    }
</script>
</body>
</html>
