<?php
class Budget extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Dashboard_model');
        $this->load->model('Organization_model');
        $this->load->library('form_validation');
        $this->load->library('session');
    }

    function index(){
        $permits = $this->session->userdata();
        if (!$permits['director'] && in_array(-1, $permits['encargado_finanzas_unidad'])) {
            redirect('inicio');
        }
        if ($permits['director'])
            $orgs = $this->Organization_model->getAllOrgsIds();
        else {
            $orgs = [];
            $aux = $permits['encargado_finanzas_unidad'];
            foreach($aux as $org){
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
                'departments' => getAllOrgsByDpto($this->Organization_model)//Notar que funcion esta en helpers
            )
        );
    }
}