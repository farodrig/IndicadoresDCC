<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Organization_library{
    
	private $id;
	private $parent;
	private $type;
    private $name;
    
    function initialize($parameters)
    {
        $this->id = element('id', $parameters);
        $this->parent = element('parent', $parameters);
        $this->type = element('type', $parameters);
        $this->name = element('name', $parameters);
        return $this;
    }
    /**
     * @return the $id
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @return the $name
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * @return the $parent
     */
    public function getParent()
    {
        return $this->parent;
    }
    
    /**
     * @return the $type
     */
    public function getType()
    {
        return $this->type;
    }

}