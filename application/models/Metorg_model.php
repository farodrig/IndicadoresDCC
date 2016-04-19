<?php
class Metorg_model extends CI_Model{

	public $title;
	public $value;
	public $metric;

	public function __construct(){
		// Call the CI_Model constructor
		parent::__construct();
		$this->title = "MetOrg";
		$this->value = "Value";
		$this->metric = "Metric";
	}

	function getMetOrg($data){
		return getGeneric($this, $this->title, $data);
	}

	function addMetOrg($data){
		return ($this->db->insert($this->title, $data)) ? $this->db->insert_id() : false;
	}

	function delMetOrg($data){
		return $this->db->delete($this->title, $data);
	}

	function getMetOrgDataByValue($valId){
		$value = getGeneric($this, $this->value, ['id'=>[$valId]])[0];
		$metOrg = getGeneric($this, $this->title, ['id'=>[$value->metorg]])[0];
		$metric = getGeneric($this, $this->metric, ['id'=>[$metOrg->metric]])[0];
		$metric->metric = $metric->id;
		$metric->id = $metOrg->id;
		$metric->org = $metOrg->org;
		return $metric;
	}
}
