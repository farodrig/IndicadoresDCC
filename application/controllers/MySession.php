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
		//Comentar Desde Aqui
		$this->load->model('User_model');
		if ($this->input->method() == "post" && $this->input->post('user')) {
			$user = $this->User_model->getUserById($this->input->post('user'));
			$this->session->set_userdata('rut', $user->id);
			$this->session->set_userdata('name', $user->name);
			$this->session->set_userdata('email', $user->email);
			$permits       = $this->User_model->getPermitByUser($user->id);
			$permits_array = getPermits($permits);
			$permits_array['title'] = getTitle($user);
			$this->session->set_userdata($permits_array);
			redirect('inicio');
		}
		$users = $this->User_model->getAllUsers();
		foreach($users as $user){
			$result['users'][$user->id] = $user->name;
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
		if (is_null($user))
			redirect('salir');

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
			'user'                      => $user
		);
		$this->load->view('index', array_merge($result, defaultResult($permits, $this->Dashboard_model)));
	}

	private function showAreaUnit() {

		$this->load->model('Organization_model');
		$areaunit = array();
		$areas    = $this->Organization_model->getAllAreas();
		foreach ($areas as $area) {
			array_push($areaunit, array('area' => $area,
										'unidades' => $this->Organization_model->getAllUnidades($area->getId()))
			);
		}
		return $areaunit;
	}

	public function validar() {
		if (is_null($this->session->rut))
			redirect('salir');
		$permits = $this->session->userdata();
		$prod = array_unique(array_merge($permits['foda']['validate'], $permits['metaP']['validate']));
		$finan = array_unique(array_merge($permits['valorF']['validate'], $permits['metaF']['validate']));
		if (!count($prod) && !count($finan)) {
			redirect('inicio');
		}
		$this->load->model('Dashboard_model');
		$this->load->model('Organization_model');
		$this->load->model('User_model');
		$success = $this->session->flashdata('success');
		if (is_null($success))
			$success = 2;

		if (count($prod) && count($finan)) {
			$orgs = array_unique(array_merge($prod, $finan));
			$category = null;
		} elseif (count($prod)) {
			$orgs = $prod;
			$category = 1;
		} elseif (count($finan)) {
			$orgs = $finan;
			$category = 2;
		}
		$data = array();
		foreach ($orgs as $org){
			$data[$org]['org'] = $this->Organization_model->getByID($org);
			$metrics = $this->Dashboard_model->getAllMetrics($org, $category, 1);
			foreach ($metrics as $metric){
                $perm['valor'] = ($metric->category==1 ? in_array($org, $permits['foda']['validate']) : in_array($org, $permits['valorF']['validate']));
                $perm['meta'] = ($metric->category==1 ? in_array($org, $permits['metaP']['validate']) : in_array($org, $permits['metaF']['validate']));
                $data[$org]['metorg'][$metric->metorg]['permits'] = $perm;
				$data[$org]['metorg'][$metric->metorg]['metric'] = $metric;
				$values = getGeneric($this->Dashboard_model, $this->Dashboard_model->value, array('metorg'=>[$metric->metorg], 'state'=>[0,-1], 'order'=>[['year', 'ASC'], ['x_value', 'ASC']]));
                $data[$org]['metorg'][$metric->metorg]['values'] = $values;
                for ($i = 0; $i<count($values); $i++){
                    $value = $values[$i];
                    //quitar el valor si este se eliminara y no se tiene permiso para validar la eliminacion
                    //o si el elemento se modificara y no se tienen los permisos para el valor
                    if (($value->state==-1 && !$perm['meta']) || ($value->state==0 && ((!$value->proposed_x_value && !$value->proposed_value && $perm['valor'] && !$perm['meta']) || (!$value->proposed_expected && !$value->proposed_target && !$perm['valor'] && $perm['meta'])))){
                        unset($data[$org]['metorg'][$metric->metorg]['values'][$i]);
                    }
                }
                if (!count($data[$org]['metorg'][$metric->metorg]['values']))
					unset($data[$org]['metorg'][$metric->metorg]);
			}
			if (!count($data[$org]['metorg']))
				unset($data[$org]);
		}
		$users = [];
		foreach ($this->User_model->getAllUsers() as $user){
			$users[$user->id] = $user->name;
		}
		$result = defaultResult($permits, $this->Dashboard_model);
		$result['success'] = $success;
		$result['users'] = $users;
		$result['data'] = $data;
		$this->load->view('validar', $result);
	}

	public function validate_reject() {
        $permits = $this->session->userdata();
		$prod = count($permits['foda']['validate']) + count($permits['metaP']['validate']);
		$finan = count($permits['valorF']['validate']) + count($permits['metaF']['validate']);
		if ($prod + $finan <=0 ) {
			redirect('inicio');
		}

		$this->load->library('form_validation');

        if ($this->input->post('Validar')) {
            $func = 'validateData';
        } elseif ($this->input->post('Rechazar')) {
            $func = 'deleteData';
        }

        //Validación de inputs
        $this->form_validation->set_rules('ids[]', 'ID', 'required|numeric|greater_than_equal_to[0]');
        if (!$this->form_validation->run() || !isset($func) || count($this->input->post('ids')) == 0) {
            $this->session->set_flashdata('success', 0);
            redirect('validar');
        }

        $data = $this->input->post('ids');

        $this->load->model('Dashboard_model');
        $this->load->model('Metorg_model');
        $this->load->library('form_validation');
		
		$success = true;
		foreach ($data as $data_id) {
			if(!$this->Dashboard_model->checkIfValidate($data_id))
				continue;
			$metorg = $this->Metorg_model->getMetOrgDataByValue($data_id);
			$validVal = ($metorg->category==1 ? in_array($metorg->org, $permits['foda']['validate']) : in_array($metorg->org, $permits['valorF']['validate']));
			$validMet = ($metorg->category==1 ? in_array($metorg->org, $permits['metaP']['validate']) : in_array($metorg->org, $permits['metaF']['validate']));
			$success = $success && $this->Dashboard_model->$func($data_id, $validVal, $validMet);
		}
		$success = ($success) ? 1:0;
		$this->session->set_flashdata('success', $success);
		redirect('validar');
	}


	public function menuConfigurar() {
		$this->load->model('Dashboard_model');
		$permits = $this->session->userdata();

		if ((count($permits['conf']['edit']) + count($permits['conf']['view'])) <= 0) {
			redirect('inicio');
		}
		$result = defaultResult($permits, $this->Dashboard_model);
		$this->load->view('menu-configurar', $result);
	}

	public function configurarMetricas() {
		$permits = $this->session->userdata();
		if ((count($permits['conf']['edit']) + count($permits['conf']['view'])) <= 0) {
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
		$result = array(
			'departments' => getAllOrgsByDpto($this->Organization_model),
			'metrics'	=> $metrics,
			'success'   => $success
		);
		$this->load->view('configurar-metricas', array_merge($result, defaultResult($permits, $this->Dashboard_model)));
	}

	public function agregarMetrica(){
		$permits = $this->session->userdata();
		if ((count($permits['conf']['edit']) + count($permits['conf']['view'])) <= 0) {
			redirect('inicio');
		}
		
		//carga de elementos
		$this->load->library('form_validation');

		//Validación de inputs
		$this->form_validation->set_rules('name', 'Nombre Métrica', 'required|alphaSpace');
		$this->form_validation->set_rules('y_unit', 'Unidad Y', 'required|alphaSpace');
		$this->form_validation->set_rules('category', 'Categoria', 'required|numeric');
		$this->form_validation->set_rules('y_name', 'Nombre Y', 'required|alphaSpace');
		$this->form_validation->set_rules('x_name', 'Nombre X', 'alphaSpace');
		$this->form_validation->set_rules('x_unit', 'Unidad X', 'alphaSpace');
		$this->form_validation->set_rules('id_insert', 'Id', 'required|numeric');

		if (!$this->form_validation->run()) {
			$this->session->set_flashdata('success', 0);
			redirect('cmetrica');
		}

		//Carga de los modelos a usar
		$this->load->model('Metrics_model');
		$this->load->model('Metorg_model');
		$this->load->model('Unit_model');

		//creación de unidad para Y
		$unit_data = array('name' => $this->input->post('y_unit'));
		$y_unit = $this->Unit_model->get_or_create($unit_data);
		$unit_data['name'] = $this->input->post('x_unit');
		$x_unit = $this->Unit_model->get_or_create($unit_data);
		if ($y_unit===false || $x_unit===false || $y_unit<0 || $x_unit<0){
			$this->session->set_flashdata('success', 0);
		}

		//Creación de métrica
		$Metricdata = array(
			'category' => $this->input->post('category'), //esto es 1 si es productividad y 2 si es finanzas. Tienes que agregar esos dos valores en la base de datos en la tabla catergory
			'name' => $this->input->post('name'),
			'y_unit' => $y_unit, //-> Busca la Unidad, si no existe la crea y entrega el id, si existe, entrega el id.
			'y_name' => $this->input->post('y_name'), //Nombre que tendrá la métrica
			'x_unit' => $x_unit, //-> Busca la Unidad, si no existe la crea y entrega el id, si existe, entrega el id.
			'x_name' => $this->input->post('x_name'), //Nombre que tendrá la métrica
		);
		$metric = $this->Metrics_model->get_or_create($Metricdata);
		if (!$metric){
			$this->session->set_flashdata('success', 0);
			redirect('cmetrica');
		}
		//Creación de link entre métrica y organización
		$metorg = array(
			'org'    => $this->input->post('id_insert'),//id de la unidad o area a la que se le quiere ingresar la metrica
			'metric' => $metric,
		);

		if ($this->Metorg_model->addMetOrg($metorg)) {
			$this->session->set_flashdata('success', 1);
		} else {
			$this->session->set_flashdata('success', 0);
		}
		redirect('cmetrica');
	}

	public function eliminarMetrica() {

		$permits = $this->session->userdata();
		if ((count($permits['conf']['edit']) + count($permits['conf']['view'])) <= 0) {
			redirect('inicio');
		}
		
		$this->load->model('Metorg_model');
		$this->load->model('Metrics_model');
		$this->load->library('form_validation');

		//Modifica valor de la métrica
		if ($this->input->post('modificar')) {
			$this->form_validation->set_rules('nameMetric', 'Nombre Métricas', 'required|alphaSpace');
			$this->form_validation->set_rules('unidad_y', 'Unidad Y', 'required|alphaSpace');
			$this->form_validation->set_rules('tipo', 'Type', 'required|numeric');
			$this->form_validation->set_rules('metrica_y', 'Metrica Y', 'required|alphaSpace');
			$this->form_validation->set_rules('id', 'Id', 'required|numeric');
			$this->form_validation->set_rules('unidad_x', 'Unidad X', 'alphaSpace');
			$this->form_validation->set_rules('metrica_x', 'Metrica X', 'alphaSpace');

			if (!$this->form_validation->run()) {
				$this->session->set_flashdata('success', 0);
				redirect('cmetrica');
			}

			$data = array(
				'metorg'     => $this->input->post('id'),
				'name' => $this->input->post('nameMetric'),
				'y_name'  => ucwords($this->input->post('metrica_y')),
				'category'      => $this->input->post('tipo'),
				'y_unit' => ucwords($this->input->post('unidad_y')),
				'x_name'  => ucwords($this->input->post('metrica_x')),
				'x_unit' => ucwords($this->input->post('unidad_x'))
			);
			if ($this->Metrics_model->updateMetric($data)) {
				$this->session->set_flashdata('success', 1);
			} else {
				$this->session->set_flashdata('success', 0);
			}
		}
		//Elimina MetOrg
		else {
			$this->form_validation->set_rules('id2', 'Id', 'required|numeric');

			if (!$this->form_validation->run()) {
				$this->session->set_flashdata('success', 0);
				redirect('cmetrica');
			}

			$data = array('id' => $this->input->post('id2'));
			if ($this->Metorg_model->delMetOrg($data)) {
				$this->session->set_flashdata('success', 1);
			} else {
				$this->session->set_flashdata('success', 0);
			}
		}
		redirect('cmetrica');
	}
}
?>
