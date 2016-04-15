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

    function getPermitByUser($user){
        $this->db->select($this->permit.'.id, '.$this->permit.'.org, '.$this->permit.'.position, resource, view, edit, validate');
        $this->db->from($this->position);
        $this->db->join($this->role, $this->position.'.id = '.$this->role.'.position');
        $this->db->join($this->permit, $this->permit.'.position = '.$this->position .'.id');
        $this->db->where($this->role.'.user', $user);
        $this->db->order_by('resource', 'ASC');
        $this->db->order_by('org', 'ASC');
        $q = $this->db->get();
        return $q->result();
    }
}