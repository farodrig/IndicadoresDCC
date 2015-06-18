<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MySession extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library('session');
	}

	public function index() {
		$result          = array();
		$result['error'] = $this->session->flashdata('error');
		//Borrar Desde Aqui
		$this->load->model('User_model');
		$result['users'] = $this->User_model->getAllUsers();

		if ($this->input->method() == "post") {
			$this->load->model('Permits_model');
			$rut  = $this->input->post('user');
			$name = $result['users'][$rut];
			$this->session->set_userdata('rut', $rut);
			$this->session->set_userdata('name', $name);
			$permits       = $this->Permits_model->getAllPermits($rut);
			$permits_array = array(
				'director'                  => $permits->getDirector(),
				'visualizador'              => $permits->getVisualizador(),
				'asistente_unidad'          => $permits->getAsistenteUnidad(),
				'asistente_finanzas_unidad' => $permits->getAsistenteFinanzasUnidad(),
				'encargado_finanzas_unidad' => $permits->getEncargadoFinanzasUnidad(),
				'encargado_unidad'          => $permits->getEncargadoUnidad(),
				'asistente_dcc'             => $permits->getAsistenteDCC());
			$title                  = $this->getTitle($permits_array);
			$permits_array['title'] = $title;

			$this->session->set_userdata($permits_array);
			redirect('inicio');
		}
		//Hasta Aqui para Pasar a PRODUCCION
		$this->load->view('login', $result);
	}

	public function logout() {
		$this->session->sess_destroy();
		redirect('');
	}

	public function contact() {
		$work = true;
		if ($this->input->method() == "post") {
			$this->load->library('email');

			$this->email->from($this->input->post('email'), $this->input->post('name'));
			$this->email->to('NoMandaMail@gmail.com');

			$this->email->subject($this->input->post('topic'));
			$this->email->message($this->input->post('message'));

			if (!$this->email->send()) {
				$work = false;
			} else {

				redirect('inicio');
			}
		}

		$this->load->view('contact', array('work' => $work));
	}

	public function inicio() {
		$user = $this->session->rut;

		if (is_null($user)) {
			redirect('salir');
		}

		$this->load->model('Dashboard_model');
		$this->load->model('Organization_model');
		$permits    = $this->session->userdata();
		$type       = is_null($this->input->get('sector')) ? "Operación" : $this->input->get('sector');
		$department = $this->Organization_model->getDepartment();
		$areaunit   = $this->showAreaUnit();
		$name       = $type;
		$aus        = $areaunit;
		$areaunit   = array();

		$type = $this->Organization_model->getTypeByName($name);

		foreach ($aus as $au)
		if ($au['area']->getType() == $type['id']) {
			array_push($areaunit, $au);
		}

		$result = array('department' => $department,
			'areaunit'                  => $areaunit,
			'types'                     => $this->Organization_model->getTypes(),
			'name'                      => $name,
			'user'                      => $user,
			'validate'                  => validation($permits, $this->Dashboard_model),
			'role'                      => $permits['title']);
		$this->load->view('index', $result);
	}

	private function showAreaUnit() {

		$this->load->model('Organization_model');
		$areaunit = array();
		$areas    = $this->Organization_model->getAllAreas();
		foreach ($areas as $area) {
			array_push($areaunit, array('area' => $area,
					'unidades'                       => $this->Organization_model->getAllUnidades($area->getId()))
			);
		}
		return $areaunit;
	}

	public function dashboard() {

		$id_unidad = $this->input->post("unidad");
		$this->session->set_flashdata("unidad", $id_unidad);
	}

	public function validar() {
		$success = $this->session->flashdata('success');
		if (is_null($success)) {
			$success = 2;
		}

		$permits = $this->session->userdata();
		if (!$permits['director'] && in_array(-1, $permits['encargado_unidad']) && in_array(-1, $permits['encargado_finanzas_unidad'])) {
			redirect('inicio');
		}

		$this->load->model('Dashboard_model');

		$result = array('success' => $success,
			'validate'               => validation($permits, $this->Dashboard_model),
			'role'                   => $permits['title']);

		if ($permits['director'] == 1) {
			$result['data'] = $this->Dashboard_model->getAllnonValidateData();
		} elseif (!in_array('-1', $permits['encargado_unidad']) && !in_array('-1', $permits['encargado_finanzas_unidad'])) {
			$result['data'] = $this->Dashboard_model->getnonValidatebyUnit($permits['encargado_finanzas_unidad']);
		} elseif (!in_array('-1', $permits['encargado_unidad'])) {
			$result['data'] = $this->Dashboard_model->getnonValidatebyUnitByType($permits['encargado_unidad'], 1);
		} elseif (!in_array('-1', $permits['encargado_finanzas_unidad'])) {
			$result['data'] = $this->Dashboard_model->getnonValidatebyUnitByType($permits['encargado_finanzas_unidad'], 2);
		}
		$this->load->view('validar', $result);

	}

	public function validate_reject() {
		$this->load->model('Dashboard_model');
		$success = 2;
		$data    = $this->input->post();
		$func    = NULL;

		if ($this->input->post('Validar')) {
			unset($data['Validar']);
			$func = 'validateData';
		} elseif ($this->input->post('Rechazar')) {
			unset($data['Rechazar']);
			$func = 'rejectData';
		}

		if (!is_null($func) && count($data) > 0) {
			$success = 0;
			if (!$this->checkIfAlreadyValidate($data)) {
				foreach ($data as $data_id) {
					$this->Dashboard_model->$func($data_id);
				}
				$success = 1;
			}
		}

		$this->session->set_flashdata('success', $success);
		redirect('validar');
	}

	private function checkIfAlreadyValidate($data) {
		$this->load->model('Dashboard_model');
		$isValidate = FALSE;
		foreach ($data as $data_id) {
			$isValidate = $isValidate || $this->Dashboard_model->checkIfValidate($data_id);
		}
		return $isValidate;

	}

	public function menuConfigurar() {
		$this->load->model('Dashboard_model');
		$permits = $this->session->userdata();

		if (!$permits['director']) {
			redirect('inicio');
		}

		$this->load->view('menu-configurar', array('validate' => validation($permits, $this->Dashboard_model),
				'role'                                              => $permits['title']));
	}

	public function configurarMetricas() {
		$permits = $this->session->userdata();

		if (!$permits['director']) {
			redirect('inicio');
		}

		$success = $this->session->flashdata('success');
		if (is_null($success)) {
			$success = 2;
		}

		$this->load->model('Organization_model');
		$this->load->model('Dashboard_model');
		$this->load->model('Metrics_model');
		$metrics = $this->Metrics_model->getAllMetrics();
		$this->load->view('configurar-metricas', array('departments' => getAllOrgsByDpto($this->Organization_model),
				'metrics'                                                  => $metrics,
				'success'                                                  => $success,
				'role'                                                     => $permits['title'],
				'validate'                                                 => validation($permits, $this->Dashboard_model)));
	}

	public function agregarMetrica() {
		$this->load->library('form_validation');

		$this->form_validation->set_rules('unidad_medida', 'UnidadMedida', 'required|alphaSpace');
		$this->form_validation->set_rules('category', 'Category', 'required|numeric');
		$this->form_validation->set_rules('name', 'Name', 'required|alphaSpace');
		$this->form_validation->set_rules('id_insert', 'Id', 'required|numeric');

		if (!$this->form_validation->run()) {
			$this->session->set_flashdata('success', 0);
			redirect('cmetrica');
		}

		$this->load->model('Metrics_model');
		$this->load->model('Metorg_model');
		$this->load->model('Unit_model');

		$Unit = array(
			'name' => ucwords($this->input->post('unidad_medida')),
		);

		$Metricdata = array(
			'category' => $this->input->post('category'), //esto es 1 si es productividad y 2 si es finanzas. Tienes que agregar esos dos valores en la base de datos
			// en la tabla catergory
			'unit' => $this->Unit_model->checkName($Unit), //-> primero revisa si hay unidad de medida en la base de datos con ese nombre, si existe toma
			// el id correspondiente y le asocias a la metrica ese id ,si no agrega la unidad, obten
			// el nuevo id y se lo asocias a la metrica
			'name' => ucwords($this->input->post('name')), //Nombre que tendrá la métrica//id de la unidad o area a la que se le quiere ingresar la metrica
		);

		$id_metric = $this->Metrics_model->addMetric($Metricdata);

		$metorg = array(
			'org'    => $this->input->post('id_insert'),
			'metric' => $id_metric,
		);

		if ($this->Metorg_model->addMetOrg($metorg)) {
			$this->session->set_flashdata('success', 1);
		} else {
			$this->session->set_flashdata('success', 0);
		}

		redirect('cmetrica');
	}

	public function eliminarMetrica() {

		$this->load->model('Metrics_model');
		$this->load->library('form_validation');

		if ($this->input->post('modificar')) {
			$this->form_validation->set_rules('unidad', 'UnidadMedida', 'required|alphaSpace');
			$this->form_validation->set_rules('tipo', 'Type', 'required|numeric');
			$this->form_validation->set_rules('metrica', 'Metric', 'required|alphaSpace');
			$this->form_validation->set_rules('id', 'Id', 'required|numeric');

			if (!$this->form_validation->run()) {
				$this->session->set_flashdata('success', 0);
				redirect('cmetrica');
			}

			$data = array(
				'id_metorg'     => $this->input->post('id'),
				'name_metrica'  => ucwords($this->input->post('metrica')),
				'category'      => $this->input->post('tipo'),
				'unidad_medida' => ucwords($this->input->post('unidad'))
			);
			if ($this->Metrics_model->updateMetric($data)) {
				$this->session->set_flashdata('success', 1);
			} else {
				$this->session->set_flashdata('success', 0);
			}
		} else {
			$this->form_validation->set_rules('id2', 'Id', 'required|numeric');

			if (!$this->form_validation->run()) {
				$this->session->set_flashdata('success', 0);
				redirect('cmetrica');
			}

			$data = array('id_metorg' => $this->input->post('id2'));
			if ($this->Metrics_model->deleteMetric($data)) {
				$this->session->set_flashdata('success', 1);
			} else {
				$this->session->set_flashdata('success', 0);
			}
		}

		redirect('cmetrica');
	}

	private function getTitle($permits_array) {
		$title = "";
		$count = 0;
		if ($permits_array['director']) {
			$title = $title."Director";
		} elseif ($permits_array['asistente_dcc']) {
			$title = $title."Asistente DCC";
		} elseif (!in_array("-1", $permits_array['encargado_unidad'])) {
			$title = $title.rtrim("Encargado de unidad");
		} elseif (!in_array("-1", $permits_array['encargado_finanzas_unidad'])) {
			$title = $title.rtrim("Encargado de finanzas <br> de unidad");
		} elseif (!in_array("-1", $permits_array['asistente_unidad'])) {
			$title = $title.rtrim("Asistente de unidad");
		} elseif (!in_array("-1", $permits_array['asistente_finanzas_unidad'])) {
			$title = $title.rtrim("Asistente de finanzas");
		} elseif ($permits_array['visualizador']) {
			$title = $title."Visualizador";
		}

		return $title;
	}
}
?>
