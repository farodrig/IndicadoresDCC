<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class DashboardConfig extends CI_Controller 
{

	function __construct()
	{
		parent::__construct();

	}

	function configUnidad() // funcion que lista todas las metricas y las deja como objeto cada una por lo tanto se puede recorrer el arreglo
	                           // y llamar a cada valor del arreglo como liberia ejemplo mas abajo
	                           // esto sirve para cuando se llama de una vista para completar por ejemplo una tabla
	{
		$route = ['Dashboard', 'Configurar'];
	    $this->load->model('DashboardConfig_model');
	    $all_metrics = $this->DashboardConfig_model->getAllMetricsUnidades(); //Retorna arrglo de arreglos de metricas de las unidades correspondientes
	    															          //Si all_metrics es falso es porque no hay areas

	    $all_areas = $this->DashboardConfig_model->getAllAreasUnidad();

	    if($all_metrics==false){
	    	$result['metricas'] = [];
	    }
	    else{
	    	$result['metricas'] = $all_metrics;   
	    }

	    if(!$all_areas)
	    	$result['areas'] = [];
	    else{
	    	$result['areas'] = $all_areas;
	    }
	    $this->load->view('configurar-dashboard', $result);
	    //debug($all_areas[2]['unidades'], true);
	}

	

}