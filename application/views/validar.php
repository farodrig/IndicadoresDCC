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
							<?php echo form_open('MySession/validate_reject', array('id' => 'validar/rechazar'));?>
							<header class="panel-heading">

								<h2 class="panel-title">Validar</h2>
							</header>

							<div class="panel-body">
								<table class="table table-bordered table-striped mb-none text-center" id="datatable">
									<thead>
										<tr>
											<td>Usuario</td>
											<td>Rol</td>
											<td>Organización</td>
											<td>Métrica</td>
											<td>Tipo</td>
											<td>Unidad</td>
											<td>Valor</td>
											<td>Esperado</td>
											<td>Meta</td>
											<td>Valor anterior</td>
											<td>Esperado anterior</td>
											<td>Meta anterior</td>
											<td>Validar</td>
										</tr>
									</thead>
									<tbody> <!-- no se que pasa aqui -->
										<?php if(count($data) >0)  :?>
										<?php foreach($data as $row) :?>
											<tr class="">
											<td> <?php  echo ucwords($row->name)?> </td>
											<td> <?php echo strcmp(ucwords($row->category),"Finanzas")==0 ? "Asistente de finanzas" :
												(strcmp($row->adcc,"-1")==0 ? "Asistente de unidad" : "Asistente de DCC") ?></td>
											<td> <?php  echo ucwords($row->org_name)?> </td>
											<td> <?php  echo ucwords($row->metric)?> </td>
											<td> <?php  echo ucwords($row->category)?> </td>
											<td> <?php  echo ucwords($row->type)?> </td>
											<td> <?php  echo strcmp($row->value, $row->o_v)==0 ? $row->value : "<b>".$row->value."</b>" ?> </td>
											<td> <?php  echo strcmp($row->target, $row->o_t)==0 ? $row->target : "<b>".$row->target."</b>" ?> </td>
											<td> <?php  echo strcmp($row->expected, $row->o_e)==0 ? $row->expected : "<b>".$row->expected."</b>" ?> </td>
											<td> <?php echo $row->o_v ?> </td>
											<td> <?php echo $row->o_t ?> </td>
											<td> <?php echo $row->o_e ?> </td>
											<td>
												<input id="for-website" value=<?php  echo $row->data_id?> type="checkbox" name=<?php  echo "check". $row->data_id?> />
											</td>
										</tr>
										<?php endforeach?>
										<?php endif?>

									</tbody>
								</table>
								<div class="row">
									<div class="col-sm-9">
										<div class="mb-md">
											<input class="mb-xs mt-xs mr-xs btn btn-primary" type="submit" value="Validar" name="Validar" id="Validar">
											<input class="mb-xs mt-xs mr-xs btn btn-danger" type="submit" value="Rechazar" name="Rechazar" id="Rechazar">
										</div>
									</div>
								</div>
							</div>
							<?php echo form_close();?>
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
					text: 'Se a validado con exito',
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
		</script>
		<script src="assets/vendor/select2/select2.js"></script>
		<script src="assets/vendor/jquery-datatables/media/js/jquery.dataTables.js"></script>
		<script src="assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>

		<!-- Examples -->
		<script src="assets/javascripts/tables/examples.datatables.editable.js"></script>
	</body>
</html>
