<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class DashboardConfig extends CI_Controller 
{

	function __construct()
	{
		parent::__construct();

	}

	function configUnidad() // funcion que lista todas las metricas y las deja como objeto cada una por lo tanto se puede recorrer el arreglo
	                           // y llamar a cada valor del arreglo como liberia ejemplo mas abajo
	                           // esto sirve para cuando se llama de una vista para completar por ejemplo una tabla
	{
	    $this->load->model('DashboardConfig_model');
	    $all_metrics = $this->DashboardConfig_model->getAllMetricsUnidades(); //Retorna arrglo de arreglos de metricas de las unidades correspondientes
	    															          //Si all_metrics es falso es porque no hay areas

	    $all_areas = $this->DashboardConfig_model->getAllAreasUnidad();

	    if($all_metrics==false){
	    	$result['metricas'] = [];
	    	$result['years'] = array(
				'min' => 2005, 
				'max' => 2015
				);
	    }
	    else{
	    	$result['metricas'] = $all_metrics;
	    	foreach ($all_metrics as $met_unidad) {
	    		foreach ($met_unidad as $met) { //Permite acceder a nombre y id una metrica
	    			$id=$met['metorg'];
	    			$min_max_years = $this->DashboardConfig_model->getMinMaxYears($id);
	    			$years[$id] = $min_max_years;
	    		}
	    		
	    	}
	    	$result['years'] = $years;
	    }

	   if(!$all_areas)
	    	$result['areas'] = [];
	    else{
	    	$result['areas'] = $all_areas;
	    }

	    $this->load->view('configurar-dashboard', $result);
	    //debug($result, true);
	}

	function configArea(){

		$this->load->model('DashboardConfig_model');
	    $all_metrics = $this->DashboardConfig_model->getAllMetricsArea(); //Retorna arrglo de arreglos de metricas de las unidades correspondientes
	    															      //Si all_metrics es falso es porque no hay areas

	    $all_areas = $this->DashboardConfig_model->getAllAreasUnidad();

	    if($all_metrics==false){
	    	$result['metricas'] = [];
	    }
	    else{
	    	$result['metricas'] = $all_metrics;   
	    }

	    if(!$all_areas)
	    	$result['areas'] = [];
	    else{
	    	$result['areas'] = $all_areas;
	    }

	    $this->load->view('configurar-dashboard-areas', $result);
	    //debug($result, true);

	}

	function configDCC(){ //Distinguir negocio y soporte?

		$this->load->model('DashboardConfig_model');
	    $all_metrics = $this->DashboardConfig_model->getAllMetricsDCC(); //Retorna arrglo de arreglos de metricas de las unidades correspondientes
	    															      //Si all_metrics es falso es porque no hay areas

	    $all_areas = $this->DashboardConfig_model->getAllAreasUnidad();

	    if($all_metrics==false){
	    	$result['metricas'] = [];
	    }
	    else{
	    	$result['metricas'] = $all_metrics;   
	    }

	    if(!$all_areas)
	    	$result['areas'] = [];
	    else{
	    	$result['areas'] = $all_areas;
	    }

	    $this->load->view('configurar-dashboard-areas', $result);
	    //debug($result, true);

	}

	function addGraph(){
		$this->load->library('form_validation');
		$this->form_validation->set_rules('from', 'From', 'required|exact_length[4]|numeric');
		$this->form_validation->set_rules('to', 'to', 'required|exact_length[4]|numeric');
		
		if(!$this->form_validation->run()){
			redirect('Session/inicio');
		}

		$org_id = intval($this->input->post('id_org'));
		$graph = $this->input->post('type');
		$id_met = $this->input->post('id_met');
		$from = $this->input->post('from');
		$to = $this->input->post('to');
		$position = $this->input->post('mostrar')==NULL ? 0 : 1;

		$data  = array('type' => $graph,
						'id_met' => $id_met,
						'from' => $from,
						'to' => $to,
						'position' => $position,
						'id_org' => $org_id );

		$this->load->model('DashboardConfig_model');
		//debug($data);
		$this->DashboardConfig_model->addGraph($data);

		$this->configUnidad();
	}

	

}