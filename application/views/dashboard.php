<!doctype html>
<html class="fixed sidebar-left-collapsed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<title>Dashboard</title>
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
		<link rel="stylesheet" href="<?php echo base_url();?>assets/vendor/select2/select2.css" />
		<link rel="stylesheet" href="<?php echo base_url();?>assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
		<link rel="stylesheet" href="<?php echo base_url();?>assets/vendor/morris/morris.css" />
		<link rel="stylesheet" href="<?php echo base_url();?>assets/vendor/chartist/chartist.css" />


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
					<a href="<?php echo base_url();?>" class="logo">
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
							<span class="separator"></span>
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
										<a href="<?php echo base_url();?>Dashboard/formAddData?var=<?php echo $id_location ?>">
											<span class="pull-right label label-primary"></span>
											<i class="fa fa-plus-square" aria-hidden="true"></i>
											<span>Añadir Datos</span>
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
						<h2>Dashboard</h2>

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
							</ol>
							<label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
						</div>
					</header>

					<!-- start: page -->
						<script type="text/javascript">
							var data = [];
							var graph_info = [];
							var index = 0;
						</script>
						<?php foreach ($data as $metric):?> 
							<div class='row'>
								<div class='col-md-6'>
									<section class='panel'>
										<header class='panel-heading'>
											<h2 class='panel-title'><?php echo element('name',$metric); ?></h2>
										</header>
										<div class='panel-body'>
											<div class='chart chart-md' style="width:100%;height:300px;" name='<?php echo element('name',$metric); ?>' id='<?php echo element('name',$metric); ?>'>.</div>
											<script type='text/javascript'>
												var info = <?php echo json_encode($metric['vals']) ?>;
												graph_info[index] = {
													max :  <?php echo $metric['max_y']?>,
													min :  <?php echo $metric['min_y']?>,
													graph_type :  <?php echo $metric['graph_type']?>,
													measure_number : <?php echo $metric['measure_number'] ?>

												};

												data[index] = [{
													data: info,
													label: "<?php echo $metric['name']?>",
													color: "#0088cc"
												}];
												index++;
											</script>
										</div>
									</section>
							</div>
							<div class='col-md-6'>
								<section class='panel'>
									<header class='panel-heading'>
										<h2 class='panel-title'><?php echo $metric['name']; ?></h2>
									</header>
									<div class='panel-body'>
										<table class="table table-bordered table-striped mb-none" id='datatable-default'>
											<thead>
												<tr>
													<th>#</th>
													<th>Año</th>
													<th>Valor</th>
													<th>Esperado</th>
													<th>Meta</th>
												</tr>
											</thead>
											<tbody><?php echo $metric['table'];?></tbody>
										</table>
									</div>
								</section>
							</div>
						</div>
						
						<?php endforeach; ?>
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
		<script src="<?php echo base_url();?>assets/vendor/jquery-appear/jquery.appear.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/jquery-easypiechart/jquery.easypiechart.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/flot/jquery.flot.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/flot-tooltip/jquery.flot.tooltip.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/flot/jquery.flot.pie.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/flot/jquery.flot.categories.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/flot/jquery.flot.resize.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/jquery-sparkline/jquery.sparkline.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/raphael/raphael.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/morris/morris.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/gauge/gauge.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/snap-svg/snap.svg.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/liquid-meter/liquid.meter.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/chartist/chartist.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/select2/select2.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>

		<!-- Theme Base, Components and Settings -->
		<script src="<?php echo base_url();?>assets/javascripts/theme.js"></script>

		<!-- Theme Custom -->
		<script src="<?php echo base_url();?>assets/javascripts/theme.custom.js"></script>

		<!-- Theme Initialization Files -->
		<script src="<?php echo base_url();?>assets/javascripts/theme.init.js"></script>
		<script src="<?php echo base_url();?>assets/javascripts/tables/examples.datatables.row.with.details.js"></script>

		<!-- Examples -->
		<script src="<?php echo base_url();?>assets/javascripts/tables/examples.datatables.row.with.details.js"></script>
		<script src="<?php echo base_url();?>assets/javascripts/tables/examples.datatables.tabletools.js"></script>
		<!--<script src="<?php echo base_url();?>assets/javascripts/tables/examples.datatables.default.js"></script>-->

		<script type="text/javascript">
			var names = <?php echo json_encode($names); ?>;
			var size = names.length;

			for(i = 0; i<size; i++){

				if(graph_info[i]['graph_type']==2){ //Es de linea
					$(document).ready((function( $ ) {

						'use strict';

						(function() {
							var plot = $.plot('#'.concat(names[i]), data[i], {
								series: {
									lines: {
										show: true,
										fill: false,
										lineWidth: 1,
										fillColor: {
											colors: [{
												opacity: 0.45
												}, {
												opacity: 0.45
											}]
										}
									},
									points: {
										show: true
									},
									shadowSize: 0
								},
								grid: {
									hoverable: true,
									clickable: true,
									borderColor: 'rgba(0,0,0,0.1)',
									borderWidth: 1,
									labelMargin: 15,
									backgroundColor: 'transparent'
								},
								yaxis: {
									min: graph_info[i]['min'],
									max: graph_info[i]['max'],
									color: 'rgba(0,0,0,0.1)'
								},
								xaxis: {
									ticks: graph_info[i]['measure_number']==0 ? 1 :  graph_info[i]['measure_number']==0,
									color: 'rgba(0,0,0,0.1)'
								},
								tooltip: true,
								tooltipOpts: {
									content: '%s: Valor para %x es %y',
									shifts: {
										x: -60,
										y: 25
									},
									defaultTheme: false
								}
							});
						})()}).apply( this, [ jQuery ]));
				}
				else{
					$(document).ready((function( $ ) {

						'use strict';

						(function() {
							var plot = $.plot('#'.concat(names[i]), [data[i][0]['data']], {
								colors: ['#8CC9E8'],
								series: {
									bars: {
										show: true,
										barWidth: 0.8,
										align: 'center'
									}
								},
								xaxis: {
									mode: 'categories',
									tickLength: 0
								},
								grid: {
									hoverable: true,
									clickable: true,
									borderColor: 'rgba(0,0,0,0.1)',
									borderWidth: 1,
									labelMargin: 15,
									backgroundColor: 'transparent'
								},
								tooltip: true,
								tooltipOpts: {
									content: '%y',
									shifts: {
										x: -10,
										y: 20
									},
								defaultTheme: false
								}
							});
						})()}).apply( this, [ jQuery ]));

				}
		
			}
		</script>
	</body>
</html>