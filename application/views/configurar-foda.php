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

        form label{
            font-weight: bold;
            font-size: 1.2em;
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

        function optionHTML(optionArray, selected){
            var optHTML = "";
            for(var i = 0; i<optionArray.length; i++){
                if (selected==optionArray[i].id)
                    optHTML += '<option value="'+ optionArray[i].id +'" selected>' + optionArray[i].name + '</option>';
                else
                    optHTML += '<option value="'+ optionArray[i].id +'">' + optionArray[i].name + '</option>';
            }
            return optHTML;
        }

        function delElem(elemId, itemId){
            if(itemId==null){
                $('#element'+elemId).remove();
                element--;
                return;
            }
            var delItem = confirm("¿Esta seguro que quiere eliminar este elemento? Será eliminado de la Base de Datos");
            if( delItem == false ){
                return;
            }
            $.ajax({
                type: "POST",
                url: "<?php echo base_url();?>foda/delItem",
                data: {'items[]': [itemId]},
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
                            $('#element'+elemId).remove();
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

        function addItemHTML(item){
            element++;
            if (item!=null) {
                priorHTML = optionHTML(priorities, item.priority);
                typeHTML = optionHTML(types, item.type);
                descrip = item.description;
                comment = item.comment;
                idElement = '<input type="hidden" value="' + item.id + '" name="items[]">';
                id = item.id;
            }
            else{
                priorHTML = optionHTML(priorities);
                typeHTML = optionHTML(types);
                descrip = "";
                comment = "";
                idElement = '<input type="hidden" name="items[]">';
                id = null;
            }
            var html = '<div id = "element'+element+'" class="form-group Element">\
        <div class="row">\
            <h3 class="col-md-2 col-md-offset-5">Elemento '+element+' <a class="btn" onclick = "delElem(' + element + ', ' + id +')" style="color: red"><i class="licon-close"></i></a></h3>\
            </div>\
        ' + idElement + '  \
        <hr>\
        <div class="form-group row">\
            <div class="form-group col-md-6">\
            <label class="col-sm-4 control-label">Prioridad:</label>\
        <div class="col-sm-3">\
            <select class="form-control" name="priorities[]">'+priorHTML+'\
            </select>\
            </div>\
            </div>\
            <div class="form-group col-md-6">\
            <label class="col-sm-3 control-label">Descripción:</label>\
        <div class="col-sm-6">\
            <textarea class="form-control" name="descriptions[]" rows="4">' + descrip + '</textarea>\
            </div>\
            </div>\
            </div>\
            <div class="form-group row">\
            <div class="form-group col-md-6">\
            <label class="col-sm-4 control-label">Tipo:</label>\
        <div class="col-sm-3">\
            <select class="form-control" name="types[]">' + typeHTML +'\
            </select>\
            </div>\
            </div>\
            <div class="form-group col-md-6">\
            <label class="col-sm-3 control-label">Comentario:</label>\
        <div class="col-sm-6">\
            <textarea class="form-control" name="comments[]" rows="4">' + comment + '</textarea>\
            </div>\
            </div>\
            </div>\
            </div>';
            $('#elemDiv').append(html);
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
            ['url'=>'foda', 'name'=>'Ver FODAs', 'icon'=>'fa fa-book']];
        include 'partials/navigation.php';
        ?>
        <!-- end: sidebar -->

        <section role="main" class="content-body">
            <header class="page-header">
                <h2>Configurar FODA</h2>

                <div class="right-wrapper pull-right">
                    <ol class="breadcrumbs">
                        <li>
                            <a href="<?php echo base_url();?>inicio">
                                <i class="fa fa-home"></i>
                            </a>
                        </li>
                        <li><span>Configurar</span></li>
                        <li><span>Foda</span></li>
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
                <div class="form-group">
                    <label class="col-sm-2 control-label">Agregar elemento al FODA:</label>
                    <a id="addElement" class="btn" style="color: green"><i class="licon-plus fa-2x"></i></a>
                </div>
                <div id="elemDiv">
                </div>
                <div
            </form>
            <input id = "submit" class="btn btn-success col-md-1 pull-right" type="submit" value="Guardar"/>
            <!-- end: page -->
        </section>
    </div>
</section>

<?php include 'partials/footer.php'; ?>

<script type="text/javascript">

    //Revisión antes de mandar información
    $('#submit').click(function(e){
        if (greenTicket==-1){
            alert('Para guardar la configuración debe seleccionar un elemento de la organización');
        }
        else{
            $('#fodaForm').submit();
        }
    });


    //Agrega formulario para elemento del FODA
    $('#addElement').click(function(e){
        addItemHTML();
    });

    //agrega comentario que exista previamente en la base de datos
    $("#year").change(function(e){
        if (greenTicket==-1 || fodas[greenTicket] === undefined || fodas[greenTicket][this.value] === undefined ) {
            $("#fodaComment").val("");
            $('#elemDiv').html("");
            element = 0;
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
                    for(var i = 0; i<data['items'].length; i++){
                        console.log(data['items'][i]);
                        addItemHTML(data['items'][i]);
                    }
                },
            error:
                function(xhr, textStatus, errorThrown){
                    alert('error');
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
            text: 'Ha ocurrido un error con su solicitud.',
            type: 'error'
        });
    }
</script>
</body>
</html>
