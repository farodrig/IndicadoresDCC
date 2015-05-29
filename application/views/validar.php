<!doctype html>
<html class="fixed sidebar-left-collapsed">
	<head>
	    <?php
        $title = "Validar datos";
        include 'partials/head.php'; 
        ?>
        		
		<link rel="stylesheet" href="assets/vendor/select2/select2.css" />
		<link rel="stylesheet" href="assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
		
	</head>
	<body>
		<section class="body">

         <?php include 'partials/header_tmpl.php'; ?>

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

								</ul>
							</nav>
						</div>
					</div>

				</aside>
				<!-- end: sidebar -->

				<section role="main" class="content-body">
					<header class="page-header">
						<h2>Validar entrada</h2>

						<div class="right-wrapper pull-right">
							<ol class="breadcrumbs">
								<li>
									<a href="<?php echo base_url();?>inicio">
										<i class="fa fa-home"></i>
									</a>
								</li>
								<li><span>Validar</span></li>
							</ol>
							<label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
						</div>
					</header>

					<!-- start: page -->
						<section class="panel">
							<header class="panel-heading">
								<div class="panel-actions">
									<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
									<a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a>
								</div>

								<h2 class="panel-title">Validar</h2>
							</header>
							<div class="panel-body">
								<table class="table table-bordered table-striped mb-none text-center" id="datatable-editable">
									<thead>
										<tr>
											<th>Usuario</th>
											<th>Rol</th>
											<th>Área</th>
											<th>Unidad</th>
											<th>Métrica</th>
											<th>Tipo</th>
											<th>Valor Anterior</th>
											<th>Valor Nuevo</th>
											<th>Validar</th>
										</tr>
									</thead>
									<tbody> <!-- no se que pasa aqui -->
										<tr class="">
											<td>Juan</td>
											<td>Encargado de área
											</td>
											<td>Pregrado</td>
											<td>Docencia</td>
											<td>Nuevos alumnos</td>
											<td>Valor</td>
											<td>20</td>
											<td>40</td>
											<td>
												<input id="for-website" value="" type="checkbox" name="validar" />
											</td>
										</tr>
									</tbody>
								</table>
								<div class="row">
									<div class="col-sm-9">
										<div class="mb-md">
											<button id="addToTable" class="mb-xs mt-xs mr-xs btn btn-primary">Validar <i class="fa fa-plus"></i></button>
											<button id="addToTable" class="mb-xs mt-xs mr-xs btn btn-danger">Rechazar <i class="fa fa-plus"></i></button>
										</div>
									</div>
								</div>
							</div>
						</section>
					<!-- end: page -->
				</section>
			</div>
		</section>

		<?php include 'partials/footer.php'; ?>

		<script src="assets/vendor/select2/select2.js"></script>
		<script src="assets/vendor/jquery-datatables/media/js/jquery.dataTables.js"></script>
		<script src="assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
		
		<!-- Examples -->		
		<script src="assets/javascripts/tables/examples.datatables.editable.js"></script>
	</body>
</html>
