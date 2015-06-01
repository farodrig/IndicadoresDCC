<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Metrics_library
{
	private $id;
	private $category;
	private $unit;
	private $name;
	
    function initialize($parameters)
    {
        $this->id = element('id', $parameters);
        $this->category = element('category', $parameters);
        $this->unit = element('unit', $parameters);
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
     * @return the $unit
     */
    public function getUnit()
    {
        return $this->name;
    }
    /**
     * @return the $category
     */
    public function getCategory()
    {
        return $this->category;
 
    }
} 