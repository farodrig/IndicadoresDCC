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

        .table > tbody > tr > td {
            vertical-align: middle;
        }
    </style>

    <script type="text/javascript">
        var years = <?php echo json_encode($years);?>;
        var fodas = <?php echo json_encode($fodas); ?>;
        var priorities = <?php echo json_encode($priorities); ?>;
        var types = <?php echo json_encode($types); ?>;
        var greenTicket = -1;
        var items = <?php echo json_encode($items); ?>;
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
                    <div class="form-group col-md-offset-4">
                        <div class="col-md-2 text-center">
                            <label class="control-label title">Año:</label>
                        </div>
                        <div class="col-md-3">
                            <select id="year" name="year" data-placeholder="Seleccione año..." class="chosen-select" style="width:200px;" onchange ="validate_year('year')">
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
                            <header class="panel panel-heading" style="background-color: transparent">
                                <div class="row">
                                    <div class="btn-block btn-primary panel-body">
                                        <h2 class="panel-title">
                                            <div class="btn-group-horizontal text-center">
                                                <label style="color:white" class="text-center"><?php echo(ucwords($kind));?></label>
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
                <input type="hidden" id ="fodaYear" name="year">
                <div class="form-group">
                    <label for="fodaComment" class="col-sm-2 control-label">Comentario:</label>
                    <div class="col-sm-4">
                        <textarea class="form-control" id="fodaComment" name="fodaComment" rows="5"></textarea>
                    </div>
                </div>
            </form>
            <div id="itemSection" class="panel-body">
                <div id="datatable-details_wrapper" class="dataTables_wrapper no-footer">
                    <div class="table-responsive">
                        <table id="datatable-details" class="table table-bordered table-striped mb-none dataTable no-footer" role="grid" aria-describedby="datatable-details_info">
                            <thead>
                                <tr role="row">
                                    <th class="sorting_disabled"></th>
                                    <th class="sorting" aria-controls="datatable-details">Item</th>
                                    <th class="sorting" aria-controls="datatable-details">Tipo</th>
                                    <th class="sorting" aria-controls="datatable-details">Prioridad</th>
                                    <th class="sorting_disabled" aria-controls="datatable-details">Editar</th>
                                </tr>
                            </thead>
                            <tbody id="tableContent">
                            </tbody>
                        </table>
                    </div>
                </div>
                <button class="btn btn-primary pull-right" id="addToTable"><i class="fa fa-plus"></i></button>
            </div>
        </section>
        <div><button class="col-md-1 col-md-offset-10 btn btn-success" type="submit" form="fodaForm" id="submit">Guardar</button></div>
    </div>
</section>

<?php include 'partials/footer.php'; ?>

<script src="<?php echo base_url();?>assets/vendor/select2/select2.js"></script>
<script src="<?php echo base_url();?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.js"></script>
<script src="<?php echo base_url();?>assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
<script src="<?php echo base_url();?>chosen/chosen.jquery.js" type="text/javascript"></script>
<script type="text/javascript">

    //ADD To Table
    $('#addToTable').on('click', function (){
        var actions = '<td class="actions"> \
                            <a class="btn on-editing cancel-row" onclick="deleteRow(this)"><i class="fa fa-times"></i></a> \
                        </td>';
        html = '<tr role="row">' +
            '<td class="text-center checkDetails"><i data-toggle class="fa fa-minus-square-o text-primary h5 m-none" style="cursor: pointer;"></i></td>' +
            '<td class="itemTitle"><input type="hidden" name="ids[]" form="fodaForm"><input class="form-control" type="text" name="titles[]" form="fodaForm"></td>' +
            '<td class="itemType"><select class="form-control" name="types[]" form="fodaForm">'+optionHTML(types)+'</select></td>' +
            '<td class="itemPriority"><select class="form-control" name="priorities[]" form="fodaForm">'+optionHTML(priorities)+'</select></td>' +
            actions + '</tr>';
        html = html +
            '<tr role="row">' +
            '<td class="details" colspan="6">Descripción: <textarea class="form-control" rows="3" name="descriptions[]" form="fodaForm"></textarea></td>'
                '</tr>';
        $('#tableContent').append(html);
    });

    function deleteRow(element){
        var $this = $(element);
        var row = $this.closest('tr');
        row.next().remove();
        row.remove();
    }

    function cancelRow(element){
        var $this = $(element);
        $this.addClass('hidden');
        $this.parent().find('.edit-row').removeClass('hidden');
        row = $this.closest('tr');
        item = getItemById(row.attr('id').substring(4));
        row.find('td').each(function(){
            cell = $(this);
            value = cell.html();
            if(cell.hasClass('checkDetails'))
                cell.html('<i onclick="showHideDetails(this)" data-toggle class="fa fa-plus-square-o text-primary h5 m-none" style="cursor: pointer;"></i>');
            else if(cell.hasClass('itemTitle'))
                cell.html(item.title);
            else if(cell.hasClass('itemType'))
                cell.html(types[item.type-1].name);
            else if(cell.hasClass('itemPriority'))
                cell.html(priorities[item.priority-1].name);
        });
        row.next().remove();
    }

    function editRow(element){
        var $this = $(element);
        $this.addClass('hidden');
        var row = $this.closest('tr');
        $this.parent().find('.cancel-row').removeClass('hidden');
        item = getItemById(row.attr('id').substring(4));
        row.find('td').each(function(){
            cell = $(this);
            value = cell.html();
            if(cell.hasClass('checkDetails'))
                cell.html('<i data-toggle class="fa fa-minus-square-o text-primary h5 m-none" style="cursor: pointer;"></i>');
            else if(cell.hasClass('itemTitle'))
                cell.html('<input type="hidden" name="ids[]" value="' + item.id + '" form="fodaForm"><input class="form-control" type="text" value="' + item.title + '" name="titles[]" form="fodaForm">');
            else if(cell.hasClass('itemType'))
                cell.html('<select class="form-control" name="types[]" form="fodaForm">'+optionHTML(types, value)+'</select>');
            else if(cell.hasClass('itemPriority'))
                cell.html('<select class="form-control" name="priorities[]" form="fodaForm">'+optionHTML(priorities, value)+'</select>');
        });
        row.after('<tr role="row"><td class="details" colspan="6">Descripción: <textarea class="form-control" rows="3" name="descriptions[]" form="fodaForm">' + item.description + '</textarea></td></tr>');
    }

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

    function getItemById(id){
        if (typeof items == "undefined")
            return "";
        for (org in items){
            for(year in items[org]){
                for(i in items[org][year]){
                    if (items[org][year][i].id==id)
                        return items[org][year][i];
                }
            }
        }
        return "";
    }

    function loadItems(){
        html = "";
        var actions = '<td class="actions"> \
                            <a class="btn on-editing cancel-row hidden" onclick="cancelRow(this)"><i class="fa fa-times"></i></a> \
                            <a class="btn on-default edit-row" onclick="editRow(this)" ><i class="fa fa-pencil"></i></a> \
                        </td>';
        year = $('#fodaYear').val();
        org = $('#org').val();
        for(i = 0; i<items[org][year].length; i++){
            cells = '<tr id="item' + items[org][year][i].id + '" role="row">';
            cells += '<td class="text-center checkDetails"><i onclick="showHideDetails(this)" data-toggle class="fa fa-plus-square-o text-primary h5 m-none" style="cursor: pointer;"></i></td>';
            cells += '<td class="itemTitle">' + items[org][year][i].title + '</td>';
            cells += '<td class="itemType">' + types[items[org][year][i].type-1].name + '</td>';
            cells += '<td class="itemPriority">' + priorities[items[org][year][i].priority-1].name + '</td>';
            html += cells + actions + "</tr>";
        }
        $('#tableContent').html(html);
        var datatableInit = function() {
            var $table = $('#datatable-details');
            // initialize
            var datatable = $table.dataTable({
                destroy: true,
                aoColumnDefs: [{
                    bSortable: false,
                    aTargets: [ 0, 4 ]
                }],
                aaSorting: [
                    [2, 'desc']
                ],
                bFilter: false,
                paging: false,
                bInfo: false,
            });
        };
        datatableInit();
    }

    function showHideDetails(element){
        var $this = $(element);
        var row = $this.closest('tr');
        if ( $this.hasClass('fa-minus-square-o')){
            $this.removeClass( 'fa-minus-square-o' ).addClass( 'fa-plus-square-o' );
            row.next().remove();
        } else {
            $this.removeClass( 'fa-plus-square-o' ).addClass( 'fa-minus-square-o');
            item = getItemById(row.attr('id').substring(4));
            row.after('<tr class="details"><td class="details" colspan="6"><p> Descripción: ' + item.description + "</p></td></tr>");
        }
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
            //reloadTable();
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

    //agrega comentario que exista previamente en la base de datos
    $("#year").change(function(e){
        $('#tableContent').html("");
        if (!validate_year('year') || greenTicket==-1) {
            $("#fodaComment").val("");
            return;
        }
        $('#fodaYear').val(this.value);
        if (fodas[greenTicket] === undefined || fodas[greenTicket][this.value] === undefined ){
            return;
        }
        loadItems();
        $("#fodaComment").val(fodas[greenTicket][this.value].comment);

    });

    var success = <?php echo($success);?>;

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
</script>
</body>
</html>
