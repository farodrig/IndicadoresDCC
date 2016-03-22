<?php
class Budget extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Dashboard_model');
        $this->load->model('Organization_model');
        $this->load->library('form_validation');
        $this->load->library('session');
        if (is_null($this->session->rut))
            redirect('salir');
    }

    function index(){
        $permits = $this->session->userdata();
        if (!$permits['admin'] && !count($permits['encargado_finanzas'])  &&  !count($permits['asistente_finanzas'])) {
            redirect('inicio');
        }
        if ($permits['admin'])
            $orgs = $this->Organization_model->getAllOrgsIds();
        else {
            $orgs = [];
            $aux = array_merge($permits['encargado_finanzas'], $permits['asistente_finanzas']);
            foreach($aux as $org){
                if($org==-1)
                    continue;
                $orgs[] = $this->Organization_model->getByID($org);
            }
        }
        $datos = array();
        $aux_org = array();
        foreach($orgs as $org){
            $datos[$org->getId()] = $this->Dashboard_model->getBudgetMeasures($org->getId(), 1);
            $aux_org[] = $org->getId();
        }
        $years = [];
        foreach($datos as $dato){
            if (!$dato)
                continue;
            foreach($dato as $org){
                $years[] = $org->year;
            }
        }
        $years = array_unique($years);
        $this->load->view('budget',
            array('title'  => 'Presupuesto',
                'role'        => $permits['title'],
                'years'       => $years,
                'orgs'        => $aux_org,
                'data'        => $datos,
                'validate'    => validation($permits, $this->Dashboard_model),
                'departments' => $this->Organization_model->getTree($aux_org)//Notar que funcion esta en helpers
            )
        );
    }

    function modify(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
        $org =  $this->input->post("org");
        $permits = $this->session->userdata();
        if (!$permits['admin'] && !in_array($org, $permits['encargado_finanzas'])  && !in_array($org, $permits['asistente_finanzas'])) {
            echo json_encode(array('success'=>0));
            return;
        }
        $validation = 0;
        if($permits['admin'] || in_array($org, $permits['encargado_finanzas'])){
            $validation = 1;
        }

        $this->form_validation->set_rules('year', 'Año', 'numeric|required|greater_than_equal_to[0]');
        $this->form_validation->set_rules('value', 'Valor', 'numeric');
        $this->form_validation->set_rules('expected', 'Esperado', 'numeric');
        $this->form_validation->set_rules('target', 'Meta', 'numeric');

        if (!$this->form_validation->run()) {
            echo json_encode(array('success'=>0));
            return;
        }

        $year =  $this->input->post("year");
        $value =  $this->input->post("value");
        $expected =  $this->input->post("expected");
        $target =  $this->input->post("target");
        $this->load->model('Dashboard_model');
        $success = $this->Dashboard_model->updateCreateBudgetValue($org, $year, $value, $expected, $target, $validation);
        $result['success'] = $success;
        if($success){
            if ($permits['admin'])
                $orgs = $this->Organization_model->getAllOrgsIds();
            else {
                $orgs = [];
                $aux = array_merge($permits['encargado_finanzas'], $permits['asistente_finanzas']);
                foreach($aux as $org){
                    if($org==-1)
                        continue;
                    $orgs[] = $this->Organization_model->getByID($org);
                }
            }
            $datos = array();
            foreach($orgs as $org){
                $datos[$org->getId()] = $this->Dashboard_model->getBudgetMeasures($org->getId(), 1);
            }
            $result['data'] = $datos;
        }
        echo json_encode($result);
    }
}