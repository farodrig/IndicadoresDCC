<?php

/**
 * Created by PhpStorm.
 * User: farodrig
 * Date: 16-07-16
 * Time: 15:52
 */
class MainConfig extends CI_Controller{

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        if (is_null($this->session->rut))
            redirect('salir');
        $permits = $this->session->userdata();
        $this->access = ((count($permits['conf']['edit']) + count($permits['conf']['view'])) > 0);
        $this->edit = (count($permits['conf']['edit']) > 0);
    }

    public function configMenu() {
        $this->load->model('Values_model');
        if (!$this->access) {
            redirect('inicio');
        }
        $permits = $this->session->userdata();
        $result = defaultResult($permits, $this->Values_model);
        $this->load->view('menu-configurar', $result);
    }
}