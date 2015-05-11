<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Session extends CI_Controller {

	public function index()
	{
		$this->load->view('login');
	}

    public function inicio()
	{
	    $this->load->model('Organization_model');
	    $department = $this->Organization_model->getDepartment();
	    $areaunit = array();
	    $areas = $this->Organization_model->getAllAreas();
	    foreach ($areas as $area){
	        array_push($areaunit, array('area' => $area,
	                                    'unidades' => $this->Organization_model->getAllUnidades($area->getId()))
	        );
	    }
	    $this->load->view('index', array('department'=> $department,
	                                     'areaunit'=>$areaunit,
	                                     'types'=>$this->Organization_model->getTypes()));
	}

	public function dashboard()
	{	
		
		$id_unidad = $this->input->post("unidad");
	    $this->session->set_flashdata("unidad", $id_unidad);
	    //redirect('Dashboard/showDashboard');
	}

	public function validar()
	{
	    $this->load->view('validar');
	}
	public function menuConfigurar()
	{
	    $this->load->view('menu-configurar');
	}
	public function agregarDato()
	{
	    $this->load->view('add-data');
	}

	
	public function configurarDashboard()
	{
	    $this->load->view('configurar-dashboard');
	}
	public function configurarMetricas()
	{
		$this->load->model('Metrics_model');
		$data['metrics'] = $this->Metrics_model->getAllMetrics();
	    $this->load->view('configurar-metricas',$data);
	}
	
	public function agregarMetrica(){
		$data= array(
			'category' => $this->input->post('tipo'), 
			'unit' => '1',
			'name' => $this->input->post('name'), 		
		);
     $this->load->model('Metrics_model');
   	 $this->Metrics_model->addMetric($data);
    $this->configurarMetricas();
		
		
	}

}