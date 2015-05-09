<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Metricas extends CI_Controller //controlador que maneja informacion De algun otro usuario
{
	function __construct()
	{
		parent::__construct();

	}


	function listAllMetrics() // funcion que lista todas las metricas y las deja como objeto cada una por lo tanto se puede recorrer el arreglo
	                           // y llamar a cada valor del arreglo como liberia ejemplo mas abajo
	                           // esto sirve para cuando se llama de una vista para completar por ejemplo una tabla
	{
	    $this->load->model('Metricas_model');

	    $all_metrics = $this->Metricas_model->getAllMetrics();


	    foreach ($all_metrics as $metrics)
	    {

                echo $metrics->getId();
                echo $metrics->getMet_org();
                echo $metrics->getState();
                echo $metrics->getValue();
                echo $metrics->getTarget();
                echo $metrics->getExpected();
                echo $metrics->getYear();
                echo $metrics->getUpdater();
                echo $metrics->getDateup();
                echo $metrics->getValidator();
                echo $metrics->getDateval();
                echo "<br>";
	    }

	    $this->load->view('login');
	    debug($all_metrics, true);
	}



	//////////////////////////// Funciones de ejemplo///////////////////////////////
	function listUsers()
	{
		$this->load->model('UserInfoModel');
		$this->data['userinfo'] = $this->UserInfoModel->getAllUsers();
		$this->load->view('users_list');
	}


	function userStatus()
	{

		$this->load->model('UserModel');

		$typeof  = trim($this->input->post('typeof'));
		$user_id = explode(',', trim($this->input->post('user_id')));

		foreach ($user_id as $user_id)
		{
			if($typeof=='0')
			{
				$this->UserModel->deactivateUser($user_id);

			}

			else
			{
				$this->UserModel->activateUser($user_id);
			}
		}

		redirect('/listusers');


	}

	function projectUserPrivilege()
	{

		$user_id = trim($this->input->post('user_id'))+0;
		$project_id = trim($this->input->post('project_id'))+0;

		$this->load->model('UserRightstModel');
		$rights = $this->UserRightstModel->searchProjectPrivilege($user_id, $project_id);
		if ($rights == false)
		{
			echo '0';
			return;
		}

		$privileges[0] = $rights->getAdmin();
		$privileges[1] = $rights->getArchitecture1();
		$privileges[2] = $rights->getArchitecture2();
		$privileges[3] = $rights->getCalculation();
		$privileges[4] = $rights->getClimate();
		$privileges[5] = $rights->getSecurity();
		$privileges[6] = $rights->getElectricity();
		$privileges[7] = $rights->getVeFire();
		$privileges[8] = $rights->getSanitary();
		$privileges[9] = $rights->getLighting();
		$privileges[10] = $rights->getEnergetic();
		$privileges[11] = $rights->getWcurrents();
		$privileges[12] = $rights->getBms();
		$privileges[13] = $rights->getMilestone();
		$privileges[14] = $rights->getInterior();
		$privileges[15] = $rights->getOther();
		$privileges[16] = $rights->getPrincipal();
		$privileges[17] = $rights->getAdviser1();
		$privileges[18] = $rights->getAdviser2();
		$privileges[19] = $rights->getAdviser3();
		$privileges[20] = $rights->getAdviser4();

		echo json_encode($privileges);
	}

	}