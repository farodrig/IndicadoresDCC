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
        .icon{
            color: lightgrey;
            text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;
        }
    </style>

    <script type="text/javascript">

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
                <h2>Presupuestos de la Organización</h2>

                <div class="right-wrapper pull-right">
                    <ol class="breadcrumbs">
                        <li>
                            <a href="<?php echo base_url();?>inicio">
                                <i class="fa fa-home"></i>
                            </a>
                        </li>
                        <li><span>Ver</span></li>
                        <li><span>Presupuesto</span></li>
                    </ol>

                    <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>

                </div>
            </header>

            <!-- start: page -->
            <section class="panel panel-transparent">
                <div class="panel-body">
                    <div class="form-group col-md-offset-5">
                        <div class="col-md-1 text-center">
                            <label class="control-label title">Año:</label>
                        </div>
                        <div class="col-md-1">
                            <select id="year" name="year" data-placeholder="Seleccione año..." class="chosen-select" style="width:200px;" onchange ="reloadIcons(); validate_year('year')" tabindex="4">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
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
                                <div class="row" onclick="">
                                    <div class="btn btn-block btn-primary panel-body">
                                        <h2 class="panel-title">
                                            <div class="btn-group-horizontal text-center">
                                                <label style="color:white" class="text-center">DCC</label>
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
                                        <header class="btn btn-block panel-heading modal-basic" style="background-color: <?php echo($color);?>" href="#modalDefault" id="<?php echo('modal'.$area['area']->getId()) ?>" onclick="openModal(<?php echo($area['area']->getId())?>);">
                                            <h2 class="<?php echo($color_button)?> panel-title text-center">
                                                <div class="btn-group-horizontal">
                                                    <label><?php echo(ucwords($area['area']->getName()));?></label>
                                                    <?php if(in_array($area['area']->getId(), $orgs)){ ?>
                                                    <i class="icon pull-right fa fa-question" id="<?php echo('icon'.$area['area']->getId())?>"></i>
                                                    <?php } ?>
                                                </div>
                                            </h2>
                                        </header>
                                        <div class="panel-body">
                                            <div class="btn-group-vertical col-md-12">
                                                <?php
                                                foreach ($area['unidades'] as $unidad){
                                                    if(in_array($unidad->getId(), $orgs)) { ?>
                                                        <div class="btn btn-default text-center modal-basic" id="<?php echo('modal' . $unidad->getId()) ?>" href="#modalDefault" onclick="openModal(<?php echo($unidad->getId()) ?>);">
                                                            <label><?php echo(ucwords($unidad->getName())); ?></label>
                                                            <i class="icon pull-right fa fa-question" id="<?php echo('icon' . $unidad->getId()) ?>"></i>
                                                        </div>
                                                    <?php
                                                    }
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
                <div class="modal-block modal-header-color modal-block-success mfp-hide" id="modalSuccess">
                </div>
                <div class="modal-block modal-header-color modal-block-warning mfp-hide" id="modalWarning">
                </div>
                <div class="modal-block modal-header-color modal-block-danger mfp-hide" id="modalDanger">
                </div>
                <div class="modal-block modal-header-color modal-block-primary mfp-hide" id="modalDefault">
                    <section class="panel">
                        <header class="panel-heading">
                            <h2 class="panel-title">Permisos Insuficientes.</h2>
                        </header>
                        <div class="panel-body">
                            <div class="modal-wrapper">
                                <div class="modal-icon">
                                    <i class="fa fa-question-circle" style="color: lightgrey"></i>
                                </div>
                                <div class="modal-text">
                                    <h4>Atención</h4>
                                    <p>Usted no tiene los permisos suficientes para visualizar este presupuesto o este no tiene datos para el año seleccionado.</p>
                                    <p>Dirijase al administrador para que habilite los permisos correspondientes o cargue la información necesaria.</p>
                                </div>
                            </div>
                        </div>
                        <footer class="panel-footer">
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button class="btn btn-default modal-dismiss">Ok</button>
                                </div>
                            </div>
                        </footer>
                    </section>
                </div>
            </section>
        </section>
    </div>
</section>

<?php include 'partials/footer.php'; ?>

<script src="<?php echo base_url();?>chosen/chosen.jquery.js" type="text/javascript"></script>
<script src="http://bernii.github.io/gauge.js/dist/gauge.min.js" type="text/javascript"></script>

<script type="text/javascript">
    //cargar años
    var datos = <?php echo json_encode($data);?>;
    var years = <?php echo json_encode($years);?>;
    for(var i = 0; i<years.length; i++){
        $('#year').append('<option>' + years[i] + '</option>');
    }

    var config = {
        '.chosen-select'           : {},
        '.chosen-select-deselect'  : {allow_single_deselect:true},
        '.chosen-select-no-single' : {disable_search_threshold:10},
        '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
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
            reloadIcons();
        }
    });

    function reloadIcons(){
        var year = $('#year').val();
        for (org_id in datos){
            modal = $('#modal'+org_id);
            icon = $('#icon'+org_id);
            icon.removeClass("fa-question");
            icon.removeClass("fa-exclamation-triangle");
            icon.removeClass("fa-thumbs-up");
            if(!datos[org_id]) {
                modal.attr("href", "#modalDefault");
                icon.addClass("fa-question");
                icon.css('color', 'lightgrey');
                continue;
            }
            cont = 0;
            for(var i = 0; i<datos[org_id].length; i++){
                if(year != datos[org_id][i].year)
                    continue;
                cont++;

                value =  parseInt(datos[org_id][i].value);
                threshold = 1000000;
                modal.addClass("modal-basic");
                if(value>threshold){
                    modal.attr("href", "#modalSuccess");
                    icon.css('color', 'forestgreen');
                    icon.addClass("fa-thumbs-up");
                }
                else if(value>0){
                    modal.attr("href", "#modalWarning");
                    icon.css('color', 'yellow');
                    icon.addClass("fa-exclamation-triangle");
                }
                else{
                    modal.attr("href", "#modalDanger");
                    icon.css('color', 'red');
                    icon.addClass("fa-exclamation-triangle");
                }
            }
            if (cont==0){
                modal.attr("href", "#modalDefault");
                icon.addClass("fa-question");
                icon.css('color', 'lightgrey');
            }
        }
    }

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

    function getValue(id, year){
        for(var i = 0; i<datos[id].length; i++){
            if(year != datos[id][i].year)
                continue;
            return datos[id][i].value;
        }
        return 0;
    }

    var opts = {
        lines: 12, // The number of lines to draw
        angle: 0.15, // The length of each line
        lineWidth: 0.44, // The line thickness
        pointer: {
            length: 0.9, // The radius of the inner circle
            strokeWidth: 0.035, // The rotation offset
            color: '#000000' // Fill color
        },
        limitMax: 'true',   // If true, the pointer will not go past the end of the gauge
        colorStart: '#d9534f',   // Colors
        colorStop: '#f0ad4e',    // just experiment with them
        strokeColor: '#E0E0E0',   // to see which ones work best for you
        generateGradient: true
    };

    function openModal(org_id){
        var href = $('#modal'+org_id).attr('href');
        var value = getValue(org_id, $('#year').val());
        var money = parseInt(value).toFixed(2).replace(/./g, function(c, i, a) {
            return i && c !== "." && ((a.length - i) % 3 === 0) ? ',' + c : c;
        });
        money = money.substring(0, money.length - 3).split(',').join('.');
        if(href=="#modalSuccess"){
            var title = "Excelente!";
            var text_title = "Muy Bien!";
            var text_content = "<p>El presupuesto actual es de: </p>";
            var button_type = "success";
            opts.colorStop = "#5cb85c";
        }
        else if (href=="#modalWarning"){
            var title = "Cuidado!";
            var text_title = "El presupuesto se acerca a un valor negativo";
            var text_content = "<p>El presupuesto actual es de: </p>";
            var button_type = "warning";
            opts.colorStop = "#f0ad4e";
        }
        else if (href=="#modalDanger"){
            var title = "Advertencia!";
            var text_title = "El presupuesto es negativo";
            var text_content = "<p>El presupuesto actual es de: </p>";
            var button_type = "danger";
            opts.colorStop = "#d9534f";
            var money = parseInt(value.substring(1)).toFixed(2).replace(/./g, function(c, i, a) {
                return i && c !== "." && ((a.length - i) % 3 === 0) ? ',' + c : c;
            });
            money = money.substring(0, money.length - 3).split(',').join('.');
            money = "-"+money;
        }
        else
            return;
        $(href).html(modalContent(href.substring(1), title, money, value, text_title, text_content, button_type));
        var target = document.getElementById('gauge_canvas'); // your canvas element
        var gauge = new Gauge(target).setOptions(opts); // create sexy gauge!
        gauge.maxValue =  Math.max(2000000, value);// set max gauge value
        gauge.animationSpeed = 32; // set animation speed (32 is default value)
        gauge.set(parseInt(value)); // set actual value
    }

    function deleteContent(modal){
        $('#'+modal).html("");
    }

    function modalContent(modal_id, title, money ,value, text_title, text_content, button_type){
        var year = $('#year').val();
        var html = '<section class="panel"> \
                        <form method="post" action=""> \
                        <header class="panel-heading"> \
                            <h2 class="panel-title">' + title + '</h2>\
                        </header> \
                        <div class="panel-body" style="display: block;"> \
                            <div class="col-md-4"> \
                                <div class="gauge row text-center"> \
                                    <canvas id="gauge_canvas" height="110" width="140"></canvas> \
                                </div> \
                                <div class="row text-center"> \
                                    <label class="title"><strong>$' + money + '</strong></label> \
                                </div> \
                            </div> \
                            <div class="modal-text"> \
                                <h4>' + text_title + '</h4>\
                                ' + text_content + ' \
                                <input name="budget" type="number" value="' + value + '"> \
                                <input name="year" type="hidden" value="' + year + '" <br>\
                                <p>Si quiere actualizar el presupuesto, cambie el valor y aprete guardar.</p> \
                            </div> \
                            <div class="row"> \
                                <div class="col-md-12 text-right"> \
                                    <input type="submit" class="btn btn-' + button_type + '" value="Guardar" onclick="deleteContent(\'' + modal_id +'\')"> \
                                    <button class="btn btn-default modal-dismiss" onclick="deleteContent(\'' + modal_id +'\')">Cancelar</button> \
                                </div> \
                            </div> \
                         </div> \
                        </form> \
                    </section>';
        return html;
    }
</script>
</body>
</html>
