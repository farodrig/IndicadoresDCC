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
    </style>

    <script type="text/javascript">

        var greenTicket = -1;

        function changeColor(id){
            if (greenTicket!=id){
                if (greenTicket!=-1){
                    $('#ticket'+greenTicket).css('color', 'lightgrey');
                }
                greenTicket = id;
                $('#ticket'+id).css('color', 'forestgreen');
            }
            else{
                $('#ticket'+id).css('color', 'lightgrey');
                greenTicket = -1;
            }
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
            ['url'=>'cdashboardUnidad', 'name'=>'Configurar Dashboard', 'icon'=>'fa fa-bar-chart']];
        include 'partials/navigation.php';
        ?>
        <!-- end: sidebar -->

        <section role="main" class="content-body">
            <header class="page-header">
                <h2>Configurar áreas y unidades</h2>

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
                                            <h2 class="panel-title text-center">
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

            <!-- end: page -->
        </section>
    </div>
</section>

<?php include 'partials/footer.php'; ?>

<script type="text/javascript">
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
