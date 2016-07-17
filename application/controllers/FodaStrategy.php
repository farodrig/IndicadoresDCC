<?php
class FodaStrategy extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Dashboard_model');
        $this->load->model('Values_model');
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
        $orgs = array_merge($permits['foda']['view'], $permits['foda']['edit'], $permits['foda']['validate']);

        if (!count($orgs)) {
            redirect('inicio');
        }
        $permit = [];
        foreach ($orgs as $org){
            $permit[$org]['edit'] = in_array($org, $permits['foda']['edit']);
            $permit[$org]['validate'] = in_array($org, $permits['foda']['validate']);
        }

        $fData = $this->getAllFodaData($permits['foda']['view'], $permits['foda']['edit'], $permits['foda']['validate']);
        $years = $fData['years'];
        
        $sData = $this->getAllStrategyData($permits['foda']['view'], $permits['foda']['edit'], $permits['foda']['validate']);
        $years = array_merge($years, $sData['years']);
        $result = array('title'     => 'Visualización de FODAs',
            'permits'     => $permit,
            'strategies'  => $sData['strategies'],
            'goals'       => $sData['goals'],
            'actions'     => $sData['actions'],
            'users'       => $sData['users'],
            'status'      => $sData['status'],
            'fodas'       => $fData['fodas'],
            'items'       => $fData['items'],
            'years'       => array_unique($years),
            'priorities'  => $this->Foda_model->getAllPriority(),
            'types'       => $this->Foda_model->getAllType(),
            'success'     => is_null($this->session->flashdata('success')) ? 2 : $this->session->flashdata('success'),
            'departments' => $this->Organization_model->getTree($orgs)
        );
        $this->load->view('foda', array_merge($result, defaultResult($permits, $this->Values_model)));
    }

    function modifyFoda(){
        if (!$this->input->is_ajax_request()) {
            echo json_encode(array('success'=>0));
            return;
        }
        
        //Validación de entradas
        $this->form_validation->set_rules('org', 'Organización', 'numeric|required|greater_than_equal_to[0]');
        $this->form_validation->set_rules('year', 'Año', 'numeric|required');
        $this->form_validation->set_rules('foda', 'Foda', 'numeric|required');
        $this->form_validation->set_rules('comment', 'Comentario', 'trim|alphaNumericSpaceSymbol');

        if (!$this->form_validation->run()) {
            echo json_encode(array('success'=>0));
            return;
        }

        //Revisión de permisos
        $permits = $this->session->userdata();
        if (!in_array($this->input->post('org'), $permits['foda']['edit'])) {
            echo json_encode(array('success'=>0));
            return;
        }

        $done = true;
        $validated = in_array($this->input->post('org'), $permits['foda']['validate']);

        $data = array('org' => $this->input->post('org'),
            'year' => $this->input->post('year'),
            'comment' => $this->input->post('comment'),
            'validated' => $validated
        );

        if ($this->input->post('foda')!=-1){
            $data['id'] = $this->input->post('foda');
            $done = $done && $this->Foda_model->modifyFoda($data);
        }
        else{
            $done = $done && ($this->Foda_model->addFoda($data) ? true : false);
        }

        $result['success'] = $done;
        $result = array_merge($result, $this->getAllStrategyData($permits['foda']['view'], $permits['foda']['edit'], $permits['foda']['validate']), $this->getAllFodaData($permits['foda']['view'], $permits['foda']['edit'], $permits['foda']['validate']));
        echo json_encode($result);
    }

    function modifyItem() {
        if (!$this->input->is_ajax_request()) {
            echo json_encode(array('success'=>0));
            return;
        }

        //Validación de entradas
        $this->form_validation->set_rules('org', 'Organización', 'numeric|required|greater_than_equal_to[0]');
        $this->form_validation->set_rules('year', 'Año', 'numeric|required');
        $this->form_validation->set_rules('item', 'Item', 'numeric|required');
        $this->form_validation->set_rules('type', 'Tipos', 'numeric|required|greater_than_equal_to[0]');
        $this->form_validation->set_rules('priority', 'Prioridades', 'numeric|required|greater_than_equal_to[0]');
        $this->form_validation->set_rules('description', 'Descripción', 'trim|alphaNumericSpaceSymbol');
        $this->form_validation->set_rules('title', 'Titulo', 'trim|required|alphaNumericSpaceSymbol');
        $this->form_validation->set_rules('goals[]', 'Objetivo', 'numeric|greater_than_equal_to[0]');

        if (!$this->form_validation->run()) {
            echo json_encode(array('success'=>0));
            return;
        }

        //Revisión de permisos
        $permits = $this->session->userdata();
        if (!in_array($this->input->post('org'), $permits['foda']['edit'])) {
            echo json_encode(array('success'=>0));
            return;
        }

        $validated = in_array($this->input->post('org'), $permits['foda']['validate']);

        $data = array('org' => [$this->input->post('org')],
                      'year' => [$this->input->post('year')]
        );
        $foda = $this->Foda_model->getFoda($data);
        $data['org'] = $data['org'][0];
        $data['year'] = $data['year'][0];
        $done = true;
        if(count($foda)!=1){
            $data['validated'] = $validated;
            $done = $this->Foda_model->addFoda($data);
            $data['org'] = [$data['org']];
            $data['year'] = [$data['year']];
            $foda = $this->Foda_model->getFoda($data);
        }
        $foda = $foda[0];
        $data = array('foda' => $foda->id,
                      'priority' => $this->input->post('priority'),
                      'type' => $this->input->post('type'),
                      'description' => $this->input->post('description'),
                      'title' => $this->input->post('title')
        );
        if ($this->input->post('item')!=-1){
            $item = $this->input->post('item');
            $data['id'] = $this->input->post('item');
            $done = $done && $this->Foda_model->modifyItem($data);
        }
        else{
            $item = $this->Foda_model->addItem($data);
            $done = $done && ($item ? true : false);
        }

        $data = [];
        if(!$validated && $foda->validated){
            $data['id'] = $foda->id;
            $data['validated'] = $validated;
            $done = $done && $this->Foda_model->modifyFoda($data);
        }
        $aux_goals = $this->Strategy_model->getGoalItem(['item'=>[$item]]);
        $goals = [];
        foreach ($aux_goals as $goal_item){
            $goals[] = $goal_item->goal;
        }
        foreach ($this->input->post('goals') as $goal){
            if(strcmp($goal,"")==0)
                continue;
            $key = array_search($goal, $goals);
            if($key !== false){
                unset($goals[$key]);
                continue;
            }
            $done = $done && $this->Strategy_model->addGoalItem($goal, $item);
        }
        foreach ($goals as $goal){
            $done = $done && $this->Strategy_model->deleteGoalItem($goal, $item);
        }
        $result['success'] = $done;
        $result = array_merge($result, $this->getAllStrategyData($permits['foda']['view'], $permits['foda']['edit'], $permits['foda']['validate']), $this->getAllFodaData($permits['foda']['view'], $permits['foda']['edit'], $permits['foda']['validate']));
        echo json_encode($result);
    }

    function modifyStrategy(){
        if (!$this->input->is_ajax_request()) {
            echo json_encode(array('success'=>0));
            return;
        }
        
        //Validación de entradas
        $this->form_validation->set_rules('org', 'Organización', 'numeric|required|greater_than_equal_to[0]');
        $this->form_validation->set_rules('year', 'Año', 'numeric|required');
        $this->form_validation->set_rules('strategy', 'Plan Estratégico', 'numeric|required');
        $this->form_validation->set_rules('deadline', 'Fecha Límite', 'date_validator');
        $this->form_validation->set_rules('comment', 'Comentario', 'trim|alphaNumericSpaceSymbol');
        $this->form_validation->set_rules('description', 'Descripción', 'trim|required|alphaNumericSpaceSymbol');
        $this->form_validation->set_rules('status', 'Estado', 'numeric|required|greater_than_equal_to[0]');

        if (!$this->form_validation->run()) {
            echo json_encode(array('success'=>0));
            return;
        }

        //Revisión de permisos
        $permits = $this->session->userdata();
        if (!in_array($this->input->post('org'), $permits['foda']['edit'])) {
            echo json_encode(array('success'=>0));
            return;
        }

        $validated = in_array($this->input->post('org'), $permits['foda']['validate']);

        $done = true;
        $data = array('org' => $this->input->post('org'),
                    'year' => $this->input->post('year'),
                    'status' => $this->input->post('status'),
                    'validated' => $validated,
                    'deadline' => date('Y-m-d', strtotime($this->input->post('deadline'))),
                    'description' => $this->input->post('description'),
                    'comment' => $this->input->post('comment')
        );

        if ($this->input->post('strategy')!=-1){
            $strategy = $this->input->post('strategy');
            $data['id'] = $strategy;
            $done = $done && $this->Strategy_model->modifyStrategicPlan($data);
        }
        else{
            $strategy = $this->Strategy_model->addStrategicPlan($data);
            $done = $done && ($strategy ? true : false);
        }

        $aux_users = $this->Strategy_model->getAllCollaborators($strategy);
        $users = [];
        foreach ($aux_users as $collaborator){
            $users[] = $collaborator->user;
        }
        foreach ($this->input->post('collaborators') as $user){
            if(strcmp($user,"")==0)
                continue;
            $key = array_search($user, $users);
            if($key !== false){
                unset($users[$key]);
                continue;
            }
            $done = $done && $this->Strategy_model->addCollaborator($strategy, $user);
        }

        foreach ($users as $user){
            $done = $done && $this->Strategy_model->deleteCollaborator($strategy, $user);
        }

        $result['success'] = $done;
        $result = array_merge($result, $this->getAllStrategyData($permits['foda']['view'], $permits['foda']['edit'], $permits['foda']['validate']), $this->getAllFodaData($permits['foda']['view'], $permits['foda']['edit'], $permits['foda']['validate']));
        echo json_encode($result);
    }

    function modifyGoal(){
        if (!$this->input->is_ajax_request()) {
            echo json_encode(array('success'=>0));
            return;
        }

        //Validación de entradas
        $this->form_validation->set_rules('org', 'Organización', 'numeric|required|greater_than_equal_to[0]');
        $this->form_validation->set_rules('year', 'Año', 'numeric|required');
        $this->form_validation->set_rules('goal', 'Objetivo', 'numeric|required');
        $this->form_validation->set_rules('deadline', 'Fecha Límite', 'date_validator');
        $this->form_validation->set_rules('comment', 'Comentario', 'trim|alphaNumericSpaceSymbol');
        $this->form_validation->set_rules('status', 'Estado', 'numeric|required|greater_than_equal_to[0]');
        $this->form_validation->set_rules('goalUser', 'Encargado', 'required');
        $this->form_validation->set_rules('description', 'Descripción', 'trim|alphaNumericSpaceSymbol');
        $this->form_validation->set_rules('title', 'Titulo', 'trim|required|alphaNumericSpaceSymbol');
        $this->form_validation->set_rules('items[]', 'Objetivo', 'numeric|greater_than_equal_to[0]');

        if (!$this->form_validation->run()) {
            echo json_encode(array('success'=>0));
            return;
        }

        //Revisión de permisos
        $permits = $this->session->userdata();
        if (!in_array($this->input->post('org'), $permits['foda']['edit'])) {
            echo json_encode(array('success'=>0));
            return;
        }

        $validated = in_array($this->input->post('org'), $permits['foda']['validate']);

        $data = array('org' => [$this->input->post('org')],
            'year' => [$this->input->post('year')]
        );
        $strategic = $this->Strategy_model->getStrategicPlan($data);
        $data['org'] = $data['org'][0];
        $data['year'] = $data['year'][0];
        $done = true;
        if(count($strategic)!=1){
            $data['validated'] = $validated;
            $done = $this->Strategy_model->addStrategicPlan($data);
            $data['org'] = [$data['org']];
            $data['year'] = [$data['year']];
            $strategic = $this->Strategy_model->getStrategicPlan($data);
        }
        $strategic = $strategic[0];
        $data = array('strategy' => $strategic->id,
            'status' => $this->input->post('status'),
            'userInCharge' => $this->input->post('goalUser'),
            'title' => $this->input->post('title'),
            'validated' => $validated,
            'timestamp' => date('Y-m-d H:i:s'),
            'deadline' => date('Y-m-d', strtotime($this->input->post('deadline'))),
            'description' => $this->input->post('description'),
            'comment' => $this->input->post('comment')
        );
        if ($this->input->post('goal')!=-1){
            $goal = $this->input->post('goal');
            $data['id'] = $goal;
            $done = $done && $this->Strategy_model->modifyGoal($data);
        }
        else{
            $goal = $this->Strategy_model->addGoal($data);
            $done = $done && ($goal ? true : false);
        }

        $data = [];
        if(!$validated && $strategic->validated){
            $data['id'] = $strategic->id;
            $data['validated'] = $validated;
            $done = $done && $this->Strategy_model->modifyStrategicPlan($data);
        }

        $aux_items = $this->Strategy_model->getGoalItem(['goal'=>[$goal]]);
        $items = [];
        foreach ($aux_items as $goal_item){
            $items[] = $goal_item->item;
        }
        foreach ($this->input->post('items') as $item){
            if(strcmp($item,"")==0)
                continue;
            $key = array_search($item, $items);
            if($key !== false){
                unset($items[$key]);
                continue;
            }
            $done = $done && $this->Strategy_model->addGoalItem($goal, $item);
        }

        foreach ($items as $item){
            $done = $done && $this->Strategy_model->deleteGoalItem($goal, $item);
        }

        $result['success'] = $done;
        $result = array_merge($result, $this->getAllStrategyData($permits['foda']['view'], $permits['foda']['edit'], $permits['foda']['validate']), $this->getAllFodaData($permits['foda']['view'], $permits['foda']['edit'], $permits['foda']['validate']));
        echo json_encode($result);
    }

    function modifyAction(){
        if (!$this->input->is_ajax_request()) {
            echo json_encode(array('success'=>0));
            return;
        }

        //Validación de entradas
        $this->form_validation->set_rules('org', 'Organización', 'numeric|required|greater_than_equal_to[0]');
        $this->form_validation->set_rules('goal', 'Objetivo', 'numeric|required|greater_than_equal_to[0]');
        $this->form_validation->set_rules('action', 'Acción', 'numeric|required');
        $this->form_validation->set_rules('current', 'Resultado Actual', 'trim|alphaNumericSpaceSymbol');
        $this->form_validation->set_rules('expected', 'Resultado Esperado', 'trim|required|alphaNumericSpaceSymbol');
        $this->form_validation->set_rules('status', 'Estado', 'numeric|required|greater_than_equal_to[0]');
        $this->form_validation->set_rules('actionUser', 'Encargado', 'required');
        $this->form_validation->set_rules('title', 'Titulo', 'trim|required|alphaNumericSpaceSymbol');

        if (!$this->form_validation->run()) {
            echo json_encode(array('success'=>0));
            return;
        }

        //Revisión de permisos
        $permits = $this->session->userdata();
        if (!in_array($this->input->post('org'), $permits['foda']['edit'])) {
            echo json_encode(array('success'=>0));
            return;
        }
        
        $done = true;
        $validated = in_array($this->input->post('org'), $permits['foda']['validate']);
        
        $data = array('goal' => $this->input->post('goal'),
                    'status' => $this->input->post('status'),
                    'userInCharge' => $this->input->post('actionUser'),
                    'title' => $this->input->post('title'),
                    'current_result' => $this->input->post('current'),
                    'expected_result' => $this->input->post('expected')
        );
        if ($this->input->post('action')!=-1){
            $data['id'] = $this->input->post('action');
            $done = $done && $this->Strategy_model->modifyAction($data);
        }
        else{
            $done = $done && ($this->Strategy_model->addAction($data) ? true : false);
        }

        $goal = $this->Strategy_model->getGoal(['id' => [$data['goal']]])[0];
        $data = [];
        if(!$validated && $goal->validated){
            $data['id'] = $goal->id;
            $data['validated'] = $validated;
            $done = $done && $this->Strategy_model->modifyGoal($data);
            $strategic = $this->Strategy_model->getStrategicPlan(array('id'=>[$goal->strategy]))[0];
            if($strategic->validated){
                $data['id'] = $strategic->id;
                $data['validated'] = $validated;
                $done = $done && $this->Strategy_model->modifyStrategicPlan($data);
            }
        }

        $result['success'] = $done;
        $result = array_merge($result, $this->getAllStrategyData($permits['foda']['view'], $permits['foda']['edit'], $permits['foda']['validate']), $this->getAllFodaData($permits['foda']['view'], $permits['foda']['edit'], $permits['foda']['validate']));
        echo json_encode($result);
    }

    function validate(){
        if (!$this->input->is_ajax_request()) {
            echo json_encode(array('success'=>0));
            return;
        }

        //Validación de entradas
        $this->form_validation->set_rules('org', 'Organización', 'numeric|required|greater_than_equal_to[0]');
        $this->form_validation->set_rules('type', 'Tipo de elemento', 'trim|required|alpha_dash');
        $this->form_validation->set_rules('id', 'ID', 'numeric|required|greater_than_equal_to[0]');

        if (!$this->form_validation->run()) {
            echo json_encode(array('success'=>0));
            return;
        }

        //Revisión de permisos
        $permits = $this->session->userdata();
        if (!in_array($this->input->post('org'), $permits['foda']['validate'])) {
            echo json_encode(array('success'=>0));
            return;
        }
        
        $type = $this->input->post('type');
        $id = $this->input->post('id');
        $data = ['id'=>$id, 'validated'=>1];
        $done = $this->validationByType($type, $data);
        if(strcmp($type, "strategy")==0){
            $goals = $this->Strategy_model->getGoal(['strategy'=>[$id]]);
            foreach ($goals as $goal){
                if(!$goal->validated){
                    $done = $done && $this->Strategy_model->modifyGoal(['id'=>$goal->id, 'validated'=>1]);
                }
            }
        }
        if(strcmp($type, "goal")==0){
            $goal = $this->Strategy_model->getGoal(['id'=>[$id]])[0];
            $goal = $this->Strategy_model->getGoal(['strategy'=>[$goal->strategy], 'limit'=>1, 'order'=>[['validated','ASC']]])[0];
            if($goal->validated){
                $data['id'] = $goal->strategy;
                $data['validated'] = $goal->validated;
                $done = $done && $this->Strategy_model->modifyStrategicPlan($data);
            }
        }
        $result['success'] = $done;
        $result = array_merge($result, $this->getAllStrategyData($permits['foda']['view'], $permits['foda']['edit'], $permits['foda']['validate']), $this->getAllFodaData($permits['foda']['view'], $permits['foda']['edit'], $permits['foda']['validate']));
        echo json_encode($result);
    }

    private function validationByType($type, $data){
        if(strcmp($type, "foda")==0)
            return $this->Foda_model->modifyFoda($data);
        elseif (strcmp($type, "strategy")==0)
            return $this->Strategy_model->modifyStrategicPlan($data);
        elseif (strcmp($type, "goal")==0)
            return $this->Strategy_model->modifyGoal($data);
    }

    function delete(){
        if (!$this->input->is_ajax_request()) {
            echo json_encode(array('success'=>0));
            return;
        }
        
        $this->form_validation->set_rules('org', 'Organización', 'numeric|required|greater_than_equal_to[0]');
        $this->form_validation->set_rules('type', 'Tipo de elemento', 'trim|required|alpha_dash');
        $this->form_validation->set_rules('id', 'ID', 'numeric|required|greater_than_equal_to[0]');
        if (!$this->form_validation->run()) {
            echo json_encode(array('success'=>0));
            return;
        }

        //Revisión de permisos
        $permits = $this->session->userdata();
        if (!in_array($this->input->post('org'), $permits['foda']['edit'])) {
            echo json_encode(array('success'=>0));
            return;
        }
        
        $type = $this->input->post('type');
        $id = $this->input->post('id');
        $result['success'] = $this->deleteElement($type, $id);
        $result = array_merge($result, $this->getAllStrategyData($permits['foda']['view'], $permits['foda']['edit'], $permits['foda']['validate']), $this->getAllFodaData($permits['foda']['view'], $permits['foda']['edit'], $permits['foda']['validate']));
        echo json_encode($result);
    }

    private function deleteElement($type, $id){
        $result = false;
        if(strcmp($type, "item")==0){
            $result = $this->Foda_model->deleteItem(['id'=>$id]);
        }
        elseif (strcmp($type, "goal")==0){
            $result = $this->Strategy_model->deleteGoal(['id'=>$id]);
        }
        elseif (strcmp($type, "action")==0){
            $result = $this->Strategy_model->deleteAction(['id'=>$id]);
        }
        return $result;
    }

    private function getAllStrategyData($view, $edit, $validate){
        $this->load->model('User_model');
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
        $years = [];
        $aux_stra = $this->Strategy_model->getStrategicPlan(['org' => array_merge($view, $edit, $validate)]);
        foreach ($aux_stra as $strategy){
            if(!in_array($strategy->year, $years))
                $years[] = $strategy->year;
            $strategy->status = $status[$strategy->status];
            $strategy->deadline = date("d-m-Y", strtotime($strategy->deadline));
            $collaborators = $this->Strategy_model->getAllCollaborators($strategy->id);
            $strategies[$strategy->org][$strategy->year]['collaborators'] = [];
            foreach ($collaborators as $collaborator){
                $strategies[$strategy->org][$strategy->year]['collaborators'][$collaborator->user]['name'] = $users[$collaborator->user]->name;
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
                $aux_actions = $this->Strategy_model->getAction(array('goal'=>[$goal->id]));
                $actions[$goal->id] = [];
                foreach ($aux_actions as $action){
                    $action->status = $status[$action->status];
                    $actions[$goal->id][$action->id] = $action;
                }
            }
        }
        return ['strategies'=>$strategies, 'goals' => $goals, 'actions' => $actions, 'years' => $years, 'status' => $status, 'users'=>$users];
    }

    private function getAllFodaData($view, $edit, $validate){
        $fodas = $this->Foda_model->getFoda(['org' => array_merge($view, $edit, $validate),'order'=>[['org', 'ASC'], ['year', 'ASC']]]);
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
            if(count( $items[$foda->org][$foda->year])==0)
                unset($items[$foda->org][$foda->year]);
            if(count( $items[$foda->org])==0)
                unset($items[$foda->org]);
            $years[] = $foda->year;
            $fodasByOrg[$index][$foda->year] = $foda;
        }
        return ['fodas' => $fodasByOrg, 'items' => $items, 'years' => $years];
    }
}