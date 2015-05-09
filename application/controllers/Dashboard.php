<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();

	}


	function addData() // funcion que lista todas las metricas y las deja como objeto cada una por lo tanto se puede recorrer el arreglo
	                           // y llamar a cada valor del arreglo como liberia ejemplo mas abajo
	                           // esto sirve para cuando se llama de una vista para completar por ejemplo una tabla
	{
		$id = 2; //Se recibe por POST, es el id de área, unidad, etc que se este considerando

	    $this->load->model('Dashboard_model');

	    $all_metrics = $this->Dashboard_model->getAllMetrics($id);
	    $route = $this->Dashboard_model->getRoute($id);


	    if(!$all_metrics){
	    	$res['result']=[];
	    }
	    else{
	    	foreach ($all_metrics as $metrics)
	    	{
                $s = "<div class='row mb-md'>".
				"<div class= 'col-md-3'>".
				"<label class='text'>".$metrics->getName()."</label>".
				"</div>".
				"<div class='col-md-3'>".
				"<input type='text' name='value".$metrics->getId()."' class='form-control'>".
				"</div>".
				"<div class='col-md-3'>".
				"<input type='text' name='expected".$metrics->getId()."' class='form-control'>".
				"</div>".
				"<div class='col-md-3'>".
				"<input type='text' name='objective".$metrics->getId()."' class='form-control'>".
				"</div>".
				"</div>";
				$data[$metrics->getId()] = $s;

	    	}
	    	$res['result'] = $data;
	    }
	    
	    $res['route'] = $route;
	    
	    $this->load->view('add-data', $res);
	    //debug($all_metrics, true);
	}


}