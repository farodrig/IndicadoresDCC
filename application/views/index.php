<!doctype html>
<html class="fixed sidebar-left-collapsed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<title>U-Dashboard</title>
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
		<link rel="stylesheet" href="<?php echo base_url();?>assets/vendor/elusive-icons/css/elusive-webfont.css" />

		<!-- Theme CSS -->
		<link rel="stylesheet" href="<?php echo base_url();?>assets/stylesheets/theme.css" />

		<!-- Skin CSS -->
		<link rel="stylesheet" href="<?php echo base_url();?>assets/stylesheets/skins/default.css" />

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="<?php echo base_url();?>assets/stylesheets/theme-custom.css">

		<!-- Head Libs -->
		<script src="<?php echo base_url();?>assets/vendor/modernizr/modernizr.js"></script>
		<style type="text/css">
            <?php
            foreach ($types as $type){
                $color = dechex(hexdec($type['color']) + 60);
                echo('body .btn-info:hover.'.$type['name'].'{');
                echo('background-color: #'.$color.';');
                echo('border-color: #'.$color.' !important;}');

                echo('body .btn-info.'.$type['name'].'{');
                echo('background-color: '.$type['color'].';');
                echo('border-color: '.$type['color'].' !important;}');
            }
            ?>

        </style>
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
									<?php
									$second = false;
									foreach ($types as $type){
									    echo ('<li> <a href="'.base_url().'inicio?sector='.$type['name'].'">');
									    echo ('<span class="pull-right label label-primary"></span>');
									    if ($second){
									       echo ('<i class="fa fa-university" aria-hidden="true"></i>');
									      
									    }
									    else{
									        echo ('<i class="el-icon-group" aria-hidden="true"></i>');
													$second = true;
												}
									    echo ('<span>'.ucwords($type['name']).'</span></a></li>');
									}
									?>
								</ul>
							</nav>
						</div>
					</div>
				</aside>
				<!-- end: sidebar -->

				<section role="main" class="content-body">
					<header class="page-header">
						<h2>U-Dashboard</h2>

						<div class="right-wrapper pull-right">
							<ol class="breadcrumbs">
								<li>
									<a href="<?php echo base_url();?>inicio">
										<i class="fa fa-home"></i>
									</a>
								</li>
								<li><span><?php echo($name);?></span></li>
							</ol>
							<label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
						</div>
					</header>
					<?php
						$dept_id=1;

						if($name == "Soporte"){
							$dept_id=0;
						}
					?>
					<?php echo form_open('dashboard');
					   echo ('<div class="pane panel-transparent">');
					   echo ('<header class="panel-heading">');
						echo ('<h2 class="panel-title"><button type="submit" name="direccion" value='.$dept_id.' class="mb-xs mt-xs mr-xs btn btn-primary btn-lg btn-block"> DCC </button></h2>');
						echo('</header>');
						echo('<div class="panel-body">');
						echo('<input type="hidden" name="user" id="user" value="'.$user.'">');
						    $counter = 0;
						    foreach ($areaunit as $au){
						        $kind = false;
						        $color = false;
						        foreach ($types as $type){
						            if ($type['id']==$au['area']->getType()){
						                $kind = $type['name'];
						                $color = $type['color'];
						            }
						        }
						        if ($counter % 2 == 0 && $counter!=0)
						            echo ('</div>');
						        if ($counter % 2 == 0)
						            echo ('<div class ="row">');
						        echo ('<div class="col-md-6">');
						        echo ('<section class="panel panel-info">');
						        echo ('<header class="panel-heading" style="background-color: '.$color.'">');
						        echo ('<h2 class="panel-title"><button type="submit" name="direccion" value='.$au['area']->getId().' class="mb-xs mt-xs mr-xs btn btn-info btn-lg btn-block '.$kind.'">'.ucwords($au['area']->getName()).'</button></h2>');
										echo ('</header>');
						        echo ('<div class="panel-body">');

						        echo ('<div class="btn-group-vertical col-md-12">');
						        foreach ($au['unidades'] as $unidad){
						            echo('<button type="submit" name="direccion" class="btn btn-default" value='.$unidad->getId().'>'.ucwords($unidad->getName()).'</button>');
						        }
						        echo form_close();
						        echo ('</div></div></section></div>');
						        $counter++;
						    }
						    ?>
						</div>
					</div>
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
		<script type="text/javascript">
			function changePage(){
      			window.location.href = "<?php echo base_url();?>dashboard";
    		}
		</script>

		<!-- Theme Base, Components and Settings -->
		<script src="<?php echo base_url();?>assets/javascripts/theme.js"></script>

		<!-- Theme Custom -->
		<script src="<?php echo base_url();?>assets/javascripts/theme.custom.js"></script>

		<!-- Theme Initialization Files -->
		<script src="<?php echo base_url();?>assets/javascripts/theme.init.js"></script>

	</body>
</html>
