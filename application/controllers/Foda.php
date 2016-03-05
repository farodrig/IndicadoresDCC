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
        $org = $this->Foda_model->getFoda($data);
        $data['org'] = $data['org'][0];
        $data['year'] = $data['year'][0];
        if(count($org)==1){
            $data['id'] = $org[0]->id;
            $success = $this->Foda_model->modifyFoda($data);
        }
        else{
            $success = $this->Foda_model->addFoda($data);
        }
        if ($success && $this->input->post('types')){
            if(count($org)!=1){
                $data['org'] = [$data['org']];
                $data['year'] = [$data['year']];
                $org = $this->Foda_model->getFoda($data);
            }
            $org = $org[0];
            $done = 0;
            for($i = 0; $i<count($this->input->post('types')); $i++){
                $data = array('foda' => $org->id,
                              'priority' => $this->input->post('priorities')[$i],
                              'type' => $this->input->post('types')[$i],
                              'description' => $this->input->post('descriptions')[$i],
                              'comment' => $this->input->post('comments')[$i]);
                if($this->Foda_model->addItem($data))
                    $done++;
            }
            if (count($this->input->post('types')) != $done)
                $success = false;
        }
        $this->session->set_flashdata('success', $success);
        redirect('foda/config');
    }
}
