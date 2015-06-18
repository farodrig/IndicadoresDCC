<?php
class ADI extends CI_Controller {

	function __construct() {
		parent::__construct();
	}

	public function user_verify() {
		session_id($_GET[session_name()]);
		session_start();
		$data = $_SESSION;
		session_destroy();
		//Aqui se debiesen hacer validaciones para que el usuario pueda ingresar a la aplicación
		$this->load->model('User_model');
		$this->load->library('session');
		if (!$this->User_model->getUserById($data['rut'])) {
			$this->session->set_flashdata("error", 1);
			redirect('');
		}
		//Aqui se debe agregar las variables de sesion que seran consultadas a futuro en la aplicacion.
		$this->load->model('Permits_model');
		$this->session->set_userdata('rut', $data['rut']);
		$this->session->set_userdata('name', $data['nombre_completo']);
		$permits       = $this->Permits_model->getAllPermits($data['rut']);
		$permits_array = array(
			'director'                  => $permits->getDirector(),
			'visualizador'              => $permits->getVisualizador(),
			'asistente_unidad'          => $permits->getAsistenteUnidad(),
			'asistente_finanzas_unidad' => $permits->getAsistenteFinanzasUnidad(),
			'encargado_finanzas_unidad' => $permits->getEncargadoFinanzasUnidad(),
			'encargado_unidad'          => $permits->getEncargadoUnidad(),
			'asistente_dcc'             => $permits->getAsistenteDCC());
		$title                  = getTitle($permits_array);
		$permits_array['title'] = $title;

		$this->session->set_userdata($permits_array);

		redirect('inicio');
	}
}