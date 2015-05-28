<?php
class ModifyOrg extends CI_Controller{

    function __construct(){
        parent::__construct();
    }

    function modifyAreaUnidad(){
	    $this->load->model('Organization_model');
	    $this->load->library('session');
	    $this->load->library('parser');
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
	                       array('title'=>'Configuración de Areas y Unidades',
	                             'name' => 'Juan Jones',
	                             'role' => $this->session->userdata("title"),
	                             'areaunit'=>$areaunit,
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
	    $this->form_validation->set_rules('name', 'Name', 'trim|required|callback_alphaSpace');

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
	    $this->form_validation->set_rules('area', 'Area', 'trim|required|callback_alphaSpace');
	    $this->form_validation->set_rules('name', 'Name', 'trim|required|callback_alphaSpace');

	    if(!$this->form_validation->run()){
			$this->setRedirect('careaunidad', array('name'=>'success', 'value'=>0));
		}
	    $data = array('name'=>ucwords($this->input->post('name')));
	    $result = $this->Organization_model->addUnidad($this->input->post('area'), $data);
	    $this->setRedirect('careaunidad', array('name'=>'success', 'value'=>$result));
	}

	function delAreaUni() {
	    $this->load->model('Organization_model');

	    $this->load->library('form_validation');
	    $this->form_validation->set_rules('name', 'Name', 'trim|required|callback_alphaSpace');

	    if(!$this->form_validation->run()){
			$this->setRedirect('careaunidad', array('name'=>'success', 'value'=>0));
		}

	    $data = $this->input->post('name');
	    $result = $this->Organization_model->delByName($data);
	    $this->setRedirect('careaunidad', array('name'=>'success', 'value'=>$result));
	}

	public function alphaSpace($str){
	    if (preg_match("^([a-zA-Zñáéíóú]\s?)+^", $str, $data) && $data[0]==$str){
	        return true;
	    }
	    else{
	        $this->form_validation->set_message('alphaSpace', 'El campo {field} contiene caracteres no alfabeticos o espacios');
	        return false;
	    }
	}
}