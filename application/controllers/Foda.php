<?php
class Foda extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Dashboard_model');
        $this->load->model('Foda_model');
        $this->load->model('Organization_model');
        $this->load->library('form_validation');
        $this->load->library('session');
    }

    function modifyFoda() {
        $permits = $this->session->userdata();
        if (!$permits['director']) {
            redirect('inicio');
        }

        $val = $this->session->flashdata('success');
        if (is_null($val)) {
            $val = 2;
        }
        $fodas = $this->Foda_model->getFoda(['order'=>[['org', 'ASC'], ['year', 'ASC']]]);
        $fodasByOrg = array();
        $index = -1;
        foreach ($fodas as $foda){
            if ($index!=intval($foda->org)){
                $index = $foda->org;
                $fodasByOrg[$index] = array();
            }
            array_push($fodasByOrg[$index], $foda);
        }
        $this->load->view('configurar-foda',
            array('title'  => 'Configuración de Foda',
                'role'        => $permits['title'],
                'success'     => $val,
                'fodas'       => $fodasByOrg,
                'validate'    => validation($permits, $this->Dashboard_model),
                'departments' => getAllOrgsByDpto($this->Organization_model)//Notar que funcion esta en helpers
            ));
    }

    function addFodaItem() {
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
}
