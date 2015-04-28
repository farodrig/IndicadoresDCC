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
		<link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.css" />

		<link rel="stylesheet" href="assets/vendor/font-awesome/css/font-awesome.css" />
		<link rel="stylesheet" href="assets/vendor/magnific-popup/magnific-popup.css" />
		<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/css/datepicker3.css" />

		<!-- Specific Page Vendor CSS -->
		<link rel="stylesheet" href="assets/vendor/select2/select2.css" />
		<link rel="stylesheet" href="assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
		<link rel="stylesheet" href="assets/vendor/morris/morris.css" />
		<link rel="stylesheet" href="assets/vendor/chartist/chartist.css" />


		<!-- Theme CSS -->
		<link rel="stylesheet" href="assets/stylesheets/theme.css" />

		<!-- Skin CSS -->
		<link rel="stylesheet" href="assets/stylesheets/skins/default.css" />

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="assets/stylesheets/theme-custom.css">

		<!-- Head Libs -->
		<script src="assets/vendor/modernizr/modernizr.js"></script>
	</head>
	<body>
		<section class="body">

			<!-- start: header -->
			<header class="header">
				<div class="logo-container">
					<a href="<?php echo base_url();?>" class="logo">
						<img src="assets/images/u-dashboard-logo.png" height="45" alt="U-Dashboard" />
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
								<img src="assets/images/!logged-user.jpg" alt="Joseph Doe" class="img-circle" data-lock-picture="assets/images/!logged-user.jpg" />
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
										<a href="<?php echo base_url();?>dato">
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
								<li><span>Negocio</span></li>
								<li><span>Área 1</span></li>
								<li><span>Unidad 2</span></li>
							</ol>
							<label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
						</div>
					</header>

					<!-- start: page -->
						<!--<h2 class="mt-none">Flot Charts</h2>
						<p class="mb-lg">Flot is a pure JavaScript plotting library for jQuery, with a focus on simple usage, attractive looks and interactive features.</p> -->

						<!--<h2>Morris Charts</h2>
						<p class="mb-lg">Good-looking charts shouldn't be difficult.</p>-->

						<div class="row">
							<div class="col-md-6">
								<section class="panel">
									<header class="panel-heading">

										<h2 class="panel-title">Métrica 1</h2>
										<!--<p class="panel-subtitle">A style of chart that is created by connecting a series of data points together with a line.</p>-->
									</header>
									<div class="panel-body">

										<!-- Morris: Line -->
										<div class="chart chart-md" id="flotBasic"></div>
										<script type="text/javascript">

											var flotBasicData = [{
												data: [
													[2000, 170],
													[2001, 169],
													[2002, 173],
													[2003, 188],
													[2004, 147],
													[2005, 113],
													[2006, 128],
													[2007, 169],
													[2008, 173],
													[2009, 128],
													[2010, 128]
												],
												label: "Métrica 1",
												color: "#0088cc"
											}];

											// See: assets/javascripts/ui-elements/examples.charts.js for more settings.

										</script>
									</div>
								</section>
							</div>
							<div class="col-md-6">
								<section class="panel">
									<header class="panel-heading">

										<h2 class="panel-title">Métrica 1</h2>
									</header>
									<div class="panel-body">
											<table class="table table-bordered table-striped" id="datatable-default">
												<thead>
													<tr>
														<th>#</th>
														<th>Año</th>
														<th>Métrica</th>
														<th>Valor</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>1</td>
														<td>2000</td>
														<td>Métrica 1</td>
														<td>170</td>
													</tr>
													<tr>
														<td>2</td>
														<td>2001</td>
														<td>Métrica 1</td>
														<td>169</td>
													</tr>
													<tr>
														<td>3</td>
														<td>2002</td>
														<td>Métrica 1</td>
														<td>173</td>
													</tr>
													<tr>
														<td>4</td>
														<td>2003</td>
														<td>Métrica 1</td>
														<td>188</td>
													</tr>
													<tr>
														<td>5</td>
														<td>2004</td>
														<td>Métrica 1</td>
														<td>147</td>
													</tr>
													<tr>
														<td>6</td>
														<td>2005</td>
														<td>Métrica 1</td>
														<td>113</td>
													</tr>
													<tr>
														<td>7</td>
														<td>2006</td>
														<td>Métrica 1</td>
														<td>128</td>
													</tr>
													<tr>
														<td>8</td>
														<td>2007</td>
														<td>Métrica 1</td>
														<td>169</td>
													</tr>
													<tr>
														<td>9</td>
														<td>2008</td>
														<td>Métrica 1</td>
														<td>173</td>
													</tr>
													<tr>
														<td>10</td>
														<td>2009</td>
														<td>Métrica 1</td>
														<td>128</td>
													</tr>
													<tr>
														<td>11</td>
														<td>2010</td>
														<td>Métrica 1</td>
														<td>128</td>
													</tr>
												</tbody>
											</table>
									</div>
								</section>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6">
								<section class="panel">
									<header class="panel-heading">

										<h2 class="panel-title">Métrica 2</h2>
										<!--<p class="panel-subtitle">Stacked Bar Chart.</p>-->
									</header>
									<div class="panel-body">

										<!-- Morris: Area -->
										<div class="chart chart-md" id="flotBars"></div>
										<script type="text/javascript">

											var flotBarsData = [
												[2000, 28],
												[2001, 42],
												[2002, 25],
												[2003, 23],
												[2004, 37],
												[2005, 33],
												[2006, 18],
												[2007, 14],
												[2008, 18],
												[2009, 15],
												[2010, 4],
												[2011, 7]
											];

											// See: assets/javascripts/ui-elements/examples.charts.js for more settings.

										</script>

									</div>
								</section>
							</div>
							<div class="col-md-6">
								<section class="panel">
									<header class="panel-heading">
										<h2 class="panel-title">Métrica 2</h2>
									</header>
									<div class="panel-body">
											<table class="table table-bordered table-striped mb-none" id="datatable-default2">
												<thead>
													<tr>
														<th>#</th>
														<th>Año</th>
														<th>Métrica</th>
														<th>Valor</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>1</td>
														<td>2000</td>
														<td>Métrica 1</td>
														<td>28</td>
													</tr>
													<tr>
														<td>2</td>
														<td>2001</td>
														<td>Métrica 1</td>
														<td>42</td>
													</tr>
													<tr>
														<td>3</td>
														<td>2002</td>
														<td>Métrica 1</td>
														<td>25</td>
													</tr>
													<tr>
														<td>4</td>
														<td>2003</td>
														<td>Métrica 1</td>
														<td>23</td>
													</tr>
													<tr>
														<td>5</td>
														<td>2004</td>
														<td>Métrica 1</td>
														<td>37</td>
													</tr>
													<tr>
														<td>6</td>
														<td>2005</td>
														<td>Métrica 1</td>
														<td>33</td>
													</tr>
													<tr>
														<td>7</td>
														<td>2006</td>
														<td>Métrica 1</td>
														<td>18</td>
													</tr>
													<tr>
														<td>8</td>
														<td>2007</td>
														<td>Métrica 1</td>
														<td>14</td>
													</tr>
													<tr>
														<td>9</td>
														<td>2008</td>
														<td>Métrica 1</td>
														<td>18</td>
													</tr>
													<tr>
														<td>10</td>
														<td>2009</td>
														<td>Métrica 1</td>
														<td>15</td>
													</tr>
													<tr>
														<td>11</td>
														<td>2010</td>
														<td>Métrica 1</td>
														<td>4</td>
													</tr>
													<tr>
														<td>12</td>
														<td>2011</td>
														<td>Métrica 1</td>
														<td>7</td>
													</tr>
												</tbody>
											</table>
									</div>
								</section>
							</div>
						</div>
					<!-- end: page -->
				</section>
			</div>

		</section>

		<!-- Vendor -->
		<script src="assets/vendor/jquery/jquery.js"></script>
		<script src="assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
		<script src="assets/vendor/bootstrap/js/bootstrap.js"></script>
		<script src="assets/vendor/nanoscroller/nanoscroller.js"></script>
		<script src="assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
		<script src="assets/vendor/magnific-popup/magnific-popup.js"></script>
		<script src="assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>

		<!-- Specific Page Vendor -->
		<script src="assets/vendor/jquery-appear/jquery.appear.js"></script>
		<script src="assets/vendor/jquery-easypiechart/jquery.easypiechart.js"></script>
		<script src="assets/vendor/flot/jquery.flot.js"></script>
		<script src="assets/vendor/flot-tooltip/jquery.flot.tooltip.js"></script>
		<script src="assets/vendor/flot/jquery.flot.pie.js"></script>
		<script src="assets/vendor/flot/jquery.flot.categories.js"></script>
		<script src="assets/vendor/flot/jquery.flot.resize.js"></script>
		<script src="assets/vendor/jquery-sparkline/jquery.sparkline.js"></script>
		<script src="assets/vendor/raphael/raphael.js"></script>
		<script src="assets/vendor/morris/morris.js"></script>
		<script src="assets/vendor/gauge/gauge.js"></script>
		<script src="assets/vendor/snap-svg/snap.svg.js"></script>
		<script src="assets/vendor/liquid-meter/liquid.meter.js"></script>
		<script src="assets/vendor/chartist/chartist.js"></script>
		<script src="assets/vendor/select2/select2.js"></script>
		<script src="assets/vendor/jquery-datatables/media/js/jquery.dataTables.js"></script>
		<script src="assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
		<script src="assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>

		<!-- Theme Base, Components and Settings -->
		<script src="assets/javascripts/theme.js"></script>

		<!-- Theme Custom -->
		<script src="assets/javascripts/theme.custom.js"></script>

		<!-- Theme Initialization Files -->
		<script src="assets/javascripts/theme.init.js"></script>
		<script src="assets/javascripts/ui-elements/examples.charts.js"></script>
		<script src="assets/javascripts/tables/examples.datatables.row.with.details.js"></script>

		<!-- Examples -->
		<script src="assets/javascripts/tables/examples.datatables.default.js"></script>
		<script src="assets/javascripts/tables/examples.datatables.row.with.details.js"></script>
		<script src="assets/javascripts/tables/examples.datatables.tabletools.js"></script>

	</body>
</html>