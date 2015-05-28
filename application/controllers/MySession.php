<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MySession extends CI_Controller {

	public function index()
	{
		$this->load->view('login');
	}

	public function logout(){
	    $this->load->library('session');
	    $this->session->sess_destroy();
	    redirect('');
	}

	public function contact(){
	    $work = true;
	    if($this->input->method()=="post"){

	        $this->load->library('email');

	        $this->email->from($this->input->post('email'), $this->input->post('name'));
	        $this->email->to('NoMandaMail@gmail.com');

	        $this->email->subject($this->input->post('topic'));
	        $this->email->message($this->input->post('message'));

	        if (! $this->email->send()){
	            $work = false;
	        }
	        else{
	            redirect('inicio');
	        }
	    }

	    $this->load->view('contact', array('work'=>$work));
	}

    public function inicio(){

    	$user= "17.586.757-0"; // usuario tipo Visualizador
    	//$user= "18.292.316-8"; // usuario tipo Administrador
    	//$user = "20.584.236-5"; // usuario tipo Visualizador
    	$this->load->library('session');
    	$this->load->model('Dashboard_model');

    	$this->load->model('Permits_model');
    	$permits = $this->Permits_model->getAllPermits($user);

    	$permits_array = array('user' => $user,
    							'director' => $permits->getDirector(),
    							'visualizador' => $permits->getVisualizador(),
    							'asistente_unidad' => $permits->getAsistenteUnidad(),
    							'asistente_finanzas_unidad' => $permits->getAsistenteFinanzasUnidad(),
    							'encargado_unidad' => $permits->getEncargadoUnidad(),
    							'asistente_dcc' => $permits->getAsistenteDCC());

    	if($permits_array['director'])
    		$validate = $this->Dashboard_model->getValidate(-1);
    	elseif(!in_array(-1,$permits_array['encargado_unidad']))
    		$validate = $this->Dashboard_model->getValidate($permits_array['encargado_unidad']);
    	else
    		$validate = "";

    	$permits_array['validate']=$validate;

    	$this->session->set_userdata($permits_array);

        $this->load->model('Organization_model');
        $type = $this->input->get('sector');
		$department = $this->Organization_model->getDepartment();
		$areaunit = $this->showAreaUnit();
		if(is_null($type)){
			$type="Operación";
			$name=$type;
			$aus = $areaunit;
		    $areaunit = array();
    		foreach ($aus as $au){
    		    if ($au['area']->getType()==2)
    		        array_push($areaunit, $au);
    		}
		}
		else{
			$name = $type;
		    $type = $this->Organization_model->getTypeByName($type);
    		$aus = $areaunit;
		    $areaunit = array();
    		foreach ($aus as $au){
    		    if ($au['area']->getType()==$type['id'])
    		        array_push($areaunit, $au);
    		}
		}
		$types = $this->Organization_model->getTypes();
		//Colocar permisos de mayor a menor
		if($permits->getDirector()){
	    	$this->load->view('index', array('department'=> $department,
	        	                             'areaunit'=>$areaunit,
	            	                         'types'=>$types,
	                	                     'name' => $name,
	                	                     'user' => $user,
	                	                     'validate' => $validate));
		}
		elseif(!in_array("-1", $permits->getEncargadoUnidad())){
			$this->load->view('indexEncargado', array('department'=> $department,
	        	                             			'areaunit'=>$areaunit,
	            	                         			'types'=>$types,
	                	                     			'name' => $name,
	                	                     			'user' => $user,
	                	                     			'validate' => $validate));

		}
		elseif(!in_array("-1", $permits->getAsistenteUnidad()) || $permits->getVisualizador()){
			$this->load->view('indexVisualizador', array('department'=> $department,
	        	                             			'areaunit'=>$areaunit,
	            	                         			'types'=>$types,
	                	                     			'name' => $name,
	                	                     			'user' => $user,
	                	                     			'validate' => $validate));
		}
	}



	private function getDepartment(){
			$this->load->model('Organization_model');
			$department = $this->Organization_model->getDepartment();
			return $department;

	}

	private function getType(){
			$this->load->model('Organization_model');
			$types = $this->Organization_model->getTypes();
			return $types;
	}


	private function showAreaUnit(){

		$this->load->model('Organization_model');
	    $department = $this->Organization_model->getDepartment();
	    $areaunit = array();
	    $areas = $this->Organization_model->getAllAreas();
	    foreach ($areas as $area){
	        array_push($areaunit, array('area' => $area,
	                                    'unidades' => $this->Organization_model->getAllUnidades($area->getId()))
	        );
	    }
		return $areaunit;
	}

	public function dashboard()
	{

		$id_unidad = $this->input->post("unidad");
	    $this->session->set_flashdata("unidad", $id_unidad);
	}

	public function validar()
	{
	    $this->load->view('validar', array('validate' => "1", ));
	}

	public function menuConfigurar()
	{
		$this->load->library('session');
		$user = $this->session->userdata("user");
    	$permits = array('director' => $this->session->userdata("director"),
    						'visualizador' => $this->session->userdata("visualizador"),
    						'asistente_unidad' => $this->session->userdata("asistente_unidad"),
    						'asistente_finanzas_unidad' => $this->session->userdata("asistente_finanzas_unidad"),
    						'encargado_unidad' => $this->session->userdata("encargado_unidad"),
    						'asistente_dcc' => $this->session->userdata("asistente_dcc"),
    						'validate' => $this->session->userdata("validate"));

    	if(!$permits['director']){
    		redirect('inicio');
    	}

	    $this->load->view('menu-configurar', array('validate' => $permits['validate']));
	}

	public function agregarDato()
	{
	    $this->load->view('add-data');
	}

	public function configurarMetricas()
	{
		$this->load->library('session');
		$user = $this->session->userdata("user");
    	$permits = array('director' => $this->session->userdata("director"),
    						'visualizador' => $this->session->userdata("visualizador"),
    						'asistente_unidad' => $this->session->userdata("asistente_unidad"),
    						'asistente_finanzas_unidad' => $this->session->userdata("asistente_finanzas_unidad"),
    						'encargado_unidad' => $this->session->userdata("encargado_unidad"),
    						'asistente_dcc' => $this->session->userdata("asistente_dcc"),
    						'validate' => $this->session->userdata("validate"));

    	if(!$permits['director']){
    		redirect('inicio');
    	}
		$success = $this->session->flashdata('success');
		if(is_null($success))
			$success=2;

		$department = $this->getDepartment();
		$areaunit = $this->showAreaUnit();
		$types = $this->getType();
		$this->load->model('Metrics_model');
		$metrics= $this->Metrics_model->getAllMetrics();
	    $this->load->view('configurar-metricas',array('department'=> $department,
	                                     				'areaunit'=>$areaunit,
	                                     				'types'=>$types,
														'metrics'=>$metrics,
														'success' => $success,
														'validate' => $permits['validate']));
	}

	public function agregarMetrica(){
		$this->load->library('session');
		$this->load->model('Unit_model');
		$this->load->library('form_validation');
	    $this->form_validation->set_rules('unidad_medida', 'UnidadMedida', 'required|callback_alphaSpace');
	    $this->form_validation->set_rules('category', 'Category', 'required|numeric');
	    $this->form_validation->set_rules('name', 'Name', 'required|callback_alphaSpace');
	    $this->form_validation->set_rules('id_insert', 'Id', 'required|numeric');

	    if(!$this->form_validation->run()){
				$this->session->set_flashdata('success',0);
				redirect('cmetrica');
		}

	   	$Unit = array(
				'name' => ucwords($this->input->post('unidad_medida')),
		);

		$Metricdata= array(
			'category' => $this->input->post('category'), //esto es 1 si es productividad y 2 si es finanzas. Tienes que agregar esos dos valores en la base de datos
														  // en la tabla catergory
			'unit' =>  $this->Unit_model->checkName($Unit), //-> primero revisa si hay unidad de medida en la base de datos con ese nombre, si existe toma
																	// el id correspondiente y le asocias a la metrica ese id ,si no agrega la unidad, obten
																	// el nuevo id y se lo asocias a la metrica
			'name' => ucwords($this->input->post('name')), //Nombre que tendrá la métrica//id de la unidad o area a la que se le quiere ingresar la metrica
		);
     	$this->load->model('Metrics_model');
   	 	$id_metric= $this->Metrics_model->addMetric($Metricdata);
		$this->load->model('Metorg_model');
		$metorg = array(
        	'org' => $this->input->post('id_insert'),
        	'metric' =>$id_metric
			);

		if($this->Metorg_model->addMetOrg($metorg))
			$this->session->set_flashdata('success',1);
		else
			$this->session->set_flashdata('success',0);

 	   	redirect('cmetrica');
	}

	public function eliminarMetrica(){

		$this->load->model('Metrics_model');
		$this->load->library('form_validation');
		$this->load->library('session');

		if($this->input->post('modificar')){
			$this->form_validation->set_rules('unidad', 'UnidadMedida', 'required|callback_alphaSpace');
	    	$this->form_validation->set_rules('tipo', 'Type', 'required|numeric');
	    	$this->form_validation->set_rules('metrica', 'Metric', 'required|callback_alphaSpace');
	    	$this->form_validation->set_rules('id', 'Id', 'required|numeric');

	    	if(!$this->form_validation->run()){
					$this->session->set_flashdata('success',0);
					redirect('cmetrica');
			}

			$data= array(
				'id_metorg' => $this->input->post('id'),
				'name_metrica' => ucwords($this->input->post('metrica')),
				'category' => $this->input->post('tipo'),
				'unidad_medida' => ucwords($this->input->post('unidad'))
			);
			if($this->Metrics_model->updateMetric($data))
				$this->session->set_flashdata('success',1);
			else
				$this->session->set_flashdata('success',0);
		}
		else{
			$this->form_validation->set_rules('id2', 'Id', 'required|numeric');

	    	if(!$this->form_validation->run()){
					$this->session->set_flashdata('success',0);
					redirect('cmetrica');
				}

			$data = array('id_metorg' => $this->input->post('id2'));
			if($this->Metrics_model->deleteMetric($data))
				$this->session->set_flashdata('success',1);
			else
				$this->session->set_flashdata('success',0);
		}

    	redirect('cmetrica');
	}

	public function alphaSpace($str){
	    if (preg_match("^([a-zA-Zñáéíóú]\s?)+^", $str, $data) && $data[0]==$str){
	        return true;
	    }
	    else{
	        $this->form_validation->set_message('alphaSpace', 'El campo {field} contiene caracteres no alfabeticos o espacios');
	        return false;
	    }
	}
}
?>
