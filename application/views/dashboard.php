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

        <?php include 'partials/'.$header.'.php'; ?>

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

					<?php include 'partials/navegation-'.$addData.'.php'; ?>

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


						<?php echo form_open("dashboardAll"); ?>
						<button name="id_org" id="id_org" type="submit" class="btn btn-primary" value="<?php echo $id_location;?>" >Ver todos los gráficos</button>
						<hr>
						<?php echo form_close(); ?>


						<?php foreach ($data as $metric):?>
							<div class='row'>
								<div class='col-md-6'>
									<section class='panel'>
										<header class='panel-heading'>
											<h2 class='panel-title'><?php echo element('name',$metric); ?></h2>
										</header>
										<div class='panel-body'>
											<div class='chart chart-md' id='<?php echo str_replace(' ', '', $metric['id']); ?>'>
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
								<?php echo form_open("export"); ?>
									<header class='panel-heading'>
										<input type="hidden" name="id_org" id="id_org" value="<?php echo $id_location;?>">
										<input type="hidden" name="id_met" id="id_met" value="<?php echo $metric['id'];?>">
										<h2 class='panel-title'><?php echo $metric['name']; ?> &nbsp;&nbsp;&nbsp;
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
		var names = <?php echo json_encode($names); ?>; //id's de las metricas
		</script>
		<script src="<?php echo base_url();?>js/plot.js"></script>
	</body>
</html>
