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
                    <div class="panel-body">
                    <div class="form-group col-md-offset-5">
                        <div class="col-md-1 text-center">
                            <label class="control-label title">Año:</label>
                        </div>
                        <div class="col-md-1">
                            <select id="year" name="year" data-placeholder="Seleccione año..." class="chosen-select" style="width:200px;" onchange ="reloadTable(); validate_year('year')" tabindex="4">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive dataTables_wrapper no-footer" id="datatable-editable_wrapper">
                        <table id="datatable-editable" class="table table-bordered mb-none dataTable tree" role="grid" aria-describedby="datatable-editable_info">
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
                            <?php
                                foreach($departments as $data_type){
                            ?>
                                <tr role="row" class="treegrid-<?php echo ($data_type['department']->getId())?> treegrid-expanded black">
                                    <td class="no-editable no-mutable"><span class="treegrid-expander glyphicon glyphicon-chevron-down"></span><?php echo ($data_type['type']['name'])?></td>
                                    <td class="org-id no-mutable" hidden><?php echo ($data_type['department']->getId())?></td>
                                    <td class="current-val"></td>
                                    <td class="min-val"></td>
                                    <td class="no-editable min-val-diff"></td>
                                    <td class="max-val"></td>
                                    <td class="no-editable max-val-diff"></td>
                                    <td class="actions no-mutable"></td>
                                </tr>
                            <?php
                                    foreach($data_type['areas'] as $data_area){
                                        if(!in_array($data_area['area']->getId(), $orgs))
                                            continue;
                                ?>
                                <tr role="row" class="treegrid-<?php echo ($data_area['area']->getId())?> treegrid-parent-<?php echo ($data_area['area']->getParent())?> treegrid-expanded black">
                                    <td class="no-editable no-mutable"><span class="treegrid-expander glyphicon glyphicon-chevron-down"></span><?php echo ($data_area['area']->getName())?></td>
                                    <td class="org-id no-mutable" hidden><?php echo ($data_area['area']->getId())?></td>
                                    <td class="current-val"></td>
                                    <td class="min-val"></td>
                                    <td class="no-editable min-val-diff"></td>
                                    <td class="max-val"></td>
                                    <td class="no-editable max-val-diff"></td>
                                    <td class="actions no-mutable">
                                        <a class="on-editing save-row hidden" href="#"><i class="fa fa-save"></i></a>
                                        <a class="on-editing cancel-row hidden" href="#"><i class="fa fa-times"></i></a>
                                        <a class="on-default edit-row" href="#"><i class="fa fa-pencil"></i></a>
                                    </td>
                                </tr>
                            <?php
                                        foreach($data_area['unidades'] as $unidad){
                            ?>
                                <tr role="row" class="treegrid-<?php echo ($unidad->getId())?> treegrid-parent-<?php echo ($unidad->getParent())?> black">
                                    <td class="no-editable no-mutable"><span class="treegrid-expander glyphicon glyphicon-chevron-down"></span><?php echo ($unidad->getName())?></td>
                                    <td class="org-id no-mutable" hidden><?php echo ($unidad->getId())?></td>
                                    <td class="current-val"></td>
                                    <td class="min-val"></td>
                                    <td class="no-editable min-val-diff"></td>
                                    <td class="max-val"></td>
                                    <td class="no-editable max-val-diff"></td>
                                    <td class="actions no-mutable">
                                        <a class="on-editing save-row hidden" href="#"><i class="fa fa-save"></i></a>
                                        <a class="on-editing cancel-row hidden" href="#"><i class="fa fa-times"></i></a>
                                        <a class="on-default edit-row" href="#"><i class="fa fa-pencil"></i></a>
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
<div class="modal-block mfp-hide" id="dialog">
    <section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Are you sure?</h2>
        </header>
        <div class="panel-body">
            <div class="modal-wrapper">
                <div class="modal-text">
                    <p>Are you sure that you want to delete this row?</p>
                </div>
            </div>
        </div>
        <footer class="panel-footer">
            <div class="row">
                <div class="col-md-12 text-right">
                    <button class="btn btn-primary" id="dialogConfirm">Confirm</button>
                    <button class="btn btn-default" id="dialogCancel">Cancel</button>
                </div>
            </div>
        </footer>
    </section>
</div>
<?php include 'partials/footer.php'; ?>

<script src="<?php echo base_url();?>assets/vendor/select2/select2.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/vendor/jquery-treegrid/js/jquery.treegrid.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/vendor/jquery-treegrid/js/jquery.treegrid.bootstrap3.js"></script>
<script src="<?php echo base_url();?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.js"></script>
<script src="<?php echo base_url();?>assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
<script src="<?php echo base_url();?>chosen/chosen.jquery.js" type="text/javascript"></script>

<script type="text/javascript">
    $('.tree').treegrid();

    //cargar años
    var datos = <?php echo json_encode($data);?>;
    var orgs = <?php echo json_encode($departments);?>;
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
            reloadTable();
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
            if($this.hasClass('no-mutable'))
                return;
            $this.html('');
        })
    }

    function reloadTable(){
        if (!validate_year('year'))
            return;
        var year = $('#year').val();
        for(org in datos){
            row = $('.treegrid-'+org);
            restartRow(row);
            if(!datos[org]) {
                continue;
            }
            count = 0;
            for(data in datos[org]){
                if(year!=datos[org][data].year) {
                    continue;
                }
                count++;
                $('.treegrid-'+org+' > td').each(function(){
                    var $this = $( this );
                    if($this.hasClass('org-id')){
                        $this.html(org);
                    }
                    else if($this.hasClass('current-val')){
                        $this.html(intToMoney(datos[org][data].value));
                    }
                    else if($this.hasClass('min-val')){
                        $this.html(intToMoney(datos[org][data].expected));
                    }
                    else if($this.hasClass('max-val')){
                        $this.html(intToMoney(datos[org][data].target));
                    }
                    else if($this.hasClass('min-val-diff')){
                        $this.html(intToMoney((datos[org][data].expected - datos[org][data].value) + ''));
                    }
                    else if($this.hasClass('max-val-diff')){
                        $this.html(intToMoney((datos[org][data].target - datos[org][data].value) + ''));
                    }
                });
                newClass = "";
                if(parseInt(datos[org][data].value) < parseInt(datos[org][data].expected))
                    newClass = "success";
                else if(parseInt(datos[org][data].value) < parseInt(datos[org][data].target))
                    newClass = "warning";
                else
                    newClass = "danger";
                row.addClass(newClass);
            }
            if (count==0)
                restartRow(row);
        }
    }

    function ajaxPostBudgetData(org, value, min, max){
        if (!validate_year('year'))
            return;
        var year = $('#year').val();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>presupuesto/modify",
            data: {'year': year,
                   'org': org,
                   'value': value,
                   'expected': min,
                   'target': max},
            dataType: "json",
            cache:false,
            success:
                function(data){
                    console.log(data);
                    if (data['success']){
                        new PNotify({
                            title: 'Éxito!',
                            text: 'Su solicitud ha sido realizada con éxito.',
                            type: 'success'
                        });
                        datos = data['data'];
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
            reloadTable();
            $actions = $row.find('td.actions');
            if ( $actions.get(0) ) {
                this.rowSetActionsDefault( $row );
            }
        },

        rowEdit: function( $row ) {
            var _self = this,
                data;

            data = this.datatable.row( $row.get(0) ).data();

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
                else if($this.hasClass('current-val')){
                    $this.html( '<input type="text" name="val" class="form-control input-block" value="' + moneyToInt($this.html()) + '"/>' );
                }
                else if($this.hasClass('min-val')){
                    $this.html( '<input type="text" name="min" class="form-control input-block" value="' + moneyToInt($this.html()) + '"/>' );
                }
                else if($this.hasClass('max-val')){
                    $this.html( '<input type="text" name="max" class="form-control input-block" value="' + moneyToInt($this.html()) + '"/>' );
                }
                else {
                    $this.html( '<input type="text" class="form-control input-block" value="' + $this.html() + '"/>' );
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
