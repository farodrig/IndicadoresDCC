<?php
class ModifyOrg extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library('session');
		if (is_null($this->session->rut))
			redirect('salir');
		
		$this->load->library('form_validation');
		$this->load->model('Dashboard_model');
		$this->load->model('Organization_model');
		$permits = $this->session->userdata();
		$this->access = ((count($permits['conf']['edit']) + count($permits['conf']['view'])) > 0);

	}

	function modifyAreaUnidad() {
		$permits = $this->session->userdata();
		if (!$this->access) {
			redirect('inicio');
		}

		$val = (is_null($this->session->flashdata('success')) ? 2 : $this->session->flashdata('success'));
		$result = array('title'  	  => 'Configuración de Areas y Unidades',
						'success'     => $val,
						'departments' => getAllOrgsByDpto($this->Organization_model)//Notar que funcion esta en helpers
		);
		$this->load->view('configurar-areas-unidades', array_merge($result, defaultResult($permits, $this->Dashboard_model)));
	}

	function addArea() {
		if (!$this->access) {
			redirect('inicio');
		}
		$this->form_validation->set_rules('type', 'Type', 'numeric|required');
		$this->form_validation->set_rules('name', 'Name', 'trim|required|alphaSpace');

		if (!$this->form_validation->run()) {
			$this->session->set_flashdata('success', 0);
			redirect('careaunidad');
		}

		$data = array('type' => $this->input->post('type'),
			          'name' => ucwords($this->input->post('name')));
		$result = $this->Organization_model->addArea($data);
		$this->session->set_flashdata('success', $result);
		redirect('careaunidad');
	}

	function addUni() {
		if (!$this->access) {
			redirect('inicio');
		}
		$this->form_validation->set_rules('area', 'Area', 'trim|required|integer');
		$this->form_validation->set_rules('name', 'Name', 'trim|required|alphaSpace');

		if (!$this->form_validation->run()) {
			$this->session->set_flashdata('success', 0);
			redirect('careaunidad');
		}
		$data   = array('name' => ucwords($this->input->post('name')));
		$result = $this->Organization_model->addUnidad($this->input->post('area'), $data);
		$this->session->set_flashdata('success', $result);
		redirect('careaunidad');
	}

	function delAreaUni() {
		if (!$this->access) {
			redirect('inicio');
		}
		$this->form_validation->set_rules('id', 'Id', 'trim|required|integer');

		if (!$this->form_validation->run()) {
			$this->session->set_flashdata('success', 0);
			redirect('careaunidad');
		}

		$result = $this->Organization_model->delById($this->input->post('id'));
		$this->session->set_flashdata('success', $result);
		redirect('careaunidad');
	}

}
