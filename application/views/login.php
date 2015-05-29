<!doctype html>
<html class="fixed">
	<head>
	   <?php
        $title = "Login";
        include 'partials/head.php'; 
        ?>
	</head>
	<body>
		<!-- start: page -->
		<section class="body-sign">
			<div class="center-sign">
				<div class="panel panel-sign">
					<div class="panel-title-sign mt-xl text-right">
					   <a href="<?php echo base_url();?>" class="logo pull-left">
					       <img src="<?php echo base_url();?>assets/images/u-dashboard-logo.png" height="54" alt="Porto Admin" />
				        </a>
						<h2 class="title text-uppercase text-bold m-none"><i class="fa fa-user mr-xs"></i> Login</h2>
					</div>
					<div class="panel-body text-center">
						<a href="<?php echo base_url();?>inicio" class="btn btn-default"> U-Pasaporte </a>
					</div>
				</div>
			</div>
		</section>
		<!-- end: page -->

		<?php include 'partials/footer.php'; ?>

	</body>
</html>