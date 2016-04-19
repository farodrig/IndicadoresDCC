<!doctype html>
<html class="fixed sidebar-left-collapsed">
<head>
    <?php
    //Para usar head.php debe ser dentro del tag head y debe haberse creado una variable $title.
    include 'partials/head.php'; ?>
    <link rel="stylesheet" href="<?php echo base_url();?>chosen/chosen.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/vendor/jquery-treegrid/css/jquery.treegrid.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/vendor/bootstrap/css/bootstrap.min.css" />
    <style type="text/css">
        .title{
            font-size: 1.5em;
        }
        .black td {
            color: black !important;
        }
        .dataTables_wrapper{
            margin-bottom: 1%;
        }

        .no_valid{
            border: 2px solid red !important;
        }

        div.table-responsive div.row {
            margin-left: 0px;
            margin-right: 0px;
        }
    </style>
</head>
<body>
<section class="body">

    <?php
    //Para usar header_tmpl.php se debe haber creado la variable $name y $role. Se pueden crear tanto aqui como en el controlador.
    include 'partials/header_tmpl.php'; ?>

    <div class="inner-wrapper">
        <!-- start: sidebar -->
        <?php
        $navData=[];
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
                    <div class="panel-body">
                    <div class="form-group col-md-offset-5">
                        <div class="col-md-2 text-center">
                            <label class="control-label title">Año:</label>
                        </div>
                        <div class="col-md-4">
                            <select id="year" name="year" data-placeholder="Seleccione año..." class="chosen-select" style="width:200px;" onchange ="reloadTable(); validate_year('year')" tabindex="4">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <?php if ($valAll){ ?>
                            <button id="validateAll" type="button" class="btn btn-success pull-right" onclick="validate(-1)"> Validar Presupuesto</button>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 text-center">
                            <input id="is_valid" type="radio" name="gender" onchange="changeData(true)" checked>Información Validada
                        </div>
                        <div class="col-sm-6 text-center">
                            <input type="radio" name="gender" onchange="changeData(false)">Información Pendiente de Validación
                        </div>
                    </div>
                    <div class="table-responsive dataTables_wrapper no-footer" id="datatable-editable_wrapper">
                        <table id="datatable-editable" class="table table-bordered table-hover mb-none dataTable tree" role="grid" aria-describedby="datatable-editable_info">
                            <thead>
                                <tr role="row">
                                    <th>Organizacion</th>
                                    <th hidden>Id</th>
                                    <th aria-controls="datatable-editable">Gasto Actual</th>
                                    <th aria-controls="datatable-editable">Gasto Esperado</th>
                                    <th>Diferencia Esperado</th>
                                    <th aria-controls="datatable-editable">Gasto Maximo</th>
                                    <th>Diferencia Maximo</th>
                                    <th aria-label="Actions">Editar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr role="row" class="treegrid-root treegrid-expanded black">
                                    <td class="no-editable no-mutable"><span class="treegrid-expander glyphicon glyphicon-chevron-down"></span>DCC</td>
                                    <td class="org-id" hidden></td>
                                    <td class="current-val"></td>
                                    <td class="min-val"></td>
                                    <td class="no-editable min-val-diff"></td>
                                    <td class="max-val"></td>
                                    <td class="no-editable max-val-diff"></td>
                                    <td class="actions no-mutable"></td>
                                </tr>
                            <?php

                                foreach($departments as $data_type){
                            ?>
                                <tr role="row" class="treegrid-<?php echo ($data_type['department']->getId())?> treegrid-parent-root treegrid-expanded black">
                                    <td class="no-editable no-mutable"><span class="treegrid-expander glyphicon glyphicon-chevron-down"></span><?php echo ($data_type['type']['name'])?></td>
                                    <td class="org-id" hidden><?php echo ($data_type['department']->getId())?></td>
                                    <td class="current-val" data-toggle="tooltip" data-placement="top"></td>
                                    <td class="min-val" data-toggle="tooltip" data-placement="top"></td>
                                    <td class="no-editable min-val-diff"></td>
                                    <td class="max-val" data-toggle="tooltip" data-placement="top"></td>
                                    <td class="no-editable max-val-diff"></td>
                                    <td class="actions no-mutable"></td>
                                </tr>
                            <?php
                                    foreach($data_type['areas'] as $data_area){
                                ?>
                                <tr role="row" class="treegrid-<?php echo ($data_area['area']->getId())?> treegrid-parent-<?php echo ($data_area['area']->getParent())?> treegrid-expanded black">
                                    <td class="no-editable no-mutable"><span class="treegrid-expander glyphicon glyphicon-chevron-down"></span><?php echo ($data_area['area']->getName())?></td>
                                    <td class="org-id" hidden><?php echo ($data_area['area']->getId())?></td>
                                    <td class="current-val" data-toggle="tooltip" data-placement="top"></td>
                                    <td class="min-val" data-toggle="tooltip" data-placement="top"></td>
                                    <td class="no-editable min-val-diff"></td>
                                    <td class="max-val" data-toggle="tooltip" data-placement="top"></td>
                                    <td class="no-editable max-val-diff"></td>
                                    <td class="actions no-mutable"></td>
                                </tr>
                            <?php
                                        foreach($data_area['unidades'] as $unidad){
                            ?>
                                <tr role="row" class="treegrid-<?php echo ($unidad->getId())?> treegrid-parent-<?php echo ($unidad->getParent())?> black">
                                    <td class="no-editable no-mutable"><span class="treegrid-expander glyphicon glyphicon-chevron-down"></span><?php echo ($unidad->getName())?></td>
                                    <td class="org-id" hidden><?php echo ($unidad->getId())?></td>
                                    <td class="current-val" data-toggle="tooltip" data-placement="top"></td>
                                    <td class="min-val" data-toggle="tooltip" data-placement="top"></td>
                                    <td class="no-editable min-val-diff"></td>
                                    <td class="max-val" data-toggle="tooltip" data-placement="top"></td>
                                    <td class="no-editable max-val-diff"></td>
                                    <td class="actions no-mutable">
                                    <?php if (in_array($unidad->getId(), $editable)){ ?>
                                        <a class="on-editing save-row hidden" href="#"><i class="fa fa-save"></i></a>
                                        <a class="on-editing cancel-row hidden" href="#"><i class="fa fa-times"></i></a>
                                        <a class="on-default edit-row hidden" href="#"><i class="fa fa-pencil"></i></a>
                                    <?php  } ?>
                                        <a class="on-default validate-row hidden" href="#"><i class="fa fa-check"></i></a>
                                    </td>
                                </tr>
                            <?php
                                        }
                                    }
                                }
                            ?>
                            </tbody>
                        </table>

                    </div>
                    </div>
                </div>

            </section>
        </section>
    </div>
</section>

<?php include 'partials/footer.php'; ?>

<script src="<?php echo base_url();?>assets/vendor/select2/select2.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/vendor/jquery-treegrid/js/jquery.treegrid.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/vendor/jquery-treegrid/js/jquery.treegrid.bootstrap3.js"></script>
<script src="<?php echo base_url();?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.js"></script>
<script src="<?php echo base_url();?>assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
<script src="<?php echo base_url();?>chosen/chosen.jquery.js" type="text/javascript"></script>

<script type="text/javascript">
    $('[data-toggle="tooltip"]').tooltip();
    $('.tree').treegrid();

    var valid_datos = <?php echo json_encode($valid_data);?>;
    var datos = valid_datos;
    var no_valid_datos = <?php echo json_encode($no_valid_data);?>;
    var editable = <?php echo json_encode($editable);?>;
    //cargar años
    var years = <?php echo json_encode($years);?>;
    years.sort();
    for(year in years){
        $('#year').append('<option>' + years[year] + '</option>');
    }

    function changeData(valid) {
        datos = (valid ? valid_datos : no_valid_datos);
        reloadTable();
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
            reloadTable();
        }
    });

    function validate_year(id){
        var opt = document.getElementById(id).value;
        return ((!isNaN(parseFloat(opt)) && isFinite(opt)) && opt.length ==4 && opt>=1980);
    }

    function validate(org) {
        if (!validate_year('year'))
            return;
        var data = {'org': org, 'year': $('#year').val()};
        ajaxCall("<?php echo base_url();?>presupuesto/validate", data);
    }

    function intToMoney(num){
        if($.type(num) === "number")
            num = num +'';
        else if(!($.type(num) === "string"))
            return "";
        sign = "";
        money="";
        if (num[0]=="-") {
            sign = "-";
            num = num.substring(1);
        }
        var i;
        for (i = num.length-1; i >= 2; i-=3) {
            money = "." + num[i-2] + num[i-1] + num[i] + money;
        }
        if(i==-1)
            money = money.substring(1);
        return "$" + sign + num.substring(0,i+1) + money;
    }

    function moneyToInt(mon){
        var i = 1;
        var sign = "";
        if (mon[1]=="-") {
            i = 2;
            sign = "-";
        }
        num = mon.substring(i);
        return sign + num.split(".").join("");
    }

    function restartRow(item){
        item.removeClass('success');
        item.removeClass('warning');
        item.removeClass('danger');
        item.children('td').each(function(){
            var $this = $( this );
            $this.removeClass('no_valid');
            if($this.hasClass('no-mutable'))
                return;
            else if($this.hasClass('org-id')){
                if ($this.find('input').length)
                    $this.html($this.children(":first").val());
                return;
            }
            $this.html('');
            $this.tooltip('disable');
        })
    }

    function reloadTable(){
        if (!validate_year('year'))
            return;
        var year = $('#year').val();
        for(org in datos){
            row = $('.treegrid-'+org);
            restartRow(row);
            var dato;
            if(!datos[org] || !(year in datos[org]))
                continue;
            $('.treegrid-'+org+' > td').each(function(){
                var $this = $( this );

                dato = datos[org][year];
                var valDato = null;
                if(!(valid_datos[org]===undefined || valid_datos[org][year]===undefined)){
                    valDato = valid_datos[org][year];
                }

                if($this.hasClass('actions') && $this.html()!=""){
                    var permits = dato.permit;
                    (editable.indexOf(org)!=-1 ? $this.children(".edit-row").removeClass('hidden') : $this.children(".edit-row").addClass('hidden'));

                    if (!permits.validate || datos!=no_valid_datos || dato.id == valDato.id){
                        $this.children(".validate-row").addClass('hidden');
                    }
                    if (permits.validate && datos==no_valid_datos && dato.id != valDato.id){
                        $this.children(".validate-row").removeClass( 'hidden' );
                    }
                }

                if($this.html()!=""){
                    return;
                }

                if($this.hasClass('org-id')){
                    $this.html(org);
                }
                else if($this.hasClass('current-val')){
                    $this.html(intToMoney(dato.value));
                    if(dato.state == 0 && dato.p_v != null) {
                        $this.addClass('no_valid');
                        if(!(valDato===null || valDato.value===undefined || valDato.value===null)){
                            $this.attr('title', intToMoney(valDato.value)).tooltip('fixTitle');
                            $this.tooltip('enable');
                        }
                    }
                }
                else if($this.hasClass('min-val')){
                    $this.html(intToMoney(dato.expected));
                    if(dato.state == 0 && dato.p_e != null) {
                        $this.addClass('no_valid');
                        if (!(valDato === null || valDato.expected === undefined || valDato.expected === null)) {
                            $this.attr('title', intToMoney(valDato.expected)).tooltip('fixTitle');
                            $this.tooltip('enable');
                        }
                    }
                }
                else if($this.hasClass('max-val')){
                    $this.html(intToMoney(dato.target));
                    if(dato.state == 0 && dato.p_t != null) {
                        $this.addClass('no_valid');
                        if (!(valDato === null || valDato.target === undefined || valDato.target === null)) {
                            $this.attr('title', intToMoney(valDato.target)).tooltip('fixTitle');
                            $this.tooltip('enable');
                        }
                    }
                }
                else if($this.hasClass('min-val-diff')){
                    $this.html(intToMoney((dato.expected - dato.value) + ''));
                }
                else if($this.hasClass('max-val-diff')){
                    $this.html(intToMoney((dato.target - dato.value) + ''));
                }
            });
            row.addClass(getRowClass(parseInt(dato.value) , parseInt(dato.expected), parseInt(dato.target)));
        }
        loadRootData();
    }

    function loadRootData() {
        var root = $('.treegrid-root');
        root.removeClass('success');
        root.removeClass('warning');
        root.removeClass('danger');
        val = getChildrenSumValue('root', 'current-val');
        min = getChildrenSumValue('root', 'min-val');
        max = getChildrenSumValue('root', 'max-val');
        difMinVal = getChildrenSumValue('root', 'min-val-diff');
        difMaxVal = getChildrenSumValue('root', 'max-val-diff');
        root.children( 'td' ).each(function() {
            var $this = $( this );
            if($this.hasClass('current-val') && !(val===null) ){
                $this.html(intToMoney(val));
            }
            else if($this.hasClass('min-val') && !(min===null)){
                $this.html(intToMoney(min));
            }
            else if($this.hasClass('max-val') && !(max===null)){
                $this.html(intToMoney(max));
            }
            else if($this.hasClass('min-val-diff') && !(difMinVal===null)){
                $this.html(intToMoney(difMinVal));
            }
            else if($this.hasClass('max-val-diff') && !(difMaxVal===null)){
                $this.html(intToMoney(difMaxVal));
            }
        });
        if(val===null || min===null || max ===null)
            return;
        root.addClass(getRowClass(val, min, max));
    }

    function getRowClass(val, min, max){
        if(val > max)
            return "danger";
        else if(val >min)
            return "warning";
        else
            return "success";
    }

    function getChildrenSumValue(id, column) {
        var sum = 0;
        var hasVal = false;
        $('.treegrid-parent-' + id).each(function () {
            var $this = $(this);
            valueRow = $this.children('td.' + column);
            num = moneyToInt(valueRow.html().trim());
            if(num.length > 0) {
                num = parseInt(num);
                sum += num;
                hasVal = true;
            }
        });
        if(!hasVal)
            return null;
        return sum;
    }

    function ajaxPostBudgetData(org, value, min, max){
        if (!validate_year('year'))
            return;
        var year = $('#year').val();
        var data = {'year': year,
            'org': org,
            'value': value,
            'expected': min,
            'target': max};
        ajaxCall("<?php echo base_url();?>presupuesto/modify", data);
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
                        valid_datos = data['valid_data'];
                        no_valid_datos = data['no_valid_data'];
                        datos = (($('#is_valid').is(":checked")) ? valid_datos : no_valid_datos);
                    }
                    else {
                        new PNotify({
                            title: 'Error!',
                            text: 'Ha ocurrido un error. El servidor no logró realizar su solicitud',
                            type: 'error'
                        });
                    }
                    reloadTable();
                },
            error:
                function(xhr, textStatus, errorThrown){
                    new PNotify({
                        title: 'Error!',
                        text: 'Ha ocurrido un error. No se logró conectar con el servidor. Intentelo más tarde',
                        type: 'error'
                    });
                    reloadTable();
                }
        });
    }
</script>

<script type="application/javascript">

    var EditableTable = {
        options: {
            table: '#datatable-editable',
            dialog: {
                wrapper: '#dialog',
                cancelButton: '#dialogCancel',
                confirmButton: '#dialogConfirm',
            }
        },

        initialize: function() {
            this
                .setVars()
                .build()
                .events();
        },

        setVars: function() {
            this.$table				= $( this.options.table );

            // dialog
            this.dialog				= {};
            this.dialog.$wrapper	= $( this.options.dialog.wrapper );
            this.dialog.$cancel		= $( this.options.dialog.cancelButton );
            this.dialog.$confirm	= $( this.options.dialog.confirmButton );

            return this;
        },

        build: function() {
            this.datatable = this.$table.DataTable({
                aoColumns: [null, null, null, null, null, null, null, { "bSortable": false }],
                bFilter: false,
                bInfo: false,
                paging: false,
                ordering: false
            });

            window.dt = this.datatable;
            return this;
        },

        events: function() {
            var _self = this;

            this.$table
                .on('click', 'a.save-row', function( e ) {
                    e.preventDefault();

                    _self.rowSave( $(this).closest( 'tr' ) );
                })
                .on('click', 'a.cancel-row', function( e ) {
                    e.preventDefault();

                    _self.rowCancel( $(this).closest( 'tr' ) );
                })
                .on('click', 'a.edit-row', function( e ) {
                    e.preventDefault();
                    if (!validate_year('year'))
                        return;
                    _self.rowEdit( $(this).closest( 'tr' ) );
                })
                .on('click', 'a.validate-row', function( e ) {
                    e.preventDefault();
                    if (!validate_year('year'))
                        return;
                    _self.rowValidate( $(this).closest( 'tr' ) );
                })

            this.dialog.$cancel.on( 'click', function( e ) {
                e.preventDefault();
                $.magnificPopup.close();
            });

            return this;
        },

        // ==========================================================================================
        // ROW FUNCTIONS
        // ==========================================================================================
        rowCancel: function( $row ) {
            var _self = this,
                $actions,
                i,
                data;

            if ( $row.hasClass('adding') ) {
                this.rowRemove( $row );
                return;
            }
            $actions = $row.find('td.actions');
            if ( $actions.get(0) ) {
                this.rowSetActionsDefault( $row );
            }
            reloadTable();
        },

        rowEdit: function( $row ) {
            var _self = this,
                data;

            data = this.datatable.row( $row.get(0) ).data();
            var org = data[1];
            var year = $('#year').val();
            var value = datos[org][year];
            var permits = (value===undefined ? {} : value.permit);
            $row.children( 'td' ).each(function( i ) {
                var $this = $( this );
                if ( $this.hasClass('actions') ) {
                    _self.rowSetActionsEditing( $row );
                    return;
                }
                else if($this.hasClass('no-editable')){
                    return;
                }
                else if($this.hasClass('org-id')){
                    $this.html( '<input type="text" name="org" hidden class="form-control input-block" value="' + $this.html() + '"/>' );
                }
                else if($this.hasClass('current-val') && permits['value']){
                    $this.html( '<input type="text" name="val" class="form-control input-block" value="' + moneyToInt($this.html()) + '"/>' );
                }
                else if($this.hasClass('min-val') && permits['meta']){
                    $this.html( '<input type="text" name="min" class="form-control input-block" value="' + moneyToInt($this.html()) + '"/>' );
                }
                else if($this.hasClass('max-val') && permits['meta']){
                    $this.html( '<input type="text" name="max" class="form-control input-block" value="' + moneyToInt($this.html()) + '"/>' );
                }
            });
        },

        rowSave: function( $row ) {
            var _self     = this,
                $actions,
                values    = [];

            if ( $row.hasClass( 'adding' ) ) {
                this.$addButton.removeAttr( 'disabled' );
                $row.removeClass( 'adding' );
            }

            values = $row.find('td').map(function() {
                var $this = $(this);

                if ( $this.hasClass('actions') ) {
                    _self.rowSetActionsDefault( $row );
                    return _self.datatable.cell( this ).data();
                }
                else {
                    return $.trim( $this.find('input').val() );
                }
            });

            $actions = $row.find('td.actions');
            if ( $actions.get(0) ) {
                this.rowSetActionsDefault( $row );
            }

            this.datatable.draw();
            ajaxPostBudgetData(values[1], values[2], values[3], values[5]);
        },

        rowValidate: function ($row) {
            var _self = this, data;
            var org;
            $row.children( 'td' ).each(function( i ) {
                var $this = $( this );
                if($this.hasClass('org-id'))
                    org = $this.html();
            });
            validate(org);
        },

        rowSetActionsEditing: function( $row ) {
            $row.find( '.on-editing' ).removeClass( 'hidden' );
            $row.find( '.on-default' ).addClass( 'hidden' );
        },

        rowSetActionsDefault: function( $row ) {
            $row.find( '.on-editing' ).addClass( 'hidden' );
            $row.find( '.on-default' ).removeClass( 'hidden' );
        }
    };
    EditableTable.initialize();

</script>
</body>
</html>
