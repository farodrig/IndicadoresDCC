<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');
}

class DashboardConfig extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->model('Organization_model');

		$this->load->model('Dashboard_model');
		$this->load->model('Dashboardconfig_model');
		$this->dashboardModel = $this->Dashboard_model;
		if (is_null($this->session->rut))
			redirect('salir');
	}
	
	function dashboardConfig(){
		$permits = $this->session->userdata();
		if (!$permits['admin']) {
			redirect('inicio');
		}

		$this->load->model('Metorg_model');
		$this->load->model('Metrics_model');

		$org_ids = $this->Organization_model->getAllIds();
		$orgs = [];
		$metrics = [];
		foreach ($org_ids as $org_id){
			$org = $this->Organization_model->getByID($org_id);
			$all_childs = $this->Organization_model->getAllChilds($org_id);
			$childs = [];
			foreach ($all_childs as $child){
				$childs[] = $child->getId();
			}
			$org->children = $childs;
			$orgs[] = $org;
			$metorgs = $this->Metorg_model->getMetOrg(['org'=>[$org_id]]);
			foreach ($metorgs as $metorg){
				$metric = $this->Metrics_model->getMetric(['id'=>[$metorg->metric]])[0];
				$metric->metorg = $metorg;
				$metrics[$org_id][] = $metric;
			}
		}

		$this->load->view('configurar-dashboard2',
			array('orgs' => $orgs,
				'title'  => 'Configuración de Dashboard',
				'role'        => $permits['title'],
				'types' => $this->Dashboardconfig_model->getSerieType([]),
				'aggregation' => $this->Dashboardconfig_model->getAggregationType([]),
				'metrics' => $metrics,
				'success'     => is_null($this->session->flashdata('success')) ? 2 : $this->session->flashdata('success'),
				'validate'    => validation($permits, $this->Dashboard_model),
				'departments' => $this->Organization_model->getTree(-1)
			)
		);
	}

	function configUnidad(){// funcion que lista todas las metricas y las deja como objeto cada una por lo tanto se puede recorrer el arreglo
	// y llamar a cada valor del arreglo como liberia ejemplo mas abajo
	// esto sirve para cuando se llama de una vista para completar por ejemplo una tabla

		$permits = $this->session->userdata();
		if (!$permits['admin']) {
			redirect('inicio');
		}

		$id_first = "-1";
		if (!is_null($this->session->flashdata('id_first_unidad'))) {
			$id_first = $this->session->flashdata('id_first_unidad');
		} elseif ($this->session->userdata('id_org') != FALSE) {
			$id_first = $this->session->userdata('id_org');
			$this->session->unset_userdata('id_org');
		}
		$this->session->set_flashdata('id_first_unidad', $id_first);

		$all_metrics = $this->Dashboardconfig_model->getAllMetricsUnidades();//Retorna arrglo de arreglos de metricas de las unidades correspondientes
		//Si all_metrics es falso es porque no hay areas
		$all_areas = $this->Dashboardconfig_model->getAllAreasUnidad();//$all areas incluye type que representa al DCC padre, 0 si es soporte, 1 si es operacion

		$result['metricas'] = [];
		$result['years']    = array(
			'id'    => -1,
			'type'  => 2,
			'min'   => 2005,
			'max'   => 2015,
			'check' => NULL
		);
		if ($all_metrics) {
			$result['metricas'] = $all_metrics;
			$id_keys            = array_keys($all_metrics);
			for ($i = 0; $i < sizeof($id_keys); $i++) {
				$id_org     = $id_keys[$i];
				$met_unidad = $all_metrics[$id_org];
				foreach ($met_unidad as $met) {//Permite acceder a nombre y id una metrica
					$id            = $met['metorg'];
					$min_max_years = $this->Dashboardconfig_model->getMinMaxYears($id, $id_org);//Si existe config entrego los años correspondientes, junto con valor check
					$years[$id]    = $min_max_years;
				}

			}
			$result['years'] = $years;
		}

		$result['areas']    = (!$all_areas) ? false : $all_areas;
		$result['validate'] = validation($permits, $this->dashboardModel);
		$result['role']     = $permits['title'];
		$result['id_first'] = $id_first;
		$this->load->view('configurar-dashboard', $result);
	}

	function configArea() {
		$permits = $this->session->userdata();

		if (!$permits['admin']) {
			redirect('inicio');
		}
		$id_first = "-1";
		if (!is_null($this->session->flashdata('id_first_area'))) {
			$id_first = $this->session->flashdata('id_first_area');
		} elseif ($this->session->userdata('id_org') != FALSE) {
			$id_first = $this->session->userdata('id_org');
			$this->session->unset_userdata('id_org');
		}

		$this->session->set_flashdata('id_first_area', $id_first);
		$all_metrics = $this->Dashboardconfig_model->getAllMetricsArea();//Retorna arreglo de arreglos de metricas de las unidades y areas correspondientes
		//Si all_metrics es falso es porque no hay areas

		$all_areas = $this->Dashboardconfig_model->getAllAreasUnidad();//arreglo de areas y sus respectivas unidades id_area =>(nombre, id, arreglo_unidades)

		$result['metricas'] = [];
		$result['years']    = array(
			'id'    => -1,
			'type'  => 2,
			'min'   => 2005,
			'max'   => 2015,
			'check' => NULL
		);
		if ($all_metrics) {
			$result['metricas'] = $all_metrics;
			$id_keys            = array_keys($all_metrics);

			//$id_org_dash = -1;
			for ($i = 0; $i < sizeof($id_keys); $i++) {
				$id_org     = $id_keys[$i];
				$met_unidad = $all_metrics[$id_org];
				foreach ($met_unidad as $met) {//Permite acceder a nombre y id una metrica
					$id         = $met['metorg'];
					$keys_areas = array_keys($all_areas);
					if (in_array($id_org, $keys_areas)) {
						$id_org_dash = $id_org;
					} else {
						for ($j = 0; $j < sizeof($keys_areas); $j++) {
							for($k=0; $k < sizeof($all_areas[$keys_areas[$j]]['unidades']); $k++)
								if (in_array($id_org, $all_areas[$keys_areas[$j]]['unidades'][$k])) {
								$id_org_dash = $keys_areas[$j];
								break;
							}
						}
					}
					$min_max_years = $this->Dashboardconfig_model->getMinMaxYears($id, $id_org_dash);//Si existe config entrego los años correspondientes, junto con valor check
					$years[$id]    = $min_max_years;
				}

			}
			$result['years'] = $years;
		}
		$result['areas']    = (!$all_areas) ? false : $all_areas;
		$result['validate'] = validation($permits, $this->dashboardModel);
		$result['role']     = $permits['title'];
		$result['id_first'] = $id_first;
		$this->load->view('configurar-dashboard-areas', $result);
	}

	function configDCC() {
		$permits = $this->session->userdata();

		if (!$permits['admin']) {
			redirect('inicio');
		}
		$id_first = "-1";
		if (!is_null($this->session->flashdata('id_first_dcc'))) {
			$id_first = $this->session->flashdata('id_first_dcc');
		} elseif (($this->session->userdata('id_org') == 1 || $this->session->userdata('id_org') == 0) && $this->session->userdata('id_org') != NULL) {
			$id_first = $this->session->userdata('id_org');
			$this->session->unset_userdata('id_org');
		}

		$this->session->set_flashdata('id_first_dcc', $id_first);
		$all_metrics = $this->Dashboardconfig_model->getAllMetricsDCC();//Retorna arrglo de arreglos de todas las métricas
		//Si all_metrics es falso es porque no hay areas
		$all_areas = $this->Dashboardconfig_model->getAllAreasUnidad();

		$result['metricas'] = [];
		$result['years']    = array(
			'id'    => -1,
			'type'  => 2,
			'min'   => 2005,
			'max'   => 2015,
			'check' => NULL
		);
		$result['met_operacion'] = [];
		$result['met_soporte']   = [];

		if ($all_metrics) {
			$met_op             = [];
			$met_sop            = [];
			$result['metricas'] = $all_metrics;

			if (!$all_areas) {
				$keys_areas = [];
			} else {
				$keys_areas = array_keys($all_areas);
			}
			$id_keys = array_keys($all_metrics);
			for ($i = 0; $i < sizeof($id_keys); $i++) {
				$id_org = $id_keys[$i];
				$metric = $all_metrics[$id_org];

				foreach ($metric as $met) {//Permite acceder a nombre y id una metrica
					$id = $met['metorg'];

					if (in_array($id_org, $keys_areas)) {
						$this_unidades = $all_areas[$id_org]['unidades'];
						if ($all_areas[$id_org]['parent'] == 0) {
							$met_sop[] = $id;
							foreach ($this_unidades as $u) {
								if (in_array($u['id'], $id_keys)) {
									foreach ($all_metrics[$u['id']] as $met_u) {
										$met_sop[] = $met_u['metorg'];
									}
								}
							}
						} else {
							$met_op[] = $id;
							foreach ($this_unidades as $u) {
								if (in_array($u['id'], $id_keys)) {
									foreach ($all_metrics[$u['id']] as $met_u) {
										$met_op[] = $met_u['metorg'];
									}
								}
							}
						}
					}

					if ($id_org == 0 || $id_org == 1) {
						$id_org_dash = strval($id_org);
					} elseif (in_array($id_org, $keys_areas)) {
						$id_org_dash = $all_areas[$id_org]['parent'];
					} else {
						$id_org_dash = -1;
						for ($j = 0; $j < sizeof($keys_areas); $j++) {
							$unidades = $all_areas[$keys_areas[$j]]['unidades'];
							foreach ($unidades as $u) {
								if (in_array($id_org, $u)) {
									$id_org_dash = $all_areas[$keys_areas[$j]]['parent'];
									break;
								}
							}
							if ($id_org_dash != -1) {
								break;
							}
						}
					}

					$min_max_years = $this->Dashboardconfig_model->getMinMaxYears($id, $id_org_dash);//Si existe config entrego los años correspondientes, junto con valor check
					$years[$id]    = $min_max_years;
				}

			}
			$met_op          = array_unique($met_op);
			$met_sop         = array_unique($met_sop);
			$result['years'] = $years;
		}

		$result['areas']    = (!$all_areas) ? [] : $all_areas;
		$result['validate'] = validation($permits, $this->dashboardModel);
		$result['role']     = $permits['title'];
		$result['id_first'] = $id_first;
		$result['colors']   = array("1" => "warning", "0" => "success");
		$this->load->view('configurar-dashboard-dcc', $result);
	}

	function addGraphUnidad() {

		$this->addGraph();
		redirect('cdashboardUnidad');
	}

	function addGraphArea() {

		$this->addGraph();
		redirect('cdashboardArea');
	}

	function addGraphDCC() {

		$this->addGraph();
		redirect('cdashboardDCC');
	}

	function addGraph() {
		$permits = $this->session->userdata();

		if (!$permits['admin']) {
			redirect('inicio');
		}

		$this->load->library('form_validation');
		$this->form_validation->set_rules('from', 'From', 'required|exact_length[4]|numeric');
		$this->form_validation->set_rules('to', 'to', 'required|exact_length[4]|numeric');
		$this->form_validation->set_rules('id_graph', 'Graph', 'required|numeric');
		$this->form_validation->set_rules('id_org', 'Org', 'required|numeric');
		$this->form_validation->set_rules('type', 'Type', 'required|numeric');
		$this->form_validation->set_rules('id_met', 'Metric', 'required|numeric');

		if (!$this->form_validation->run()) {
			redirect('inicio');
		}

		$this->session->set_userdata('id_org', $this->input->post('id_org'));

		$id_graph = intval($this->input->post('id_graph'));
		$org_id   = intval($this->input->post('id_org'));
		$graph    = $this->input->post('type');
		$id_met   = $this->input->post('id_met');
		$from     = $this->input->post('from');
		$to       = $this->input->post('to');
		$position = $this->input->post('mostrar') == NULL ? 0 : 1;

		$data = array('type' => $graph,
			'id_met'            => $id_met,
			'from'              => $from,
			'to'                => $to,
			'position'          => $position,
			'id_org'            => $org_id,
			'id_graph'          => $id_graph);

		$this->Dashboardconfig_model->addGraph($data);
	}

	private function getColors() {
		$this->load->model("Organization_model");
		$types = $this->Organization_model->getTypes();

		foreach ($types as $t) {
			$colors[$t['name']] = $t['color'];
		}
		return $colors;
	}

}
