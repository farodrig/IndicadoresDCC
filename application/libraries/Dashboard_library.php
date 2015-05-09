<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard_library
{
	private $id;
    private $name;
	/*private $met_org;
	private $stat;
	private $value;
	private $target;
	private $expected;
	private $year;
	private $updater;
	private $dateup;
	private $validator;
	private $dateval;*/


	function initialize($parameters)
	{
		$this->id = element('id', $parameters);
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
     * @return the $met_org
     */
    public function getName()
    {
        return $this->name;
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
   /* public function getValue()
    {
        return $this->value;
    }

 /**
     * @return the $target
     */
   /* public function getTarget()
    {
        return $this->target;
    }

 /**
     * @return the $expected
     */
   /* public function getExpected()
    {
        return $this->expected;
    }

 /**
     * @return the $year
     */
   /* public function getYear()
    {
        return $this->year;
    }

 /**
     * @return the $updater
     */
   /* public function getUpdater()
    {
        return $this->updater;
    }

 /**
     * @return the $dateup
     */
   /* public function getDateup()
    {
        return $this->dateup;
    }

 /**
     * @return the $validator
     */
   /* public function getValidator()
    {
        return $this->validator;
    }

 /**
     * @return the $dateval
     */
   /* public function getDateval()
    {
        return $this->dateval;
    }

 /**
     * @param field_type $id
     */
   /* public function setId($id)
    {
        $this->id = $id;
    }

 /**
     * @param field_type $met_org
     */
   /* public function setMet_org($met_org)
    {
        $this->met_org = $met_org;
    }

 /**
     * @param field_type $state
     */
   /* public function setState($state)
    {
        $this->state = $state;
    }

 /**
     * @param field_type $value
     */
   /* public function setValue($value)
    {
        $this->value = $value;
    }

 /**
     * @param field_type $target
     */
   /* public function setTarget($target)
    {
        $this->target = $target;
    }

 /**
     * @param field_type $expected
     */
   /* public function setExpected($expected)
    {
        $this->expected = $expected;
    }

 /**
     * @param field_type $year
     */
   /* public function setYear($year)
    {
        $this->year = $year;
    }

 /**
     * @param field_type $updater
     */
   /* public function setUpdater($updater)
    {
        $this->updater = $updater;
    }

 /**
     * @param field_type $dateup
     */
   /* public function setDateup($dateup)
    {
        $this->dateup = $dateup;
    }

 /**
     * @param field_type $validator
     */
    /*public function setValidator($validator)
    {
        $this->validator = $validator;
    }

 /**
     * @param field_type $dateval
     */
    /*public function setDateval($dateval)
    {
        $this->dateval = $dateval;
    }

*/
}
