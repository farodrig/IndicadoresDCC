<?php
class Dashboardconfig_model extends CI_Model{

	public $serie;
	public $type;
	public $graphic;
	public $aggregation;
	public $dashboard;
	public $org;

	public function __construct(){
		parent::__construct();
		$this->serie = "Serie";
		$this->type = "Serie_Type";
		$this->graphic = "Graphic";
		$this->aggregation = "Aggregation_Type";
		$this->dashboard = "Dashboard";
		$this->org = "Organization";
	}

	function getSerieType($data){
		return getGeneric($this, $this->type, $data);
	}
	
	function getAggregationType($data){
		return getGeneric($this, $this->aggregation, $data);
	}

	function getAllGraphicByOrg($org, $all){
		$this->db->select($this->graphic.'.id, '.$this->graphic.'.title, ver_x, min_year, max_year, position, display');
		$this->db->from($this->graphic);
		$this->db->join($this->dashboard, $this->graphic.'.dashboard = '.$this->dashboard.'.id');
		$this->db->where($this->dashboard.'.org', $org);
		if(!$all)
			$this->db->where($this->graphic.'.display', 1);
		$this->db->order_by($this->graphic.'.position', 'ASC');
		$q = $this->db->get();
		return $q->result();
	}

	function getAllSeriesByGraph($graph){
		return getGeneric($this, $this->serie, ['graphic'=>[$graph]]);
	}

	function getOrCreateDashboard($org){
		$this->db->from($this->dashboard);
		$this->db->where('org', $org);
		$q = $this->db->get();
		if(count($q->result())==0){
			$organization = getGeneric($this, $this->org, ['id'=>[$org]]);
			if(count($organization)==0)
				return false;
			$organization = $organization[0];
			$this->db->insert($this->dashboard, ['org'=>$org, 'title'=>'Dashboard '.$organization->name]);
			$id = $this->db->insert_id();
			$q = $this->db->get_where($this->dashboard, array('id' => $id));
			return (count($q->result())==1 ? $q->row() : false);
		}
		return $q->row();
	}
	
	function addGraphic($title, $max, $min, $pos, $x, $dashboard, $display){
		$data = ['title'=>$title, 'max_year'=>$max, 'min_year'=>$min, 'position'=>$pos, 'ver_x'=>$x, 'dashboard'=>$dashboard, 'display'=>$display];
		return ($this->db->insert($this->graphic, $data)) ? $this->db->insert_id() : false;
	}

	function modifyGraphic($id, $title, $max, $min, $pos, $x, $dashboard, $display){
		$this->db->where('id', $id);
		$data = ['title'=>$title, 'max_year'=>$max, 'min_year'=>$min, 'position'=>$pos, 'ver_x'=>$x, 'dashboard'=>$dashboard, 'display'=>$display];
		return $this->db->update($this->graphic, $data);
	}
	
	function deleteGraphic($id){
		return $this->db->delete($this->graphic, ['id'=>$id]);
	}

	function addSerie($graphic, $metorg, $type, $x, $year, $color){
		$data = ['graphic'=>$graphic, 'metorg'=>$metorg, 'type'=>$type, 'x_aggregation'=>$x, 'year_aggregation'=>$year, 'color'=>$color];
		return ($this->db->insert($this->serie, $data)) ? $this->db->insert_id() : false;
	}

	function modifySerie($id, $graphic, $metorg, $type, $x, $year, $color){
		$this->db->where('id', $id);
		$data = ['graphic'=>$graphic, 'metorg'=>$metorg, 'type'=>$type, 'x_aggregation'=>$x, 'year_aggregation'=>$year, 'color'=>$color];
		return $this->db->update($this->serie, $data);
	}
	
	function deleteSerie($id){
		return $this->db->delete($this->serie, ['id'=>$id]);
	}

}
