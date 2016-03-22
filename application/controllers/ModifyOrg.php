<?php
class ModifyOrg extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('Dashboard_model');
		$this->load->model('Organization_model');
		$this->load->library('form_validation');
		$this->load->library('session');
		if (is_null($this->session->rut))
			redirect('salir');
	}

	function modifyAreaUnidad() {
		$permits = $this->session->userdata();
		if (!$permits['admin']) {
			redirect('inicio');
		}

		$val = $this->session->flashdata('success');
		if (is_null($val)) {
			$val = 2;
		}

		$this->load->view('configurar-areas-unidades',
			array('title'  => 'Configuración de Areas y Unidades',
				'role'        => $permits['title'],
				'success'     => $val,
				'validate'    => validation($permits, $this->Dashboard_model),
				'departments' => getAllOrgsByDpto($this->Organization_model)//Notar que funcion esta en helpers
			));
	}

	function addArea() {
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
