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
}