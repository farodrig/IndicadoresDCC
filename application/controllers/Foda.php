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

    function fodaIndex(){
        $permits = $this->session->userdata();
        if (!$permits['director']) {
            redirect('inicio');
        }

        $fodas = $this->Foda_model->getFoda(['order'=>[['org', 'ASC'], ['year', 'ASC']]]);
        $fodasByOrg = array();
        $index = -1;
        foreach ($fodas as $foda){
            if ($index!=intval($foda->org)){
                $index = $foda->org;
                $fodasByOrg[$index] = array();
            }
            $fodasByOrg[$index][$foda->year] = $foda;
        }
        $this->load->view('foda',
            array('title'  => 'Visualización de FODAs',
                'role'        => $permits['title'],
                'fodas'       => $fodasByOrg,
                'priorities'  => $this->Foda_model->getAllPriority(),
                'types'       => $this->Foda_model->getAllType(),
                'validate'    => validation($permits, $this->Dashboard_model),
                'departments' => getAllOrgsByDpto($this->Organization_model)//Notar que funcion esta en helpers
            ));
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
            $fodasByOrg[$index][$foda->year] = $foda;
        }
        $this->load->view('configurar-foda',
            array('title'  => 'Configuración de Foda',
                'role'        => $permits['title'],
                'success'     => $val,
                'fodas'       => $fodasByOrg,
                'priorities'  => $this->Foda_model->getAllPriority(),
                'types'       => $this->Foda_model->getAllType(),
                'validate'    => validation($permits, $this->Dashboard_model),
                'departments' => getAllOrgsByDpto($this->Organization_model)//Notar que funcion esta en helpers
            ));
    }

    function addFodaItem() {
        $permits = $this->session->userdata();
        if (!$permits['director']) {
            $this->session->set_flashdata('success', 0);
            redirect('foda');
        }

        $this->form_validation->set_rules('org', 'Organización', 'numeric|required|greater_than_equal_to[0]');
        $this->form_validation->set_rules('year', 'Año', 'numeric|required');
        $this->form_validation->set_rules('fodaComment', 'Comentario', 'trim|alpha_numeric_spaces');

        if ($this->input->post('types')){
            $this->form_validation->set_rules('types[]', 'Tipos', 'numeric|required|greater_than_equal_to[0]');
            $this->form_validation->set_rules('priorities[]', 'Prioridades', 'numeric|required|greater_than_equal_to[0]');
            $this->form_validation->set_rules('descriptions[]', 'Descripción', 'trim|alpha_numeric_spaces');
            $this->form_validation->set_rules('comments[]', 'Comentarios', 'trim|alpha_numeric_spaces');
        }
        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('success', 0);
            redirect('foda');
        }
        $data = array('org' => [$this->input->post('org')],
                      'year' => [$this->input->post('year')],
                      'comment' => $this->input->post('fodaComment'));
        $foda = $this->Foda_model->getFoda($data);
        $data['org'] = $data['org'][0];
        $data['year'] = $data['year'][0];
        if(count($foda)==1){
            $data['id'] = $foda[0]->id;
            $success = $this->Foda_model->modifyFoda($data);
        }
        else{
            $success = $this->Foda_model->addFoda($data);
        }
        if ($success && $this->input->post('types')){
            if(count($foda)!=1){
                $data['org'] = [$data['org']];
                $data['year'] = [$data['year']];
                $foda = $this->Foda_model->getFoda($data);
            }
            $foda = $foda[0];
            $done = true;
            for($i = 0; $i<count($this->input->post('types')); $i++){
                $data = array('foda' => $foda->id,
                              'priority' => $this->input->post('priorities')[$i],
                              'type' => $this->input->post('types')[$i],
                              'description' => $this->input->post('descriptions')[$i],
                              'comment' => $this->input->post('comments')[$i]);
                if ($this->input->post('items')[$i]!=""){
                    $data['id'] = $this->input->post('items')[$i];
                    $done = $done && $this->Foda_model->modifyItem($data);
                }
                else{
                    $done = $done && $this->Foda_model->addItem($data);
                }
            }
            $success = $done;
        }
        $this->session->set_flashdata('success', $success);
        redirect('foda/config');
    }

    function getFodaItems(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $permits = $this->session->userdata();
        if (!$permits['director']) {
            return;
        }

        $result = array();
        $data = array('org' => [$this->input->post('id')],
                      'year' => [$this->input->post('year')]);
        $result['foda'] = $this->Foda_model->getFoda($data)[0];
        $data = array('foda' => [$result['foda']->id], 'order'=>[['type', 'ASC'], ['priority', 'ASC']]);
        $items = $this->Foda_model->getItem($data);
        $result['items'] = $items;
        $result['itemsByType'] = array();
        $result['itemsByPriority'] = array();
        foreach ($items as $item){
            if (!array_key_exists($item->type-1, $result['itemsByType'])){
                $result['itemsByType'][$item->type-1] = array();
            }
            array_push($result['itemsByType'][$item->type-1], $item);
            if (!array_key_exists($item->priority-1, $result['itemsByPriority'])){
                $result['itemsByPriority'][$item->priority-1] = array();
            }
            array_push($result['itemsByPriority'][$item->priority-1], $item);
        }
        echo json_encode($result);
    }

    function deleteItems(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $permits = $this->session->userdata();
        if (!$permits['director']) {
            return;
        }
        $this->form_validation->set_rules('items[]', 'Elementos', 'numeric|required|greater_than_equal_to[0]');
        if (!$this->form_validation->run()) {
            $result['success'] = false;
            echo json_encode($result);
            return;
        }
        $success = true;
        foreach($this->input->post('items') as $item){
            $success= $success && $this->Foda_model->deleteItem(array('id'=>$item));
        }
        $result['success'] = $success;
        echo json_encode($result);
    }
}