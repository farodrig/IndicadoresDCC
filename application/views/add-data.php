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
    <script type="text/javascript">
        var success = <?php echo ($success);?>;
        var jArray= <?php echo json_encode($measurements);?>;
        var metrics = <?php echo json_encode($metrics);?>;
        var years = [];
        var xValues = [];
        for(metorg in jArray){
            for(year in jArray[metorg]){
                if ( years.indexOf(year) == -1){
                    years.push(year);
                }
                for(i in jArray[metorg][year]){
                    if (jArray[metorg][year][i].valueX && xValues.indexOf(jArray[metorg][year][i].valueX) == -1){
                        xValues.push(jArray[metorg][year][i].valueX);
                    }
                }
            }
        }
        years.sort();
    </script>
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
                        $orgName = "";
                        for ($i = sizeof($route); $i > 0; $i--) {
                            if (sizeof($route)-1==$i)
                                $orgName = $route[$i];
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
                    <?php echo form_open('agregarDato');?>
                    <section class="panel form-horizontal form-bordered">
                        <header class="panel-heading">

                            <h2 class="panel-title">Añadir y Borrar Datos - <?php echo (ucwords($orgName));?></h2>

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
                                    <section class="toggle active" >
                                        <label class="text-left"><?php echo (ucwords($metric->name));?></label >
                                        <div id="content<?php echo ($metric->metorg);?>" class="toggle-content" style = "display: block;" >
                                            <div class="table-responsive">
                                                <table id="datatable-details" class="table table-bordered table-striped mb-none dataTable no-footer" role="grid">
                                                    <thead>
                                                        <tr role="row">
                                                            <?php if ($metric->x_name){ ?>
                                                            <th class="text-center"><?php echo (ucwords($metric->x_name));?> [<?php echo (ucwords($metric->x_unit));?>]</th>
                                                            <?php } ?>
                                                            <th class="text-center"><?php echo (ucwords($metric->y_name));?> [<?php echo (ucwords($metric->y_unit));?>]</th>
                                                            <th class="text-center">Esperado</th>
                                                            <th class="text-center">Meta</th>
                                                            <th class="text-center">Borrar</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tableContent<?php echo ($metric->metorg);?>">
                                                    </tbody>
                                                </table>
                                            </div>
                                            <?php if($metric->x_name){?>
                                                <button type="button" onclick="addRow(<?php echo ($metric->metorg);?>)" class="btn btn-success pull-right fa fa-plus" style="margin-bottom: 1%"></button>
                                            <?php } ?>
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

<script src="<?php echo base_url();?>chosen/chosen.jquery.js" type="text/javascript"></script>

<script type="text/javascript">
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


    $('#year').on('chosen:no_results', function(e,params) {
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

    function deleteRow(a) {
        var $this = $(a);
        row = $this.closest('tr');
        row.remove();
    }

    function rowWithData(metorg, valId, valueY, valueX, target, expected, hasX, eliminable){
        delButton = '';
        del = "";
        tdX = "";
        if(eliminable) {
            delButton = '<a class="btn cancel-row row" onclick="deleteRow(this)"><i class="fa fa-times"></i></a>';
            del = 'hidden';
        }
        else{
            delButton = '<input type="checkbox" value=' + valId + ' name="delete[]" class="form-control">';
        }
        if(hasX)
            tdX = '<td><input type="text" name="valueX[]" value="' + valueX + '" class="form-control"></td>';
        else
            tdX = '<input type="hidden" name="valueX[]" value="" class="form-control">';
        row = '<tr> \
                    <input type="hidden" name="valId[]" value="' + valId + '">    \
                    <input type="hidden" name="metorg[]" value="' + metorg + '">' + tdX + ' \
                    <td><input type="text" name="valueY[]" value="' + valueY + '" class="form-control" onkeyup ="validate(this)" onfocus ="validate(this)"></td>\
                    <td><input type="text" name="expected[]" value="' + expected + '" class="form-control" onkeyup ="validate(this)" onfocus ="validate(this)"></td> \
                    <td><input type="text" name="target[]" value="' + target + '" class="form-control" onkeyup ="validate(this)" onfocus ="validate(this)"></div> \
                    <td class="text-center">' + delButton + '</td> \
               </tr>';
        return row;
    }

    function loadMetrics(){
        for(var i in metrics) {
            var metorg = metrics[i].metorg;
            $('#tableContent' + metorg).html("");
            var cont = 0;
            for (var year in jArray[metorg]) {
                if (year != $('#year').val())
                    continue;
                cont++;
                for (var xVal in jArray[metorg][year]) {
                    var row = rowWithData(metorg, jArray[metorg][year][xVal]['id'], jArray[metorg][year][xVal]['valueY'], jArray[metorg][year][xVal]['valueX'], jArray[metorg][year][xVal]['target'], jArray[metorg][year][xVal]['expected'], metrics[i].x_name,false);
                    $('#tableContent' + metorg).append(row);
                }
            }
            if (cont==0){
                var row = rowWithData(metorg, "", "", "", "", "", metrics[i].x_name, false);
                $('#tableContent' + metorg).append(row);
            }
        }
    }

    function addRow(metorg){
        var hasX="";
        for(var i in metrics) {
            if(metorg == metrics[i].metorg){
                hasX = metrics[i].x_name;
                break;
            }
        }
        row = rowWithData(metorg, "", "", "", "", "", hasX, true);
        $('#tableContent' + metorg).append(row);
    }

    function selectYear(){
        var year = document.getElementById("year").value;
        $('#metrics').show();
        loadMetrics();
    }

    function validate(elem){
        var value = elem.value;
        return changeOnValidation(elem, ((!isNaN(parseFloat(value)) && isFinite(value)) || value.length ==0));
    }

    function validate_year(id){
        var value = document.getElementById(id).value;
        return changeOnValidation(document.getElementById(id), ((!isNaN(parseFloat(value)) && isFinite(value)) && value.length ==4 && value>=1980));
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

    function getValueById(id) {
        for(metorg in jArray){
            for(year in jArray[metorg]){
                for(i in jArray[metorg][year]){
                    if (jArray[metorg][year][i].id == id){
                        return jArray[metorg][year][i];
                    }
                }
            }
        }
        return false;
    }

    function pageValidate(){
        return true;
    }

    $('form').on('submit', function(e){
        year = $('#year').val();
        var result = true;
        var x_aux = xValues;
        var newX = [];
        for(metorg in jArray){
            $('#tableContent'+metorg + ' input[name="valueX[]"]:visible').each(function () {
                var $this = $(this);
                id = $this.closest('tr').children('input[name="valId[]"]').val();
                value = getValueById(id);
                if(value && value.valueX != this.value && x_aux.indexOf(this.value) != -1){
                    alert("No se pueden tener 2 elementos con el mismo valor de X. Añada un nuevo elemento y elimine este para obtener el mismo resultado.");
                    result = false;
                    this.value = value.valueX;
                    return false;
                }
                else if(!value && newX.indexOf(this.value) != -1){
                    alert("No se pueden tener 2 elementos con el mismo valor de X para en año. Modifique el elemento con el mismo nombre.");
                    result = false;
                    return false;
                }
                if(x_aux.indexOf(this.value) == -1)
                    x_aux.push(this.value);
                newX.push(this.value);
            });
        }
        return result;
    });
</script>
</body>
</html>
