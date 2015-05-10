<!doctype html>
<html class="fixed sidebar-left-collapsed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<title>Configurar Dashboard</title>
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
		<link rel="stylesheet" href="assets/vendor/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css" />

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
					<a href="<?php echo base_url();?>inicio" class="logo">
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
										<a href="<?php echo base_url();?>careaunidad">
											<span class="pull-right label label-primary"></span>
											<i class="fa fa-th-large" aria-hidden="true"></i>
											<span>Configurar Áreas y Unidades</span>
										</a>
									</li>
									<li>
										<a href="<?php echo base_url();?>cmetrica">
											<span class="pull-right label label-primary"></span>
											<i class="fa fa-server" aria-hidden="true"></i>
											<span>Añadir y Borrar Métricas</span>
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
						<h2>Configurar Dashboard</h2>

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
						<div class="col-md-6">
							<section class="panel-warning">
								<header class="panel-heading">
									<h2 class="panel-title">
										<form id="conf-dash">
											<div class="form-group mt-lg">
												<div class="btn-group-horizontal text-center">
													<form>
													<select class="form-control btn btn-warning">
														<option value="a1">Área 1</option>
														<option value="a2">Área 2</option>
														<option value="a3">Área 3</option>
														<option value="a4">Área 4</option>
													</select>
													<select class="form-control btn btn-warning">
														<option value="--">--</option>
														<option value="u1">Unidad 1</option>
														<option value="u2">Unidad 2</option>
														<option value="u3">Unidad 3</option>
														<option value="u4">Unidad 4</option>
													</select>
													</form>
												</div>
											</div>
										</form>
									</h2>

								</header>
								<div class="panel-body">
									<div class="btn-group-vertical col-md-12">
										<a href="#" id="popover" class="btn btn-default"> Métrica 1 </a>
										<div id="popover-head" class="hide">Configurar métrica</div>
										<div id="popover-content" data-placement="right" class="hide">
											<form>
												<label>Tipo de gráfico:</label>
												<select class="form-control btn btn-default">
														<option value="g1">Líneas</option>
														<option value="g2">Barra</option>
												</select>
												<label>Periodo</label>
												<div class="mt-lg mb-lg slider-primary" data-plugin-slider data-plugin-options='{ "values": [ 25, 75 ], "range": true, "max": 100 }' data-plugin-slider-output="#listenSlider2">
													<input id="listenSlider2" type="hidden" value="25, 75" />
												</div>
												<p class="output2">Desde <b class="min">2008</b> a <b class="max">2012</b></p>
												<label>Mostrar:</label>
												<input id="for-website" value="" type="checkbox" name="mostrar" />
												</br>
												</br>
												<button onclick="$('#popover').popover('hide');" class="btn btn-primary"> Guardar</button>
											</form>
										</div>
										<a href="#" id="popover2" class="btn btn-default"> Métrica 2 </a>
										<a href="#" id="popover3" class="btn btn-default"> Métrica 3 </a>
										<a href="#" id="popover4" class="btn btn-default"> Métrica 4 </a>
									</div>
								</div>
							</section>
						</div>
					</div>
					<!-- end: page -->
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
		<script src="assets/vendor/jquery-ui/js/jquery-ui-1.10.4.custom.js"></script>
		<script src="assets/vendor/jquery-ui-touch-punch/jquery.ui.touch-punch.js"></script>

		<!-- Theme Base, Components and Settings -->
		<script src="assets/javascripts/theme.js"></script>

		<!-- Theme Custom -->
		<script src="assets/javascripts/theme.custom.js"></script>

		<!-- Theme Initialization Files -->
		<script src="assets/javascripts/theme.init.js"></script>

		<!-- Examples -->
		<script src="assets/javascripts/ui-elements/popover.js"></script>

		<!-- Demo Purpose Only -->
		<script>
			(function() {
				$('#listenSlider').change(function() {
					$('.output b').text( this.value );
				});

				$('#listenSlider2').change(function() {
					var min = parseInt(this.value.split('/')[0], 10);
					var max = parseInt(this.value.split('/')[1], 10);

					$('.output2 b.min').text( min );
					$('.output2 b.max').text( max );
				});
			})();
		</script>
	</body>
</html>