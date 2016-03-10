<!doctype html>
<html class="fixed sidebar-left-collapsed">
	<head>
	    <?php
        $title = "Configurar métricas";
        include 'partials/head.php';
        ?>

		<link rel="stylesheet" href="<?php echo base_url();?>assets/vendor/select2/select2.css" />
		<link rel="stylesheet" href="<?php echo base_url();?>assets/vendor/jquery-datatables-bs3/assets/css/datatables.css"
	</head>
	<body>
		<section class="body">

			<?php include 'partials/header_tmpl.php'; ?>

			<div class="inner-wrapper">

				<!-- start: sidebar -->
				<?php
				$navData=[['url'=>'inicio', 'name'=>'U-Dashboard', 'icon'=>'fa fa-home'],
					['url'=>'careaunidad', 'name'=>'Configurar Áreas y Unidades', 'icon'=>'fa fa-th-large'],
					['url'=>'cdashboardUnidad', 'name'=>'Configurar Dashboard', 'icon'=>'fa fa-bar-chart'],
					['url'=>'foda/config', 'name'=>'Configurar FODAs', 'icon'=>'fa fa-book']];
				include 'partials/navigation.php';
				?>
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
			        $c = 0;
			        foreach ($departments as $dpto){
			            $c++;
			            $counter = 0;
			            $kind = $dpto['type']['name'];
			            $color = $dpto['type']['color'];
			            if($c==count($departments))
			              echo('<section class="panel panel-transparent">');
			            else
			              echo('<section class="panel panel-transparent Areas">');
			            ?>
			            <h2 style="text-align:center;"><?php echo(ucwords($kind))?></h2>
			            <hr>

			            <header class="panel-heading" style="padding-left: 15px; padding-right: 15px;">
			              <div class="row">
			                 <div style="background-color:#08C" class="panel-body">
			                     <h2 class="panel-title">
			                         <div class="btn-group-horizontal text-center">
							             <a style="color: green" href="#modalForm" class="btn modal-with-form insert" id="DCC" title="<?php echo($dpto['department']->getId())?>">
											<i aria-hidden="true" class="licon-plus"></i>
										</a>
										<a style="color: purple" href="#deleteMetrica" class="btn modal-with-form modify" id="DCC" title="<?php echo($dpto['department']->getId())?>">
								            <i aria-hidden="true" class="fa fa-edit"></i>
										</a>
										<label style="color:white" class="text-center">DCC</label>
									 </div>
								 </h2>
							 </div>
						  </div>
					    </header>

					    <div class="panel-body">
			            <?php
			            foreach ($dpto['areas'] as $area){
				            if ($counter % 2 == 0 && $counter!=0)
				                echo ('</div>');
				            if ($counter % 2 == 0)
				                echo ('<div class ="row">');
				            ?>
				            <div class="col-md-6">
				              <section class="panel panel-info">
				                  <header class="panel-heading" style="background-color: <?php echo($color);?>">
				                      <h2 class="panel-title">
					                      <div class="btn-group-horizontal text-center">
                                              <a style="color: green" href="#modalForm" title="<?php echo(ucwords($area['area']->getId()));?>" id="<?php echo(ucwords($area['area']->getName()));?>" class="btn modal-with-form insert">
									            <i aria-hidden="true" class="licon-plus"></i>
    									      </a>
    										  <a style="color: purple" href="#deleteMetrica" title="<?php echo(ucwords($area['area']->getId()));?>" id="<?php echo(ucwords($area['area']->getName()));?>" class="btn modal-with-form modify">
										  	    <i aria-hidden="true" class="fa fa-edit"></i>
    										  </a>
					                          <label class="text-center" style="color:white"><?php echo(ucwords($area['area']->getName()));?></label>
					                      </div>
				                      </h2>
			                      </header>
				                  <div class="panel-body">
				                      <div class="btn-group-vertical col-md-12">
				            <?php
				            foreach ($area['unidades'] as $unidad){
				                ?>
						                <div class="btn btn-default btn-group-horizontal text-center">
						                  <a style="color: green" href="#modalForm" title="<?php echo(ucwords($unidad->getId()));?>" id="<?php echo(ucwords($area['area']->getName()));?>: <?php echo(ucwords($unidad->getName()));?>" class="btn modal-with-form insert">
											<i aria-hidden="true" class="licon-plus"></i>
										  </a>
										  <a style="color: purple" href="#deleteMetrica" title="<?php echo(ucwords($unidad->getId()));?>" id="<?php echo(ucwords($area['area']->getName()));?>: <?php echo(ucwords($unidad->getName()));?>" class="btn modal-with-form modify">
											<i aria-hidden="true" class="fa fa-edit"></i>
										  </a>
						                  <label class="text-center"><?php echo(ucwords($unidad->getName()));?></label>
					                    </div>
				            <?php
				            }
				            ?>
				                      </div>
			                      </div>
		                      </section>
	                        </div>
				            <?php
				            $counter++;
				            if($counter==count($dpto['areas'])){
				                echo ('</div>');
				            }
			            }
			            echo ('</div>');
			        }
				    ?>

							<div id="modalForm" class="modal-block modal-block-primary mfp-hide">
									<?php echo form_open('MySession/agregarMetrica', array('onsubmit' => "return checkInput();"));?>
									<section class="panel">
										<form>
										<header class="panel-heading">
											<h2 class="panel-title">Añadir métrica</h2>
											<div id="subtitle" name="subtitle"></div>
										</header>
										<div class="panel-body">
											<input type="hidden" name="id_insert" id="id_insert" value="" />
											<div class="form-group mt-lg">
												<label class="col-sm-3 control-label">Nombre del Eje Y (Valores):</label>
													<div class="col-sm-9">
														<input type="text" name="y_name" id='y_name' class="form-control" required/>
													</div>
											</div>
											<div class="form-group mt-lg">
												<label class="col-sm-3 control-label">Unidad de Medida del Eje Y:</label>
													<div class="col-sm-9">
														<input type="text" name="y_unit" id='y_unit' class="form-control"  required/>
													</div>
											</div>
											<div class="form-group mt-lg">
												<label class="col-sm-3 control-label">Nombre del Eje X (Dimensiones):</label>
												<div class="col-sm-9">
													<input type="text" name="x_name" id='x_name' class="form-control" required/>
												</div>
											</div>
											<div class="form-group mt-lg">
												<label class="col-sm-3 control-label">Unidad de Medida del Eje X:</label>
												<div class="col-sm-9">
													<input type="text" name="x_unit" id='x_unit' class="form-control"  required/>
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

								<div id="deleteMetrica" class="modal-block modal-block-lg modal-block-primary mfp-hide">
								<?php echo form_open('MySession/eliminarMetrica', array('id' => 'modificarMetrica')); ?>
									<section class="panel">
										<header class="panel-heading">
											<h2 class="panel-title">Modificar métricas</h2>
											<div id="subtitle2" name="subtitle2"></div>
										</header>
									<div class="panel-body">
											<input type='hidden' name='modificar' id='modificar' value='' />
											<input type='hidden' name='id' id='id' value='' />
											<input type='hidden' name='metrica_y' id='metrica_y' value='' />
											<input type='hidden' name='unidad_y' id='unidad_y' value='' />
											<input type='hidden' name='metrica_x' id='metrica_x' value='' />
											<input type='hidden' name='unidad_x' id='unidad_x' value='' />
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
		<?php echo form_open('MySession/eliminarMetrica', array('id' => 'eliminarMetrica'));?>
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

        <?php include 'partials/footer.php'; ?>

		<script src="<?php echo base_url();?>assets/vendor/select2/select2.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>

		<script type="text/javascript">
		var table_metrics = <?php echo json_encode($metrics); ?>;
		var org_metrics = Object.keys(table_metrics);
		var table_head = "<table class='table table-bordered table-striped mb-none text-center' id='config-metricas'>".concat("<thead>",
									"<tr>",
											"<th class='text-center'>Medida Eje Y</th>",
											"<th class='text-center'>Categoria</th>",
											"<th class='text-center'>Unidad de medida Eje Y</th>",
											"<th class='text-center'>Medida Eje X</th>",
											"<th class='text-center'>Unidad de Medida Eje X</th>",
											"<th class='text-center'>Acciones</th>",
										"</tr>",
									"</thead>",
									"<tbody>");
		var values_mod = [];

		function setVal(e,id){
			values_mod[id] = e.value;
		}

		function checkInput(){
			if($('#y_name').val()=="" || $('#y_unit').val()=="" || $('#x_name').val()=="" || $('#x_unit').val()==""){
				alert("Debe ingresar valores para nombre de métrica y unidad de medida");
				return false;
			}
			return true;
		}

		//Agrega el id de la organización al input que se enviará y agrega el nombre al subtitulo del modal
		$('a.insert').click(function( e ) {
			var title = $(this)[0]['attributes']['id'].value;
			$('#subtitle').empty();
			$('<p class="panel-subtitle">'.concat(title,'</p>')).appendTo($('#subtitle'));
			var id = $(this)[0]['attributes']['title'].value;
			$('#id_insert').val(id);
		});

		//Carga los datos necesarios para modificar.
		$('a.modify').click(function( e ) {
			var title = $(this)[0]['attributes']['id'].value;
			$('#subtitle2').empty();
			$('<p class="panel-subtitle">'.concat(title,'</p>')).appendTo($('#subtitle2'));
			var id = $(this)[0]['attributes']['title'].value;
			$('#rows').empty();

			var table = table_head;
			if(org_metrics.indexOf(id)!=-1){
				for(key in table_metrics[id]){
					table = table.concat('<tr class=',table_metrics[id][key]['metorg'],'>',
					'<td>',table_metrics[id][key]['y_name'],'</td>',
					'<td>',table_metrics[id][key]['category'],'</td>',
					'<td>',table_metrics[id][key]['y_unit'],'</td>',
					'<td>',table_metrics[id][key]['x_name'],'</td>',
					'<td>',table_metrics[id][key]['x_unit'],'</td>',
					'<td class="actions" title=',table_metrics[id][key]['metorg'],'>',
						'<a href="#" class="hidden on-editing save-row" ><i class="fa fa-save" id=',table_metrics[id][key]['metorg'],'></i></a>',
						'<a href="#" class="hidden on-editing cancel-row" ><i class="fa fa-times" id=',table_metrics[id][key]['metorg'],'></i></a>',
						'<a href="#" class="on-default edit-row"><i class="fa fa-pencil" id=',table_metrics[id][key]['metorg'],'></i></a>',
						'<a href="#" class="on-default remove-row"><i class="fa fa-trash-o" id=',table_metrics[id][key]['metorg'],'></i></a>',
					'</td></tr>');
				}

			}
			table = table.concat('</tbody></table>');
			$(table).appendTo($('#rows'));
		});

		//Borra los datos que se encuentren en el modal de inputs
		function borrarDatos(){
			$('#y_name').val("");
			$('#y_unit').val("");
			$('#category').val(1);
			$('#x_name').val("");
			$('#x_unit').val("");
		}


		$('#rows').click(function(e) {
			if(e['target']['localName']!="i")
				return;

			var id = e['target']['attributes']['id'].value;
			var row = $('a.edit-row').closest( 'tr[class='.concat(id,']') );
			var actions = row.find('td.actions');
			var id_location = actions[0]['attributes']['title'].value;

			//accion de editar los datos de una metrica
			if(e['target']['attributes']['class'].value=="fa fa-pencil"){
				var tds = row.find('td');
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
								'<input type="text" class="form-control input-block" value="' + data[i] + '" onchange="setVal(this, i)" required/>' );
						}
					}
				});
			}
			//Accion de cancelas edicion de datos a una metrica
			else if(e['target']['attributes']['class'].value=="fa fa-times"){
				var inputs = row.find("input[type='hidden']");
				var data = [];

				for(i=0; i<5; i++)
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
			//Mandar los datos modificados si estan correctos
			else if(e['target']['attributes']['class'].value=="fa fa-save"){
				var inputs = row.find("input[type!='hidden']");
				var select = row.find('select')[0]['value'];

				$('#modificar').val(1);
				$('#id').val(id_location);
				$('#metrica_y').val(inputs[0]['value']);
				$('#tipo').val(select);
				$('#unidad_y').val(inputs[1]['value']);
				$('#metrica_x').val(inputs[2]['value']);
				$('#unidad_x').val(inputs[3]['value']);

				if(inputs[0]['value']=="" || inputs[1]['value']=="" || inputs[2]['value']=="" || inputs[3]['value']==""){
					alert("No puede dejar campos en blanco");
				}
				else{
					$('#modificarMetrica').submit();
				}

			}
			//Eliminar metrica
			else if(e['target']['attributes']['class'].value=="fa fa-trash-o"){
				var inputs = row.find('input');
				$('#modificar').val(0);
				$('#id2').val(id_location);

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
			}
		});

		var success = <?php echo($success);?>;

		   if (success==1){
			   	new PNotify({
					title: 'Éxito!',
					text: 'Su solicitud ha sido realizada con éxito.',
					type: 'success'
				});
			}
			if (success==0){
				new PNotify({
					title: 'Error!',
					text: 'Solicitud no pudo ser realizada. Recuerde que las entradas no pueden incluir símbolos',
					type: 'error'
				});
			}
		</script>
	</body>
</html>
