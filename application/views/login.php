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
					<!-- Descomentar desde aqui
					<div class="panel-body text-center">
                        <?php echo form_open('');
                        echo form_dropdown('user', $users);
                        echo form_submit('submit', 'Ingresar');
                        echo form_close();
                        ?>
                    </div>
					Hasta Aqui para pasar a DESARROLLO -->
					<div class="panel-body text-center">
					    <script language="JavaScript" src="https://www.u-cursos.cl/upasaporte/javascript?servicio=dashboard_dcc&debug=1"></script>
					</div>

				</div>
			</div>
		</section>
		<!-- end: page -->

        <?php include 'partials/footer.php';?>
        <script type="text/javascript">
		   var error = <?php echo ($error);?>;

		   if (error==0){
			   new PNotify({
					title: 'Exito!',
					text: 'Su solicitud ha sido realizada con exito.',
					type: 'success'
				});
			   }
		   if (error==1){
			   new PNotify({
					title: 'Error!',
					text: 'Usted no tiene acceso a esta aplicación.<br>Comuniquese con el Administrador para poder ingresar.',
					type: 'error'
				});
			}
		</script>
	</body>
</html>
