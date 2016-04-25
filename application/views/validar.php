<!doctype html>
<html class="fixed sidebar-left-collapsed">
	<head>
	    <?php
        $title = "Validar datos";
        include 'partials/head.php';
        ?>
		<link rel="stylesheet" href="<?php echo base_url();?>assets/vendor/bootstrap/css/bootstrap.min.css" />

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

			td.black {
				color: black;
			}

			label{
				font-weight: normal !important;
			}

			div.checkbox-custom{
				padding: 0 0 0 0;
			}
		</style>
	</head>
	<body>
		<section class="body">

         <?php include 'partials/header_tmpl.php'; ?>

			<div class="inner-wrapper">
				<!-- start: sidebar -->
				<?php
				$navData=[];
				include 'partials/navigation.php';
				?>
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
						<?php
						if(count($data)) {
							echo form_open('MySession/validate_reject', array('id' => 'validar/rechazar'));?>
						<div class="panel-body">
							<div class="panel-group" id="accordion">
									<?php foreach ($data as $org) { ?>
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title">
													<a class="" data-toggle="collapse" data-parent="#accordion"
													   href="#org<?php echo($org['org']->getId()); ?>">
														<?php echo(ucwords($org['org']->getName())); ?>
													</a>
												</h4>
											</div>
											<div id="org<?php echo($org['org']->getId()); ?>"
												 class="panel-collapse collapse in">
												<div class="panel-body">
													<div class="panel-group"
														 id="nested<?php echo($org['org']->getId()); ?>">
														<?php foreach ($org['metorg'] as $metorg) { 
																$permits = $metorg['permits'];
															?>
															<div class="panel panel-default">
																<div class="panel-heading">
																	<h4 class="panel-title">
																		<a data-toggle="collapse"
																		   data-parent="#nested<?php echo($org['org']->getId()); ?>"
																		   href="#metorg<?php echo($metorg['metric']->metorg); ?>">
																			<?php echo(ucwords($metorg['metric']->name)); ?> - <?php echo ($metorg['metric']->category == 2) ? "Finanzas" : "Productividad" ?>
																		</a>
																	</h4>
																</div><!--/.panel-heading -->
																<div id="metorg<?php echo($metorg['metric']->metorg); ?>" class="panel-collapse collapse in">
																	<div class="panel-body">
																		<div class="table-responsive">
																			<table id="datatable-details"
																				   class="table table-bordered table-striped mb-none dataTable no-footer"
																				   role="grid">
																				<thead>
																				<tr>
																					<td class="text-center">Usuario</td>
																					<td class="text-center">Rol</td>
																					<td class="text-center">Año</td>
																						<?php if ($metorg['metric']->x_name) { ?>
																								<td class="text-center"><?php echo $metorg['metric']->x_name; ?> </td>
																						<?php } ?>
																					<td class="text-center"><?php echo($metorg['metric']->y_name); ?></td>
																					<td class="text-center">Esperado</td>
																					<td class="text-center">Meta</td>
																					<td class="text-center col-sm-2">Seleccionar Propuestas</td>
																				</tr>
																				</thead>
																				<tbody
																					id="tableContent<?php echo($metorg['metric']->metorg); ?>">
																				<?php
																				$xVal = "";
																				$year = "";
																				foreach ($metorg['values'] as $value) {
																					if($xVal!= $value->x_value || $year != $value->year){
																						$xVal = $value->x_value;
																						$year = $value->year;
																				?>
																					<tr class="success">
																						<td></td>
																						<td></td>
																						<td style="color: black !important;"><?php echo($value->year); ?></td>
																						<?php if ($metorg['metric']->x_name) { ?>
																								<td style="color: black !important;"><?php echo (!is_null($value->x_value) && $value->x_value) ? $value->x_value : '-'; ?></td>
																						<?php } ?>
																							<td style="color: black !important;"><?php echo (!is_null($value->value)) ? $value->value : '-'; ?></td>
																							<td style="color: black !important;"><?php echo (!is_null($value->expected)) ? $value->expected : '-'; ?></td>
																							<td style="color: black !important;"><?php echo (!is_null($value->target)) ? $value->target : '-'; ?></td>
																						<td></td>
																					</tr>
																					<?php } ?>
																					<tr>
																						<td><?php echo($users[$value->updater]); ?></td>
																						<td><?php echo ($metorg['metric']->category == 2) ? "Asistente de finanzas" : "Asistente de unidad" ?></td>
																						<td></td>
																						<?php if ($permits['valor']){
																								if ($metorg['metric']->x_name) { ?>
																									<td><?php echo (!is_null($value->proposed_x_value) && $value->proposed_x_value && $value->state == 0) ? $value->proposed_x_value : '-'; ?></td>
																								<?php } ?>
																									<td><?php echo (!is_null($value->proposed_value) && $value->state == 0) ? '<b>' . $value->proposed_value . '</b>' : '-'; ?></td>
																						<?php }
																							else{
																								if ($metorg['metric']->x_name) { echo '<td>-</td>';}
																								echo '<td>-</td>';
																							}
																						if($permits['meta']){ ?>
																							<td><?php echo (!is_null($value->proposed_expected) && $value->state == 0) ? '<b>' . $value->proposed_expected . '</b>' : '-'; ?></td>
																							<td><?php echo (!is_null($value->proposed_target) && $value->state == 0) ? '<b>' . $value->proposed_target . '</b>' : '-'; ?></td>
																						<?php }
																						else{
																							echo '<td>-</td>';
																							echo '<td>-</td>';
																						}?>
																						<td class="text-center">
																							<div class="checkbox-custom checkbox-default col-sm-offset-1">
																								<input type="checkbox" class="styled" name="ids[]" id="checkbox<?php echo $value->id ?>" value="<?php echo $value->id ?>">
																								<label for="checkbox<?php echo $value->id ?>"><?php echo ($value->state == 0 ? "(Modificar)" : "(Eliminar)");?></label>
																							</div>
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
									<?php } ?>
							</div>
							<div class="row pull-right">
								<div class="mb-md">
									<input class="mb-xs mt-xs mr-xs btn btn-primary" type="submit" value="Validar Propuestas Seleccionadas" name="Validar" id="Validar">
									<input class="mb-xs mt-xs mr-xs btn btn-danger" type="submit" value="Rechazar Propuestas Seleccionadas" name="Rechazar" id="Rechazar">
								</div>
							</div>
						</div>
						<?php echo form_close();
							}
						else{ ?>
							<h5>No se encuentran datos para validar.</h5>
						<?php } ?>
					</div>
				</section>

					<!-- end: page -->
				</section>
			</div>
		</section>

		<?php include 'partials/footer.php'; ?>
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
					text: 'Ha ocurrido un error, intentelo nuevamente.',
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
	</body>
</html>
