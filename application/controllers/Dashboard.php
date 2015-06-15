<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
	private $dashboardModel;

	function __construct()
	{
		parent::__construct();
		$this->load->model('Dashboard_model');
		$this->dashboardModel = $this->Dashboard_model;

	}

	function formAddData() // funcion que lista todas las metricas y las deja como objeto cada una por lo tanto se puede recorrer el arreglo
	                           // y llamar a cada valor del arreglo como liberia ejemplo mas abajo
	                           // esto sirve para cuando se llama de una vista para completar por ejemplo una tabla
	{
		$this->load->library('session');
		$user = $this->session->userdata("rut");
    	$permits = array('director' => $this->session->userdata("director"),
    						'visualizador' => $this->session->userdata("visualizador"),
    						'asistente_unidad' => $this->session->userdata("asistente_unidad"),
    						'asistente_finanzas_unidad' => $this->session->userdata("asistente_finanzas_unidad"),
								'encargado_finanzas_unidad' => $this->session->userdata("encargado_finanzas_unidad"),
    						'encargado_unidad' => $this->session->userdata("encargado_unidad"),
    						'asistente_dcc' => $this->session->userdata("asistente_dcc"),
								'title' =>$this->session->userdata("title"));
	    $val = $this->session->flashdata('id');

	    if(!is_null($val)){
	    	$id=$val;
	    }
	    else{
	    	$id = $this->input->get('var'); //Se recibe por GET, es el id de área, unidad, etc que se este considerando

			if(is_null($id)  && is_null(($id=$this->session->flashdata('id')))){
				redirect('inicio');
				}
	    }

		$this->session->set_flashdata('id',$id);
	    $this->load->model('Dashboard_model');
	    $route = $this->Dashboard_model->getRoute($id);

			//Se obtienen las metricas correspondientes a los permisos del usuario, junto con las mediciones
			//correspondientes
	    if($permits['director'] || ((in_array($id,$permits['asistente_finanzas_unidad']) || in_array($id, $permits['encargado_finanzas_unidad']))
			&& ($permits['asistente_dcc'] || in_array($id, $permits['encargado_unidad']) || in_array($id, $permits['asistente_unidad'])))){
	    	$all_metrics = $this->Dashboard_model->getAllMetrics($id,0);
	   		$all_measurements = $this->Dashboard_model->getAllMeasurements($id,0);
		}
	    elseif($permits['asistente_dcc'] || in_array($id, $permits['encargado_unidad']) || in_array($id, $permits['asistente_unidad'])){
	    	$all_metrics = $this->Dashboard_model->getAllMetrics($id,1);
	   		$all_measurements = $this->Dashboard_model->getAllMeasurements($id,1);
	    }
	    elseif(in_array($id, $permits['asistente_finanzas_unidad']) || in_array($id, $permits['encargado_finanzas_unidad'])){
	    	$all_metrics = $this->Dashboard_model->getAllMetrics($id,2);
	   		$all_measurements = $this->Dashboard_model->getAllMeasurements($id,2);
	    }
	    else{
	    	redirect('inicio');
	    }

	    if(!$all_measurements){
	    	$res['measurements']=[[],[]];
	    }
	    else{
	    	$res['measurements']=$this->_parseMeasurements($all_measurements);
	    }


	    if(!$all_metrics){
	    	$res['result']=[];
	    }
	    else{
				//Se construye la tabla donde se podrán ingresar mediciones y actualizar valores
	    	foreach ($all_metrics as $metrics)
	    	{
                $s = "<div class='row mb-md'>".
				"<div class= 'col-md-3'>".
				"<label class='text'>".ucwords($metrics->getName())."</label>".
				"</div>".
				"<div class='col-md-3'>".
				"<input type='text' name='value".$metrics->getId()."' id='value".$metrics->getId()."' class='form-control' onkeyup =\"validate('value".$metrics->getId()."')\"
				 onfocus =\"validate('value".$metrics->getId()."')\">".
				"</div>".
				"<div class='col-md-3'>".
				"<input type='text' name='target".$metrics->getId()."' id='target".$metrics->getId()."' class='form-control' onkeyup =\"validate('target".$metrics->getId()."')\"
				 onfocus =\"validate('target".$metrics->getId()."')\">".
				"</div>".
				"<div class='col-md-3'>".
				"<input type='text' name='expected".$metrics->getId()."' id='expected".$metrics->getId()."' class='form-control' onkeyup =\"validate('expected".$metrics->getId()."')\"
				 onfocus =\"validate('target".$metrics->getId()."')\">".
				"</div>".
				"</div>";
				$data[$metrics->getId()] = $s;

	    	}
	    	$res['result'] = $data;
	    }

	    $res['route'] = $route;
	    $res['id_location'] = $id;
			$res['validate'] = validation($permits, $this->dashboardModel);
	    $res['success'] = $this->session->flashdata('success')==null ? 2 : $this->session->flashdata('success');
			$res['role'] = $permits['title'];
			$this->load->view('add-data', $res);
	    //debug($all_metrics, true);
	}

  //Función encargada de llamar a las funciones correspondientes del modelo, para poder actualizar valores en la base de datos
	function addData(){
		$this->load->library('session');
		$id = $this->input->post("id_location");

		if(is_null($id))
			redirect('inicio');

		$user = $this->session->userdata("rut");
    	$permits = array('director' => $this->session->userdata("director"),
    						'visualizador' => $this->session->userdata("visualizador"),
    						'asistente_unidad' => $this->session->userdata("asistente_unidad"),
    						'asistente_finanzas_unidad' => $this->session->userdata("asistente_finanzas_unidad"),
    						'encargado_unidad' => $this->session->userdata("encargado_unidad"),
								'encargado_finanzas_unidad' => $this->session->userdata("encargado_finanzas_unidad"),
    						'asistente_dcc' => $this->session->userdata("asistente_dcc"));

		$this->load->model('Dashboard_model');
		$metrics_id = $this->Dashboard_model->getAllMetricOrgIds($id);
		$all_measurements = $this->Dashboard_model->getAllMeasurements($id,0);

		//Se validan los datos ingresados
		$this->load->library('form_validation');
		$this->form_validation->set_rules('year', 'Year', 'required|exact_length[4]|numeric');
		foreach ($metrics_id as $i){
			$this->form_validation->set_rules('value'.$i->getId(), 'Value'.$i->getId(), 'numeric');
			$this->form_validation->set_rules('target'.$i->getId(), 'Target'.$i->getId(), 'numeric');
			$this->form_validation->set_rules('expected'.$i->getId(), 'Expected'.$i->getId(), 'numeric');
		}

		if(!$this->form_validation->run()){
		    $this->session->set_flashdata('success', $success);
			redirect('formAgregarDato');
		}

		$year = $this->input->post('year');

		if($all_measurements){
			foreach ($all_measurements as $measure) {
				if($measure->getYear()==$year){
					$data[] = $measure->getMetOrg();
					$vals[$measure->getMetOrg()] = array(
													'value' => $measure->getValue(),
													'target' => $measure->getTarget(),
													'expected' => $measure->getExpected()
												);
				}
			}
		}
		else{
			$vals[] = [];
		}
		$data[] = -1; // Asi el arreglo nunca sera null

		if($permits['director']){
			$validation = 1;
		}
		elseif($permits['asistente_dcc'] || in_array($id, $permits['asistente_finanzas_unidad']) ||
		 in_array($id, $permits['asistente_unidad'])){
			$validation = 0;
		}
		elseif(in_array(-1, $permits['encargado_finanzas_unidad']) && in_array(-1, $permits['encargado_unidad'])){
			redirect('inicio');
		}

		$success = 1;
		foreach($metrics_id as $i){
			$id_met = $i->getId();
			$value = $this->input->post('value'.$id_met);
			$target = $this->input->post('target'.$id_met);
			$expected = $this->input->post('expected'.$id_met);

			//Si no se ingresaron datos no se agregan a la base de datos
			if($value=="" && $target=="" && $expected==""){
				continue;
			}
			$met_type = $this->Dashboard_model->getMetType($id_met);

			if((in_array($id, $permits['encargado_unidad']) && !strcmp($met_type,"Productividad")) ||
			 (in_array($id, $permits['encargado_finanzas_unidad']) && !strcmp($met_type,"Finanzas"))){
				$validation = 1;
			}

			if(in_array($id_met, $data)==1 && ($value!=$vals[$id_met]['value'] || $target!=$vals[$id_met]['target'] || $expected!=$vals[$id_met]['expected'])){
				$q = $this->Dashboard_model->updateData($id_met, $year, $value, $target, $expected, $user, $validation);
			}
			else if(in_array($id_met, $data)!=1){
				$q = $this->Dashboard_model->insertData($id_met, $year, $value, $target, $expected, $user, $validation); // si $q es falso significa que fallo la query
			}
			else{
			    $q = -1;
			}
			if ($q == 0){
				//Success es la flag que desplegara el mensaje de exito o fallo según corresponda
			    $success = 0;
			}
		}
		$this->session->set_flashdata('id', $id);
		$this->session->set_flashdata('success', $success);
		//Se redirige a la pantalla de agregar datos
		redirect('formAgregarDato');
	}

	//Función que genera un arreglo de dos arreglos. El primero contiene todas las mediciones
	//que se reciben en el argumento $m. Y el segundo contiene todos los años que presentan mediciones
	function _parseMeasurements($m){

		foreach ($m as $measure) {
			$data['id']= $measure->getIdMeasurement();
			$data['metorg'] = $measure->getMetOrg();
			$data['value'] = $measure->getValue();
			$data['target'] = $measure->getTarget();
			$data['expected'] = $measure->getExpected();
			$data['year'] = $measure->getYear();

			$valid_years[] = $data['year'];
			$result[] = $data;
		}

		return array(
				0 => $result,
				1 => $valid_years);
	}

	//construye tabla que va al lado de los gráficos, y además genera arreglo con los
	//datos necesarios para graficar
	function auxShowDashboard($dashboard_metrics,$id){

		function cmpPairs($p1, $p2)
		{
    		return $p1[0]>$p2[0];
		}

		//-------------

		$result['id_location'] = $id;
	    $this->load->model('Dashboard_model');
	    $route = $this->Dashboard_model->getRoute($id);

	    //Guardar en variables de sesion
		$this->session->set_flashdata('id',$id);
		//-------------

	    if(!$dashboard_metrics){
	    	$metrics=[];
	    	$names=[];
	    }
	    else{
	    	$all_measurements = $this->Dashboard_model->getDashboardMeasurements($dashboard_metrics);
	    	foreach($dashboard_metrics as $metric){
	    		$metrics[$metric->getId()]= array( //Se obtienen datos que ocupará la librería para graficar
	    										'id' => $metric->getId(),
	    										'vals' => [],
	    										'name' => "",
	    										'table' => "",
	    										'graph_type' => $metric->getGraphType(),
	    										'max_y' => 0,
	    										'min_y' => 0,
	    										'measure_number' => 0,
													'unit' => $metric->getUnit()
	    										);

	    	}

	    	if($all_measurements){
	    		foreach ($all_measurements as $measure) {
	    			$count = 1;
	    			$id_met = $measure['id'];
	    			$names[]= $id_met;
	    			$metrics[$id_met]['name'] = $measure['name'];

	    			$values=[];
	    			$years=[];
						//Tabla de datos que va al lado del gráfico
	    			foreach ($measure['measurements'] as $m){
	    				$s = "<tr>
	    				  <td>".$count."</td>
	    			  	  <td>".$m->getYear()."</td>
	    			      <td>".$m->getValue()."</td>
	    			      <td>".$m->getTarget()."</td>
	    			      <td>".$m->getExpected()."</td>
	    			      </tr>";
	    			    $values[] = $m->getValue();
	    			    $years[] = $m->getYear();
	    				$metrics[$id_met]['table'] = $metrics[$id_met]['table'].$s;
	    				$metrics[$id_met]['vals'][] = array($m->getYear(), $m->getValue());
	    				$count++;

	    			}


	    			usort($metrics[$id_met]['vals'], "cmpPairs");
	    			$metrics[$id_met]['measure_number'] = max($years)-min($years);
	    			$min = min($values);

	    			$metrics[$id_met]['min_y'] = $min>0 ? floor(0.85*$min) : floor(1.15*$min);
	    			$metrics[$id_met]['max_y'] = ceil(1.15*max($values));


	    		}

	    		$res=[];
	    		$id_met = array_keys($metrics);
	    		foreach ($id_met as $id) {
	    			if($metrics[$id]['name']=="")
	    				continue;
	    			$res[$id]=$metrics[$id];
	    		}
	    		$metrics= $res;
	    	}
	    	else{
	    		$metrics=[];  //Si la metrica no tiene mediciones => no se muestra
	    		$names=[];
	    	}
		}
	    $result['data'] = $metrics;
	    $result['route'] = $route;
	    $result['names'] = $names;

	    return $result;
	}

	//Función para exportar datos de tabla al lado de los gráficos en archivo csv
	function exportData(){
		$this->load->library('form_validation');
		$this->form_validation->set_rules('id_org', 'Org', 'required|numeric');
		$this->form_validation->set_rules('id_met', 'Met', 'required|numeric');

		if(!$this->form_validation->run()){
			redirect('inicio');
		}

		$id_org = $this->input->post('id_org');
		$id_met = $this->input->post('id_met');

		$this->load->library('session');
		$this->session->set_userdata('id_location', $id_org);
		$this->load->model('Dashboard_model');
		$data = $this->Dashboard_model->getAllData($id_org, $id_met);

	}

	//función que permite mostrar un dashboard. Se recibe el id de la organizacion correspondiente
	// y a partir de eso se obtienen las métricas y mediciones asociadas
	function showDashboard(){

		$this->load->library('session');
		$user = $this->session->userdata("rut");
    	$permits = array('director' => $this->session->userdata("director"),
    						'visualizador' => $this->session->userdata("visualizador"),
    						'asistente_unidad' => $this->session->userdata("asistente_unidad"),
    						'asistente_finanzas_unidad' => $this->session->userdata("asistente_finanzas_unidad"),
    						'encargado_unidad' => $this->session->userdata("encargado_unidad"),
								'encargado_finanzas_unidad' => $this->session->userdata("encargado_finanzas_unidad"),
    						'asistente_dcc' => $this->session->userdata("asistente_dcc"),
								'title' =>$this->session->userdata("title"));
		$id = $this->input->post("direccion"); //Se recibe por POST, es el id de área, unidad, etc que se este considerando

		if($this->session->userdata('id_location')!=FALSE){
			$id =$this->session->userdata('id_location');
			$this->session->unset_userdata('id_location');
		}
		else if(is_null($id) && is_null(($id=$this->session->flashdata('id'))))
			redirect('inicio');
		//-------------
	    //Guardar en variables de sesion
		$this->session->set_flashdata('id',$id);
		$show_all = $this->input->post('show_all');
		//Permite ver si se esta en la pantalla de mostrar todos los gráficos o no
		if($show_all==null)
			$show_all = 0;

		if($show_all){
			$dashboard_metrics = $this->getAllDashboardMetrics($id, $permits);
		}
		else {
			$dashboard_metrics = $this->getDashboardMetrics($id, $permits);
		}

		$result= $this->auxShowDashboard($dashboard_metrics, $id);
		$result['validate'] = validation($permits, $this->dashboardModel);
		$result['show_all'] = $show_all;
		$result['role'] = $permits['title'];
	  $this->session->set_flashdata('id',$id);
		$this->session->set_flashdata('show_all',$show_all);
		$add_data = 0;
		//Permite determinar si el usuario deberá o no ver la pestaña de agregar datos
	  if($permits['director'] || in_array($id, $permits['encargado_unidad']) ||
		 	in_array($id, $permits['asistente_unidad']) || in_array($id, $permits['asistente_finanzas_unidad']) ||
		 	$permits['asistente_dcc'] || in_array($id, $permits['encargado_finanzas_unidad'])){
			$add_data = 1;
	  }
		$result['add_data'] = $add_data;
		//Si no se tienen permisos para acceder a un dashboard en particular, entonces se muestra un mensaje
		if(!$permits['visualizador'] && !$permits['director'] && !$permits['asistente_dcc'] &&
			!in_array($id, $permits['encargado_unidad']) && !in_array($id, $permits['asistente_finanzas_unidad']) &&
			!in_array($id, $permits['asistente_unidad']) && !in_array($id, $permits['encargado_finanzas_unidad'])){
	    	$this->load->view('no-dashboard', $result);
	  }
	  else
	    $this->load->view('dashboard', $result);

	}

	//Función que permite obtener todas las métricas, asociadas a una organizacion y a los permisos de un usuario
	private function getAllDashboardMetrics($id, $permits){

		$this->load->model('Dashboard_model');
		if($permits['director'] || $permits['visualizador'] || ((in_array($id, $permits['asistente_finanzas_unidad']) || in_array($id, $permits['encargado_finanzas_unidad']))
			&& ($permits['asistente_dcc'] || in_array($id, $permits['encargado_unidad']) || in_array($id, $permits['asistente_unidad'])))){
	    	$dashboard_metrics = $this->Dashboard_model->getAllDashboardMetrics($id,0);
		}
	    elseif($permits['asistente_dcc'] || in_array($id, $permits['encargado_unidad']) || in_array($id, $permits['asistente_unidad'])){
	    	$dashboard_metrics = $this->Dashboard_model->getAllDashboardMetrics($id,1);
	    }
	    elseif(in_array($id, $permits['asistente_finanzas_unidad']) || in_array($id, $permits['encargado_finanzas_unidad'])){
	    	$dashboard_metrics = $this->Dashboard_model->getAllDashboardMetrics($id,2);
	    }
	    else{
	    	$dashboard_metrics=[];
	    }

			return $dashboard_metrics;
	}

	//Función que permite obtener solo las métricas cuyos gráficos tienen posicion = 1, asociadas a una organizacion y a los permisos de un usuario
	private function getDashboardMetrics($id, $permits){

		$this->load->model('Dashboard_model');
		if($permits['director'] || $permits['visualizador'] || ((in_array($id, $permits['asistente_finanzas_unidad']) || in_array($id, $permits['encargado_finanzas_unidad']))
			&& ($permits['asistente_dcc'] || in_array($id, $permits['encargado_unidad']) || in_array($id, $permits['asistente_unidad'])))){
	    	$dashboard_metrics = $this->Dashboard_model->getDashboardMetrics($id,0);
		}
	    elseif($permits['asistente_dcc'] || in_array($id, $permits['encargado_unidad']) || in_array($id, $permits['asistente_unidad'])){
	    	$dashboard_metrics = $this->Dashboard_model->getDashboardMetrics($id,1);
	    }
	    elseif(in_array($id, $permits['asistente_finanzas_unidad']) || in_array($id, $permits['encargado_finanzas_unidad'])){
	    	$dashboard_metrics = $this->Dashboard_model->getDashboardMetrics($id,2);
	    }
	    else{
	    	$dashboard_metrics=[];
	    }
			return $dashboard_metrics;
	}
}
