<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard_library
{
    private $name_met;
	  private $id_measurement;
	  private $metorg;
	  private $value;
	  private $target;
	  private $expected;
	  private $year;
    private $graph_type;
	  private $id_org;
	  private $name_org;
    private $min_year;
    private $max_year;
    private $unit;
	/*private $validator;
	private $dateval;*/


	function initialize($parameters){
		$this->metorg = element('id', $parameters);
		$this->name_met = element('name', $parameters);
		return $this;
	}

    function initializeOrg($parameters){
        $this->id_org = element('id', $parameters);
        $this->name_org = element('name', $parameters);
        return $this;
    }
    function initializeIds($parameters)
    {
        $this->metorg = element('id', $parameters);
        return $this;
    }

    function initializeDashboardMetrics($parameters)
    {
        $this->min_year = element('min_year', $parameters);
        $this->max_year = element('max_year', $parameters);
        $this->metorg = element('met_org', $parameters);
        $this->graph_type = element('type', $parameters);
        $this->unit = element('unit', $parameters);
        return $this;
    }

    function initializeMeasurement($parameters)
    {
        $this->id_measurement = element('id', $parameters);
        $this->metorg = element('metorg', $parameters);
        $this->value = element('value', $parameters);
        $this->target = element('target', $parameters);
        $this->expected = element('expected', $parameters);
        $this->year = element('year', $parameters);
        return $this;
    }

    function initializeData($parameters){

        $this->value = element('value', $parameters);
        $this->target = element('target', $parameters);
        $this->expected = element('expected', $parameters);
        $this->year = element('year', $parameters);
        return $this;
    }


    /**
        * @return the unit
        */
       public function getUnit()
       {
           return $this->unit;
       }

 /**
     * @return the $id_met
     */
    public function getId()
    {
        return $this->metorg;
    }

     /**
     * @return the $min_year
     */
    public function getMinYear()
    {
        return $this->min_year;
    }

     /**
     * @return the $max_year
     */
    public function getMaxYear()
    {
        return $this->max_year;
    }

     /**
     * @return the $graph_type
     */
    public function getGraphType()
    {
        return $this->graph_type;
    }

 /**
     * @return the $name
     */
    public function getName()
    {
        return $this->name_met;
    }

/**
     * @return the $id_measurement
     */
    public function getIdMeasurement()
    {
        return $this->id_measurement;
    }

 /**
     * @return the $metorg
     */
    public function getMetOrg()
    {
        return $this->metorg;
    }

     /**
     * @return the $id_org
     */
    public function getIdOrg()
    {
        return $this->id_org;
    }

     /**
     * @return the $name_org
     */
    public function getNameOrg()
    {
        return $this->name_org;
    }

 /**
     * @return the $state
     */
 /*   public function getState()
    {
        return $this->state;
    }

 /**
     * @return the $value
     */
    public function getValue()
    {
        return $this->value;
    }

 /**
     * @return the $target
     */
    public function getTarget()
    {
        return $this->target;
    }

 /**
     * @return the $expected
     */
    public function getExpected()
    {
        return $this->expected;
    }

 /**
     * @return the $year
     */
    public function getYear()
    {
        return $this->year;
    }

}
