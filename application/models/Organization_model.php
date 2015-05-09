<?php

class Organization_model extends CI_Model{

    function getDepartment(){
        $this->db->where("id = parent");
        $query = $this->db->get('Organization');
        if ($query->num_rows() != 1)
            return false;
        else
            return  $this->buildOrganization($query->row());
    }
    
    function addArea($data){
        $root = $this->getDepartment();
        return $this->addChild($root->getId(), $data);
    }
    
    function addUnidad($parent, $data){
        return $this->addChild($parent, $data);
    }
    
    function addChild($parent, $data){
        $data['parent'] = $parent;
        if ($this->db->insert("Organization", $data))
            return true;
        else
            return false;           
    }
    
    function getAllUnidades($area){
        return $this->getAllChilds($area);
    }
    
    function getAllAreas(){
        $root = $this->getDepartment();
        return $this->getAllChilds($root->getId());
    }
    
    function getAllChilds($id){
        $this->db->where(array('parent'=>$id));
        $this->db->where('id!=parent');
        $query = $this->db->get('Organization');
        return $this->buildAllOrganization($query);        
    }
    
    function buildAllOrganization($q){
        $orgs = array();
        foreach ($q->result() as $row){
            array_push($orgs, $this->buildOrganization($row));
        }
        return $orgs;
    }
    
    function buildOrganization($row){
        $this->load->library('Organization_library');
        $parameters = array(
            'id' => $row->id,
            'parent' => $row->parent,
            'type' => $row->type,
            'name' => $row->name
        );        
        $org = new Organization_library();        
        return $org->initialize($parameters);
    }
}