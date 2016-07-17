<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Validation extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->model('Dashboard_model');
		$this->load->model('Values_model');
	}

	public function index() {
		if (is_null($this->session->rut))
			redirect('salir');
		$permits = $this->session->userdata();
		$prod = array_unique(array_merge($permits['foda']['validate'], $permits['metaP']['validate']));
		$finan = array_unique(array_merge($permits['valorF']['validate'], $permits['metaF']['validate']));
		if (!count($prod) && !count($finan)) {
			redirect('inicio');
		}
		$this->load->model('Metrics_model');
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
		foreach ($orgs as $org) {
			$data[$org]['org'] = $this->Organization_model->getByID($org);
			$metrics = $this->Metrics_model->getAllMetricsByOrg($org, $category, 1);
			$metrics = (!$metrics) ? [] : $metrics;
			foreach ($metrics as $metric) {
				$perm['valor'] = ($metric->category == 1 ? in_array($org, $permits['foda']['validate']) : in_array($org, $permits['valorF']['validate']));
				$perm['meta'] = ($metric->category == 1 ? in_array($org, $permits['metaP']['validate']) : in_array($org, $permits['metaF']['validate']));
				$data[$org]['metorg'][$metric->metorg]['permits'] = $perm;
				$data[$org]['metorg'][$metric->metorg]['metric'] = $metric;
				$values = getGeneric($this->Dashboard_model, $this->Dashboard_model->value, array('metorg' => [$metric->metorg], 'state' => [0, -1], 'order' => [['year', 'ASC'], ['x_value', 'ASC'], ['proposed_x_value', 'ASC']]));
				$data[$org]['metorg'][$metric->metorg]['values'] = $values;
				for ($i = 0; $i < count($values); $i++) {
					$value = $values[$i];
					//quitar el valor si este se eliminara y no se tiene permiso para validar la eliminacion
					//o si el elemento se modificara y no se tienen los permisos para el valor
					if (($value->state == -1 && !$perm['meta']) || ($value->state == 0 && ((!$value->proposed_value && $perm['valor'] && !$perm['meta']) || (!$value->proposed_expected && !$value->proposed_target && !$value->proposed_x_value && !$perm['valor'] && $perm['meta'])))) {
						unset($data[$org]['metorg'][$metric->metorg]['values'][$i]);
					}
				}
				if (!count($data[$org]['metorg'][$metric->metorg]['values']))
					unset($data[$org]['metorg'][$metric->metorg]);
			}
			if (!key_exists('metorg', $data[$org]) || !count($data[$org]['metorg']))
				unset($data[$org]);
		}
		$users = [];
		foreach ($this->User_model->getAllUsers() as $user){
			$users[$user->id] = $user->name;
		}
		$result = defaultResult($permits, $this->Values_model);
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

		$this->load->model('Metorg_model');
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

        $this->load->library('form_validation');

		$success = true;
		foreach ($data as $data_id) {
			if(!$this->Values_model->checkIfValidate($data_id))
				continue;
			$metorg = $this->Metorg_model->getMetOrgDataByValue($data_id);
			$validVal = ($metorg->category==1 ? in_array($metorg->org, $permits['foda']['validate']) : in_array($metorg->org, $permits['valorF']['validate']));
			$validMet = ($metorg->category==1 ? in_array($metorg->org, $permits['metaP']['validate']) : in_array($metorg->org, $permits['metaF']['validate']));
			$success = $success && $this->Values_model->$func($data_id, $validVal, $validMet);
		}
		$success = ($success) ? 1:0;
		$this->session->set_flashdata('success', $success);
		redirect('validar');
	}
}
?>
