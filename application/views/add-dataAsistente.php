<!doctype html>
<html class="fixed sidebar-left-collapsed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<title>Añadir datos</title>
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
		<link rel="stylesheet" href="<?php echo base_url();?>assets/vendor/pnotify/pnotify.custom.css" />		
		
		<link rel="stylesheet" href="<?php echo base_url();?>chosen/chosen.css">

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

        <?php include 'partials/header-asistente.php'; ?>

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
										<a href="<?php echo base_url();?>dashboard">
											<i class="fa fa-line-chart" aria-hidden="true"></i>
											<span>Volver al dashboard</span>
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
						<h2>Añadir Datos</h2>

						<div class="right-wrapper pull-right">
							<ol class="breadcrumbs">
								<li>
									<a href="<?php echo base_url();?>inicio">
										<i class="fa fa-home"></i>
									</a>
								</li>
								<?php
									for($i=sizeof($route);$i>0;$i--)
										echo "<li><span>".$route[$i]."</span></li>";
								?>

								<li><span>Añadir Datos</span></li>
							</ol>
							<label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
						</div>
					</header>

					<!-- start: page -->
						<div class="col-md-12">
						<?php echo form_open('agregarDato', array('onSubmit' => "return pageValidate();")); ?>
							<section class="panel form-horizontal form-bordered">
								<header class="panel-heading">

									<h2 class="panel-title">Añadir Datos</h2>

									<p class="panel-subtitle">
										Deje en blanco campos correspondientes a métricas que no desea considerar. Para modificar datos existentes elija un año 
										del menú desplegable y reemplace los datos mostrados.
									</p>
								</header>
								<div class="panel-body">
									<div class="form-group">
										<div class="col-md-3">
											<label class="control-label">Año:</label>
										</div>
										<div class="col-md-3">
											<select id="year" name="year" data-placeholder="Seleccione año..." class="chosen-select" style="width:200px;" onchange ="selectYear(); validate_year('year')" tabindex="4">
											<option value=""></option>
											<?php
												$years = array_unique($measurements[1]);
												foreach ($years as $year) {
													echo('<option value="'.$year.'">'.$year.'</option>');
												}
											?>
											</select>
										</div>
									</div>
									<div class="row mb-md">
										<div class="col-md-3">
											<label horizontal-align="middle" class="control-label"><u><b>Métrica</b></u></label>
										</div>
										<div class="col-md-3">
											<label class="control-label"><u><b>Valor</b></u></label>
										</div>
										<div class="col-md-3">
											<label class="control-label"><u><b>Esperado</b></u></label>
										</div>
										<div class="col-md-3">
											<label class="control-label"><u><b>Meta</b></u></label>
										</div>
									</div>

									<?php
										echo ('<input type="hidden" name="id_location" id="id_location" value='.$id_location.'>');
										foreach ($result as $metrics){
											echo $metrics;
										}

									?>

								</div>
								<footer class="panel-footer">
									<input type="submit" class="btn btn-primary" value="Añadir">
								</footer>
							</section>
							<?php echo form_close(); ?>
						</div>


					<!-- end: page -->
				</section>
			</div>
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
		<script src="<?php echo base_url();?>assets/vendor/pnotify/pnotify.custom.js"></script>
		
		<!-- Theme Base, Components and Settings -->
		<script src="<?php echo base_url();?>assets/javascripts/theme.js"></script>

		<!-- Theme Custom -->
		<script src="<?php echo base_url();?>assets/javascripts/theme.custom.js"></script>

		<!-- Theme Initialization Files -->
		<script src="<?php echo base_url();?>assets/javascripts/theme.init.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js" type="text/javascript"></script>
  		<script src="<?php echo base_url();?>chosen/chosen.jquery.js" type="text/javascript"></script>
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
					title: 'error!',
					text: 'Ha ocurrido un error con su solicitud.<br>Revise que la información de los campos sea correcta.<br>Si el problema persiste, intente de nuevo más tarde.',
					type: 'error'
				});
			}  	 
  		</script>
  		
  		<script type="text/javascript">
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
  		</script>
  		
		<script type="text/javascript">

			$('#year').live('chosen:no_results', function(e,params) {
    			var value = $('.chosen-search > input:nth-child(1)').val();
    			if(value.length==4 && (!isNaN(parseFloat(value)) && isFinite(value))){
    				$('#year').append($("<option>" , {
        					text: value,
        					value: value
    						}));
    				$('#year option[value="'.concat(value,'"]')).attr("selected", "selected");
  					$('#year').trigger('chosen:updated');
  					selectYear();

    			}
  			});

			function selectYear(){

				var year = document.getElementById("year").value;
				var jArray= <?php echo json_encode($measurements[0]); ?>;
				var years = <?php echo json_encode($measurements[1]); ?>;
				var size = <?php echo sizeof($measurements[0]); ?> ;
				if(year=="" || years.indexOf(year)==-1){
					var last_metorg = -1;
					for(i=0;i<size; i++){
						var metorg = jArray[i]['metorg'];
						if(metorg==last_metorg)
							continue;
						last_metorg = metorg;
						loadValues(metorg,"","","");
					}
				}

				else{
					for (i=0; i<size; i++) {
						var aux_year = jArray[i]['year'];
						if(aux_year==year){
							loadValues(jArray[i]['metorg'],jArray[i]['value'],jArray[i]['target'],jArray[i]['expected']);
						}
	    			}
				}
			}

			function loadValues(metorg_id, value, target, expected){
				document.getElementById("value".concat(metorg_id)).value=value;
				document.getElementById("target".concat(metorg_id)).value=target;
				document.getElementById("expected".concat(metorg_id)).value=expected;

			}

			function validate(id){
				var opt = document.getElementById(id).value;
				return changeOnValidation(id, ((!isNaN(parseFloat(opt)) && isFinite(opt)) || opt.length ==0));
			}

			function validate_year(id){
				var opt = document.getElementById(id).value;
				return changeOnValidation(id, ((!isNaN(parseFloat(opt)) && isFinite(opt)) && opt.length ==4 && opt>=1980));
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

			function pageValidate(){
				var jArray= <?php echo json_encode($measurements[0]); ?>;
				var size = <?php echo sizeof($measurements[0]); ?> ;

				var ids =[];

				if(!validate_year('year')){
					alert("Año ingresado inválido");
					return false;
				}

				for (i=0; i<size; i++) {
						var metorg = jArray[i]['metorg'];
						if(ids.indexOf(metorg)==-1){
							ids.push(metorg);
						}
	    		}

	    		for(i=0; i<ids.length; i++){
	    			if(!validate('value'.concat(ids[i])) || !validate('target'.concat(ids[i])) || !validate('expected'.concat(id[i]))){
	    				alert("Los valores ingresados deben ser numéricos");
	    				return false;
	    			}
	    		}
	    		return true;
	    	}
		</script>
	</body>
</html>