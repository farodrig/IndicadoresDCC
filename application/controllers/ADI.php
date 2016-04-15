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
		$user = $this->User_model->getUserById($data['rut']);
		if (!$user) {
			$this->session->set_flashdata("error", 1);
			redirect('');
		}
		if(!$user->name){
			$this->User_model->modifyUser(array('id'=>$user->id, 'name'=>$data['nombre_completo']));
		}
		//Aqui se debe agregar las variables de sesion que seran consultadas a futuro en la aplicacion.
		$this->session->set_userdata('rut', $data['rut']);
		$this->session->set_userdata('name', $data['nombre_completo']);
		$this->session->set_userdata('email', $user->email);
		$this->session->set_userdata('admin', $user->isAdmin);
		$permits       = $this->User_model->getPermitByUser($user->id);
		$permits_array = getPermits($permits);
		$permits_array['title'] = getTitle($user);
		$this->session->set_userdata($permits_array);
		redirect('inicio');
	}
}