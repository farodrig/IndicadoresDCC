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
        		width: 100%;
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

			<!-- start: header -->
			<header class="header">
				<div class="logo-container">
					<a href="<?php echo base_url();?>inicio" class="logo">
						<img src="<?php echo base_url();?>assets/images/u-dashboard-logo.png" height="45" alt="U-Dashboard" />
					</a>
					<div class="visible-xs toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
						<i class="fa fa-bars" aria-label="Toggle sidebar"></i>
					</div>
				</div>

				<!-- start: search & user box -->
				<div class="header-right">

					<ul class="notifications">
						<li>
							<label>Configurar</label>
							<a href="<?php echo base_url();?>configurar" class="notification-icon">
								<i class="fa fa-gear"></i>
							</a>
							<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						</li>
						<li>
							<label>Validar</label>
							<a href="<?php echo base_url();?>validar" class="notification-icon">
								<i class="fa fa-check-circle" style="color:green"></i>
								<span class="badge">1</span>
							</a>

						</li>
					</ul>

					<span class="separator"></span>

					<div id="userbox" class="userbox">
						<a href="#" data-toggle="dropdown">
							<figure class="profile-picture">
								<img src="<?php echo base_url();?>assets/images/!logged-user.jpg" alt="Joseph Doe" class="img-circle" data-lock-picture="assets/images/!logged-user.jpg" />
							</figure>
							<div class="profile-info" data-lock-name="John Doe" data-lock-email="johndoe@okler.com">
								<span class="name">John Doe Junior</span>
								<span class="role">administrator</span>
							</div>

							<i class="fa custom-caret"></i>
						</a>

						<div class="dropdown-menu">
							<ul class="list-unstyled">
								<li class="divider"></li>
								<li>
									<a role="menuitem" tabindex="-1" href="pages-signin.html"><i class="fa fa-power-off"></i> Logout</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<!-- end: search & user box -->
			</header>
			<!-- end: header -->

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
						$first_area_key = array_keys($areas)[0];
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
										<?php echo form_open('addGraph', array('onSubmit' => "return checkInput();")); ?>
												<label>Tipo de gráfico:</label>
												<input type="hidden" id="id_org" name="id_org" value=""/>
												<input type="hidden" id="id_met" name="id_met" value=""/>
												<input type="hidden" id="id_graph" name="id_graph" value=""/>
												<select class="form-control btn btn-default" id="type" name="type">
														<option value=2>Líneas</option>
														<option value=1>Barra</option>
												</select>
												<div class="container btn-group-vertical col-md-12">
													<br>
													<label>Desde:</label>
													<input type="number" class="rounded" id="from" name="from" onchange ="saveValFrom(this)" onkeyup="validate_year('from',from)" >
													<label>Hasta:</label>
													<input type="number" class="rounded" id="to" name="to" onkeyup="saveValTo(this)" onkeyup="validate_year('to',to)"  >
													<hr>
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
			var years = <?php echo json_encode($years); ?>;
			var from =2000;
			var to = 2000;

			function saveValFrom(e){
				from = e.value;
			}

			function saveValTo(e){
				from = e.value;
			}

			function checkInput(){
				if(validate_year('from', from) && validate_year('to', to)){

					if(from<=to)
						return true;
					else{
						console.log(validate_year(from));
						alert("Año de inicio debe ser menor al año final");
						return false;
					}
				}
				else{
					alert("Años inválidos");
					return false;
				}
			}

			function changePage(page){
      			window.location.href = "<?php echo base_url();?>".concat(page);
    		}

			var metricas = <?php echo json_encode($metricas); ?>; 
			console.log($('#popover'));
			var unidad_value = $( "#unidad" ).val();
			$('#metricas').empty();
			var metricas_unidad = metricas[unidad_value];
			$('#id_org').attr('value',unidad_value);
  			for (i in metricas_unidad) {
  				var popover = "<button href='#popover' id='".concat(metricas_unidad[i]['metorg'], "' value='", metricas_unidad[i]['metorg'],
  					"' class='btn btn-default' onclick='updateYears(",metricas_unidad[i]['metorg'], ")'>", metricas_unidad[i]['name'], "</button>"); 
    			$(popover).appendTo($('#metricas'));
  			}

  			function updateYears(id){
  				var min_year = years[id]['min'];
  				var max_year = years[id]['max'];
  				var check = years[id]['checked'];
  				var type = years[id]['type'];
  				var id_graph = years[id]['id'];

  				$('#from').attr('value',new Number(JSON.parse(min_year)));
  				$('#to').attr('value',new Number(JSON.parse(max_year)));
				$('#id_met').attr('value',new Number(id));
				$('#id_graph').attr('value',new Number(id_graph));
				$('#mostrar').attr('checked', check==0 ? null : 1);

				var select_grafico = document.getElementById('type');
				select_grafico.options.length = 0;
				
				if(type=="2"){
					select_grafico.options[select_grafico.options.length]= new Option('Líneas', 2);
					select_grafico.options[select_grafico.options.length]= new Option('Barra', 1);
				}
				else{
					select_grafico.options[select_grafico.options.length]= new Option('Barra', 1);
					select_grafico.options[select_grafico.options.length]= new Option('Líneas', 2);
				}

  			}

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

			$('section.body').click(function(e){
				if(!(e['target']['attributes']['class'].value=="btn-group-vertical col-md-12") && 
					!(e['target']['attributes']['class'].value=="btn btn-default" && e['target']['attributes']['href'].value=="#popover")){
					$('#popover').popover('hide');
				}
			});

			function validate_year(id,opt){
				return changeOnValidation(id, ((!isNaN(parseFloat(opt)) && isFinite(opt)) && opt.toString().length==4 && opt>=1980));  
			}

			function changeOnValidation(id, validator){
				if(validator){
					document.getElementById(id).style.borderColor="green";
					return true;
				}
				else{
					document.getElementById(id).style.borderColor="red";
					document.getElementById(id).focus();
					return false;
				}
			}

		</script>
	</body>
</html>