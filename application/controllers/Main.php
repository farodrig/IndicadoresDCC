<?php

/**
 * Created by PhpStorm.
 * User: farodrig
 * Date: 16-07-16
 * Time: 14:19
 */
class Main  extends CI_Controller{

    function __construct() {
        parent::__construct();
        $this->load->library('session');
    }

    public function index() {
        $result          = array();
        $result['error'] = $this->session->flashdata('error');
        /* Descomentar Desde Aqui
        $this->load->model('User_model');
        if ($this->input->method() == "post" && $this->input->post('user')) {
            $user = $this->User_model->getUserById($this->input->post('user'));
            $this->session->set_userdata('rut', $user->id);
            $this->session->set_userdata('name', $user->name);
            $this->session->set_userdata('email', $user->email);
            $permits       = $this->User_model->getPermitByUser($user->id);
            $permits_array = getPermits($permits);
            $permits_array['title'] = getTitle($user);
            $this->session->set_userdata($permits_array);
            redirect('inicio');
        }
        $users = $this->User_model->getAllUsers();
        $result['users'] = [];
        foreach($users as $user){
            $result['users'][$user->id] = $user->name;
        }
        //Hasta Aqui para Pasar a DESARROLLO */
        $this->load->view('login', $result);
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('');
    }

    public function inicio() {
        $user = $this->session->rut;
        if (is_null($user))
            redirect('salir');

        $this->load->model('Dashboard_model');
        $this->load->model('Organization_model');

        $permits    = $this->session->userdata();
        $type       = is_null($this->input->get('sector')) ? "Operación" : $this->input->get('sector');
        $department = $this->Organization_model->getDepartment();
        $areaunit   = $this->showAreaUnit();
        $name       = $type;
        $aus        = $areaunit;
        $areaunit   = array();

        $type = $this->Organization_model->getTypeByName($name);

        foreach ($aus as $au)
            if ($au['area']->getType() == $type['id']) {
                array_push($areaunit, $au);
            }
        $result = array('department' => $department,
            'areaunit'                  => $areaunit,
            'types'                     => $this->Organization_model->getTypes(),
            'name'                      => $name,
            'user'                      => $user
        );
        $this->load->view('index', array_merge($result, defaultResult($permits, $this->Dashboard_model)));
    }

    private function showAreaUnit() {

        $this->load->model('Organization_model');
        $areaunit = array();
        $areas    = $this->Organization_model->getAllAreas();
        foreach ($areas as $area) {
            array_push($areaunit, array('area' => $area,
                    'unidades' => $this->Organization_model->getAllUnidades($area->getId()))
            );
        }
        return $areaunit;
    }
}