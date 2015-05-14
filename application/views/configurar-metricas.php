<!doctype html>
<html class="fixed sidebar-left-collapsed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<title>Configurar métricas</title>
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
		<link rel="stylesheet" href="<?php echo base_url();?>assets/vendor/pnotify/pnotify.custom.css" />
		<link rel="stylesheet" href="<?php echo base_url();?>assets/vendor/lineicons/css/lineicons.css" />
		<link rel="stylesheet" href="<?php echo base_url();?>assets/vendor/select2/select2.css" />
		<link rel="stylesheet" href="<?php echo base_url();?>assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />


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

			<!-- start: header -->
			<header class="header">
				<div class="logo-container">
					<a href="inicio" class="logo">
						<img src="<?php echo base_url();?>assets/images/u-dashboard-logo.png" height="45" alt="U-Dashboard" />
					</a>
					<div class="visible-xs toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
						<i class="fa fa-bars" aria-label="Toggle sidebar"></i>
					</div>
				</div>

				<!-- start: search & user box -->
				<div class="header-right">

					<ul class="notifications">
						<li>
							<label>Configurar</label>
							<a href="<?php echo base_url();?>configurar" class="notification-icon">
								<i class="fa fa-gear"></i>
							</a>
							<span class="separator"></span>
						</li>
						<li>
							<label>Validar</label>
							<a href="<?php echo base_url();?>validar" class="notification-icon">
								<i class="fa fa-check-circle" style="color:green"></i>
								<span class="badge">1</span>
							</a>

						</li>
					</ul>

					<span class="separator"></span>

					<div id="userbox" class="userbox">
						<a href="#" data-toggle="dropdown">
							<figure class="profile-picture">
								<img src="<?php echo base_url();?>assets/images/!logged-user.jpg" alt="Joseph Doe" class="img-circle" data-lock-picture="assets/images/!logged-user.jpg" />
							</figure>
							<div class="profile-info" data-lock-name="John Doe" data-lock-email="johndoe@okler.com">
								<span class="name">John Doe Junior</span>
								<span class="role">administrator</span>
							</div>

							<i class="fa custom-caret"></i>
						</a>

						<div class="dropdown-menu">
							<ul class="list-unstyled">
								<li class="divider"></li>
								<li>
									<a role="menuitem" tabindex="-1" href="pages-signin.html"><i class="fa fa-power-off"></i> Logout</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<!-- end: search & user box -->
			</header>
			<!-- end: header -->

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
									<li>
										<a href="<?php echo base_url();?>careaunidad">
											<span class="pull-right label label-primary"></span>
											<i class="fa fa-th-large" aria-hidden="true"></i>
											<span>Configurar áreas y unidades</span>
										</a>
									</li>
									<li>
										<a href="<?php echo base_url();?>cdashboard">
											<span class="pull-right label label-primary"></span>
											<i class="fa fa-bar-chart" aria-hidden="true"></i>
											<span>Configurar Dashboard</span>
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
						<h2>Configurar métricas</h2>

						<div class="right-wrapper pull-right">
							<ol class="breadcrumbs">
								<li>
									<a href="<?php echo base_url();?>inicio">
										<i class="fa fa-home"></i>
									</a>
								</li>
								<li><span>Configurar</span></li>
								<li><span>Métricas</span></li>
							</ol>

							<label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
						</div>
					</header>

					<!-- start: page -->
					<?php 
					   echo ('<div class="pane panel-transparent">');
					   echo ('<header class="panel-heading">');
						echo ('<section class="panel"><div class="panel-body" style="background-color:#08C"><h2 class="panel-title"><div class="btn-group-horizontal text-center">
							  <a title='.$department->getId().' id="DCC" class="btn modal-with-form insert" href="#modalForm" style="color: green">
														<i class="licon-plus" aria-hidden="true"></i>
													</a>
													<a title='.$department->getId().' id="DCC" class="btn modal-with-form modify" href="#deleteMetrica" style="color: purple">
														<i class="fa fa-edit" aria-hidden="true"></i>
													</a><label class="text-center" style="color:white">'.ucwords($department->getName()).'</label>
													</div></h2></div></section>');
						echo('</header>');
						echo('<div class="panel-body">');
						    
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
						        echo ('<h2 class="panel-title"><div class="btn-group-horizontal text-center">
													<a class="btn modal-with-form insert" id="'.ucwords($au['area']->getName()).'" title='.$au['area']->getId().' href="#modalForm" style="color: green">
														<i class="licon-plus" aria-hidden="true"></i>
													</a>
													<a class="btn modal-with-form modify" id="'.ucwords($au['area']->getName()).'" title='.$au['area']->getId().' href="#deleteMetrica" style="color: purple">
														<i class="fa fa-edit" aria-hidden="true"></i>
													</a>
													<label class="text-center" style="color:white">'.ucwords($au['area']->getName()).'</label>
												</div></h2><p class="panel-subtitle text-center">'.ucwords($kind).'</p></header>');
						        echo ('<div class="panel-body">');						        
						        echo ('<div class="btn-group-vertical col-md-12">');
						        foreach ($au['unidades'] as $unidad){
										echo(	'<div class="btn btn-default btn-group-horizontal text-center">
													<a class="btn modal-with-form insert" id="'.ucwords($au['area']->getName()).': '.ucwords($unidad->getName()).'" title='.$unidad->getId().' href="#modalForm" style="color: green">
														<i class="licon-plus" aria-hidden="true"></i>
													</a>
													<a class="btn modal-with-form modify" id="'.ucwords($au['area']->getName()).': '.ucwords($unidad->getName()).'" title='.$unidad->getId().' href="#deleteMetrica" style="color: purple">
														<i class="fa fa-edit" aria-hidden="true"></i>
													</a>
													<label class="text-center">'.ucwords($unidad->getName()).'</label>
												</div>');
						        }
						       
						        echo ('</div></div></section></div>');
						        $counter++;
						    }						    
						    ?>
	
							<div id="modalForm" class="modal-block modal-block-primary mfp-hide">
									<?php echo form_open('session/agregarMetrica', array('onsubmit' => "return checkInput();"));?>
									<section class="panel">
										<form>
										<header class="panel-heading">
											<h2 class="panel-title">Añadir métrica</h2>
											<div id="subtitle" name="subtitle"></div>
										</header>
										<div class="panel-body">
												<input type="hidden" name="id_insert" id="id_insert" value="" />
												<div class="form-group mt-lg">
													<label class="col-sm-3 control-label">Nombre de la métrica:</label>
														<div class="col-sm-9">
															<input type="text" name="name" id='name' class="form-control" required/>
														</div>
												</div>
												<div class="form-group mt-lg">
													<label class="col-sm-3 control-label">Unidad de Medida:</label>
														<div class="col-sm-9">
															<input type="text" name="unidad_medida" id='unidad_medida' class="form-control"  required/>
														</div>
												</div>
												<div class="form-group mt-lg">
													<label class="col-sm-3 control-label">Categoria:</label> <!-- 1: Productividad 2:Finanzas -->
														<div class="btn-group dropdown col-sm-9">
															<select name='category' id='category' class="mb-xs mt-xs mr-xs btn btn-default dropdown-toggle">
																<option value=1 defaultSelected>Productividad</option>
																<option value=2>Finanzas</option>
															</select>
														</div>
												</div>
										</div>
										<footer class="panel-footer">
											<div class="row">
												<div class="col-md-12 text-right">
													<input class="btn btn-success" type="submit" value="Agregar" id="submit">
													<button class="btn modal-dismiss" data-dismiss="modal" onClick="borrarDatos()">Cancelar</button>
												</div>
											</div>
										</footer>
										</form>
									</section>
									<?php echo form_close();?>
								</div>
								
								<div id="deleteMetrica" class="modal-block modal-block-primary mfp-hide">
								<?php echo form_open('session/eliminarMetrica', array('id' => 'modificarMetrica')); ?>
									<section class="panel">
										<header class="panel-heading">
											<h2 class="panel-title">Modificar métricas</h2>
											<div id="subtitle2" name="subtitle2"></div>
										</header>
									<div class="panel-body">
											<input type='hidden' name='modificar' id='modificar' value='' />
											<input type='hidden' name='id' id='id' value='' />
											<input type='hidden' name='metrica' id='metrica' value='' />
											<input type='hidden' name='unidad' id='unidad' value='' />
											<input type='hidden' name='tipo' id='tipo' value='' />

											<div id="rows" name="rows"></div>

										<footer class="panel-footer">
											<div class="row">
												<div class="col-md-12 text-right">
													<button class="btn btn-default modal-dismiss" data-dismiss="modal">Cerrar</button>
												</div>
											</div>
										</footer>
									</section>
									<?php echo form_close(); ?>
								</div>
								
						</div>
					</section>

					<!-- end: page -->
				</section>
			</div>
		</section>
		
		<div id="dialog" class="modal-block mfp-hide">
		<?php echo form_open('session/eliminarMetrica', array('id' => 'eliminarMetrica'));?>
			<section class="panel">
				<header class="panel-heading">
					<h2 class="panel-title">Eliminar métrica</h2>
				</header>
				<div class="panel-body">
					<div class="modal-wrapper">
					<div id="hidden_id"></div>
						<div class="modal-text">
							<p>¿Está seguro de eliminar esta métrica?</p>
						</div>
					</div>
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 text-right">
							<input type='hidden' name='modificar' id='modificar' value='' />
							<input type='hidden' name='id2' id='id2' value='' />
							<button type="submit" id="dialogConfirm" class="btn btn-primary">Confirm</button>
							<button id="dialogCancel" class="btn btn-default modal-dismiss">Cancel</button>
						</div>
					</div>
				</footer>
			</section>
			<?php echo form_close(); ?>
		</div>
		

		<!-- Vendor -->
		<script src="<?php echo base_url();?>assets/vendor/jquery/jquery.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/bootstrap/js/bootstrap.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/nanoscroller/nanoscroller.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/magnific-popup/magnific-popup.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>

		<!-- Specific Page Vendor -->
		<script src="<?php echo base_url();?>assets/vendor/pnotify/pnotify.custom.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/select2/select2.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>

		<!-- Theme Base, Components and Settings -->
		<script src="<?php echo base_url();?>assets/javascripts/theme.js"></script>

		<!-- Theme Custom -->
		<script src="<?php echo base_url();?>assets/javascripts/theme.custom.js"></script>

		<!-- Theme Initialization Files -->
		<script src="<?php echo base_url();?>assets/javascripts/theme.init.js"></script>


		<!-- Examples -->
		<script src="<?php echo base_url();?>assets/javascripts/ui-elements/examples.modals.js"></script>
		<script type="text/javascript">
		var table_metrics = <?php echo json_encode($metrics); ?>;
			
		function checkInput(){
			if(document.getElementById('name').value=="" || document.getElementById('unidad_medida').value==""){
				alert("Debe ingresar valores para nombre de métrica y unidad de medida");
				return false;
			}
			return true;
		}

		$('a.insert').click(function( e ) {
			var title = $(this)[0]['attributes']['id'].value;
			$('#subtitle').empty();
			$('<p class="panel-subtitle">'.concat(title,'</p>')).appendTo($('#subtitle'));
			var id = $(this)[0]['attributes']['title'].value;
			document.getElementById('id_insert').value= id;

			
	
		})

		$('a.modify').click(function( e ) {
			var title = $(this)[0]['attributes']['id'].value;
			$('#subtitle2').empty();
			$('<p class="panel-subtitle">'.concat(title,'</p>')).appendTo($('#subtitle2'));
			var id = $(this)[0]['attributes']['title'].value;
			$('#rows').empty();
			$(table_metrics[id]).appendTo($('#rows'));
		})

		function borrarDatos(){
			document.getElementById('name').value="";
			document.getElementById('unidad_medida').value="";
			document.getElementById('category').value=1;
		}
			
		$('#rows').click(function(e) {
			if(e['target']['localName']=="i"){
			if(e['target']['attributes']['class'].value=="fa fa-pencil"){
				var id = e['target']['attributes']['id'].value;
				var row = $('a.edit-row').closest( 'tr[class='.concat(id,']') );
				var tds = row.find('td');
				var actions = row.find('td.actions');

				var id_location = actions[0]['attributes']['title'].value;
				var data = [];

				for(i=0; i<tds['length'];i++)
					data[i] = tds[i]['childNodes'][0]['nodeValue'];

				$(row[0]).children( 'td' ).each(function( i ) {
					var $this = $( this );
					if ( $this.hasClass('actions') ) {
						row.find( '.on-editing' ).removeClass( 'hidden' );
						row.find( '.on-default' ).addClass( 'hidden' );
					} else {
						if(i==1){
							if(data[i]=="Productividad")
								$this.html( '<input type="hidden" class="form-control input-block" value="' + data[i] + '"/>'+
									'<select id="tipo" name="tipo"><option value=1>Productividad</option><option value=2>Finanzas</option></select>');
							else
								$this.html( '<input type="hidden" class="form-control input-block" value="' + data[i] + '"/>'+
									'<select id="tipo" name="tipo"><option value=2>Finanzas</option><option value=1>Productividad</option></select>');
						}
						else{
							$this.html( '<input type="hidden" class="form-control input-block" value="' + data[i] + '"/>'+
							'<input type="text" class="form-control input-block" value="' + data[i] + '"/>' );
						}
					}
				});
			}
			else if(e['target']['attributes']['class'].value=="fa fa-times"){
				var id = e['target']['attributes']['id'].value;
				var row = $('a.edit-row').closest( 'tr[class='.concat(id,']') );
				var inputs = row.find("input[type='hidden']");
				var actions = row.find('td.actions');
				var id_location = actions[0]['attributes']['title'].value;
				var data = [];

				for(i=0; i<3; i++)
					data[i]=inputs[i]['value'];

				$(row[0]).children( 'td' ).each(function( i ) {
					var $this = $( this );
					if ( $this.hasClass('actions') ) {
						row.find( '.on-editing' ).addClass( 'hidden' );
						row.find( '.on-default' ).removeClass( 'hidden' );
					} else {
						$this.html( data[i] );
					}
				});
			}
			else if(e['target']['attributes']['class'].value=="fa fa-save"){
				var id = e['target']['attributes']['id'].value;
				var row = $('a.edit-row').closest( 'tr[class='.concat(id,']') );
				var inputs = row.find("input[type!='hidden']");
				var select = row.find('select')[0]['value'];

				var actions = row.find('td.actions');
				var id_location = actions[0]['attributes']['title'].value;
				var data = [];

				for(i=0; i<2;i++)
					if(i==1){
						data[i]=select;
						data[i+1]=inputs[i]['value'];
					}
					else
						data[i] = inputs[i]['value'];

				document.getElementById('modificar').value = 1;
				document.getElementById('id').value = id_location;
				document.getElementById('metrica').value = data[0];
				document.getElementById('tipo').value = data[1];
				document.getElementById('unidad').value = data[2];
				
				document.getElementById('modificarMetrica').submit();
			}
			else if(e['target']['attributes']['class'].value=="fa fa-trash-o"){
				var id = e['target']['attributes']['id'].value;
				var row = $('a.edit-row').closest( 'tr[class='.concat(id,']') );
				var inputs = row.find('input');

				var actions = row.find('td.actions');
				var id_location = actions[0]['attributes']['title'].value;
		
				document.getElementById('modificar').value = 0;
				document.getElementById('id2').value = id_location;

				$.magnificPopup.open({
						items: {
							src: '#dialog',
							type: 'inline'
						},
						preloader: false,
						modal: true,
						callbacks: {
							change: function() {
									$('#dialog').$confirm.on( 'click', function( e ) {
									$.magnificPopup.close();
								});
							},
							close: function() {
								$('#dialog').$confirm.off( 'click' );
							}
						}
					});
			}}
		})
		</script>
	</body>
</html>
