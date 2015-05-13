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
    
    function addUnidad($parentName, $data){
        $parent = $this->getByName($parentName);
        $data['type']=$parent->getType();
        return $this->addChild($parent->getId(), $data);
    }
    
    private function addChild($parent, $data){
        $data['parent'] = $parent;
        if ($this->db->insert("Organization", $data))
            return true;
        else
            return false;           
    }
    
    function getTypes(){
        $this->db->where(array('name!='=>""));
        $query = $this->db->get('OrgType');
        $result = array();
        $colores = array('#47a447', '#ed9c28');
        foreach ($query->result() as $row){
            array_push($result, array('id'=>$row->id, 'name'=>$row->name, 'color'=> $colores[count($result)]));
        }
        return $result;
    }
    
    function getAllUnidades($area){
        return $this->getAllChilds($area);
    }
    
    function getAllAreas(){
        $root = $this->getDepartment();
        return $this->getAllChilds($root->getId());
    }
    
    private function getAllChilds($id){
        $this->db->where(array('parent'=>$id));
        $this->db->where('id!=parent');
        $query = $this->db->get('Organization');
        return $this->buildAllOrganization($query);
    }
    
    function delByName($name){
        $this->db->where(array('name'=>$name));
        $query = $this->db->delete('Organization');
        $val = $this->db->affected_rows();
        if($val==0)
            return false;
        else
            return True;
    }
    
    function getByID($id){
        $this->db->where(array('id'=>$id));
        $query = $this->db->get('Organization');
        if ($query->num_rows() != 1)
            return false;
        else
            return  $this->buildOrganization($query->row());
    }
    
    function getByName($name){
        $this->db->where(array('name'=>$name));
        $query = $this->db->get('Organization');
        if ($query->num_rows() != 1)
            return false;
        else
            return  $this->buildOrganization($query->row());
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