<!doctype html>
<html class="fixed sidebar-left-collapsed">
	<head>
	    <?php
        $title = "Validar datos";
        include 'partials/head.php';
        ?>
		<link rel="stylesheet" href="<?php echo base_url();?>chosen/chosen.css">
		<link rel="stylesheet" href="assets/vendor/select2/select2.css" />
		<link rel="stylesheet" href="assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
		<style>
			.panel-group .panel-heading + .panel-collapse > .panel-body {
				border: 1px solid #ddd;
			}
			.panel-group,
			.panel-group .panel,
			.panel-group .panel-heading,
			.panel-group .panel-heading a,
			.panel-group .panel-title,
			.panel-group .panel-title a,
			.panel-group .panel-body,
			.panel-group .panel-group .panel-heading + .panel-collapse > .panel-body {
				border-radius: 2px;
				border: 0;
			}
			.panel-group .panel-heading {
				padding: 0;
			}
			.panel-group .panel-heading a {
				display: block;
				background: #668bb1;
				color: #ffffff;
				padding: 15px;
				text-decoration: none;
				position: relative;
			}
			.panel-group .panel-heading a.collapsed {
				background: #eeeeee;
				color: inherit;
			}
			.panel-group .panel-heading a:after {
				content: '-';
				position: absolute;
				right: 20px;
				top:5px;
				font-size:30px;
			}
			.panel-group .panel-heading a.collapsed:after {
				content: '+';
				margin-top: 0.5%;
			}
			.panel-group .panel-collapse {
				margin-top: 5px !important;
			}
			.panel-group .panel-body {
				background: #ffffff;
				padding: 15px;
			}
			.panel-group .panel {
				background-color: transparent;
			}
			.panel-group .panel-body p:last-child,
			.panel-group .panel-body ul:last-child,
			.panel-group .panel-body ol:last-child {
				margin-bottom: 0;
			}
		</style>
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
					<div class="panel">
						<?php echo form_open('MySession/validate_reject', array('id' => 'validar/rechazar'));?>
						<header class="panel-heading">

							<h2 class="panel-title">Validar</h2>
						</header>

						<div class="panel-body">
							<div class="panel-group" id="accordion">
								<?php
								if(count($data)) {
									foreach ($data as $org) { ?>
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title">
													<a class="collapsed" data-toggle="collapse" data-parent="#accordion"
													   href="#org<?php echo($org['org']->getId()); ?>">
														<?php echo(ucwords($org['org']->getName())); ?>
													</a>
												</h4>
											</div>
											<div id="org<?php echo($org['org']->getId()); ?>"
												 class="panel-collapse collapse">
												<div class="panel-body">
													<div class="panel-group"
														 id="nested<?php echo($org['org']->getId()); ?>">
														<?php foreach ($org['metorg'] as $metorg) { ?>
															<div class="panel panel-default">
																<div class="panel-heading">
																	<h4 class="panel-title">
																		<a data-toggle="collapse"
																		   data-parent="#nested<?php echo($org['org']->getId()); ?>"
																		   href="#metorg<?php echo($metorg['metric']->metorg); ?>">
																			<?php echo(ucwords($metorg['metric']->name)); ?>
																		</a>
																	</h4>
																</div><!--/.panel-heading -->
																<div
																	id="metorg<?php echo($metorg['metric']->metorg); ?>"
																	class="panel-collapse collapse in">
																	<div class="panel-body">
																		<div class="table-responsive">
																			<table id="datatable-details"
																				   class="table table-bordered table-striped mb-none dataTable no-footer"
																				   role="grid">
																				<thead>
																				<tr>
																					<td>Usuario</td>
																					<td>Rol</td>
																					<td>Tipo</td>
																					<td>Año</td>
																					<?php if ($metorg['metric']->x_name) { ?>
																						<td><?php echo $metorg['metric']->x_name; ?> </td>
																					<?php } ?>
																					<td><?php echo($metorg['metric']->y_name); ?></td>
																					<td>Esperado</td>
																					<td>Meta</td>
																					<td>Seleccionar</td>
																				</tr>
																				</thead>
																				<tbody
																					id="tableContent<?php echo($metorg['metric']->metorg); ?>">
																				<?php foreach ($metorg['values'] as $value) { ?>
																					<tr>
																						<td><?php echo($users[$value->updater]); ?></td>
																						<td><?php echo ($metorg['metric']->category == 2) ? "Asistente de finanzas" : "Asistente de unidad" ?></td>
																						<td><?php echo ($metorg['metric']->category == 2) ? "Finanzas" : "Productividad" ?></td>
																						<td><?php echo($value->year); ?></td>
																						<?php if ($metorg['metric']->x_name) { ?>
																							<td><?php echo (!is_null($value->x_value) && $value->x_value) ? $value->x_value : '-'; ?></td>
																						<?php } ?>
																						<td><?php echo (!is_null($value->value)) ? $value->value : '-'; ?></td>
																						<td><?php echo (!is_null($value->expected)) ? $value->expected : '-'; ?></td>
																						<td><?php echo (!is_null($value->target)) ? $value->target : '-'; ?></td>
																						<td></td>
																					</tr>
																					<tr>
																						<td></td>
																						<td></td>
																						<td></td>
																						<td></td>
																						<?php if ($metorg['metric']->x_name) { ?>
																							<td><?php echo (!is_null($value->proposed_x_value) && $value->proposed_x_value && $value->state == 0) ? $value->proposed_x_value : '-'; ?></td>
																						<?php } ?>
																						<td><?php echo (!is_null($value->proposed_value) && $value->state == 0) ? '<b>' . $value->proposed_value . '</b>' : '-'; ?></td>
																						<td><?php echo (!is_null($value->proposed_expected) && $value->state == 0) ? '<b>' . $value->proposed_expected . '</b>' : '-'; ?></td>
																						<td><?php echo (!is_null($value->proposed_target) && $value->state == 0) ? '<b>' . $value->proposed_target . '</b>' : '-'; ?></td>
																						<td>
																							<input id="for-website"
																								   value=<?php echo $value->id ?> type="checkbox"
																								   name=<?php echo "check" . $value->id ?>/>
																						</td>
																					</tr>
																				<?php } ?>
																				</tbody>
																			</table>
																		</div>
																	</div><!--/.panel-body -->
																</div><!--/.panel-collapse -->
															</div>
														<?php } ?>
													</div>
												</div>
											</div>
										</div>
									<?php }
								}
								else{?>
									<h5>No se encuentran datos para validar.</h5>
								<?php } ?>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-9">
								<div class="mb-md">
									<input class="mb-xs mt-xs mr-xs btn btn-primary" type="submit" value="Validar" name="Validar" id="Validar">
									<input class="mb-xs mt-xs mr-xs btn btn-danger" type="submit" value="Rechazar" name="Rechazar" id="Rechazar">
								</div>
							</div>
						</div>
						<?php echo form_close();?>
					</div>
				</section>

					<!-- end: page -->
				</section>
			</div>
		</section>

		<?php include 'partials/footer.php'; ?>
		<script src="<?php echo base_url();?>chosen/chosen.jquery.js" type="text/javascript"></script>
		<script type="text/javascript">

		   var success = <?php echo ($success);?>;

		   if (success==1){
			   new PNotify({
					title: 'Éxito!',
					text: 'Se ha completado la acción con exito',
					type: 'success'
				});
		   }
		   if (success==0){
			   new PNotify({
					title: 'Error!',
					text: 'Intento validar un dato ya validado',
					type: 'error'
				});
		   }

		   $('a[data-toggle="collapse"]').on('click',function(){

			   var objectID=$(this).attr('href');

			   if($(objectID).hasClass('in'))
			   {
				   $(objectID).collapse('hide');
			   }

			   else{
				   $(objectID).collapse('show');
			   }
		   });
		</script>
		<script src="assets/vendor/select2/select2.js"></script>
		<script src="assets/vendor/jquery-datatables/media/js/jquery.dataTables.js"></script>
		<script src="assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>

		<!-- Examples -->
		<script src="assets/javascripts/tables/examples.datatables.editable.js"></script>
	</body>
</html>
