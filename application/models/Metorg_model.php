<?php
class Metorg_model extends CI_Model{


	function addMetOrg($data){
		//REcuerda insertar en MetOrg
		return $this->db->insert('MetOrg', $data); 

	}
}
