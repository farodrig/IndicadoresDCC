<!doctype html>
<html class="fixed sidebar-left-collapsed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<title>Configurar Dashboard Unidades</title>
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
		<meta name="keywords" content="HTML5 Admin Template" />
		<meta name="description" content="Porto Admin - Responsive HTML5 Template">
		<meta name="author" content="okler.net">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- Web Fonts  -->
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="<?php echo base_url();?>assets/vendor/bootstrap/css/bootstrap.css" />

		<link rel="stylesheet" href="<?php echo base_url();?>assets/vendor/font-awesome/css/font-awesome.css" />
		<link rel="stylesheet" href="<?php echo base_url();?>assets/vendor/magnific-popup/magnific-popup.css" />
		<link rel="stylesheet" href="<?php echo base_url();?>assets/vendor/bootstrap-datepicker/css/datepicker3.css" />

		<!-- Specific Page Vendor CSS -->
		<link rel="stylesheet" href="<?php echo base_url();?>assets/vendor/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css" />

		<!-- Theme CSS -->
		<link rel="stylesheet" href="<?php echo base_url();?>assets/stylesheets/theme.css" />

		<!-- Skin CSS -->
		<link rel="stylesheet" href="<?php echo base_url();?>assets/stylesheets/skins/default.css" />

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="<?php echo base_url();?>assets/stylesheets/theme-custom.css">

		<!-- Head Libs -->
		<script src="<?php echo base_url();?>assets/vendor/modernizr/modernizr.js"></script>
	</head>
	<body>
		<section class="body">

        <?php include 'partials/header.php'; ?>

			<div class="inner-wrapper">
				<!-- start: sidebar -->
				<aside id="sidebar-left" class="sidebar-left">

					<div class="sidebar-header">
						<div class="sidebar-title">
							Navegación
						</div>
						<div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
							<i class="fa fa-bars" aria-label="Toggle sidebar"></i>
						</div>
					</div>

					<div class="nano">
						<div class="nano-content">
							<nav id="menu" class="nav-main" role="navigation">
								<ul class="nav nav-main">
									<li>
										<a href="<?php echo base_url();?>inicio">
											<i class="fa fa-home" aria-hidden="true"></i>
											<span>U-Dashboard</span>
										</a>
									</li>
									<li>
										<a href="<?php echo base_url();?>careaunidad">
											<span class="pull-right label label-primary"></span>
											<i class="fa fa-th-large" aria-hidden="true"></i>
											<span>Configurar áreas y unidades</span>
										</a>
									</li>
									<li>
										<a href="<?php echo base_url();?>cmetrica">
											<span class="pull-right label label-primary"></span>
											<i class="fa fa-server" aria-hidden="true"></i>
											<span>Configurar métricas</span>
										</a>
									</li>
								</ul>
							</nav>

						</div>

					</div>

				</aside>
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
						<button class= "mb-xs mt-xs mr-xs btn btn-danger btn-lg" onclick="changePage('cdashboardDCC')">Configurar Dashboard DCC</button>
						<button class= "mb-xs mt-xs mr-xs btn btn-info btn-lg" onclick="changePage('cdashboardArea')">Configurar Dashboard áreas</button>
						<button class= "mb-xs mt-xs mr-xs btn btn-primary btn-lg" onclick="changePage('cdashboardUnidad')">Configurar Dashboard unidades</button>

					</div>
					</div>
					<?php
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
						if($areas[$first_area_key]['type']=="1"){
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
													<select name="area" id= "area" class="<?php echo("form-control btn ".$color_button);?>" onchange= "selectUnidades();">
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
										<?php echo form_open('DashboardConfig/addGraphArea', array('onSubmit' => "return checkInput();")); ?>
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
					<!-- end: page -->
				</section>

		<!-- Vendor -->
		<script src="<?php echo base_url();?>assets/vendor/jquery/jquery.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/bootstrap/js/bootstrap.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/nanoscroller/nanoscroller.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/magnific-popup/magnific-popup.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>

		<!-- Specific Page Vendor -->
		<script src="<?php echo base_url();?>assets/vendor/jquery-ui/js/jquery-ui-1.10.4.custom.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/jquery-ui-touch-punch/jquery.ui.touch-punch.js"></script>

		<!-- Theme Base, Components and Settings -->
		<script src="<?php echo base_url();?>assets/javascripts/theme.js"></script>

		<!-- Theme Custom -->
		<script src="<?php echo base_url();?>assets/javascripts/theme.custom.js"></script>

		<!-- Theme Initialization Files -->
		<script src="<?php echo base_url();?>assets/javascripts/theme.init.js"></script>

		<!-- Examples -->
		<script src="<?php echo base_url();?>assets/javascripts/ui-elements/popover.js"></script>

		<!-- Demo Purpose Only -->
		<script>
			var id_first= <?php echo $id_first; ?>;
			var years = <?php echo json_encode($years); ?>;

			function changePage(page){
      			window.location.href = "<?php echo base_url();?>".concat(page);
    		}

			var metricas = <?php echo json_encode($metricas); ?>;
			$(document).ready(function(){
				if(id_first!="-1"){
					var first_area=<?php echo $first_area_key; ?>;
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
  						var popover = "<button href='#popover' id='".concat(metricas_unidad[i]['metorg'], "' value='", metricas_unidad[i]['metorg'],
  							"' class='btn btn-default' onclick='updateYears(",metricas_unidad[i]['metorg'], ")'>", metricas_unidad[i]['name'], "</button>");
    					$(popover).appendTo($('#metricas'));
  					}
  				}
  			});


			function selectUnidades(){

				var id_area = document.getElementById("area").value;
				var areas = <?php echo json_encode($areas); ?>;
				var unidades = areas[id_area]['unidades'];
				var color = areas[id_area]['type']=="1" ? "warning" : "success";

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
