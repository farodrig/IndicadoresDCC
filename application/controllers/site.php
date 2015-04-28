<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		include_once('is_logged_in.php');
		include_once('is_new.php');

	}

	public function projects()
	{
		// Seteo el proyecto seleccionado en falso dado a que estoy volviendo a la vista de proyectos
		$user_data = unserialize($this->session->userdata('session_user'))->setSelectedProject(false);
		$this->session->set_userdata('session_user', serialize($user_data));

		$this->load->view('projects');
	}

	public function documents()
	{
		$selected_project = unserialize($this->session->userdata('session_user'))->getSelectedProject();
		$this->load->view('documents');
	}

	public function profile()
	{
		$this->load->view('profile');
	}

	public function nprofile()
	{
		$this->load->view('first_profile');
	}

	public function projectDetails()
	{
		$this->load->view('doc_details');
	}

	public function opcion1()
	{
		//recupero el id del proyecto seleccionado
		$selected_project = $this->input->post('selected_project');

		// si me lo pasaron por post es porque vengo del listado de obras y lo seteo en la sesion
		if($selected_project)
		{
			$data = unserialize($this->session->userdata('session_user'))->setSelectedProject($selected_project);
			$this->session->set_userdata('session_user', serialize($data));
		}
		// si no me lo pasan por post es porque vengo de las otras vistas y tengo que recuperarlo de la sesion
		else
		{
			$selected_project = unserialize($this->session->userdata('session_user'))->getSelectedProject();
		}

		$this->load->view('proj_details_external');
	}

	public function opcion2()
	{
		//recupero el id del proyecto seleccionado
		$selected_project = $this->input->post('selected_project');

		// si me lo pasaron por post es porque vengo del listado de obras y lo seteo en la sesion
		if($selected_project)
		{
			$data = unserialize($this->session->userdata('session_user'))->setSelectedProject($selected_project);
			$this->session->set_userdata('session_user', serialize($data));
		}
		// si no me lo pasan por post es porque vengo de las otras vistas y tengo que recuperarlo de la sesion
		else
		{
			$selected_project = unserialize($this->session->userdata('session_user'))->getSelectedProject();
		}

		$this->load->view('proj_details_internal');
	}
}