<?php
class Unit_model extends CI_Model{


	function checkName($name){

		    $q =  $this->db->select('id')
							->from('Unit')
							->where('name', $name['name'])
				  ->get();
    if($q->num_rows() > 0){
			return $metric_id = $q->result_array()[0]['id'];
		}
		else
			$this->db->insert('Unit', $name);
			$this->db->where('name', $name['name']);
		  $q = $this->db->select('id')
									->from('Unit')
									->where('name', $name['name'])
									->get();
	    return $metric_id = $q->result_array()[0]['id'];
	  }

}
