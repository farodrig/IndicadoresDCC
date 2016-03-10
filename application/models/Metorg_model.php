<?php
class Metorg_model extends CI_Model{

	public $title;

	public function __construct(){
		// Call the CI_Model constructor
		parent::__construct();
		$this->title = "MetOrg";
	}

	function getMetOrg($data){
		return getGeneric($this, $this->title, ['id', 'org', 'metric'], $data);
	}

	function addMetOrg($data){
		//REcuerda insertar en MetOrg
		return $this->db->insert($this->title, $data);
	}

	function delMetOrg($data){
		return $this->db->delete($this->title, $data);
	}
}
