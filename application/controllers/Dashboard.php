<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');}

class Dashboard extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->model('Dashboard_model');
		$this->load->model('Dashboardconfig_model');
		$this->load->model('Metorg_model');
		$this->load->model('Metrics_model');
		if (is_null($this->session->rut))
			redirect('salir');
	}

    //Función que genera un arreglo de dos arreglos. El primero contiene todas las mediciones
    //que se reciben en el argumento $m. Y el segundo contiene todos los años que presentan mediciones
    function _parseMeasurements($measures) {
        $result = array();
        if($measures) {
            foreach ($measures as $measure) {
                $data['id'] = $measure->getIdMeasurement();
                $data['metorg'] = $measure->getMetOrg();
                $data['valueY'] = $measure->getValueY();
                $data['valueX'] = $measure->getValueX();
                $data['target'] = $measure->getTarget();
                $data['expected'] = $measure->getExpected();
                $data['year'] = $measure->getYear();

                $result[$data['metorg']][$data['year']][] = $data;
            }
        }
        return $result;
    }

    // funcion que lista todas las metricas y las deja como objeto cada una por lo tanto se puede recorrer el arreglo
    // y llamar a cada valor del arreglo como liberia ejemplo mas abajo
    // esto sirve para cuando se llama de una vista para completar por ejemplo una tabla
	function formAddData(){
		$permits = $this->session->userdata();
		$org= $this->input->get('org');//Es el id de área, unidad, etc que se este considerando
		if (is_null($org))
            redirect('inicio');

		//Se obtienen las metricas correspondientes a los permisos del usuario, junto con las mediciones correspondientes
		if ($permits['admin'] || ((in_array($org, $permits['asistente_finanzas']) || in_array($org, $permits['encargado_finanzas']))
				 && (in_array($org, $permits['encargado_unidad']) || in_array($org, $permits['asistente_unidad'])))) {
			$cat = 0;
		} elseif (in_array($org, $permits['encargado_unidad']) || in_array($org, $permits['asistente_unidad'])) {
			$cat = 1;
		} elseif (in_array($org, $permits['asistente_finanzas']) || in_array($org, $permits['encargado_finanzas'])) {
			$cat = 2;
		} else {
			redirect('inicio');
		}
		$all_metrics      = $this->Dashboard_model->getAllMetrics($org, $cat, 0);
		$all_measurements = $this->Dashboard_model->getAllMeasurements($org, $cat);
		$res = defaultResult($permits, $this->Dashboard_model);
		$res['measurements'] = $this->_parseMeasurements($all_measurements);
		$res['metrics']      = !$all_metrics ? [] : $all_metrics;
		$res['route']        = getRoute($this, $org);
		$res['org']  = $org;
		$res['success']      = $this->session->flashdata('success') === null ? 2 : $this->session->flashdata('success');
		$this->load->view('add-data', $res);
		//debug($all_metrics, true);
	}

	//Función encargada de llamar a las funciones correspondientes del modelo, para poder actualizar valores en la base de datos
	function addData() {
		$id = $this->input->post("org");
		if (is_null($id))
			redirect('inicio');

		$borrar = ($this->input->post('borrar')) ? 1 : 0;
		$user    = $this->session->userdata("rut");
		$permits = $this->session->userdata();

		//Se validan los datos ingresados
		$this->form_validation->set_rules('year', 'Year', 'required|exact_length[4]|numeric');
		$this->form_validation->set_rules('valueY[]', 'Value', 'numeric');
		$this->form_validation->set_rules('metorg[]', 'Value', 'numeric');
		$this->form_validation->set_rules('target[]', 'Target', 'numeric');
		$this->form_validation->set_rules('expected[]', 'Expected', 'numeric');

		if (!$this->form_validation->run()) {
			$this->session->set_flashdata('success', 0);
			redirect('formAgregarDato');
		}
		$year = $this->input->post('year');

		if ($permits['admin'] || in_array($id, $permits['encargado_finanzas']) || in_array($id, $permits['encargado_unidad'])) {
			$validation = 1;
		} elseif (in_array($id, $permits['asistente_finanzas']) || in_array($id, $permits['asistente_unidad'])) {
			$validation = 0;
		} else {
			$this->session->set_flashdata('success', 0);
			redirect('formAgregarDato');
		}
		$success = true;

		if($borrar){
			foreach ($this->input->post('delete') as $valId){
				if(!$valId)
					continue;
				$success = $success && $this->Dashboard_model->deleteValue($valId, $user, $validation);
			}
			$success = ($success) ? 1:0;
			$this->session->set_flashdata('success', $success);
			redirect('formAgregarDato');
		}
		for($i = 0; $i<count($this->input->post('valueY')); $i++){
			$id_metorg = $this->input->post('metorg')[$i];
			$valId = $this->input->post('valId')[$i];
			$valueY = $this->input->post('valueY')[$i];
			$valueX    = $this->input->post('valueX')[$i];
			$target   = $this->input->post('target')[$i];
			$expected = $this->input->post('expected')[$i];
			$metorg = $this->Metorg_model->getMetOrg(array('id'=>[$id_metorg]))[0];
			$metric = $this->Metrics_model->getMetric(array('id'=>[$metorg->metric]))[0];

			//si los datos no cambiaron o no hay valor en y saltamos datos
			if ((strcmp($valueY, "")==0 && strcmp($valueX, "")==0 && strcmp($expected, "")==0 && strcmp($target, "")==0) || $valueY == "") {
				$success = false;
				continue;
			}
			//si no se tiene un id para el valor y no se tiene un eje X
			if (!$valId && (strcmp($metric->x_name, "")==0)){
				$oldVal = $this->Dashboard_model->getValue(array('metorg'=>[$id_metorg], 'year'=>[$year], 'state'=>[1]));
				//Si existia un valor previo, se debe hacer un update de la data, sino, simplemente se inserta.
				if (count($oldVal)==1){
					$oldVal = $oldVal[0];
					if ($valueY != $oldVal->value || $expected != $oldVal->expected || $target != $oldVal->target)
						$success = $success && $this->Dashboard_model->updateData($id_metorg, $year, $oldVal->x_value, $valueY, "", $target, $expected, $user, $validation);
				}
				else if (count($oldVal)==0){
					$success = $success && $this->Dashboard_model->insertData($id_metorg, $year, $valueY, "", $target, $expected, $user, $validation);
				}
				//hubo un error, nunca debería haber más de 1 validado.
				else{
					$success = false;
					debug($oldVal);
				}
			}
			//si no se tiene un ID para el valor y se tiene un eje X, 
			elseif(!$valId && strcmp($metric->x_name, "")!=0 && strcmp($valueX, "")!=0){
				$oldVal = $this->Dashboard_model->getValue(array('metorg'=>[$id_metorg], 'year'=>[$year], 'x_value'=>[$valueX], 'state'=>[1]));
				//Si existia un valor previo, se debe hacer un update de la data, sino, simplemente se inserta.
				if (count($oldVal)==1){
					$oldVal = $oldVal[0];
					if ($valueY != $oldVal->value || strcmp($valueX, $oldVal->x_value)!=0 || $expected != $oldVal->expected || $target != $oldVal->target)
						$success = $success && $this->Dashboard_model->updateData($id_metorg, $year, $oldVal->x_value, $valueY, $valueX, $target, $expected, $user, $validation);
				}
				else if (count($oldVal)==0){
					$success = $success && $this->Dashboard_model->insertData($id_metorg, $year, $valueY, $valueX, $target, $expected, $user, $validation);
				}
				//hubo un error, nunca debería haber más de 1 validado.
				else{
					$success = false;
					debug($oldVal);
				}
			}
			//si se tiene valId, ya existia el valor por tanto se debe actualizar
			else if($valId){
				$oldVal = $this->Dashboard_model->getValue(array('id'=>[$valId]))[0];
				if ($valueY != $oldVal->value || strcmp($valueX, $oldVal->x_value)!=0 || $expected != $oldVal->expected || $target != $oldVal->target) {
					$success = $success && $this->Dashboard_model->updateData($id_metorg, $year, $oldVal->x_value, $valueY, $valueX, $target, $expected, $user, $validation);
				}
			}
			else{
				$success = false;
			}
		}
		$success = ($success) ? 1:0;
		$this->session->set_flashdata('success', $success);
		redirect('formAgregarDato');
	}

	//Función para exportar datos de tabla al lado de los gráficos en archivo csv
	function exportData() {

		function build_sorter($key) {
			return function ($a, $b) use ($key) {
				return strnatcmp($a->$key, $b->$key);
			};
		}

		$this->form_validation->set_rules('graphic', 'Gráfico', 'required|numeric|greater_than_equal_to[0]');
		$this->form_validation->set_rules('all', 'Todo', 'required|numeric|in_list[0,1]');

		if (!$this->form_validation->run()) {
			redirect('inicio');
		}

		$graphic = $this->input->post('graphic');
		$all = (strcmp($this->input->post('all'), "1")==0 ? true : false);
		$graphic = ($all ? $this->Dashboard_model->getAllGraphicData($graphic) : $this->Dashboard_model->getGraphicData($graphic));
		$key = ($all ? 'year' : 'x');
		$data = [];
		foreach ($graphic->series as $serie){
			$prename = ($all || strcmp($serie->aggregation,"")==0 ? "" : $serie->aggregation . " de ");
			foreach ($serie->values as $value) {
				if(!key_exists('target', $value))
					continue;
				$value->metric =  $prename . $serie->name . " de " . $serie->org;
				$data[] = $value;
			}
		}

		$title = $graphic->title;
		if($graphic->ver_x){
			$title .= " Periodo (".$graphic->min_year." - ".$graphic->max_year.")";
		}
		usort($data, build_sorter($key));
		download_send_headers(str_replace(" ", "_", $title)."_".date("d-m-Y").".csv");
		echo(array2csv($data,$title, $graphic->x_name, $graphic->y_name));
		return;
	}
	
	
	private function getAllDashboardData($org, $all){
		$graphics = $this->Dashboardconfig_model->getAllGraphicByOrg($org, $all);
		$aux_graphs = [];
		foreach ($graphics as $graphic){
			$aux_graphs[] = $this->Dashboard_model->getGraphicData($graphic->id);
		}
		return $aux_graphs;
	}
	
	//función que permite mostrar un dashboard. Se recibe el id de la organizacion correspondiente
	// y a partir de eso se obtienen las métricas y mediciones asociadas
	function showDashboard(){
		$permits = $this->session->userdata();
		$org      = $this->input->get("org");//$this->input->post("direccion");//Se recibe por POST, es el id de área, unidad, etc que se este considerando
		$show_all = $this->input->get('all');
		if(is_null($org))
			redirect('inicio');
		//Permite ver si se esta en la pantalla de mostrar todos los gráficos o no
		if ($show_all == null) {
			$show_all = 0;
		}

		$graphics = $this->getAllDashboardData($org, $show_all);
		$show_button = ($show_all) ? true : $this->Dashboard_model->showButton($org);
		$aggregation = [];
		foreach ($this->Dashboardconfig_model->getAggregationType([]) as $type){
			$aggregation[$type->id] = $type;
		}
		$result = defaultResult($permits, $this->Dashboard_model);
		$result['add_data'] = 1;
		$result['show_all'] = $show_all;
		$result['show_button'] = $show_button;
		$result['aggregation'] = $aggregation;
		$result['route'] = getRoute($this, $org);
		$result['graphics'] = $graphics;
		$result['org'] = $org;
		$this->load->view('dashboard', $result);
	}
}
