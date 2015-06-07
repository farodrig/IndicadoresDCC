<?php

class Organization_model extends CI_Model{

    function getDepartment(){
        $this->db->where("id = parent");
        $query = $this->db->get('Organization');
        if ($query->num_rows() != 2)
            return false;
        else{
            $res = [];
            foreach ($query->result() as $row ) {
                array_push($res, $this->buildOrganization($row));
            }
            return $res;
        }
    }
    
    function addArea($data){
        $root = $this->getDepartment();
        foreach ($root as $r) {
            if($r->getType()==$data['type'])
                return $this->addChild($r->getId(), $data);
        }
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
        return $this->getTypesWhere(array());
    }
    
    function getTypeByName($name){
        return $this->getTypesWhere(array('name'=>$name));
    }
    
    function getTypeById($id){
        return $this->getTypesWhere(array('id'=>$id));
    }
    
    private function getTypesWhere($where){
        $this->db->where(array('name!='=>""));
        if(count($where)!=0)
            $this->db->where($where);
        $query = $this->db->get('OrgType');
        $result = array();
        $colores = array('Operación'=>'#47a447', 'operación'=>'#47a447', 'Soporte'=>'#ed9c28', 'soporte'=>'#ed9c28');
        foreach ($query->result() as $row){
            array_push($result, array('id'=>$row->id, 'name'=>$row->name, 'color'=> $colores[$row->name]));
        }
        if (count($result)==1)
            return $result[0];
        return $result;
    }
    
    function getAllUnidades($area){
        return $this->getAllChilds($area);
    }
    
    function getAllAreas(){
        $root = $this->getDepartment();
        $res = [];
        foreach ($root as $key) {
            $res = array_merge($res, $this->getAllChilds($key->getId()));
        }
        return $res;
    }
    
    function getAllChilds($id){
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
        if ($query->num_rows() != 2)
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