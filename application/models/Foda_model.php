<?php
class Foda_model extends CI_Model{

    public $title;
    public $item;
    public $priority;
    public $type;

    public function __construct(){
        // Call the CI_Model constructor
        parent::__construct();
        $this->title = "FODA";
        $this->item = "Item";
        $this->priority = "Priority";
        $this->type = "FODA_Type";
    }

    public function addFoda($data){
        return ($this->db->insert($this->title, $data)) ? true : false;
    }

    public function getFoda($data){
        return getGeneric($this, $this->title, ['org', 'year'], $data);
    }

    public function addItem($data){
        return ($this->db->insert($this->item, $data)) ? true : false;
    }

    public function getItem($data){
        return getGeneric($this, $this->item, ['foda', 'priority', 'type'], $data);
    }

    public function getPriority($data){
        return getGeneric($this, $this->priority, ['name'], $data);
    }

    public function getAllPriority(){
        $query = $this->db->get($this->priority);
        return $query->result();
    }

    public function getType($data){
        return getGeneric($this, $this->type, ['name'], $data);
    }

    public function getAllType(){
        $query = $this->db->get($this->type);
        return $query->result();
    }
}