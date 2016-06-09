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
        .table-responsive {
            overflow-x: visible !important;
            overflow-y: visible !important;
        }
        .redData{
            border: 3px solid red !important;
        }
        .chosen-container {
            width: 200px !important
        }
    </style>
    <script type="text/javascript">
        var success = <?php echo ($success);?>;
        var valid = <?php echo json_encode($valid);?>;
        var values= <?php echo json_encode($no_valid);?>;
        var metrics = <?php echo json_encode($metrics);?>;
        var editP = <?php echo json_encode($editP);?>;
        var editF = <?php echo json_encode($editF);?>;
        var editMetaP = <?php echo json_encode($editMetaP);?>;
        var editMetaF = <?php echo json_encode($editMetaF);?>;
        var years = [];
        var xValues = [];
        for(metorg in values){
            for(year in values[metorg]){
                if ( years.indexOf(year) == -1){
                    years.push(year);
                }
                for(i in values[metorg][year]){
                    if (values[metorg][year][i].x_value && xValues.indexOf(values[metorg][year][i].x_value) == -1){
                        xValues.push(values[metorg][year][i].x_value);
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
        $navData=[['url'=>'dashboard?org='.$org, 'name'=>'Volver al Dashboard', 'icon'=>'fa fa-line-chart']];
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

                            <h2 class="panel-title">Añadir y Borrar Datos - <?php echo (ucwords($route[1]));?></h2>

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
                                <div class="col-md-2 col-md-offset-6">
                                    <button id="expand-collapse" class="expanded btn btn-default" type="button">Expandir Todos</button>
                                </div>
                            </div>
                            <div id="metrics" data-plugin-toggle="" class="toggle" hidden>
                                <input type="hidden" name="org" value="<?php echo ($org);?>">
                                <?php
                                foreach ($metrics as $metric) { ?>
                                    <section class="toggle" id="section<?php echo ($metric->metorg);?>">
                                        <label class="text-left"><?php echo (ucwords($metric->name));?></label >
                                        <div id="content<?php echo ($metric->metorg);?>" class="toggle-content" style = "display: none;" >
                                            <div class="table-responsive">
                                                <table id="datatable-details" class="table table-bordered table-striped" role="grid">
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
                                            <?php if($metric->x_name && (($metric->category==1 && $editMetaP) || ($metric->category==2 && $editMetaF))){?>
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

<script src="<?php echo base_url();?>assets/vendor/select2/select2.js"></script>
<script src="<?php echo base_url();?>chosen/chosen.jquery.js" type="text/javascript"></script>

<script type="text/javascript">
    //cargar años

    var yearElement = $('#year');

    for(var i = 0; i<years.length; i++){
        yearElement.append('<option>' + years[i] + '</option>');
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
    };
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }


    yearElement.on('chosen:no_results', function(e,params) {
        var value = $('.chosen-search > input:nth-child(1)')[0].value;
        if(value.length==4 && (!isNaN(parseFloat(value)) && isFinite(value))){
            yearElement.append($("<option>" , {
                text: value,
                value: value
            }));
            $('#year option[value="'.concat(value,'"]')).attr("selected", "selected");
            yearElement.trigger('chosen:updated');
            selectYear();
        }
    });

    $('#expand-collapse').on('click', function (e) {
        var $this = $(this);
        if ($this.hasClass('expanded')){
            $this.removeClass('expanded');
            $this.addClass('collapsed');
            $this.html('Colapsar Todos');
            $('section.toggle').addClass('active');
            $('section.toggle > div.toggle-content').show();
        }
        else{
            $this.removeClass('collapsed');
            $this.addClass('expanded');
            $this.html('Expandir Todos');
            $('section.toggle').removeClass('active');
            $('section.toggle > div.toggle-content').hide();
        }
    });

    function deleteRow(a) {
        var $this = $(a);
        row = $this.closest('tr');
        row.remove();
    }

    function rowWithData(metorg, valId, valueY, valueX, target, expected, validV, validT, validE, validX, hasX, eliminable, edit, editSensitive){
        var delButton = '';
        var del = "";
        var tdX = "";
        var tdV = "";
        var tdM = "";
        var Vclass = (valueY == validV || valueY==null ? "" : "redData");
        var Tclass = (target == validT || target==null ? "" : "redData");
        var Eclass = (expected == validE || expected==null ? "" : "redData");
        var Xclass = (valueX == validX || valueX==null ? "" : "redData");

        var tool = 'data-toggle="tooltip" data-placement="top" data-container="body"';
        var toolV = (Vclass!="" && validV!=null && validV!="" ? tool + ' title="' + validV + '"' : '');
        var toolT = (Tclass!="" && validT!=null && validT!="" ? tool + ' title="' + validT + '"' : '');
        var toolE = (Eclass!="" && validE!=null && validE!="" ? tool + ' title="' + validE + '"' : '');
        var toolX = (Xclass!="" && validX!=null && validX!="" ? tool + ' title="' + validX + '"' : '');

        valueY = (valueY===null ? (validV == null ? "" : validV ) : valueY);
        target = (target===null ? (validT == null ? "" : validT ) : target);
        expected = (expected===null ? (validE == null ? "" : validE ) : expected);

        if(eliminable) {
            delButton = '<a class="btn cancel-row row" onclick="deleteRow(this)"><i class="fa fa-times"></i></a>';
            del = 'hidden';
        }
        else{
            delButton = '<input type="checkbox" value=' + valId + ' name="delete[]" class="form-control">';
        }
        if (edit){
            tdV = '<td class="' + Vclass +'" ' + toolV + '><input type="text" name="valueY[]" value="' + valueY + '" class="form-control" onkeyup ="validate(this)" onfocus ="validate(this)"></td>';
        }
        else{
            tdV = '<td>' + valueY + '<input type="hidden" name="valueY[]" value="' + null + '"></td>';
        }

        var row = '<tr> \
                    <input type="hidden" name="valId[]" value="' + valId + '"> \
                    <input type="hidden" name="metorg[]" value="' + metorg + '">';
        if(editSensitive){
            tdX = '<td class="' + Xclass +'" ' + toolX + ' ><select name="valueX[]" class="chosen-select valueX" style="width:200px;" data-placeholder="Indique el valor..." tabindex="4"><option value=""></option>' + optionHTML(metrics[metorg].x_values, valueX) + '</select></td>';
            tdM = '<td class="' + Eclass +'" ' + toolE + ' ><input type="text" name="expected[]" value="' + expected + '" class="form-control" onkeyup ="validate(this)" onfocus ="validate(this)"></td> \
                    <td class="' + Tclass +'" ' + toolT + ' ><input type="text" name="target[]" value="' + target + '" class="form-control" onkeyup ="validate(this)" onfocus ="validate(this)"></td>';
        }
        else{
            tdX = '<td>' + valueX + '<input type="hidden" name="valueX[]" value="' + null + '"></td>';
            tdM = '<td>' + expected + '<input type="hidden" name="expected[]" value="' + null + '"></td> \ ' +
                '<td>' + target + '<input type="hidden" name="target[]" value="' + null + '"></td>';
        }
        if(!hasX)
            tdX = '<input type="hidden" name="valueX[]" value="" class="form-control">';

        row += tdX + tdV + tdM + '<td class="text-center">' + delButton + '</td></tr>';
        return row;
    }

    function loadMetrics(){
        for(var i in metrics) {
            var metorg = metrics[i].metorg;
            var table = $('#tableContent' + metorg);
            var section = $('#section' + metorg);
            table.html("");
            section.show();
            var sensitive = false;
            var edit = false;
            if((metrics[i].category==1 && editMetaP) || (metrics[i].category==2 && editMetaF))
                sensitive = true;
            if((metrics[i].category==1 && editP) || (metrics[i].category==2 && editF))
                edit = true;
            var cont = 0;
            for (var year in values[metorg]) {
                if (year != $('#year').val())
                    continue;
                cont++;
                for (var j in values[metorg][year]) {
                    var validV = ((valid[metorg] && valid[metorg][year] && valid[metorg][year][j]) ? valid[metorg][year][j]['value'] : null);
                    var validT = ((valid[metorg] && valid[metorg][year] && valid[metorg][year][j]) ? valid[metorg][year][j]['target'] : null);
                    var validE = ((valid[metorg] && valid[metorg][year] && valid[metorg][year][j]) ? valid[metorg][year][j]['expected'] : null);

                    var row = rowWithData(metorg, values[metorg][year][j]['id'], values[metorg][year][j]['value'], values[metorg][year][j]['x_value'], values[metorg][year][j]['target'], values[metorg][year][j]['expected'], validV, validT, validE, j, metrics[i].x_name,false, edit,sensitive);
                    table.append(row);
                }
            }
            if (cont==0 && edit){
                (!sensitive ? section.hide() : false);
                var row = rowWithData(metorg, "", "", "", "", "", "", "", "", "", metrics[i].x_name, false, edit, sensitive);
                table.append(row);
            }
            var xElem = $('.valueX');
            xElem.each(function () {
                var $this = $(this);
                $this.chosen();
                $this.on('chosen:no_results', function(e) {
                    var re = new RegExp("^[A-Za-zñáéíóúÁÉÍÓÚÑü0-9()-\/\* ]*[A-Za-zñáéíóúÁÉÍÓÚÑü][A-Za-zñáéíóúÁÉÍÓÚÑü0-9()-\/\* ]*$");
                    $this.next().find(".chosen-search > input:nth-child(1)").keyup(function (event) {
                        if (event.which !== 13 || event.keyCode !== 13) {
                            return;
                        }
                        var value = $(this).val();
                        if (value.match(re)){
                            $this.append($("<option>" , {
                                text: value,
                                value: value
                            }));
                            $this.children('[value="'.concat(value,'"]')).attr("selected", "selected");
                            $this.trigger('chosen:updated');
                        }
                    });
                });
                $this.trigger('chosen:updated');
            });
        }
        $('[data-toggle="tooltip"]').tooltip().tooltip('fixTitle').tooltip('enable');
    }

    function addRow(metorg){
        var hasX="";
        var cat = 0;
        var sensitive = false;
        var edit = false;
        for(var i in metrics) {
            if(metorg == metrics[i].metorg){
                hasX = metrics[i].x_name;
                cat = metrics[i].category;
                if((metrics[i].category==1 && editMetaP) || (metrics[i].category==2 && editMetaF))
                    sensitive = true;
                if((metrics[i].category==1 && editP) || (metrics[i].category==2 && editF))
                    edit = true;
                break;
            }
        }
        row = rowWithData(metorg, "", "", "", "", "", "", "", "", "", hasX, true, edit, sensitive);
        $('#tableContent' + metorg).append(row);
        var xElem = $('.valueX');
        xElem.each(function () {
            var $this = $(this);
            $this.chosen();
            $this.on('chosen:no_results', function(e) {
                var re = new RegExp("^[A-Za-zñáéíóúÁÉÍÓÚÑü0-9()-\/\* ]*[A-Za-zñáéíóúÁÉÍÓÚÑü][A-Za-zñáéíóúÁÉÍÓÚÑü0-9()-\/\* ]*$");
                $this.next().find(".chosen-search > input:nth-child(1)").keyup(function (event) {
                    if (event.which !== 13 || event.keyCode !== 13) {
                        return;
                    }
                    var value = $(this).val();
                    if (value.match(re)){
                        $this.append($("<option>" , {
                            text: value,
                            value: value
                        }));
                        $this.children('[value="'.concat(value,'"]')).attr("selected", "selected");
                        $this.trigger('chosen:updated');
                    }
                });
            });
            $this.on('chosen:open', function(e) {
                console.log("abrip");
                table.css('overflow-y', 'auto');
            });
            $this.trigger('chosen:updated');
        });
    }

    function selectYear(){
        var year = document.getElementById("year").value;
        $('#metrics').show();
        loadMetrics();
    }

    function validateX(elem) {
        var value = elem.value;
        var re = new RegExp("^[A-Za-zñáéíóúÁÉÍÓÚÑü0-9()-\/\* ]*[A-Za-zñáéíóúÁÉÍÓÚÑü][A-Za-zñáéíóúÁÉÍÓÚÑü0-9()-\/\* ]*$");
        return changeOnValidation(elem, (value.match(re)));
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
        for(metorg in values){
            for(year in values[metorg]){
                for(i in values[metorg][year]){
                    if (values[metorg][year][i].id == id){
                        return values[metorg][year][i];
                    }
                }
            }
        }
        return false;
    }

    $('form').on('submit', function(e){
        year = $('#year').val();
        var result = true;
        var x_aux = xValues;
        var newX = [];
        for(metorg in values){
            $('#tableContent'+metorg + ' select[name="valueX[]"]').each(function () {
                var $this = $(this);
                var id = $this.closest('tr').children('input[name="valId[]"]').val();
                var value = getValueById(id);
                if(value && value.x_value != this.value && x_aux.indexOf(this.value) != -1){
                    alert("El valor de X ya se encuentra en algún otro año. Añada un nuevo elemento y elimine este para obtener el mismo resultado.");
                    result = false;
                    return false;
                }
                else if(!value && newX.indexOf(this.value) != -1){
                    alert("No se pueden tener 2 elementos con el mismo valor de X para un mismo año. Modifique el elemento con el mismo nombre.");
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

    function optionHTML(optionArray, selected){
        var optHTML = "";
        for(var i = 0; i<optionArray.length; i++){
            var select = "";
            if (selected==optionArray[i])
                select = "selected";
            optHTML += '<option value="'+ optionArray[i] +'" ' + select + '>' + optionArray[i] + '</option>';
        }
        return optHTML;
    }
</script>
</body>
</html>
