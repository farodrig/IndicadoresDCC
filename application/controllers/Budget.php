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
        if (!$permits['admin'] && !count($permits['encargado_finanzas'])  &&  !count($permits['asistente_finanzas']))
            redirect('inicio');
        $data = $this->budgetData($permits);
        $aux_org = $data[0];
        $valid_data = $data[1];
        $no_valid_data = $data[2];
        $years = $data[3];
        $result =  array('title'  => 'Presupuesto',
            'years'       => $years,
            'orgs'        => $aux_org,
            'valid_data'  => $valid_data,
            'no_valid_data'  => $no_valid_data,
            'departments' => $this->Organization_model->getTree($aux_org)//Notar que funcion esta en helpers
        );
        $this->load->view('budget', array_merge($result, defaultResult($permits, $this->Dashboard_model)));
    }

    function modify(){
        if (!$this->input->is_ajax_request()) {
            echo json_encode(array('success'=>0));
            return;
        }
        $org =  $this->input->post("org");
        $permits = $this->session->userdata();
        if (!$permits['admin'] && !in_array($org, $permits['encargado_finanzas'])  && !in_array($org, $permits['asistente_finanzas'])) {
            echo json_encode(array('success'=>0));
            return;
        }

        $this->form_validation->set_rules('year', 'Año', 'numeric|required|greater_than_equal_to[0]');
        $this->form_validation->set_rules('value', 'Valor', 'numeric|required');
        $this->form_validation->set_rules('expected', 'Esperado', 'numeric|required');
        $this->form_validation->set_rules('target', 'Meta', 'numeric|required');

        if (!$this->form_validation->run()) {
            echo json_encode(array('success'=>0));
            return;
        }
        $this->load->library('Dashboard_library');

        $validation = 0;
        $order = "ASC";
        if($permits['admin'] || in_array($org, $permits['encargado_finanzas'])) {
            $validation = 1;
            $order = "DESC";
        }
        $year =  $this->input->post("year");
        $value =  $this->input->post("value");
        $expected =  $this->input->post("expected");
        $target =  $this->input->post("target");
        $org = $this->Organization_model->getById($org);
        $oldVal = $this->Dashboard_model->getBudgetMeasure($org->getId(), $year, $order);
        $success = $this->Dashboard_model->updateCreateBudgetValue($org->getId(), $year, $value, $expected, $target, $validation);
        $currentVal = $this->Dashboard_model->getBudgetMeasure($org->getId(), $year, $order);
        $currentOrg = $org;
        $parent = $this->Organization_model->getById($org->getParent());
        while($currentOrg->getId()!=$parent->getId()){
            if(!$oldVal){
                $oldVal = (object) [
                    'value' => 0,
                    'expected' => 0,
                    'target' => 0,
                    'state' => 1
                ];
            }
            elseif($oldVal->state==0){
                $oldVal->value = $oldVal->p_v;
                $oldVal->expected = $oldVal->p_e;
                $oldVal->target = $oldVal->p_t;
            }

            $parentVal = $this->Dashboard_model->getBudgetMeasure($parent->getId(), $year, $order);
            if(!$parentVal){
                $parentVal = (object) [
                    'value' => 0,
                    'expected' => 0,
                    'target' => 0,
                    'state' => 1
                ];
            }
            elseif ($parentVal->state==0){
                $parentVal->value = $parentVal->p_v;
                $parentVal->expected = $parentVal->p_e;
                $parentVal->target = $parentVal->p_t;
            }

            if(!$validation){
                $currentVal->value = $currentVal->p_v;
                $currentVal->expected = $currentVal->p_e;
                $currentVal->target = $currentVal->p_t;
            }

            $newVal = $parentVal->value + $currentVal->value - $oldVal->value;
            $newExpected = $parentVal->expected + $currentVal->expected - $oldVal->expected;
            $newTarget = $parentVal->target + $currentVal->target - $oldVal->target;
            $success = $success && $this->Dashboard_model->updateCreateBudgetValue($parent->getId(), $year, $newVal, $newExpected, $newTarget, $validation);
            $currentOrg = $parent;
            $parent = $this->Organization_model->getById($parent->getParent());
            $oldVal = $parentVal;
            $currentVal = $this->Dashboard_model->getBudgetMeasure($currentOrg->getId(), $year, $order);
        }
        $result['success'] = $success;
        if($success){
            $data = $this->budgetData($permits);
            $result['valid_data'] = $data[1];
            $result['no_valid_data'] = $data[2];
        }
        echo json_encode($result);
    }

    public function validate(){
        if (!$this->input->is_ajax_request()) {
            echo json_encode(array('success'=>0));
            return;
        }
        //Validación de Entradas
        $this->form_validation->set_rules('org', 'Organización', 'numeric|required');
        $this->form_validation->set_rules('year', 'Año', 'numeric|required|greater_than_equal_to[1950]');
        if (!$this->form_validation->run()) {
            echo json_encode(array('success'=>0));
            return;
        }
        $org =  $this->input->post("org");
        $year =  $this->input->post("year");
        $permits = $this->session->userdata();
        //Validación de Permisos
        if (!$permits['admin'] && !in_array($org, $permits['encargado_finanzas'])) {
            echo json_encode(array('success'=>0));
            return;
        }
        $data = $this->budgetData($permits);
        $done = true;
        if($org==-1) {
            foreach ($data[2] as $org_id => $dataByYear) {
                if (array_key_exists($year, $dataByYear)) {
                    $value = $dataByYear[$year];
                    if ($value->state != 0)
                        continue;
                    $done = $done && $this->Dashboard_model->validateData($value->id);
                }
            }
        }
        else{
            $organization = $this->Organization_model->getByID($org);
            if(!$organization){
                echo json_encode(array('success'=>0));
                return;
            }
            while ($organization->getParent()!=$organization->getId()){
                if (array_key_exists($year, $data[2][$organization->getId()])) {
                    $value = $data[2][$organization->getId()][$year];
                    if ($value->state == 0)
                        $done = $done && $this->Dashboard_model->validateData($value->id);
                }
                $organization = $this->Organization_model->getByID($organization->getParent());
            }
        }
        $result['success'] = $done;
        if($done){
            $data = $this->budgetData($permits);
            $result['valid_data'] = $data[1];
            $result['no_valid_data'] = $data[2];
        }
        echo json_encode($result);
    }

    private function budgetData($permits){
        if ($permits['admin'])
            $orgs = $this->Organization_model->getAllOrgsIds();
        else {
            $orgs = [];
            $aux = array_merge($permits['encargado_finanzas'], $permits['asistente_finanzas']);
            foreach($aux as $org){
                if($org<0)
                    continue;
                $orgs[] = $this->Organization_model->getByID($org);
            }
        }
        $org_ids = [];
        $valid_data = [];
        $no_valid_data = [];
        $years = [];
        foreach($orgs as $org){
            $org_ids[] = $org->getId();
            $datos = $this->Dashboard_model->getBudgetMeasures($org->getId());
            if(!$datos)
                continue;
            foreach ($datos as $dato){
                $years[] = $dato->year;
                if($dato->state==0){
                    $dato->value = is_null($dato->p_v) ? $dato->value : $dato->p_v;
                    $dato->expected = is_null($dato->p_e) ? $dato->expected : $dato->p_e;
                    $dato->target = is_null($dato->p_t) ? $dato->target : $dato->p_t;
                    $no_valid_data[$org->getId()][$dato->year] = $dato;
                }
                else{
                    $valid_data[$org->getId()][$dato->year] = $dato;
                    if(!array_key_exists($org->getId(), $no_valid_data) || !array_key_exists($dato->year, $no_valid_data[$org->getId()]))
                        $no_valid_data[$org->getId()][$dato->year] = $dato;
                }
            }
        }
        return [$org_ids, $valid_data, $no_valid_data, array_values(array_unique($years))];
    }
}