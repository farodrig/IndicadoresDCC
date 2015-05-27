<!doctype html>
<html class="fixed">
	<head>
		<!-- Basic -->
		<meta charset="UTF-8">

		<meta name="keywords" content="HTML5 Admin Template" />
		<meta name="description" content="Porto Admin - Responsive HTML5 Template">
		<meta name="author" content="okler.net">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- Web Fonts  -->
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.css" />

		<link rel="stylesheet" href="assets/vendor/font-awesome/css/font-awesome.css" />
		<link rel="stylesheet" href="assets/vendor/magnific-popup/magnific-popup.css" />
		<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/css/datepicker3.css" />

		<!-- Theme CSS -->
		<link rel="stylesheet" href="assets/stylesheets/theme.css" />

		<!-- Skin CSS -->
		<link rel="stylesheet" href="assets/stylesheets/skins/default.css" />

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="assets/stylesheets/theme-custom.css">

		<!-- Head Libs -->
		<script src="assets/vendor/modernizr/modernizr.js"></script>
	</head>
	<body>
		<!-- start: page -->
		<section class="body-sign">
			<div class="center-sign">
				<div class="panel panel-sign">
					<div class="panel-title-sign mt-xl">
					   <a href="<?php echo base_url();?>inicio" class="logo">
						<img src="<?php echo base_url();?>assets/images/u-dashboard-logo.png" height="45" alt="U-Dashboard" />
					   </a>
			           <h2 class="title pull-right text-uppercase text-bold m-none"><i class="fa fa-user mr-xs"></i>Contacto</h2>
					</div>
					<div class="panel-body">
						<?php echo form_open_multipart('contacto', 'id="contact_form"'); ?>
							<div class="form-group mb-lg">
								<label>Nombre Completo</label>
								<input name="name" data-validation="custom" data-validation-regexp="^([a-zA-Zñáéíóú]+\s)([a-zA-Zñáéíóú]+\s?)+$" data-validation-error-msg = "Debes escribir al menos un nombre y un apellido" type="text" class="form-control input-lg" />
							</div>

							<div class="form-group mb-lg">
								<label>Correo Electrónico</label>
								<input name="topic" data-validation="email" data-validation-error-msg="Ingrese un correo electrónico válido" type="email" class="form-control input-lg" />
							</div>

                            <div class="form-group mb-lg">
								<label>Tema</label>
								<input name="topic" data-validation="alphanumeric" data-validation-allowing="-_. " data-validation-error-msg="No puede tener caracteres especiales, excepto '-_. '" type="text" class="form-control input-lg" />
							</div>
							
							<div class="form-group mb-lg">
								<label>Mensaje</label>
								<textarea name="message" data-validation="length" data-validation-length="min5" data-validation-error-msg="Debe tener al menor 5 caracteres de largo" class="form-control" rows="5"></textarea>
							</div>
							
							<div class="row">
								<button type="submit" class="btn btn-primary hidden-xs pull-right">Enviar</button>
							</div>
						<?php  echo form_close(); ?>
						<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
                        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.2.1/jquery.form-validator.min.js"></script>
                        <script> $.validate({
                            form : '#contact_form',
                            onError : function() {
                              alert('Error con la validación. Corrija errores antes de enviar.');
                            },
                            onSuccess : function() {
                              return true;
                            }
                          }); </script>
					</div>
				</div>
			</div>
		</section>
		<!-- end: page -->

		<!-- Vendor -->
		<script src="assets/vendor/jquery/jquery.js"></script>		<script src="assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>		<script src="assets/vendor/bootstrap/js/bootstrap.js"></script>		<script src="assets/vendor/nanoscroller/nanoscroller.js"></script>		<script src="assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>		<script src="assets/vendor/magnific-popup/magnific-popup.js"></script>		<script src="assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>
		
		<!-- Theme Base, Components and Settings -->
		<script src="assets/javascripts/theme.js"></script>
		
		<!-- Theme Custom -->
		<script src="assets/javascripts/theme.custom.js"></script>
		
		<!-- Theme Initialization Files -->
		<script src="assets/javascripts/theme.init.js"></script>

	</body>
</html>