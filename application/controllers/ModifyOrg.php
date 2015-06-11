<?php
class ModifyOrg extends CI_Controller{

  private $dashboardModel;

    function __construct(){
        parent::__construct();
        $this->load->model('Dashboard_model');
    		$this->dashboardModel = $this->Dashboard_model;
    }

    function modifyAreaUnidad(){
	    $this->load->model('Organization_model');
        $this->load->model('Dashboard_model');
	    $this->load->library('session');
	    $this->load->library('parser');

		$user = $this->session->userdata("rut");
    	$permits = array('director' => $this->session->userdata("director"));

    	if(!$permits['director']){
    		redirect('inicio');
    	}

	    $val = $this->session->flashdata('success');
	    if (is_null($val))
	       $val = 2;

	    $this->load->view('configurar-areas-unidades',
	                       array('title'=>'Configuración de Areas y Unidades',
	                             'name' => 'Juan Jones',
	                             'role' => $this->session->userdata("title"),
	                             'success'=> $val,
	                             'validate' => validation($permits, $this->dashboardModel),
	                             'departments' => getAllOrgsByDpto($this->Organization_model) //Notar que funcion esta en helpers
	                       ));
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
	    $this->form_validation->set_rules('name', 'Name', 'trim|required|alphaSpace');

	    if(!$this->form_validation->run()){
			$this->setRedirect('careaunidad', array('name'=>'success', 'value'=>0));
		}

	    $data = array('type'=>$this->input->post('type'), 'name'=>ucwords($this->input->post('name')));
	    $result = $this->Organization_model->addArea($data);
	    $this->setRedirect('careaunidad', array('name'=>'success', 'value'=>$result));
	}

	function addUni() {
	    $this->load->model('Organization_model');

	    $this->load->library('form_validation');
	    $this->form_validation->set_rules('area', 'Area', 'trim|required|alphaSpace');
	    $this->form_validation->set_rules('name', 'Name', 'trim|required|alphaSpace');

	    if(!$this->form_validation->run()){
			$this->setRedirect('careaunidad', array('name'=>'success', 'value'=>-1));
		}
	    $data = array('name'=>ucwords($this->input->post('name')));
	    $result = $this->Organization_model->addUnidad($this->input->post('area'), $data);
	    $this->setRedirect('careaunidad', array('name'=>'success', 'value'=>$result));
	}

	function delAreaUni() {
	    $this->load->model('Organization_model');

	    $this->load->library('form_validation');
	    $this->form_validation->set_rules('name', 'Name', 'trim|required|alphaSpace');

	    if(!$this->form_validation->run()){
			$this->setRedirect('careaunidad', array('name'=>'success', 'value'=>0));
		}

	    $data = $this->input->post('name');
	    $result = $this->Organization_model->delByName($data);
	    $this->setRedirect('careaunidad', array('name'=>'success', 'value'=>$result));
	}

}
