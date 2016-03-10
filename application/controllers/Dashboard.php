<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');}

class Dashboard extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->model('Dashboard_model');
		$this->dashboardModel = $this->Dashboard_model;
	}

	function formAddData(){// funcion que lista todas las metricas y las deja como objeto cada una por lo tanto se puede recorrer el arreglo
	// y llamar a cada valor del arreglo como liberia ejemplo mas abajo
	// esto sirve para cuando se llama de una vista para completar por ejemplo una tabla
		$permits = $this->session->userdata();
		$id     = $this->session->flashdata('id');

        if (is_null($id))
            $id = $this->input->get('var');//Se recibe por GET, es el id de área, unidad, etc que se este considerando
		if (is_null($id))
            redirect('inicio');

		$this->session->set_flashdata('id', $id);
		//Se obtienen las metricas correspondientes a los permisos del usuario, junto con las mediciones correspondientes
		if ($permits['director'] || ((in_array($id, $permits['asistente_finanzas_unidad']) || in_array($id, $permits['encargado_finanzas_unidad']))
				 && ($permits['asistente_dcc'] || in_array($id, $permits['encargado_unidad']) || in_array($id, $permits['asistente_unidad'])))) {
			$cat = 0;
		} elseif ($permits['asistente_dcc'] || in_array($id, $permits['encargado_unidad']) || in_array($id, $permits['asistente_unidad'])) {
			$cat = 1;
		} elseif (in_array($id, $permits['asistente_finanzas_unidad']) || in_array($id, $permits['encargado_finanzas_unidad'])) {
			$cat = 2;
		} else {
			redirect('inicio');
		}

		$all_metrics      = $this->Dashboard_model->getAllMetrics($id, $cat);
		$all_measurements = $this->Dashboard_model->getAllMeasurements($id, $cat);
		$res['measurements'] = !$all_measurements ? [[], []] : $this->_parseMeasurements($all_measurements);
		$res['metrics']      = !$all_metrics ? [] : $all_metrics;
		$res['route']        = getRoute($this, $id);
		$res['id_location']  = $id;
		$res['validate']     = validation($permits, $this->dashboardModel);
		$res['success']      = $this->session->flashdata('success') == null ? 2 : $this->session->flashdata('success');
		$res['role']         = $permits['title'];
		$this->load->view('add-data', $res);
		//debug($all_metrics, true);
	}

	//Función encargada de llamar a las funciones correspondientes del modelo, para poder actualizar valores en la base de datos
	function addData() {
		$id = $this->input->post("id_location");
		$borrar = ($this->input->post('borrar')) ? 1 : 0;

		if (is_null($id))
			redirect('inicio');

		$user    = $this->session->userdata("rut");
		$permits = $this->session->userdata();

		$metorgs_id       = $this->Dashboard_model->getAllMetricOrgIds($id);
		$all_measurements = $this->Dashboard_model->getAllMeasurements($id, 0);

		//Se validan los datos ingresados
		$this->load->library('form_validation');
		$this->form_validation->set_rules('year', 'Year', 'required|exact_length[4]|numeric');

		foreach ($metorgs_id as $i) {
			$this->form_validation->set_rules('value'.$i->getId(), 'Value'.$i->getId(), 'numeric');
			$this->form_validation->set_rules('target'.$i->getId(), 'Target'.$i->getId(), 'numeric');
			$this->form_validation->set_rules('expected'.$i->getId(), 'Expected'.$i->getId(), 'numeric');
		}

		if (!$this->form_validation->run()) {
			$this->session->set_flashdata('success', 0);
			redirect('formAgregarDato');
		}

		$year = $this->input->post('year');

		$vals[] = [];
		if ($all_measurements) {
			foreach ($all_measurements as $measure) {
				if ($measure->getYear() == $year) {
                    $data[] = $measure->getMetOrg();
					$vals[$measure->getMetOrg()] = array(
						'value'    => $measure->getValue(),
						'target'   => $measure->getTarget(),
						'expected' => $measure->getExpected()
					);
				}
			}
		}

		$data[] = -1; // Asi el arreglo nunca sera null

		if ($permits['director']) {
			$validation = 1;
		} elseif ($permits['asistente_dcc'] || in_array($id, $permits['asistente_finanzas_unidad']) ||
			in_array($id, $permits['asistente_unidad'])) {
			$validation = 0;
		} elseif (in_array(-1, $permits['encargado_finanzas_unidad']) && in_array(-1, $permits['encargado_unidad'])) {
			redirect('inicio');
		}

		$success = 1;
		foreach ($metorgs_id as $i){
			$id_metorg   = $i->getId();
			$value    = $this->input->post('value'.$id_metorg);
			$target   = $this->input->post('target'.$id_metorg);
			$expected = $this->input->post('expected'.$id_metorg);
			$delete = $this->input->post('borrar'.$id_metorg);

			//Si no se ingresaron datos no se agregan a la base de datos
			if ($value == "" && $target == "" && $expected == "")
				continue;
			if($value=="")
				$value=0;
			if($target=="")
				$target=0;
			if($expected=="")
				$expected=0;
			$metorg_cat = $this->Dashboard_model->getMetType($id_metorg);

			if ((in_array($id, $permits['encargado_unidad']) && !strcmp($metorg_cat, "Productividad")) ||
				(in_array($id, $permits['encargado_finanzas_unidad']) && !strcmp($metorg_cat, "Finanzas"))) {
				$validation = 1;
			}
			if($borrar && $delete)
				$q = $this->Dashboard_model->deleteMeasure($id_metorg, $year, $user, $validation);
			elseif (!$borrar && in_array($id_metorg, $data) && ($value != $vals[$id_metorg]['value'] || $target != $vals[$id_metorg]['target'] || $expected != $vals[$id_metorg]['expected'])) {
				$q = $this->Dashboard_model->updateData($id_metorg, $year, $value, $target, $expected, $user, $validation);
			} else if (!$borrar && !in_array($id_metorg, $data)) {
				$q = $this->Dashboard_model->insertData($id_metorg, $year, $value, $target, $expected, $user, $validation);// si $q es falso significa que fallo la query
			} else {
				$q = -1;
			}
			if ($q == 0) {
				//Success es la flag que desplegara el mensaje de exito o fallo según corresponda
				$success = 0;
			}
		}

		$this->session->set_flashdata('id', $id);
		$this->session->set_flashdata('success', $success);
		redirect('formAgregarDato');
	}

	//Función que genera un arreglo de dos arreglos. El primero contiene todas las mediciones
	//que se reciben en el argumento $m. Y el segundo contiene todos los años que presentan mediciones
	function _parseMeasurements($m) {

		foreach ($m as $measure) {
			$data['id']       = $measure->getIdMeasurement();
			$data['metorg']   = $measure->getMetOrg();
			$data['value']    = $measure->getValue();
			$data['target']   = $measure->getTarget();
			$data['expected'] = $measure->getExpected();
			$data['year']     = $measure->getYear();

			$valid_years[] = $data['year'];
			$result[]      = $data;
		}

		return array(
			0=> $result,
			1=> $valid_years);
	}

	//construye tabla que va al lado de los gráficos, y además genera arreglo con los
	//datos necesarios para graficar
	function auxShowDashboard($dashboard_metrics, $id) {

		function cmpPairs($p1, $p2) {
			return $p1[0] > $p2[0];
		}

		//-------------

		$result['id_location'] = $id;
		$route                 = getRoute($this, $id);

		//Guardar en variables de sesion
		$this->session->set_flashdata('id', $id);
		//-------------

		$metrics = [];
		$names   = [];

		if ($dashboard_metrics) {
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
	    			      <td>".$m->getValue()."</td>
	    			      <td>".$m->getTarget()."</td>
	    			      <td>".$m->getExpected()."</td>
	    			      </tr>";
						$values[]                   = $m->getValue();
						$years[]                    = $m->getYear();
						$metrics[$id_met]['table']  = $metrics[$id_met]['table'].$s;
						$metrics[$id_met]['vals'][] = array($m->getYear(), $m->getValue());
						$count++;

					}

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
		$result['route'] = $route;
		$result['names'] = $names;

		return $result;
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

		if ($show_all) {
			$dashboard_metrics = $this->getAllDashboardMetrics($id, $permits);
			$show_button = true;
		} else {
			$dashboard_metrics = $this->getDashboardMetrics($id, $permits);
			$show_button = $this->Dashboard_model->showButton($id);
		}

		$result             = $this->auxShowDashboard($dashboard_metrics, $id);
		$result['validate'] = validation($permits, $this->dashboardModel);
		$result['show_all'] = $show_all;
		$result['show_button'] = $show_button;
		$result['role']     = $permits['title'];
		$this->session->set_flashdata('id', $id);
		$this->session->set_flashdata('show_all', $show_all);
		$add_data = 0;
		//Permite determinar si el usuario deberá o no ver la pestaña de agregar datos
		if ($permits['director'] || in_array($id, $permits['encargado_unidad']) ||
			in_array($id, $permits['asistente_unidad']) || in_array($id, $permits['asistente_finanzas_unidad']) ||
			$permits['asistente_dcc'] || in_array($id, $permits['encargado_finanzas_unidad'])) {
			$add_data = 1;
		}
		$result['add_data'] = $add_data;
		//Si no se tienen permisos para acceder a un dashboard en particular, entonces se muestra un mensaje
		if (!$permits['visualizador'] && !$permits['director'] && !$permits['asistente_dcc'] &&
			!in_array($id, $permits['encargado_unidad']) && !in_array($id, $permits['asistente_finanzas_unidad']) &&
			!in_array($id, $permits['asistente_unidad']) && !in_array($id, $permits['encargado_finanzas_unidad'])) {
			$this->load->view('no-dashboard', $result);
		} else {
			$this->load->view('dashboard', $result);
		}
	}

	//Función que permite obtener todas las métricas, asociadas a una organizacion y a los permisos de un usuario
	private function getAllDashboardMetrics($id, $permits) {

		return $this->getDashboardMetricsGeneric($id, $permits, 'getAllDashboardMetrics');
	}

	//Función que permite obtener solo las métricas cuyos gráficos tienen posicion = 1, asociadas a una organizacion y a los permisos de un usuario
	private function getDashboardMetrics($id, $permits) {

		return $this->getDashboardMetricsGeneric($id, $permits, 'getDashboardMetrics');
	}

	private function getDashboardMetricsGeneric($id, $permits, $func) {

		if ($permits['director'] || $permits['visualizador'] || ((in_array($id, $permits['asistente_finanzas_unidad']) || in_array($id, $permits['encargado_finanzas_unidad']))
				 && ($permits['asistente_dcc'] || in_array($id, $permits['encargado_unidad']) || in_array($id, $permits['asistente_unidad'])))) {
			$val = 0;
		} elseif ($permits['asistente_dcc'] || in_array($id, $permits['encargado_unidad']) || in_array($id, $permits['asistente_unidad'])) {
			$val = 1;
		} elseif (in_array($id, $permits['asistente_finanzas_unidad']) || in_array($id, $permits['encargado_finanzas_unidad'])) {
			$val = 2;
		} else {
			$val = -1;
		}
		return $val == -1 ? [] : $this->Dashboard_model->$func($id, $val);
	}
}
