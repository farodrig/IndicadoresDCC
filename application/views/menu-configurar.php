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
										onclick="changePage('<?php echo base_url();?>cdashboardUnidad')">Configurar Dashboard</button>
								</div>
								<div class="panel-body">
									<button type="button" class="mb-xs mt-xs mr-xs btn btn-success btn-lg btn-block"
										onclick="changePage('<?php echo base_url();?>cmetrica/')">Añadir y Borrar Métricas</button>
								</div>
								<div class="panel-body">
									<button type="button" class="mb-xs mt-xs mr-xs btn btn-warning btn-lg btn-block"
										onclick="changePage('<?php echo base_url();?>careaunidad')">Configurar Áreas y Unidades</button>
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
