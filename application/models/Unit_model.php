<?php
class Unit_model extends CI_Model{

	public $title;

	public function __construct(){
		// Call the CI_Model constructor
		parent::__construct();
		$this->title = "Unit";
	}

	function get_or_create($name){
		return get_or_create($this, $name, 'id');
	}

}
