<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Session extends CI_Controller {

	public function index()
	{
		$this->load->view('login');
	}

	public function inicio()
	{
	    $this->load->view('index');
	}

	public function dashboard()
	{
	    $this->load->view('dashboard');
	}

	public function validar()
	{
	    $this->load->view('validar');
	}
	public function menuConfigurar()
	{
	    $this->load->view('menu-configurar');
	}
	public function agregarDato()
	{
	    $this->load->view('add-data');
	}

	public function configurarAreasUnidades()
	{
	    $this->load->view('configurar-areas-unidades');
	}
	public function configurarDashboard()
	{
	    $this->load->view('configurar-dashboard');
	}
	public function configurarMetricas()
	{
	    $this->load->view('configurar-metricas');
	}

}