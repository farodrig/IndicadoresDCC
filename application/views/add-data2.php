<!doctype html>
<html class="fixed sidebar-left-collapsed">
<head>
    <?php
    $title = "Añadir datos";
    include 'partials/head.php';
    ?>
    <link rel="stylesheet" href="<?php echo base_url();?>chosen/chosen.css">
    <style type="text/css">
        section label{
            text-align: center;
        }
    </style>
</head>
<body>
<section class="body">

    <?php include 'partials/header_tmpl.php';?>

    <div class="inner-wrapper">
        <!-- start: sidebar -->
        <?php
        $navData=[['url'=>'inicio', 'name'=>'U-Dashboard', 'icon'=>'fa fa-home'],
            ['url'=>'dashboard', 'name'=>'Volver al Dashboard', 'icon'=>'fa fa-line-chart']];
        include 'partials/navigation.php';
        ?>
        <!-- end: sidebar -->

        <section role="main" class="content-body">
            <header class="page-header">
                <h2>Añadir y Borrar Datos</h2>

                <div class="right-wrapper pull-right">
                    <ol class="breadcrumbs">
                        <li>
                            <a href="<?php echo base_url();?>inicio">
                                <i class="fa fa-home"></i>
                            </a>
                        </li>
                        <?php
                        for ($i = sizeof($route); $i > 0; $i--) {
                            echo "<li><span>".$route[$i]."</span></li>";
                        }

                        ?>
                        <li><span>Añadir Datos</span></li>
                    </ol>
                    <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                </div>
            </header>

            <!-- start: page -->
            <div class="col-md-12">
                <?php if(sizeof($metrics)==0){ ?>
                    <h2> No hay métricas en el sistema </h2>
                <?php }
                else{ ?>
                    <?php echo form_open('agregarDato', array('onSubmit' => "return pageValidate();"));?>
                    <section class="panel form-horizontal form-bordered">
                        <header class="panel-heading">

                            <h2 class="panel-title">Añadir y Borrar Datos</h2>

                            <p class="panel-subtitle">
                                Deje en blanco campos correspondientes a métricas que no desea considerar. Para modificar datos existentes elija un año
                                del menú desplegable y reemplace los datos mostrados.
                            </p>
                        </header>
                        <div class="panel-body">
                            <div class="form-group">
                                <div class="col-md-2 text-center">
                                    <label class="control-label">Año:</label>
                                </div>
                                <div class="col-md-2">
                                    <select id="year" name="year" data-placeholder="Seleccione año..." class="chosen-select" style="width:200px;" onchange ="selectYear(); validate_year('year')" tabindex="4">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div id="metrics" data-plugin-toggle="" class="toggle" hidden>
                                <?php
                                echo ('<input type="hidden" name="id_location" id="id_location" value='.$id_location.'>');
                                foreach ($metrics as $metric) { ?>
                                    <section class="toggle" >
                                        <label class="text-left"><?php echo (ucwords($metric->y_name));?> V/S <?php echo (ucwords($metric->x_name));?></label >
                                        <div id="content<?php echo ($metric->metorg);?>" class="toggle-content" style = "display: none;" >
                                        </div >
                                    </section >
                                <?php } ?>
                            </div>
                        </div>
                        <footer class="panel-footer">
                            <input type="submit" class="btn btn-primary" value="Añadir" id="anadir" name="anadir">
                            <label>&nbsp;&nbsp;</label>
                            <input type="submit" class="btn btn-danger" value="Borrar seleccionados" id="borrar" name="borrar">
                        </footer>
                    </section>
                    <?php echo form_close();?>
                <?php } ?>
            </div>


            <!-- end: page -->
        </section>
    </div>
</section>

<?php include 'partials/footer.php';?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>chosen/chosen.jquery.js" type="text/javascript"></script>

<script type="text/javascript">
    var success = <?php echo ($success);?>;
    var jArray= <?php echo json_encode($measurements);?>;
    var metrics = <?php echo json_encode($metrics);?>;
    var years = [];
    for(var metorg in jArray){
        for(var year in jArray[metorg]){
            if ( years.indexOf(year) == -1){
                years.push(year);
            }
        }
    }

    //cargar años
    for(var i = 0; i<years.length; i++){
        $('#year').append('<option>' + years[i] + '</option>');
    }

    if (success==1){
        new PNotify({
            title: 'Éxito!',
            text: 'Su solicitud ha sido realizada con éxito. Recuerde que, dependiendo de su rol, una validación será necesaria antes de que su cambio sea visible',
            type: 'success'
        });
    }
    if (success==0){
        new PNotify({
            title: 'error!',
            text: 'Ha ocurrido un error con su solicitud.<br>Revise que la información de los campos sea correcta.<br>Si el problema persiste, intente de nuevo más tarde.',
            type: 'error'
        });
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


    $('#year').live('chosen:no_results', function(e,params) {
        var value = $('.chosen-search > input:nth-child(1)').val();
        if(value.length==4 && (!isNaN(parseFloat(value)) && isFinite(value))){
            $('#year').append($("<option>" , {
                text: value,
                value: value
            }));
            $('#year option[value="'.concat(value,'"]')).attr("selected", "selected");
            $('#year').trigger('chosen:updated');
            selectYear();
        }
    });

    function rowWithData(metorg, valueY, valueX, target, expected){
        row = '<div class="row mb-md form-group"> \
                    <div class="col-md-2">\
                        <label class="control-label">Valor Y: </label> \
                        <input type="text" name="valueY' + metorg + '[]" value="' + valueY + '" class="form-control" onkeyup ="validate(this)" onfocus ="validate(this)"> \
                    </div>\
                    <div class="col-md-2">\
                        <label class="control-label">Valor X: </label>\
                        <input type="text" name="valueX' + metorg + '[]" value="' + valueX + '" class="form-control" onkeyup ="validate(this)" onfocus ="validate(this)"> \
                    </div> \
                    <div class="col-md-2"> \
                        <label class="control-label">Esperado: </label> \
                        <input type="text" name="target' + metorg + '[]" value="' + target + '" class="form-control" onkeyup ="validate(this)" onfocus ="validate(this)"> \
                    </div> \
                    <div class="col-md-2"> \
                        <label class="control-label">Meta</label> \
                        <input type="text" name="expected' + metorg + '[]" value="' + expected + '" class="form-control" onkeyup ="validate(this)" onfocus ="validate(this)"> \
                    </div> \
                    <div class="col-md-2 text-center"> \
                        <label class="control-label">Borrar</label> \
                        <input type="checkbox" disabled value=1 name="borrar' + metorg + '[]" class="form-control"> \
                    </div> \
                </div>';
        return row;
    }

    function loadMetrics(){
        for(var i in metrics) {
            var metorg = metrics[i].metorg;
            $('#content' + metorg).html("");
            var cont = 0;
            for (var year in jArray[metorg]) {
                if (year != $('#year').val())
                    continue;
                cont++;
                for (var xVal in jArray[metorg][year]) {
                    var row = rowWithData(metorg, jArray[metorg][year][xVal]['valueY'], jArray[metorg][year][xVal]['valueX'], jArray[metorg][year][xVal]['target'], jArray[metorg][year][xVal]['expected']);
                    $('#content' + metorg).append(row);
                }
            }
            var row = rowWithData(metorg, "", "", "", "");
            $('#content' + metorg).append(row);
        }
    }

    function selectYear(){
        var year = document.getElementById("year").value;
        $('#metrics').show();
        loadMetrics();
    }

    function validate(elem){
        var opt = elem.value;
        return changeOnValidation(elem, ((!isNaN(parseFloat(opt)) && isFinite(opt)) || opt.length ==0));
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

    function pageValidate(){
        return true;
    }
</script>
</body>
</html>
