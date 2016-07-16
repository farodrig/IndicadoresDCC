<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');
}

class DashboardConfig extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library('session');
		if (is_null($this->session->rut))
			redirect('salir');
		$this->load->library('form_validation');
		$this->load->model('Organization_model');
		$this->load->model('Dashboard_model');
		$this->load->model('Dashboardconfig_model');
		$this->load->model('Metorg_model');
		$this->load->model('Metrics_model');
		$permits = $this->session->userdata();
        $this->access = ((count($permits['conf']['edit']) + count($permits['conf']['view'])) > 0);
        $this->edit = (count($permits['conf']['edit']) > 0);
	}
	
	function dashboardConfig(){
		$permits = $this->session->userdata();
		if (!$this->access) {
			redirect('inicio');
		}

		$org_ids = $this->Organization_model->getAllIds();
		$data = $this->getDashboardConfigData($org_ids);

		$types = [];
		foreach ($this->Dashboardconfig_model->getSerieType([]) as $type) {
			$types[$type->id] = $type;
		}
		$aggregation = [];
		foreach ($this->Dashboardconfig_model->getAggregationType([]) as $type) {
			$aggregation[$type->id] = $type;
		}
		$result = array('orgs' => $data['orgs'],
			'title' => 'Configuración de Dashboard',
			'graphics' => $data['graphics'],
			'types' => $types,
			'aggregation' => $aggregation,
			'metrics' => $data['metrics'],
			'success' => is_null($this->session->flashdata('success')) ? 2 : $this->session->flashdata('success'),
			'departments' => $this->Organization_model->getTree(-1)
		);
		$this->load->view('configurar-dashboard', array_merge($result, defaultResult($permits, $this->Dashboard_model)));
	}
	
	function modifyGraphic(){
        //Revisión de permisos
		if (!$this->input->is_ajax_request() || !$this->edit) {
			echo json_encode(array('success'=>0));
			return;
		}
        
		//Validación de entradas
		$this->form_validation->set_rules('org', 'Organization', 'numeric|required|greater_than_equal_to[0]');
		$this->form_validation->set_rules('graphic', 'Gráfico', 'numeric|required');
		$this->form_validation->set_rules('title', 'Titulo', 'trim|required|alphaNumericSpaceSymbol');
		$this->form_validation->set_rules('minYear', 'Año Mínimo', 'numeric|required|greater_than_equal_to[1950]');
		$this->form_validation->set_rules('maxYear', 'Año Máximo', 'numeric|required|greater_than_equal_to[1950]');
		$this->form_validation->set_rules('position', 'Posición', 'numeric|required|greater_than_equal_to[0]');
		$this->form_validation->set_rules('byYear', 'Por Año', 'required|numeric');
		$this->form_validation->set_rules('display', 'Mostrar', 'required|numeric');
		
		if (!$this->form_validation->run()) {
			echo json_encode(array('success'=>0));
			return;
		}

		$done = true;
		$id = $this->input->post('graphic');
		$org = $this->input->post('org');
		$title = $this->input->post('title');
		$max = $this->input->post('maxYear');
		$min = $this->input->post('minYear');
		$pos = $this->input->post('position');
		$x = ($this->input->post('byYear') ? 0 : 1);
		$display = $this->input->post('display');
        $dash = $this->Dashboardconfig_model->getOrCreateDashboard($org);
		if ($this->input->post('graphic')!=-1){
			$done = $done && $this->Dashboardconfig_model->modifyGraphic($id, $title, $max, $min, $pos, $x, $dash->id, $display);
		}
		else{
			$id = $this->Dashboardconfig_model->addGraphic($title, $max, $min, $pos, $x, $dash->id, $display);
			$done = $done && ($id? true : false);
		}

		$org_ids = $this->Organization_model->getAllIds();
		$result['success'] = $done;
		$result = array_merge($result, $this->getDashboardConfigData($org_ids));
		echo json_encode($result);
	}

	function modifySerie(){
        //Revisión de permisos
        if (!$this->input->is_ajax_request() || !$this->edit) {
            echo json_encode(array('success'=>0));
            return;
        }
        
		//Validación de entradas
		$this->form_validation->set_rules('graphic', 'Gráfico', 'numeric|required|greater_than_equal_to[0]');
		$this->form_validation->set_rules('metorg', 'Métrica', 'numeric|required|greater_than_equal_to[0]');
		$this->form_validation->set_rules('serie', 'Serie', 'numeric|required');
		$this->form_validation->set_rules('type', 'Tipo', 'numeric|required|greater_than_equal_to[0]');
		$this->form_validation->set_rules('aggregX', 'Agregación en X', 'numeric|required|greater_than_equal_to[0]');
		$this->form_validation->set_rules('aggregYear', 'Agregación en Años', 'numeric|required|greater_than_equal_to[0]');
		$this->form_validation->set_rules('color', 'Color', 'color_validator');

		if (!$this->form_validation->run()) {
			echo json_encode(array('success'=>0));
			return;
		}
		
		$done = true;
		$id = $this->input->post('serie');
		$graphic = $this->input->post('graphic');
		$metorg = $this->input->post('metorg');
		$type = $this->input->post('type');
		$aggregX = $this->input->post('aggregX');
		$aggregYear = $this->input->post('aggregYear');
		$color = (!$this->input->post('color') ? null : $this->input->post('color'));
		
		if ($this->input->post('serie')!=-1){
			$done = $done && $this->Dashboardconfig_model->modifySerie($id, $graphic, $metorg, $type, $aggregX, $aggregYear, $color);
		}
		else{
			$done = $done && ($this->Dashboardconfig_model->addSerie($graphic, $metorg, $type, $aggregX, $aggregYear, $color)? true : false);
		}

		$org_ids = $this->Organization_model->getAllIds();
		$result['success'] = $done;
		$result = array_merge($result, $this->getDashboardConfigData($org_ids));
		echo json_encode($result);
	}
	
	function graphicValues(){
        //Revisión de permisos
        if (!$this->input->is_ajax_request() || !$this->access) {
            echo json_encode(array('success'=>0));
            return;
        }
        
		//Validación de entradas
		$this->form_validation->set_rules('graphic', 'Gráfico', 'numeric|required|greater_than_equal_to[0]');
		if (!$this->form_validation->run()) {
			echo json_encode(array('success'=>0));
			return;
		}
		$done = true;
		$result['values'] = $this->Dashboard_model->getGraphicData($this->input->post('graphic'));
		$result['success'] = $done && ($result['values'] ? true : false);
		echo json_encode($result);
		return;
	}

	function delete(){
        //Revisión de permisos
        if (!$this->input->is_ajax_request() || !$this->edit) {
            echo json_encode(array('success'=>0));
            return;
        }

		$this->form_validation->set_rules('type', 'Tipo de elemento', 'trim|required|alpha_dash');
		$this->form_validation->set_rules('id', 'ID', 'numeric|required|greater_than_equal_to[0]');
		if (!$this->form_validation->run()) {
			echo json_encode(array('success'=>0));
			return;
		}
		$type = $this->input->post('type');
		$id = $this->input->post('id');
		$result['success'] = $this->deleteElement($type, $id);
		$org_ids = $this->Organization_model->getAllIds();
		$result = array_merge($result, $this->getDashboardConfigData($org_ids));
		echo json_encode($result);
	}

	private function deleteElement($type, $id){
		$result = false;
		if(strcmp($type, "graphic")==0){
			$result = $this->Dashboardconfig_model->deleteGraphic($id);
		}
		elseif (strcmp($type, "serie")==0){
			$result = $this->Dashboardconfig_model->deleteSerie($id);
		}
		return $result;
	}

	function getDashboardConfigData($org_ids){
		$orgs = [];
		$metrics = [];
		$graphics = [];
		foreach ($org_ids as $org_id){
			$org = $this->Organization_model->getByID($org_id);
			$orgs[$org->getId()] = $org->getName();
			$metorgs = $this->Metorg_model->getMetOrg(['org'=>[$org_id]]);
			foreach ($metorgs as $metorg){
				$metric = $this->Metrics_model->getMetric(['id'=>[$metorg->metric]])[0];
				$metrics[$org_id][$metorg->id] = $metric;
			}
			$graphs = $this->Dashboardconfig_model->getAllGraphicByOrg($org_id, true);
			foreach ($graphs as $graph){
				$graph->series = $this->Dashboardconfig_model->getAllSeriesByGraph($graph->id);
				$graphics[$org_id][$graph->id] = $graph;
			}
		}
		return ['orgs'=>$orgs, 'graphics'=>$graphics, 'metrics'=>$metrics];
	}
}
