<?php
class User_model extends CI_Model{
    
    function getAllUsers(){
        $result = array();
        $query = $this->db->get('User');
        foreach ($query->result() as $row ) {
            $result[$row->id] = $row->name;
        }
        return $result;
    }
    
    function getUserById($id){
        $this->db->where(array('id'=>$id));
        $query = $this->db->get('User');
        if ($query->num_rows() == 1){
            return $query->result_array()[0];
        }
        return false;
    }
}