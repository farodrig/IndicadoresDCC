<!doctype html>
<html class="fixed sidebar-left-collapsed">
	<head>	
	    <?php
        $title = "Configurar";
        include 'partials/head.php'; 
        ?>
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
						<h2>Configurar</h2>

						<div class="right-wrapper pull-right">
							<ol class="breadcrumbs">
								<li>
									<a href="<?php echo base_url();?>inicio">
										<i class="fa fa-home"></i>
									</a>
								</li>
								<li><span>Configurar</span></li>
							</ol>
							<label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
						</div>
					</header>
						<div class="col-md-12">
							<section class="panel">
								<header class="panel-heading">
									<h2 class="panel-title">Menú de configuración</h2>
									<p class="panel-subtitle">Cada botón permite realizar distintas configuraciones</p>
								</header>
								<div class="panel-body">
									<button type="button" class="mb-xs mt-xs mr-xs btn btn-primary btn-lg btn-block"
										onclick="changePage('<?php echo base_url();?>config/dashboard')">Configurar Dashboard</button>
								</div>
								<div class="panel-body">
									<button type="button" class="mb-xs mt-xs mr-xs btn btn-success btn-lg btn-block"
										onclick="changePage('<?php echo base_url();?>config/metricas/')">Añadir y Borrar Métricas</button>
								</div>
								<div class="panel-body">
									<button type="button" class="mb-xs mt-xs mr-xs btn btn-warning btn-lg btn-block"
										onclick="changePage('<?php echo base_url();?>config/organizacion')">Configurar Áreas y Unidades</button>
								</div>
							</section>
					</div>
					<!-- end: page -->
				</section>
			</div>
		</section>

		<?php include 'partials/footer.php'; ?>
		
        <script type="text/javascript">
			function changePage(ref){
      			window.location.href = ref;
    		}
		</script>
	</body>
</html>
