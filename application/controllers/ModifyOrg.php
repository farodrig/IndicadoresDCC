<?php
class ModifyOrg extends CI_Controller{
    
    function __construct(){
        parent::__construct();
    }
    
    function modifyAreaUnidad(){
	    $this->load->model('Organization_model');
	    $this->load->library('session');
		$user = $this->session->userdata("user");
    	$permits = array('director' => $this->session->userdata("director"),
    						'visualizador' => $this->session->userdata("visualizador"),
    						'asistente_unidad' => $this->session->userdata("asistente_unidad"),
    						'asistente_finanzas_unidad' => $this->session->userdata("asistente_finanzas_unidad"),
    						'encargado_unidad' => $this->session->userdata("encargado_unidad"),
    						'asistente_dcc' => $this->session->userdata("asistente_dcc"),
    						'validate' => $this->session->userdata("validate"));

    	if(!$permits['director']){
    		redirect('inicio');
    	}
	    $val = $this->session->flashdata('success');
	    if (is_null($val))
	       $val = 2;
	    $areaunit = array();
	    $areas = $this->Organization_model->getAllAreas();
	    foreach ($areas as $area){
	        array_push($areaunit, array('area' => $area, 
	                                    'unidades' => $this->Organization_model->getAllUnidades($area->getId()))
	                   );
	    }
	    $this->load->view('configurar-areas-unidades', 
	                       array('areaunit'=>$areaunit, 
	                             'success'=> $val, 
	                             'types'=>$this->Organization_model->getTypes(),
	                             'validate' => $permits['validate']));
	}
		
	private function setRedirect($url, $data) {
	    $this->load->library('session');
	    $this->session->set_flashdata($data['name'], $data['value']);
	    redirect($url);
	}
	
	function addArea() {
	    $this->load->model('Organization_model');
	    $this->load->library('form_validation');
	    $this->form_validation->set_rules('type', 'Type', 'numeric|required');
	    $this->form_validation->set_rules('name', 'Name', 'required');

	    if(!$this->form_validation->run()){
			redirect('inicio');
		}

	    $data = array('type'=>$this->input->post('type'), 'name'=>$this->input->post('name'));
	    $result = $this->Organization_model->addArea($data);
	    $this->setRedirect('/careaunidad', array('name'=>'success', 'value'=>$result));
	}
	
	function addUni() {
	    $this->load->model('Organization_model');
	    $this->load->library('form_validation');
	    $this->form_validation->set_rules('area', 'Area', 'required');
	    $this->form_validation->set_rules('name', 'Name', 'required');

	    if(!$this->form_validation->run()){
			redirect('inicio');
		}
	    $data = array('name'=>$this->input->post('name'));
	    $result = $this->Organization_model->addUnidad($this->input->post('area'), $data);
	    $this->setRedirect('/careaunidad', array('name'=>'success', 'value'=>$result));
	}
	
	function delAreaUni() {
	    $this->load->model('Organization_model');
	    
	    $this->load->library('form_validation');
	    $this->form_validation->set_rules('name', 'Name', 'required');

	    if(!$this->form_validation->run()){
			redirect('inicio');
		}

	    $data = $this->input->post('name');
	    $result = $this->Organization_model->delByName($data);
	    $this->setRedirect('/careaunidad', array('name'=>'success', 'value'=>$result));
	}	
}