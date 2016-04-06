<!doctype html>
<html class="fixed sidebar-left-collapsed">
	<head>
	    <?php
        $title = "Configurar Dashboard Unidades";
        include 'partials/head.php';
        ?>
		<link rel="stylesheet" href="<?php echo base_url();?>assets/vendor/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css" />

		<style type="text/css">
    		.container {
        		width: 214px;
        		clear: both;
    		}
    		.container input {
        		width: 100px;
        		clear: both;
    		}
    		input.rounded {

        	    border: 1px solid #ccc;

        	    -moz-border-radius: 10px;

        	    -webkit-border-radius: 10px;

        	    border-radius: 10px;

        	    -moz-box-shadow: 2px 2px 3px #666;

        	    -webkit-box-shadow: 2px 2px 3px #666;

        	    box-shadow: 2px 2px 3px #666;

        	    font-size: 20px;

        	    padding: 4px 7px;

        	    outline: 0;

        	    -webkit-appearance: none;

        	}

        	input.rounded:focus {
        	    border-color: #339933;
        	    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset, 0 0 8px rgba(51, 153, 51, 0.6);
                outline: 0 none;
        	}
    	</style>
	</head>
	<body>
		<section class="body">

			<?php include 'partials/header_tmpl.php'; ?>

			<div class="inner-wrapper">
				<!-- start: sidebar -->
				<?php
				$navData=[['url'=>'inicio', 'name'=>'U-Dashboard', 'icon'=>'fa fa-home'],
					['url'=>'careaunidad', 'name'=>'Configurar áreas y unidades', 'icon'=>'fa fa-th-large'],
					['url'=>'cmetrica', 'name'=>'Configurar Métricas', 'icon'=>'fa fa-server']];
				include 'partials/navigation.php';
				?>
				<!-- end: sidebar -->
				<section role="main" class="content-body">
					<header class="page-header">
						<h2>Configurar Dashboard unidades</h2>

						<div class="right-wrapper pull-right">
							<ol class="breadcrumbs">
								<li>
									<a href="<?php echo base_url();?>inicio">
										<i class="fa fa-home"></i>
									</a>
								</li>
								<li><span>Configurar</span></li>
								<li><span>Dashboard</span></li>
							</ol>
							<label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
						</div>
					</header>

					<!-- start: page -->
					<div class="row">
					<div class="text-center col-sm-12 btn-group-horizontal">
						<a class= "mb-xs mt-xs mr-xs btn btn-danger btn-lg" href="<?php echo base_url();?>cdashboardDCC">Configurar Dashboard DCC</a>
						<a class= "mb-xs mt-xs mr-xs btn btn-info btn-lg" href="<?php echo base_url();?>cdashboardArea">Configurar Dashboard áreas</a>
						<a class= "mb-xs mt-xs mr-xs btn btn-primary btn-lg" href="<?php echo base_url();?>cdashboardUnidad">Configurar Dashboard unidades</a>
					</div>
					</div>
					<?php
					if($areas){
						if($id_first=="-1")
							$first_area_key = array_keys($areas)[0];
						else{
							$first_area_key="";
							foreach ($areas as $a) {
								foreach ($a['unidades'] as $unidad) {
									if($unidad['id']==intval($id_first)){
										$first_area_key = $a['id'];
										break;
									}
								}
								if($first_area_key!="")
									break;
							}
						}
						if($areas[$first_area_key]['type']=="Operación"){
							$color_panel="panel-warning";
							$color_button = "btn-warning";
						}
						else{
							$color_panel="panel-success";
							$color_button = "btn-success";
						}
					?>
					<div class="row">
						<div class="col-md-6">
							<section name="section" id="section" class="<?php echo $color_panel; ?>">
								<header class="panel-heading">
									<h2 class="panel-title">
											<div class="form-group mt-lg">
												<div class="btn-group-horizontal text-center">
													<form>
													<?php
														$first_area_unidades = $areas[$first_area_key]['unidades'];?>
													<select name="area" id= "area" class="<?php echo("form-control btn ".$color_button);?>" onchange="selectUnidades();">
													<?php
														foreach ($areas as $area) {
															echo "<option class='select' value='".$area['id']."'>".$area['name']."</option>";
														}
													?>
													</select>
													<select name="unidad" id="unidad" class="<?php echo("form-control btn ".$color_button);?>">
													<?php

														foreach ($first_area_unidades as $unidad) {
															echo "<option class='select' value='".$unidad['id']."'>".$unidad['name']."</option>";
														}
													?>
													</select>
													</form>
												</div>
											</div>
										</form>
									</h2>

								</header>
								<div class="panel-body">

									<div class="btn-group-vertical col-md-12" name="popover" id="popover">
									<div class="btn-group-vertical col-md-12" name="metricas" id="metricas"></div>

										<div id="popover-head" class="hide">Configurar gráfico para métrica</div>
										<div id="popover-content" data-placement="right" class="hide">
										<?php echo form_open('DashboardConfig/addGraphUnidad', array('onSubmit' => "return checkInput();")); ?>
												<label>Tipo de gráfico:</label>
												<input type="hidden" id="id_org" name="id_org" value=""/>
												<input type="hidden" id="id_met" name="id_met" value=""/>
												<input type="hidden" id="id_graph" name="id_graph" value=""/>
												<select class="form-control btn btn-default" id="type" name="type">
														<option value=2>Líneas</option>
														<option value=1>Barra</option>
												</select>
												<div class="container btn-group-vertical col-md-12">
													<div class="form-group">
    													<label for="from">Desde:</label>
    													<input type="number" class="form-control rounded"id="from" name="from" onchange ="saveValFrom(this)" onkeyup="validate_year('from',from)" >
													</div>
													<div class="form-group">
    													<label for="to">Hasta:</label>
    													<input type="number" class="form-control rounded" id="to" name="to" onchange="saveValTo(this)" onkeyup="validate_year('to',to)"  >
													</div>
													<hr style="width:250px;">
												</div>
												<br>
												<br>
												<label></label>
												<label>Mostrar en dashboard:</label>
												<input id="mostrar" type="checkbox" name="mostrar" value="1" />
												</br>
												</br>
												<button type="submit" onclick="$('#popover').popover('hide');" class="btn btn-primary"> Guardar</button>
											<?php echo form_close(); ?>
										</div>


									</div>

								</div>
							</section>

						</div>
					</div>
					<?php
					}
					?>
					<!-- end: page -->
				</section>

        <?php include 'partials/footer.php'; ?>

		<!-- Specific Page Vendor -->
		<script src="<?php echo base_url();?>assets/vendor/jquery-ui/js/jquery-ui-1.10.4.custom.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/jquery-ui-touch-punch/jquery.ui.touch-punch.js"></script>

		<!-- Examples -->
		<script src="<?php echo base_url();?>assets/javascripts/ui-elements/popover.js"></script>

		<!-- Demo Purpose Only -->
		<script>
			var id_first= <?php echo $id_first; ?>;
			var years = <?php echo json_encode($years); ?>;

			var metricas = <?php echo json_encode($metricas); ?>;
			$(document).ready(function(){
				if(id_first!="-1"){
					var first_area=<?php echo !$areas ? 0 : $first_area_key; ?>;
					$('#area option[value="'.concat(first_area,'"]')).attr("selected", "selected");
					$('#unidad option[value="'.concat(id_first,'"]')).attr("selected", "selected");
					$('#unidad').trigger('change');
				}
				else{
					var unidad_value = $( "#unidad" ).val();
					$('#metricas').empty();
					var metricas_unidad = metricas[unidad_value];
					$('#id_org').attr('value',unidad_value);
  					for (i in metricas_unidad) {
  						var popover = "<button href='#popover' id='id".concat(metricas_unidad[i]['metorg'], "' value='", metricas_unidad[i]['metorg'],
  							"' class='btn btn-default' onclick='updateYears(",metricas_unidad[i]['metorg'], ")'>", metricas_unidad[i]['name'], "</button>");
    					$(popover).appendTo($('#metricas'));
  					}
  				}
  			});


			function selectUnidades(){

				var id_area = document.getElementById("area").value;
				var areas = <?php echo json_encode($areas); ?>;
				var unidades = areas[id_area]['unidades'];
				var color = areas[id_area]['type']=="Operación" ? "warning" : "success";

				$('#section').attr('class',"panel-".concat(color));
				$('#area').attr('class', "form-control btn btn-".concat(color));
				$('#unidad').attr('class', "form-control btn btn-".concat(color));

				var select_unidad = document.getElementById('unidad');
				$('#metricas').empty();

				select_unidad.options.length = 0; //Resetear select

				for(i in unidades){
 					opt = new Option(unidades[i]['name'], unidades[i]['id']);
 					opt.className="select";
 					select_unidad.options[select_unidad.options.length]=opt;
				}
				var unidad_value = $( "#unidad" ).val();
				$('#id_org').attr('value',unidad_value);
				$('#metricas').empty();
				var metricas_unidad = metricas[unidad_value];
  				for (i in metricas_unidad) {
  					var popover = "<button href='#popover' id='".concat(metricas_unidad[i]['metorg'], "' value='", metricas_unidad[i]['metorg'],
  					"' class='btn btn-default' onclick='updateYears(",metricas_unidad[i]['metorg'], ")'>", metricas_unidad[i]['name'], "</button>");
    				$(popover).appendTo($('#metricas'));
  				}
			}


			$('#unidad').change(function() {
				var unidad_value = $( "#unidad" ).val();
				$('#metricas').empty();
				$('#id_org').attr('value',unidad_value);
				var metricas_unidad = metricas[unidad_value];
  				for (i in metricas_unidad) {
  					var popover = "<button href='#popover' id='".concat(metricas_unidad[i]['metorg'], "' value='", metricas_unidad[i]['metorg'],
  					"' class='btn btn-default' onclick='updateYears(",metricas_unidad[i]['metorg'], ")'>", metricas_unidad[i]['name'], "</button>");
    				$(popover).appendTo($('#metricas'));
  				}
			});
		</script>
	<script src="<?php echo base_url();?>js/functions.js"></script>
	</body>
</html>
