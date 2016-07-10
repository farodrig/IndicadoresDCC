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
        if (!count($permits['valorF']['view']) && !count($permits['valorF']['edit']) && !count($permits['valorF']['validate']) && !count($permits['metaF']['view']) && !count($permits['metaF']['validate']) && !count($permits['metaF']['validate']))
            redirect('inicio');
        $data = $this->budgetData($permits);
        $aux_org = $data[0];
        $valid_data = $data[1];
        $no_valid_data = $data[2];
        $years = $data[3];
        $result =  array('title'  => 'Presupuesto',
            'editable'    => array_unique(array_merge($permits['valorF']['edit'], $permits['metaF']['edit'])),
            'years'       => $years,
            'permits'     => $data[4],
            'orgs'        => $aux_org,
            'valid_data'  => $valid_data,
            'no_valid_data'  => $no_valid_data,
            'valAll'      => count(array_diff($aux_org, array_unique(array_merge($permits['valorF']['validate'], $permits['metaF']['validate']))))==0,
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
        if (!in_array($org, $permits['valorF']['edit'])  && !in_array($org, $permits['metaF']['edit'])) {
            echo json_encode(array('success'=>0));
            return;
        }

        $this->form_validation->set_rules('year', 'Año', 'numeric|required|greater_than_equal_to[0]');
        $this->form_validation->set_rules('value', 'Valor', 'numeric');
        $this->form_validation->set_rules('expected', 'Esperado', 'numeric');
        $this->form_validation->set_rules('target', 'Meta', 'numeric');

        if (!$this->form_validation->run()) {
            echo json_encode(array('success'=>0));
            return;
        }
        $this->load->library('Dashboard_library');
        
        $value =  $this->input->post("value");
        $expected =  $this->input->post("expected");
        $target =  $this->input->post("target");
        if(!in_array($org, $permits['valorF']['edit'])){
            $value = null;
        }
        if(!in_array($org, $permits['metaF']['edit'])){
            $target = null;
            $expected = null;
        }
        if(is_null($value) && is_null($target) && is_null($expected)){
            echo json_encode(array('success'=>0));
            return;
        }
        
        $validValue = in_array($org, $permits['valorF']['validate']);
        $validMeta = in_array($org, $permits['metaF']['validate']);
        $year =  $this->input->post("year");
        $org = $this->Organization_model->getById($org);
        $oldValidVal = $this->Dashboard_model->getBudgetMeasure($org->getId(), $year, "DESC");
        $oldVal = $this->Dashboard_model->getBudgetMeasure($org->getId(), $year, "ASC");
        $success = $this->Dashboard_model->updateCreateBudgetValue($org->getId(), $year, $value, $expected, $target, $validValue, $validMeta);
        $currentValidVal = $this->Dashboard_model->getBudgetMeasure($org->getId(), $year, "DESC");
        $currentVal = $this->Dashboard_model->getBudgetMeasure($org->getId(), $year, "ASC");
        $currentOrg = $org;
        $parent = $this->Organization_model->getById($org->getParent());
        while($currentOrg->getId()!=$parent->getId()){
            if (!$oldVal) {
                $oldVal = (object)[
                    'value' => 0,
                    'expected' => 0,
                    'target' => 0
                ];
            }
            
            $parentVal = $this->Dashboard_model->getBudgetMeasure($parent->getId(), $year, "ASC");
            $parentValidVal = $this->Dashboard_model->getBudgetMeasure($parent->getId(), $year, "DESC");
            if(!$parentVal){
                $parentVal = (object) [
                    'value' => 0,
                    'expected' => 0,
                    'target' => 0
                ];
            }
            
            if ($validValue){
                if ($oldValidVal)
                    $oldVal->value = (is_null($oldValidVal->value) ? $oldVal->value : $oldValidVal->value);

                if ($parentValidVal)
                    $parentVal->value = (is_null($parentValidVal->value) ? $parentVal->value : $parentValidVal->value);

                if ($currentValidVal)
                    $currentVal->value = (is_null($currentValidVal->value) ? $currentVal->value : $currentValidVal->value);

            }
            else{
                $oldVal->value = (!property_exists($oldVal, 'p_v') || is_null($oldVal->p_v) ? $oldVal->value : $oldVal->p_v);
                $parentVal->value = (!property_exists($parentVal, 'p_v') || is_null($parentVal->p_v) ? $parentVal->value : $parentVal->p_v);
                $currentVal->value = (is_null($currentVal->p_v) ? $currentVal->value : $currentVal->p_v);
            }
            
            if ($validMeta){
                if ($oldValidVal){
                    $oldVal->expected = (is_null($oldValidVal->expected) ? $oldVal->expected : $oldValidVal->expected);
                    $oldVal->target = (is_null($oldValidVal->target) ? $oldVal->target : $oldValidVal->target);
                }
                if ($parentValidVal){
                    $parentVal->expected = (is_null($parentValidVal->expected) ? $parentVal->expected : $parentValidVal->expected);
                    $parentVal->target = (is_null($parentValidVal->target) ? $parentVal->target : $parentValidVal->target);
                }
                if ($currentValidVal){
                    $currentVal->expected = (is_null($currentValidVal->expected) ? $currentVal->expected : $currentValidVal->expected);
                    $currentVal->target = (is_null($currentValidVal->target) ? $currentVal->target : $currentValidVal->target);
                }
            }
            else{
                $oldVal->expected = (!property_exists($oldVal, 'p_e') || is_null($oldVal->p_e) ? $oldVal->expected : $oldVal->p_e);
                $oldVal->target = (!property_exists($oldVal, 'p_t') || is_null($oldVal->p_t) ? $oldVal->target : $oldVal->p_t);
                $parentVal->expected = (!property_exists($parentVal, 'p_e') || is_null($parentVal->p_e) ? $parentVal->expected : $parentVal->p_e);
                $parentVal->target = (!property_exists($parentVal, 'p_t') || is_null($parentVal->p_t) ? $parentVal->target : $parentVal->p_t);
                $currentVal->expected = (is_null($currentVal->p_e) ? $currentVal->expected : $currentVal->p_e);
                $currentVal->target = (is_null($currentVal->p_t) ? $currentVal->target : $currentVal->p_t);
            }

            $newVal = $parentVal->value + $currentVal->value - $oldVal->value;
            $newExpected = $parentVal->expected + $currentVal->expected - $oldVal->expected;
            $newTarget = $parentVal->target + $currentVal->target - $oldVal->target;

            $success = $success && $this->Dashboard_model->updateCreateBudgetValue($parent->getId(), $year, $newVal, $newExpected, $newTarget, $validValue, $validMeta);
            
            $currentOrg = $parent;
            $parent = $this->Organization_model->getById($parent->getParent());
            $oldVal = $parentVal;
            $oldValidVal = false;
            $currentVal = $this->Dashboard_model->getBudgetMeasure($currentOrg->getId(), $year, "ASC");
            $currentValidVal = $this->Dashboard_model->getBudgetMeasure($currentOrg->getId(), $year, "DESC");
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
        if ($org!=-1 && !in_array($org, $permits['valorF']['validate']) && !in_array($org, $permits['metaF']['validate']) ) {
            echo json_encode(array('success'=>0));
            return;
        }
        $data = $this->budgetData($permits);
        $done = true;
        if($org==-1) {
            $first = true;
            foreach ($data[2] as $org_id => $dataByYear) {
                if ($first) {
                    $valValue = in_array($org_id, $permits['valorF']['validate']);
                    $valMeta = in_array($org_id, $permits['metaF']['validate']);
                    $first = false;
                }
                if (array_key_exists($year, $dataByYear)) {
                    $value = $dataByYear[$year];
                    if ($value->state != 0)
                        continue;
                    $done = $done && $this->Dashboard_model->validateData($value->id, $valValue, $valMeta);
                }
            }
        }
        else{
            $organization = $this->Organization_model->getByID($org);
            if(!$organization){
                echo json_encode(array('success'=>0));
                return;
            }
            $valValue = in_array($org, $permits['valorF']['validate']);
            $valMeta = in_array($org, $permits['metaF']['validate']);
            if(!($valValue || $valMeta)){
                echo json_encode(array('success'=>0));
                return;
            }
            $first = true;
            $val = null;
            while ($done){
                if (array_key_exists($year, $data[2][$organization->getId()])) {
                    $value = $data[2][$organization->getId()][$year];
                    if ($first) {
                        $val = $this->Dashboard_model->getValue(['id' => [$value->id]]);
                        if (!count($val)){
                            $done = false;
                            break;
                        }
                        $val = $val[0];
                        $val->value = (is_null($val->value) ? 0 : $val->value);
                        $val->target = (is_null($val->target) ? 0 : $val->target);
                        $val->expected = (is_null($val->expected) ? 0 : $val->expected);
                        
                        $done = $done && $this->Dashboard_model->validateData($value->id, $valValue, $valMeta);
                        $first = false;                        
                    }
                    else if(!is_null($val)){
                        if ($this->notValidChildren($this->Organization_model->getAllChilds($organization->getId()), $year, $data[1], $data[2])!=1)
                            $done = $done && $this->Dashboard_model->validateBudgetValue($value->id, $valValue, $valMeta, $val);
                        else
                            $done = $done && $this->Dashboard_model->validateData($value->id, $valValue, $valMeta);
                    }
                    
                }
                if($organization->getParent() == $organization->getId())
                    break;
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
    
    private function notValidChildren($children, $year, $valid_data, $no_valid_data){
        $count = 0;
        foreach ($children as $child){
            if (!array_key_exists($child->getId(), $valid_data) || !array_key_exists($year, $valid_data[$child->getId()]) || !array_key_exists($child->getId(), $no_valid_data) || !array_key_exists($year, $no_valid_data[$child->getId()]))
                continue;
            if ($valid_data[$child->getId()][$year] != $no_valid_data[$child->getId()][$year])
                $count++;
        }
        return $count;
    }

    private function budgetData($permits){
        $orgs = [];
        $aux = array_unique(array_merge($permits['valorF']['view'], $permits['valorF']['edit'], $permits['valorF']['validate'], $permits['metaF']['view'], $permits['metaF']['edit'], $permits['metaF']['validate']));
        foreach($aux as $org){
            if($org<0)
                continue;
            $orgs[] = $this->Organization_model->getByID($org);
        }
        $org_ids = [];
        $valid_data = [];
        $no_valid_data = [];
        $years = [];
        $permit = [];
        foreach($orgs as $org){
            $permit[$org->getId()]['validateValue'] = in_array($org->getId(), $permits['valorF']['validate']);
            $permit[$org->getId()]['validateMeta'] = in_array($org->getId(), $permits['metaF']['validate']);
            $permit[$org->getId()]['value'] = in_array($org->getId(), $permits['valorF']['edit']);
            $permit[$org->getId()]['meta'] = in_array($org->getId(), $permits['metaF']['edit']);
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
        return [$org_ids, $valid_data, $no_valid_data, array_values(array_unique($years)), $permit];
    }
}