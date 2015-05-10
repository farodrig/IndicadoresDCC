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
	/*private $updater;
	private $dateup;
	private $validator;
	private $dateval;*/


	function initialize($parameters)
	{
		$this->metorg = element('id', $parameters);
		$this->name_met = element('name', $parameters);
		return $this;
	}

    function initializeIds($parameters)
    {
        $this->metorg = element('id', $parameters);
        return $this;
    }

    function initializeDashboardMetrics($parameters)
    {
        $this->metorg = element('met_org', $parameters);
        $this->graph_type = element('type', $parameters);
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

 /**
     * @return the $id_met
     */
    public function getId()
    {
        return $this->metorg;
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
