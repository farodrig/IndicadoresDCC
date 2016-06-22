<?php
class User_model extends CI_Model{

    public $title;
    public $permit;
    public $role;
    public $position;
    public $resource;


    public function __construct(){
        // Call the CI_Model constructor
        parent::__construct();
        $this->title = "User";
        $this->permit = "Permit";
        $this->role = "Role";
        $this->position = "Position";
        $this->resource = "Resource";
    }

    function getUser($data){
        return getGeneric($this, $this->title, $data);
    }

    function modifyUser($data){
        $this->db->where('id', $data['id']);
        unset($data['id']);
        return $this->db->update($this->title, $data);
    }
    
    function getAllUsers(){
        return getGeneric($this, $this->title, array('order'=>[['name', "DESC"]]));
    }
    
    function getUserById($id){
        return getGeneric($this, $this->title, array('limit' =>1, 'id'=>[$id]))[0];
    }

    function getPermit($data){
        return getGeneric($this, $this->permit, $data);
    }

    function getUsersByPositionName($positionName){
        $this->db->from($this->role);
        $this->db->join($this->position, $this->position.'.id = '.$this->role.'.position');
        $this->db->join($this->title, $this->title.'.id = '.$this->role.'.user');
        $this->db->like($this->position.'.short_name', $positionName);
        $this->db->group_start();
            $this->db->group_start();
                $this->db->where($this->role.'.final_date = ', null);
                $this->db->where($this->role.'.initial_date <= ', date('Y-m-d H:i:s'));
            $this->db->group_end();
            $this->db->or_group_start();
                $this->db->where($this->role.'.final_date >= ', date('Y-m-d H:i:s'));
                $this->db->where($this->role.'.initial_date <= ', date('Y-m-d H:i:s'));
            $this->db->group_end();
        $this->db->group_end();
        $q = $this->db->get();
        return $q->result();
    }

    function getPermitByUser($user){
        $this->db->select($this->permit.'.id, '.$this->permit.'.org, '.$this->permit.'.position, resource, view, edit, validate');
        $this->db->from($this->position);
        $this->db->join($this->role, $this->position.'.id = '.$this->role.'.position');
        $this->db->join($this->permit, $this->permit.'.position = '.$this->position .'.id');
        $this->db->where($this->role.'.user', $user);
        $this->db->group_start();
            $this->db->group_start();
                $this->db->where($this->role.'.final_date = ', null);
                $this->db->where($this->role.'.initial_date <= ', date('Y-m-d H:i:s'));
            $this->db->group_end();
            $this->db->or_group_start();
                $this->db->where($this->role.'.final_date >= ', date('Y-m-d H:i:s'));
                $this->db->where($this->role.'.initial_date <= ', date('Y-m-d H:i:s'));
            $this->db->group_end();
        $this->db->group_end();
        $this->db->order_by('resource', 'ASC');
        $this->db->order_by('org', 'ASC');
        $q = $this->db->get();
        return $q->result();
    }
}