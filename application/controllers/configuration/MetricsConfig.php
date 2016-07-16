<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: farodrig
 * Date: 16-07-16
 * Time: 15:29
 */
class MetricsConfig extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        if (is_null($this->session->rut))
            redirect('salir');
        $permits = $this->session->userdata();
        $this->access = ((count($permits['conf']['edit']) + count($permits['conf']['view'])) > 0);
        $this->edit = (count($permits['conf']['edit']) > 0);
    }

    public function metricsConfig() {
        $permits = $this->session->userdata();
        if (!$this->access) {
            redirect('inicio');
        }

        $success = $this->session->flashdata('success');
        if (is_null($success)) {
            $success = 2;
        }

        $this->load->model('Organization_model');
        $this->load->model('Dashboard_model');
        $this->load->model('Metrics_model');
        $metrics = $this->Metrics_model->getAllMetrics();
        $result = array(
            'departments' => getAllOrgsByDpto($this->Organization_model),
            'metrics'	=> $metrics,
            'success'   => $success
        );
        $this->load->view('configurar-metricas', array_merge($result, defaultResult($permits, $this->Dashboard_model)));
    }

    public function addMetric(){
        if (!$this->edit) {
            redirect('inicio');
        }

        //carga de elementos
        $this->load->library('form_validation');

        //Validación de inputs
        $this->form_validation->set_rules('name', 'Nombre Métrica', 'required|alphaSpace');
        $this->form_validation->set_rules('y_unit', 'Unidad Y', 'required|alphaSpace');
        $this->form_validation->set_rules('category', 'Categoria', 'required|numeric');
        $this->form_validation->set_rules('y_name', 'Nombre Y', 'required|alphaSpace');
        $this->form_validation->set_rules('x_name', 'Nombre X', 'alphaSpace');
        $this->form_validation->set_rules('x_unit', 'Unidad X', 'alphaSpace');
        $this->form_validation->set_rules('id_insert', 'Id', 'required|numeric');

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('success', 0);
            redirect('config/metricas');
        }

        //Carga de los modelos a usar
        $this->load->model('Metrics_model');
        $this->load->model('Metorg_model');
        $this->load->model('Unit_model');

        //creación de unidad para Y
        $unit_data = array('name' => $this->input->post('y_unit'));
        $y_unit = $this->Unit_model->get_or_create($unit_data);
        $unit_data['name'] = $this->input->post('x_unit');
        $x_unit = $this->Unit_model->get_or_create($unit_data);
        if ($y_unit===false || $x_unit===false || $y_unit<0 || $x_unit<0){
            $this->session->set_flashdata('success', 0);
        }

        //Creación de métrica
        $Metricdata = array(
            'category' => $this->input->post('category'), //esto es 1 si es productividad y 2 si es finanzas. Tienes que agregar esos dos valores en la base de datos en la tabla catergory
            'name' => $this->input->post('name'),
            'y_unit' => $y_unit, //-> Busca la Unidad, si no existe la crea y entrega el id, si existe, entrega el id.
            'y_name' => $this->input->post('y_name'), //Nombre que tendrá la métrica
            'x_unit' => $x_unit, //-> Busca la Unidad, si no existe la crea y entrega el id, si existe, entrega el id.
            'x_name' => $this->input->post('x_name'), //Nombre que tendrá la métrica
        );
        $metric = $this->Metrics_model->get_or_create($Metricdata);
        if (!$metric){
            $this->session->set_flashdata('success', 0);
            redirect('config/metricas');
        }
        //Creación de link entre métrica y organización
        $metorg = array(
            'org'    => $this->input->post('id_insert'),//id de la unidad o area a la que se le quiere ingresar la metrica
            'metric' => $metric,
        );

        if ($this->Metorg_model->addMetOrg($metorg)) {
            $this->session->set_flashdata('success', 1);
        } else {
            $this->session->set_flashdata('success', 0);
        }
        redirect('config/metricas');
    }

    public function delModMetric() {
        if (!$this->edit) {
            redirect('inicio');
        }

        $this->load->model('Metorg_model');
        $this->load->model('Metrics_model');
        $this->load->library('form_validation');

        //Modifica valor de la métrica
        if ($this->input->post('modificar')) {
            $this->form_validation->set_rules('nameMetric', 'Nombre Métricas', 'required|alphaSpace');
            $this->form_validation->set_rules('unidad_y', 'Unidad Y', 'required|alphaSpace');
            $this->form_validation->set_rules('tipo', 'Type', 'required|numeric');
            $this->form_validation->set_rules('metrica_y', 'Metrica Y', 'required|alphaSpace');
            $this->form_validation->set_rules('id', 'Id', 'required|numeric');
            $this->form_validation->set_rules('unidad_x', 'Unidad X', 'alphaSpace');
            $this->form_validation->set_rules('metrica_x', 'Metrica X', 'alphaSpace');

            if (!$this->form_validation->run()) {
                $this->session->set_flashdata('success', 0);
                redirect('config/metricas');
            }

            $data = array(
                'metorg'     => $this->input->post('id'),
                'name' => $this->input->post('nameMetric'),
                'y_name'  => ucwords($this->input->post('metrica_y')),
                'category'      => $this->input->post('tipo'),
                'y_unit' => ucwords($this->input->post('unidad_y')),
                'x_name'  => ucwords($this->input->post('metrica_x')),
                'x_unit' => ucwords($this->input->post('unidad_x'))
            );
            if ($this->Metrics_model->updateMetric($data)) {
                $this->session->set_flashdata('success', 1);
            } else {
                $this->session->set_flashdata('success', 0);
            }
        }
        //Elimina MetOrg
        else {
            $this->form_validation->set_rules('id2', 'Id', 'required|numeric');

            if (!$this->form_validation->run()) {
                $this->session->set_flashdata('success', 0);
                redirect('config/metricas');
            }

            $data = array('id' => $this->input->post('id2'));
            if ($this->Metorg_model->delMetOrg($data)) {
                $this->session->set_flashdata('success', 1);
            } else {
                $this->session->set_flashdata('success', 0);
            }
        }
        redirect('config/metricas');
    }
}