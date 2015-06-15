<!doctype html>
<html class="fixed sidebar-left-collapsed">
	<head>
	   <?php
        $title = "Dashboard";
        include 'partials/head.php';
        ?>
		<link rel="stylesheet" href="<?php echo base_url();?>assets/vendor/select2/select2.css" />
		<link rel="stylesheet" href="<?php echo base_url();?>assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
		<link rel="stylesheet" href="<?php echo base_url();?>assets/vendor/morris/morris.css" />
		<link rel="stylesheet" href="<?php echo base_url();?>assets/vendor/chartist/chartist.css" />
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

					<?php include 'partials/navegation_tmpl.php'; ?>

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


						<?php echo form_open("dashboard");
						if($show_all){
						?>
							<input type="hidden" id="show_all" name="show_all" value="0">
							<button name="id_org" id="id_org" type="submit" class="btn btn-primary" value="<?php echo $id_location;?>" >Ver gráficos seleccionados</button>
						<?php }
						else { ?>
							<input type="hidden" id="show_all" name="show_all" value="1">
							<button name="id_org" id="id_org" type="submit" class="btn btn-primary" value="<?php echo $id_location;?>" >Ver todos los gráficos</button>
						<?php } ?>
						<hr>
						<?php echo form_close(); ?>


						<?php foreach ($data as $metric):?>
							<div class='row'>
								<div class='col-md-6'>
									<section class='panel'>
										<header class='panel-heading'>
											<h2 class='panel-title'><?php echo ucwords(element('name',$metric)); ?></h2>
										</header>
										<div class='panel-body'>
											<div class='chart chart-md' id='<?php echo str_replace(' ', '', $metric['id']); ?>'>
											<script type='text/javascript'>
												var info = <?php echo json_encode($metric['vals']) ?>;
												graph_info[index] = {
													max :  <?php echo $metric['max_y']?>,
													min :  <?php echo $metric['min_y']?>,
													graph_type :  <?php echo $metric['graph_type']?>,
													measure_number : <?php echo $metric['measure_number'] ?>,
													unit : "<?php echo $metric['unit'] ?>"
												};

												data[index] = [{
													data: info,
													label: "<?php echo ucwords($metric['name']);?>",
													color: "#0088cc"
												}];
												index++;
											</script>
										</div>
									</section>
							</div>
							<div class='col-md-6'>
								<section class='panel'>
								<?php echo form_open("export"); ?>
									<header class='panel-heading'>
										<input type="hidden" name="id_org" id="id_org" value="<?php echo $id_location;?>">
										<input type="hidden" name="id_met" id="id_met" value="<?php echo $metric['id'];?>">
										<h2 class='panel-title'><?php echo ucwords($metric['name']); ?> &nbsp;&nbsp;&nbsp;
										<button name="export" id="export" class="btn btn-primary" type="submit">Exportar</button></h2>
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
									<?php echo form_close(); ?>
								</section>

							</div>
						</div>

						<?php endforeach; ?>
					<!-- end: page -->
				</section>
			</div>

		</section>

		<?php include 'partials/footer.php'; ?>

		<!-- Specific Page Vendor -->
		<script src="<?php echo base_url();?>assets/vendor/jquery-appear/jquery.appear.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/jquery-easypiechart/jquery.easypiechart.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/flot/jquery.flot.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/flot-tooltip/jquery.flot.tooltip.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/flot/jquery.flot.pie.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/flot/jquery.flot.categories.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/flot/jquery.flot.resize.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/jquery-sparkline/jquery.sparkline.js"></script>
		<script type="text/javascript" src="<?php echo base_url();?>assets/vendor/flot/jquery.flot.axislabels.js"></script>
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

		<script src="<?php echo base_url();?>assets/javascripts/tables/examples.datatables.row.with.details.js"></script>

		<!-- Examples -->
		<script src="<?php echo base_url();?>assets/javascripts/tables/examples.datatables.row.with.details.js"></script>
		<script src="<?php echo base_url();?>assets/javascripts/tables/examples.datatables.tabletools.js"></script>
		<!--<script src="<?php echo base_url();?>assets/javascripts/tables/examples.datatables.default.js"></script>-->

		<script type="text/javascript">
		var names = <?php echo json_encode($names); ?>; //id's de las metricas
		console.log(graph_info);
		</script>
		<script src="<?php echo base_url();?>js/plot.js"></script>
	</body>
</html>
