<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');}

class Dashboard extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->model('Dashboard_model');
		$this->load->model('Metorg_model');
		$this->load->model('Metrics_model');
		$this->dashboardModel = $this->Dashboard_model;
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
		$id     = $this->session->flashdata('id');

        if (is_null($id))
            $id = $this->input->get('var');//Es el id de área, unidad, etc que se este considerando
		if (is_null($id))
            redirect('inicio');

		$this->session->set_flashdata('id', $id);
		//Se obtienen las metricas correspondientes a los permisos del usuario, junto con las mediciones correspondientes
		if ($permits['admin'] || ((in_array($id, $permits['asistente_finanzas']) || in_array($id, $permits['encargado_finanzas']))
				 && (in_array($id, $permits['encargado_unidad']) || in_array($id, $permits['asistente_unidad'])))) {
			$cat = 0;
		} elseif (in_array($id, $permits['encargado_unidad']) || in_array($id, $permits['asistente_unidad'])) {
			$cat = 1;
		} elseif (in_array($id, $permits['asistente_finanzas']) || in_array($id, $permits['encargado_finanzas'])) {
			$cat = 2;
		} else {
			redirect('inicio');
		}
		$all_metrics      = $this->Dashboard_model->getAllMetrics($id, $cat);
		$all_measurements = $this->Dashboard_model->getAllMeasurements($id, $cat);
		$res['measurements'] = $this->_parseMeasurements($all_measurements);
		$res['metrics']      = !$all_metrics ? [] : $all_metrics;
		$res['route']        = getRoute($this, $id);
		$res['id_location']  = $id;
		$res['validate']     = validation($permits, $this->dashboardModel);
		$res['success']      = $this->session->flashdata('success') === null ? 2 : $this->session->flashdata('success');
		$res['role']         = $permits['title'];
		$this->load->view('add-data2', $res);
		//debug($all_metrics, true);
	}

	//Función encargada de llamar a las funciones correspondientes del modelo, para poder actualizar valores en la base de datos
	function addData() {
		$id = $this->input->post("id_location");
		$this->session->set_flashdata('id', $id);
		if (is_null($id))
			redirect('inicio');

		$borrar = ($this->input->post('borrar')) ? 1 : 0;
		$user    = $this->session->userdata("rut");
		$permits = $this->session->userdata();

		//Se validan los datos ingresados
		$this->load->library('form_validation');
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
		$this->load->library('form_validation');
		$this->form_validation->set_rules('id_org', 'Org', 'required|numeric');
		$this->form_validation->set_rules('id_met', 'Met', 'required|numeric');

		if (!$this->form_validation->run()) {
			redirect('inicio');
		}

		$id_org = $this->input->post('id_org');
		$id_met = $this->input->post('id_met');

		$this->session->set_userdata('id_location', $id_org);
		$data = $this->Dashboard_model->getAllData($id_org, $id_met);

	}

    //construye tabla que va al lado de los gráficos, y además genera arreglo con los
    //datos necesarios para graficar
    private function auxShowDashboard($dashboard_metrics, $id) {

        function cmpPairs($p1, $p2) {
            return $p1[0] > $p2[0];
        }

        //Guardar en variables de sesion
        $this->session->set_flashdata('id', $id);

        $metrics = [];
        $names   = [];
        if ($dashboard_metrics){
            $all_measurements = $this->Dashboard_model->getDashboardMeasurements($dashboard_metrics);
            foreach ($dashboard_metrics as $metric) {
                $metrics[$metric->getId()] = array(//Se obtienen datos que ocupará la librería para graficar
                    'id'             => $metric->getId(),
                    'vals'           => [],
                    'name'           => "",
                    'table'          => "",
                    'graph_type'     => $metric->getGraphType(),
                    'max_y'          => 0,
                    'min_y'          => 0,
                    'measure_number' => 0,
                    'unit'           => $metric->getUnit()
                );
            }
            if ($all_measurements) {
                foreach ($all_measurements as $measure) {
                    $count                    = 1;
                    $id_met                   = $measure['id'];
                    $names[]                  = $id_met;
                    $metrics[$id_met]['name'] = $measure['name'];

                    $values = [];
                    $years  = [];
                    //Tabla de datos que va al lado del gráfico
                    foreach ($measure['measurements'] as $m) {
                        $s = "<tr>
	    				  <td>".$count."</td>
	    			  	  <td>".$m->getYear()."</td>
	    			      <td>".$m->getValueY()."</td>
	    			      <td>".$m->getTarget()."</td>
	    			      <td>".$m->getExpected()."</td>
	    			      </tr>";
                        $values[]                   = $m->getValueY();
                        $years[]                    = $m->getYear();
                        $metrics[$id_met]['table']  = $metrics[$id_met]['table'].$s;
                        $metrics[$id_met]['vals'][] = array($m->getYear(), $m->getValueY());
                        $count++;
                    }
                    //ordena valores por el primer elemento del arreglo = Año
                    usort($metrics[$id_met]['vals'], "cmpPairs");
                    $metrics[$id_met]['measure_number'] = max($years)-min($years);
                    $min                                = min($values);

                    $metrics[$id_met]['min_y'] = $min > 0 ? floor(0.85*$min) : floor(1.15*$min);
                    $metrics[$id_met]['max_y'] = ceil(1.15*max($values));
                }

                $res    = [];
                $id_met = array_keys($metrics);
                foreach ($id_met as $id) {
                    if ($metrics[$id]['name'] == "") {
                        continue;
                    }
                    $res[$id] = $metrics[$id];
                }
                $metrics = $res;
            } else {
                $metrics = [];//Si la metrica no tiene mediciones => no se muestra
                $names   = [];
            }
        }
        $result['data']  = $metrics;
        $result['names'] = $names;
        $result['id_location'] = $id;
        return $result;
    }

	//función que permite mostrar un dashboard. Se recibe el id de la organizacion correspondiente
	// y a partir de eso se obtienen las métricas y mediciones asociadas
	function showDashboard() {

		$permits = $this->session->userdata();
		$id      = $this->input->post("direccion");//Se recibe por POST, es el id de área, unidad, etc que se este considerando

		if ($this->session->userdata('id_location') != FALSE) {
			$id = $this->session->userdata('id_location');
			$this->session->unset_userdata('id_location');
		} else if (is_null($id) && is_null(($id = $this->session->flashdata('id')))) {
			redirect('inicio');
		}

		//-------------
		//Guardar en variables de sesion
		$this->session->set_flashdata('id', $id);
		$show_all = $this->input->post('show_all');
		//Permite ver si se esta en la pantalla de mostrar todos los gráficos o no
		if ($show_all == null) {
			$show_all = 0;
		}

        $dashboard_metrics = $this->getDashboardMetricsGeneric($id, $permits, $show_all);
        $show_button = ($show_all) ? true : $this->Dashboard_model->showButton($id);

		$result             = $this->auxShowDashboard($dashboard_metrics, $id);
		$result['validate'] = validation($permits, $this->dashboardModel);
		$result['show_all'] = $show_all;
		$result['show_button'] = $show_button;
		$result['role']     = $permits['title'];
        $result['route'] = getRoute($this, $id);

		$this->session->set_flashdata('id', $id);
		$this->session->set_flashdata('show_all', $show_all);

		$add_data = 0;
		//Permite determinar si el usuario deberá o no ver la pestaña de agregar datos
		if ($permits['admin'] || in_array($id, $permits['encargado_unidad']) ||
			in_array($id, $permits['asistente_unidad']) || in_array($id, $permits['asistente_finanzas'])
			|| in_array($id, $permits['encargado_finanzas'])) {
			$add_data = 1;
		}
		$result['add_data'] = $add_data;
		//Si no se tienen permisos para acceder a un dashboard en particular, entonces se muestra un mensaje
		if (!$permits['visualizador'] && !$permits['admin'] &&
			!in_array($id, $permits['encargado_unidad']) && !in_array($id, $permits['asistente_finanzas']) &&
			!in_array($id, $permits['asistente_unidad']) && !in_array($id, $permits['encargado_finanzas'])) {
			$this->load->view('no-dashboard', $result);
		} else {
			$this->load->view('dashboard', $result);
		}
	}

    //Función que permite obtener las métricas, asociadas a una organizacion y a los permisos de un usuario. $all decide si se entregan todos o solo los q tiene posicion 1.
    private function getDashboardMetricsGeneric($id, $permits, $all) {
		if ($permits['admin'] || $permits['visualizador'] || ((in_array($id, $permits['asistente_finanzas']) || in_array($id, $permits['encargado_finanzas']))
				 && (in_array($id, $permits['encargado_unidad']) || in_array($id, $permits['asistente_unidad'])))) {
			$val = 0;
		} elseif (in_array($id, $permits['encargado_unidad']) || in_array($id, $permits['asistente_unidad'])) {
			$val = 1;
		} elseif (in_array($id, $permits['asistente_finanzas']) || in_array($id, $permits['encargado_finanzas'])) {
			$val = 2;
		} else {
			return [];
		}
		return $this->Dashboard_model->getDashboardMetrics($id, $val, $all);
	}
}
