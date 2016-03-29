<?php
class Foda extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Dashboard_model');
        $this->load->model('Foda_model');
        $this->load->model('Strategy_model');
        $this->load->model('Organization_model');
        $this->load->library('form_validation');
        $this->load->library('session');
        if (is_null($this->session->rut))
            redirect('salir');
    }

    function fodaIndex(){
        $permits = $this->session->userdata();
        if (!$permits['admin']) {
            redirect('inicio');
        }
        $this->load->model('User_model');
        $orgs = null;

        $fodas = $this->Foda_model->getFoda(['order'=>[['org', 'ASC'], ['year', 'ASC']]]);
        $fodasByOrg = array();
        $index = -1;
        $years = [];
        $items = [];
        foreach ($fodas as $foda){
            if ($index!=intval($foda->org)){
                $index = $foda->org;
                $fodasByOrg[$index] = array();
            }
            $data = array('foda' => [$foda->id], 'order'=>[['type', 'ASC'], ['priority', 'ASC']]);
            $items[$foda->org][$foda->year] = $this->Foda_model->getItem($data);
            foreach ($items[$foda->org][$foda->year] as $aux_item){
                $aux_item->goals = [];
                foreach ($this->Strategy_model->getGoalItem(array('item'=>[$aux_item->id])) as $aux_goal){
                    $aux_item->goals[] = $aux_goal->goal;
                }
            }
            $years[] = $foda->year;
            $fodasByOrg[$index][$foda->year] = $foda;
        }
        $status = [];
        $aux_status = $this->Strategy_model->getCompletitionStatus(array());
        foreach ($aux_status as $stat){
            $status[$stat->id] = $stat->status;
        }
        $users = [];
        $aux_users = $this->User_model->getAllUsers();
        foreach ($aux_users as $user){
            $users[$user->id] = $user;
        }
        $strategies = [];
        $goals = [];
        $actions = [];
        $aux_stra = $this->Strategy_model->getStrategicPlan(array());
        foreach ($aux_stra as $strategy){
            $strategy->status = $status[$strategy->status];
            $strategy->deadline = date("d-m-Y", strtotime($strategy->deadline));
            $collaborators = $this->Strategy_model->getAllCollaborators($strategy->id);
            $strategies[$strategy->org][$strategy->year]['collaborators'] = [];
            foreach ($collaborators as $collaborator){
                $strategies[$strategy->org][$strategy->year]['collaborators'][$collaborator->user]['name'] = $users[$collaborator->user]->name;
                $strategies[$strategy->org][$strategy->year]['collaborators'][$collaborator->user]['description'] = $collaborator->description;
            }
            $strategies[$strategy->org][$strategy->year]['strategy'] = $strategy;
            $aux_goals = $this->Strategy_model->getGoal(array('strategy'=>[$strategy->id]));
            foreach ($aux_goals as $goal){
                $goal->status = $status[$goal->status];
                $goal->deadline = date("d-m-Y", strtotime($goal->deadline ));
                $goal->timestamp = date("d-m-Y H:i:s", strtotime($goal->deadline ));
                $goals[$strategy->org][$strategy->year][$goal->id] = $goal;
                $goals[$strategy->org][$strategy->year][$goal->id]->items = [];
                foreach ($this->Strategy_model->getGoalItem(array('goal'=>[$goal->id])) as $aux_item){
                    $goals[$strategy->org][$strategy->year][$goal->id]->items[] = $aux_item->item;
                }
                $strategies[$strategy->org][$strategy->year]['goals'][$goal->id] = $goal;
                $aux_actions = $this->Strategy_model->getAction(array('goal'=>[$goal->id]));
                $actions[$goal->id] = [];
                foreach ($aux_actions as $action){
                    $action->status = $status[$action->status];
                    $actions[$goal->id][$action->id] = $action;
                }
            }
        }
        $this->load->view('foda',
            array('title'  => 'Visualización de FODAs',
                'strategies'  => $strategies,
                'goals'       => $goals,
                'actions'     => $actions,
                'users'       => $users,
                'status'      => $status,
                'role'        => $permits['title'],
                'fodas'       => $fodasByOrg,
                'items'       => $items,
                'years'       => array_unique($years),
                'priorities'  => $this->Foda_model->getAllPriority(),
                'types'       => $this->Foda_model->getAllType(),
                'success'     => $this->session->flashdata('success') == null ? 2 : $this->session->flashdata('success'),
                'validate'    => validation($permits, $this->Dashboard_model),
                'departments' => $this->Organization_model->getTree($orgs)//Notar que funcion esta en helpers
            )
        );
    }

    function addFodaItem() {
        //Revisión de permisos
        $permits = $this->session->userdata();
        if (!$permits['admin']) {
            $this->session->set_flashdata('success', 0);
            redirect('foda');
        }

        //Validación de entradas
        $this->form_validation->set_rules('org', 'Organización', 'numeric|required|greater_than_equal_to[0]');
        $this->form_validation->set_rules('year', 'Año', 'numeric|required');
        $this->form_validation->set_rules('fodaComment', 'Comentario', 'trim|alphaNumericSpace');

        if ($this->input->post('types')){
            $this->form_validation->set_rules('types[]', 'Tipos', 'numeric|required|greater_than_equal_to[0]');
            $this->form_validation->set_rules('priorities[]', 'Prioridades', 'numeric|required|greater_than_equal_to[0]');
            $this->form_validation->set_rules('descriptions[]', 'Descripción', 'trim|alphaNumericSpace');
            $this->form_validation->set_rules('titles[]', 'Titulo', 'trim|required|alphaNumericSpace');
        }
        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('success', 0);
            redirect('foda');
        }
        $verification = false;
        if($permits['admin'] || $permits['encargado_finanzas'])
            $verification = true;

        $data = array('org' => [$this->input->post('org')],
                      'year' => [$this->input->post('year')],
                      'comment' => $this->input->post('fodaComment'),
                      'validated' => $verification);
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
                              'title' => $this->input->post('titles')[$i]);
                if ($this->input->post('ids')[$i]!=""){
                    $data['id'] = $this->input->post('ids')[$i];
                    $done = $done && $this->Foda_model->modifyItem($data);
                }
                else{
                    $done = $done && $this->Foda_model->addItem($data);
                }
            }
            $success = $done;
        }
        $this->session->set_flashdata('success', $success);
        redirect('foda');
    }

    //To Delete
    function getFodaItems(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $permits = $this->session->userdata();
        if (!$permits['admin']) {
            return;
        }

        $result = array();
        $data = array('org' => [$this->input->post('id')],
                      'year' => [$this->input->post('year')]);
        $result['foda'] = $this->Foda_model->getFoda($data)[0];
        $data = array('foda' => [$result['foda']->id], 'order'=>[['type', 'ASC'], ['priority', 'ASC']]);
        $items = $this->Foda_model->getItem($data);
        $result['items'] = $items;
        echo json_encode($result);
    }

    //To Delete
    function deleteItems(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $permits = $this->session->userdata();
        if (!$permits['admin']) {
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