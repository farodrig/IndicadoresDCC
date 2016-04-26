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

			.btn{
				padding: 6px 8px;
			}
        </style>

        <script type="text/javascript">

           function validateName(input){
        	   var re = new RegExp('^([a-zA-Zñáéíóú]\\s?)+$');
        	   if (input.value.match(re)) {
        		   input.style.borderColor="#cccccc";
        	   }
        	   else {
        		    alert("Los nombres de Áreas y Unidades solo puede tener letras, tildes y espacios.");
        		    input.style.borderColor="red";
        	   }

           }

           function addArea(value){
        	   $("#AreaName").val("");
        	   $("#AreaName").css("border-color", "#cccccc");
        	   $("#segment").val(value);
           }

		   function addUnidad(area, id){
			   $("#UniName").val("");
        	   $("#UniName").css("border-color", "#cccccc");
        	   $("#addUniAreaId").html(id);
			   $("#addUniAreaName").html(area);
		   }

		   function delUnidad(unidad, id){
			   $("#delUniId").html(id);
			   $("#delUniName").html(unidad);
		   }

		   function delArea(area, id){
			   $("#delAreaId").html(id);
			   $("#delAreaName").html(area);
		   }

		   function redirectPost(location, args){
			   var form = '';
		        $.each( args, function( key, value ) {
		            form += '<input type="hidden" name="'+key+'" value="'+value+'">';
		        });
		        $('<form action="'+location+'" method="POST">'+form+'</form>').appendTo('body').submit();
		   }

		   function postAddArea(){
			    var n = $("#AreaName").val();
			    var segment = $("#segment").val();

			    if(n==""){
			   		alert("Debe ingresar un nombre para el área");
			   	}
			   	else{
			    	redirectPost('<?php echo base_url();?>ModifyOrg/addArea', {'name': n, 'type': segment});
			    }
		   }

		   function postDelArea(){
			   var n = $("#delAreaId").html();
			   redirectPost('<?php echo base_url();?>ModifyOrg/delAreaUni', {'id': n});
		   }

		   function postAddUni(){
			   var area = $("#addUniAreaId").html();
			   var name = $("#UniName").val();
			   if(name==""){
			   		alert("Debe ingresar un nombre para la unidad");
			   }
			   else{
			   		redirectPost('<?php echo base_url();?>ModifyOrg/addUni', {'area': area, 'name': name});
			   	}
		   }

		   function postDelUni(){
			   var n = $("#delUniId").html();
			   redirectPost('<?php echo base_url();?>ModifyOrg/delAreaUni', {'id': n});
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
				$navData=[['url'=>'cmetrica', 'name'=>'Configurar Métricas', 'icon'=>'fa fa-server'],
						  ['url'=>'cdashboard', 'name'=>'Configurar Dashboard', 'icon'=>'fa fa-bar-chart']];
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
								<li><span>Áreas y Unidades</span></li>
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
						  <?php
						            foreach ($dpto['areas'] as $area){
    						            if ($counter % 2 == 0 && $counter!=0)
    						                echo ('</div>');
    						            if ($counter % 2 == 0)
    						                echo ('<div class ="row">');
    						            ?>
    						            <div class="col-md-6">
    						              <section class="panel panel-info">
    						                  <header class="panel-heading" style="background-color: <?php echo($color);?>">
    						                      <h2 class="panel-title text-center">
        						                      <div class="btn-group-horizontal">
        						                          <label><?php echo(ucwords($area['area']->getName()));?></label>
        						                          <a class="btn modal-with-form" href="#deleteArea" onclick = "delArea('<?php echo(ucwords($area['area']->getName())."', ".$area['area']->getId());?>)" style="color: red"><i class="licon-close"></i></a>
        						                      </div>
    						                      </h2>
						                      </header>
    						                  <div class="panel-body">
    						                      <div class="btn-group-vertical col-md-12">
    						            <?php
    						            foreach ($area['unidades'] as $unidad){
    						                ?>
            						                <div class="btn btn-default text-center">
            						                    <label><?php echo(ucwords($unidad->getName()));?></label>
                                                        <a class="btn modal-with-form pull-right" href="#deleteUnidad" onclick = "delUnidad('<?php echo(ucwords($unidad->getName())."', ".$unidad->getId());?>)" style="color: red"><i class="licon-close"></i></a>
        						                    </div>
    						            <?php
    						            }
    						            ?>
    						                        <a class="btn modal-with-form" href="#agregarUnidad" onclick = "addUnidad('<?php echo(ucwords($area['area']->getName())."', ".$area['area']->getId());?>)" style="color: green"><i class="licon-plus"></i></a>
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
						            <div class="row text-center">
						              <a class="btn modal-with-form" href="#agregarArea" style="color: green" onclick = "addArea(<?php echo($dpto['type']['id'])?>);">
						                  <h1><i class="licon-plus"></i></h1>
						              </a>
						            </div>
					              </section>

						            <?php
						        }
						        ?>

                                <div id="agregarArea" class="modal-block modal-block-primary mfp-hide">
                                    <section class="panel">
                                        <header class="panel-heading">
                                            <h2 class="panel-title">Agregar área</h2>
                                        </header>
                                        <div class="panel-body">
                                            <form id="demo-form" class="form-horizontal mb-lg">
                                                <div class="form-group mt-lg">
                                                    <label class="col-sm-3 control-label">Nombre:</label>
                                                    <div class="col-sm-9">

                                                        <input id = "AreaName" onchange = "validateName(this);" type="text" name="name" class="form-control" placeholder="nombre de la nueva área..." required/>

                                                    </div>
                                                </div>
                                                <div class="form-group mt-lg">
                                                    <label class="col-sm-3 control-label">Segmento:</label>
                                                    <div class="col-sm-9">
                                                        <select class="form-control" id="segment">
                                                          <?php
                                                            foreach ($departments as $dpto){
                                                                echo('<option value="'.$dpto['type']['id'].'">'.ucwords($dpto['type']['name']).'</option>');
                                                            }
                                                          ?>
                                                        </select>														</div>
                                                </div>
                                            </form>
                                        </div>
                                        <footer class="panel-footer">
                                            <div class="row">
                                                <div class="col-md-12 text-right">
                                                    <button class="btn btn-primary" onclick="postAddArea()">Añadir</button>
                                                    <button class="btn btn-default modal-dismiss">Cancelar</button>
                                                </div>
                                            </div>
                                        </footer>
                                    </section>
                                </div>
								<div id="agregarUnidad" class="modal-block modal-block-primary mfp-hide">
                                    <section class="panel">
                                        <header class="panel-heading">
                                            <h2 class="panel-title">Agregar unidad</h2>
                                            <p hidden id="addUniAreaId"></p>
                                            <p class="panel-subtitle" id = "addUniAreaName">Área 1</p>
                                        </header>
                                        <div class="panel-body">
                                            <form id="demo-form" class="form-horizontal mb-lg">
                                                <div class="form-group mt-lg">
                                                    <label class="col-sm-3 control-label">Nombre:</label>
                                                    <div class="col-sm-9">
                                                        <input id = "UniName" onchange = "validateName(this);" type="text" name="name" class="form-control" placeholder="nombre de la nueva unidad..." required/>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <footer class="panel-footer">
                                            <div class="row">
                                                <div class="col-md-12 text-right">
                                                    <button class="btn btn-primary" onclick="postAddUni()">Añadir</button>
                                                    <button class="btn btn-default modal-dismiss">Cancelar</button>
                                                </div>
                                            </div>
                                        </footer>
                                    </section>
                                </div>
                                <div id="deleteArea" class="modal-block mfp-hide">
                                    <section class="panel">
                                        <header class="panel-heading">
                                            <h2 class="panel-title">¿Está seguro?</h2>
                                        </header>
                                        <div class="panel-body">
                                            <div class="modal-wrapper">
                                                <div class="modal-text">
                                                    <p>¿Está seguro de que quiere eliminar esta área?</p>
                                                    <ul class="titulo">
                                                        <li>
                                                            <p hidden id="delAreaId"></p>
                                                            <p ><strong id="delAreaName">area 1</strong></p>
                                                        </li>
                                                    </ul>
                                                    <div class="alert alert-warning">
                                                        <i class="fa fa-warning"></i>
                                                        <strong>Advertencia</strong><br>Al eliminar esta área se perderá toda la información asociada a ésta.<br>
                                                        Por favor tener cuidado de respaldar los datos que no quiera que se pierdan.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <footer class="panel-footer">
                                            <div class="row">
                                                <div class="col-md-12 text-right">
                                                    <button class="btn btn-primary" onclick="postDelArea()">Confirmar</button>
                                                    <button class="btn btn-default modal-dismiss">Cancelar</button>
                                                </div>
                                            </div>
                                        </footer>
                                    </section>
                                </div>
                                <div id="deleteUnidad" class="modal-block mfp-hide">
                                    <section class="panel">
                                        <header class="panel-heading">
                                            <h2 class="panel-title">¿Está seguro?</h2>
                                        </header>
                                        <div class="panel-body">
                                            <div class="modal-wrapper">
                                                <div class="modal-text">
                                                    <p>¿Está seguro de que quiere eliminar esta unidad?</p>
                                                    <ul class="titulo">
                                                        <li>
                                                            <p hidden id="delUniId"></p>
                                                            <p><strong id="delUniName">unidad 1</strong></p>
                                                        </li>
                                                    </ul>
                                                    <div class="alert alert-warning">
                                                        <i class="fa fa-warning"></i>
                                                        <strong>Advertencia</strong><br>Al eliminar esta unidad se perderá toda la información asociada a ésta.<br>
                                                        Por favor tener cuidado de respaldar los datos que no quiera que se pierdan.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <footer class="panel-footer">
                                            <div class="row">
                                                <div class="col-md-12 text-right">
                                                    <button class="btn btn-primary" onclick="postDelUni()">Confirmar</button>
                                                    <button class="btn btn-default modal-dismiss">Cancelar</button>
                                                </div>
                                            </div>
                                        </footer>
                                    </section>
                                </div>
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
					text: 'Ha ocurrido un error con su solicitud.<br>Los nombres de Áreas y Unidades solo puede tener letras, tildes y espacios.',
					type: 'error'
				});
			}
		</script>
    </body>
</html>
