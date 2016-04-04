<?php
class Strategy_model extends CI_Model{

    public $title;
    public $goal;
    public $action;
    public $foda;
    public $item;
    public $goalItem;
    public $status;
    public $collaborators;

    public function __construct(){
        // Call the CI_Model constructor
        parent::__construct();
        $this->title = "Strategic_Plan";
        $this->goal = "Goal";
        $this->action = "Action";
        $this->foda = "Foda";
        $this->item = "Item";
        $this->goalItem = "Goal_Item";
        $this->status = 'Completion_Status';
        $this->collaborators = 'Collaborator';
    }

    public function getStrategicPlan($data){
        return getGeneric($this, $this->title, $data);
    }

    public function addStrategicPlan($data){
        return ($this->db->insert($this->title, $data)) ? $this->db->insert_id() : false;
    }

    public function modifyStrategicPlan($data){
        $this->db->where('id', $data['id']);
        unset($data['id']);
        return $this->db->update($this->title, $data);
    }    

    public function getGoal($data){
        return getGeneric($this, $this->goal, $data);
    }

    public function addGoal($data){
        return ($this->db->insert($this->goal, $data)) ? $this->db->insert_id() : false;
    }

    public function modifyGoal($data){
        $this->db->where('id', $data['id']);
        unset($data['id']);
        return $this->db->update($this->goal, $data);
    }

    public function deleteGoal($data){
        return $this->db->delete($this->goal, $data);
    }

    public function getAction($data){
        return getGeneric($this, $this->action, $data);
    }

    public function addAction($data){
        return ($this->db->insert($this->action, $data)) ? $this->db->insert_id() : false;
    }

    public function modifyAction($data){
        $this->db->where('id', $data['id']);
        unset($data['id']);
        return $this->db->update($this->action, $data);
    }

    public function deleteAction($data){
        return $this->db->delete($this->action, $data);
    }

    public function getCompletitionStatus($data){
        return getGeneric($this, $this->status, $data);
    }

    public function getAllCollaborators($strategy){
        return getGeneric($this, $this->collaborators, array('strategy'=>[$strategy]));
    }

    public function addCollaborator($strategy, $user){
        return ($this->db->insert($this->collaborators, ['strategy'=>$strategy, 'user'=>$user])) ? true : false;
    }
    
    public function deleteCollaborator($strategy, $user){
        return $this->db->delete($this->collaborators, ['user' => $user, 'strategy' => $strategy]);
    }
    
    public function getGoalItem($data){
        return getGeneric($this, $this->goalItem, $data);
    }

    public function addGoalItem($goal, $item){
        return ($this->db->insert($this->goalItem, ['goal'=>$goal, 'item'=>$item])) ? true : false;
    }

    public function deleteGoalItem($goal, $item){
        return $this->db->delete($this->goalItem, ['goal' => $goal, 'item' => $item]);
    }
}