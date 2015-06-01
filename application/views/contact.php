<!doctype html>
<html class="fixed">
	<head>
	   <?php
        $title = "Contacto";
        include 'partials/head.php'; 
        ?>
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

		<?php include 'partials/footer.php'; ?>

	</body>
</html>