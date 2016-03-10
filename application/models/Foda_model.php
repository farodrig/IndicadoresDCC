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
        return ($this->db->insert($this->title, $data)) ? $this->db->insert_id() : false;
    }

    public function modifyFoda($data){
        $this->db->where('id', $data['id']);
        unset($data['id']);
        return $this->db->update($this->title, $data);
    }

    public function getFoda($data){
        return getGeneric($this, $this->title, ['org', 'year'], $data);
    }

    public function addItem($data){
        return ($this->db->insert($this->item, $data)) ? $this->db->insert_id() : false;
    }

    public function modifyItem($data){
        $this->db->where('id', $data['id']);
        unset($data['id']);
        return $this->db->update($this->item, $data);
    }

    public function deleteItem($data){
        return $this->db->delete($this->item, $data);
    }

    public function getItem($data){
        return getGeneric($this, $this->item, ['foda', 'priority', 'type'], $data);
    }

    public function getPriority($data){
        return getGeneric($this, $this->priority, ['name'], $data);
    }

    public function getAllPriority(){
        return $this->db->get($this->priority)->result();
    }

    public function getType($data){
        return getGeneric($this, $this->type, ['name'], $data);
    }

    public function getAllType(){
        return $this->db->get($this->type)->result();
    }
}