<?php
class User_model extends CI_Model{

    public $title;
    public $permit;
    public $separator;
    public $role;

    public function __construct(){
        // Call the CI_Model constructor
        parent::__construct();
        $this->title = "User";
        $this->permit = "Permit";
        $this->separator = ",";
        $this->role = "Role";
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
        return getGeneric($this, $this->permit, array('user' =>[$user]));
    }

    function getPermitByUserByOrg($user, $org){
        return getGeneric($this, $this->permit, array('user' =>[$user], 'org' => [$org], 'limit'=>1))[0];
    }

    function getSeparator(){
        return $this->separator;
    }
}