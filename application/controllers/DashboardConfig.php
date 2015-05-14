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
	    		'id' => -1,
	    		'type' => 2,
				'min' => 2005, 
				'max' => 2015,
				'check' => NULL
				);
	    }
	    else{
	    	$result['metricas'] = $all_metrics;
	    	foreach ($all_metrics as $met_unidad) {
	    		$id_org = key($all_metrics);
	    		foreach ($met_unidad as $met) { //Permite acceder a nombre y id una metrica
	    			$id=$met['metorg'];
	    			$min_max_years = $this->DashboardConfig_model->getMinMaxYears($id,$id_org); //Si existe config entrego los años correspondientes, junto con valor check
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
	    //debug($all_metrics, true);
	}

	function configArea(){

		$this->load->model('DashboardConfig_model');
	    $all_metrics = $this->DashboardConfig_model->getAllMetricsArea(); //Retorna arrglo de arreglos de metricas de las unidades y areas correspondientes
	    															      //Si all_metrics es falso es porque no hay areas
	    
	    $all_areas = $this->DashboardConfig_model->getAllAreasUnidad(); //arreglo de areas y sus respectivas unidades id_area =>(nombre, id, arreglo_unidades)
	    
	    if($all_metrics==false){
	    	$result['metricas'] = [];
	    	$result['years'] = array(
	    		'id' => -1,
	    		'type' => 2,
				'min' => 2005, 
				'max' => 2015,
				'check' => NULL
				);
	    }
	    else{
	    	$result['metricas'] = $all_metrics;
	    	foreach ($all_metrics as $met_unidad) {
	    		$id_org = key($all_metrics);
	    		foreach ($met_unidad as $met) { //Permite acceder a nombre y id una metrica
	    			$id=$met['metorg'];
	    			$min_max_years = $this->DashboardConfig_model->getMinMaxYears($id,$id_org); //Si existe config entrego los años correspondientes, junto con valor check
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

	    $this->load->view('configurar-dashboard-areas', $result);
	    //debug($all_metrics, true);

	}

	function configDCC(){ //Distinguir negocio y soporte?

		$this->load->model('DashboardConfig_model');
	    $all_metrics = $this->DashboardConfig_model->getAllMetricsDCC(); //Retorna arrglo de arreglos de todas las métricas
	    															      //Si all_metrics es falso es porque no hay areas

	    $all_areas = $this->DashboardConfig_model->getAllAreasUnidad();

	    if($all_metrics==false){
	    	$result['metricas'] = [];
	    	$result['years'] = array(
	    		'id' => -1,
	    		'type' => 2,
				'min' => 2005, 
				'max' => 2015,
				'check' => NULL
				);
	    }
	    else{
	    	$result['metricas'] = $all_metrics;
	    	foreach ($all_metrics as $met_unidad) {
	    		$id_org = key($all_metrics);
	    		foreach ($met_unidad as $met) { //Permite acceder a nombre y id una metrica
	    			$id=$met['metorg'];
	    			$min_max_years = $this->DashboardConfig_model->getMinMaxYears($id,$id_org); //Si existe config entrego los años correspondientes, junto con valor check
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
		
	    $this->load->view('configurar-dashboard-dcc',$result);
	    //debug($all_metrics, true);

	}

	function addGraphUnidad(){

		$this->addGraph();
		$this->configUnidad();
	}

	function addGraphArea(){

		$this->addGraph();
		$this->configArea();
	}

	function addGraph(){
		$this->load->library('form_validation');
		$this->form_validation->set_rules('from', 'From', 'required|exact_length[4]|numeric');
		$this->form_validation->set_rules('to', 'to', 'required|exact_length[4]|numeric');
		$this->form_validation->set_rules('id_graph', 'Graph', 'required|numeric');
		$this->form_validation->set_rules('id_org', 'Org', 'required|numeric');
		$this->form_validation->set_rules('type', 'Type', 'required|numeric');
		$this->form_validation->set_rules('id_met', 'Metric', 'required|numeric');
		
		if(!$this->form_validation->run()){
			redirect('Session/inicio');
		}

		$id_graph = intval($this->input->post('id_graph'));
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
						'id_org' => $org_id,
						'id_graph' => $id_graph );

		$this->load->model('DashboardConfig_model');
		//debug($data);
		$this->DashboardConfig_model->addGraph($data);

		
	}

	

}