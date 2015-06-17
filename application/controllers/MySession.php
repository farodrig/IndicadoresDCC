<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MySession extends CI_Controller {

	public function index()
	{
	    $this->load->library('session');
	    $this->load->model('User_model');
		$this->load->model('Permits_model');
	    $result = array();
	    $result['users'] = $this->User_model->getAllUsers();
        $result['error'] = $this->session->flashdata('error');        
        
	    if($this->input->method()=="post"){
	        $rut = $this->input->post('user');
	        $name = $result['users'][$rut];
    	    $this->session->set_userdata('rut', $rut);
    	    $this->session->set_userdata('name', $name);
			$permits = $this->Permits_model->getAllPermits($rut);
		    $permits_array = array(
		    					    'director' => $permits->getDirector(),
		    					    'visualizador' => $permits->getVisualizador(),
		    					    'asistente_unidad' => $permits->getAsistenteUnidad(),
		    					    'asistente_finanzas_unidad' => $permits->getAsistenteFinanzasUnidad(),
		    					    'encargado_finanzas_unidad' => $permits->getEncargadoFinanzasUnidad(),
		    					    'encargado_unidad' => $permits->getEncargadoUnidad(),
		    					    'asistente_dcc' => $permits->getAsistenteDCC());
			$title = $this->getTitle($permits_array);
			$permits_array['title'] = $title;

	    	$this->session->set_userdata($permits_array);
	        redirect('inicio');
	    }
		$this->load->view('login', $result);
	}

	public function user_verify(){
	    session_id( $_GET[ session_name() ] );
	    session_start();
	    $data = $_SESSION;
	    session_destroy();
        //Aqui se debiesen hacer validaciones para que el usuario pueda ingresar a la aplicación
	    $this->load->model('User_model');
	    if(!$this->User_model->getUserById($data['rut'])){
            $this->session->set_flashdata("error", 1);
	        redirect('');
        }
	    //Aqui se debe agregar las variables de sesion que seran consultadas a futuro en la aplicacion.
	    $this->load->library('session');
		$this->load->model('Permits_model');
	    $this->session->set_userdata('rut', $data['rut']);
	    $this->session->set_userdata('name', $data['nombre_completo']);
		$permits = $this->Permits_model->getAllPermits($data['rut']);
    	$permits_array = array(
    							'director' => $permits->getDirector(),
    							'visualizador' => $permits->getVisualizador(),
    							'asistente_unidad' => $permits->getAsistenteUnidad(),
    							'asistente_finanzas_unidad' => $permits->getAsistenteFinanzasUnidad(),
    							'encargado_finanzas_unidad' => $permits->getEncargadoFinanzasUnidad(),
    							'encargado_unidad' => $permits->getEncargadoUnidad(),
    							'asistente_dcc' => $permits->getAsistenteDCC());
		$title = $this->getTitle($permits_array);
		$permits_array['title'] = $title;

    	$this->session->set_userdata($permits_array);
	    
	    redirect('inicio');
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
    	$this->load->library('session');
    	$user = $this->session->rut;
        if(is_null($user))
            redirect('salir');

    	$this->load->model('Dashboard_model');
        $this->load->model('Organization_model');
		$permits = array(   'director' => $this->session->userdata("director"),
    						'visualizador' => $this->session->userdata("visualizador"),
    						'asistente_unidad' => $this->session->userdata("asistente_unidad"),
    						'asistente_finanzas_unidad' => $this->session->userdata("asistente_finanzas_unidad"),
							'encargado_finanzas_unidad' => $this->session->userdata("encargado_finanzas_unidad"),
    						'encargado_unidad' => $this->session->userdata("encargado_unidad"),
    						'asistente_dcc' => $this->session->userdata("asistente_dcc"),
							'title' =>$this->session->userdata("title"));
        $type = $this->input->get('sector');
		$department = $this->Organization_model->getDepartment();
		$areaunit = $this->showAreaUnit();
        $name=$type;
		$aus = $areaunit;
	    $areaunit = array();
		if(is_null($type)){
			$name="Operación";			
			foreach ($aus as $au){
		        if ($au['area']->getType()==2)
		            array_push($areaunit, $au);
			}
		}
		else{
	        $type = $this->Organization_model->getTypeByName($type);
		    foreach ($aus as $au){
		        if ($au['area']->getType()==$type['id'])
		            array_push($areaunit, $au);
		    }
		}
		$types = $this->Organization_model->getTypes();
		$result = array('department'=> $department,
						'areaunit'=>$areaunit,
						'types'=>$types,
						'name' => $name,
						'user' => $user,
						'validate' => validation($permits, $this->Dashboard_model),
						'role' => $permits['title']);
		$this->load->view('index', $result);
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
		$this->load->library('session');
		$success = $this->session->flashdata('success');
		if(is_null($success))
			$success=2;
		$permits = array('director' => $this->session->userdata("director"),
							'encargado_unidad' => $this->session->userdata("encargado_unidad"),
							'encargado_finanzas_unidad' => $this->session->userdata("encargado_finanzas_unidad"),
							'title' =>$this->session->userdata("title"));
		if(!$permits['director'] && in_array(-1, $permits['encargado_unidad']) && in_array(-1, $permits['encargado_finanzas_unidad']))
		  redirect('inicio');

		$this->load->library('session');
		$this->load->model('Dashboard_model');
		//$this->load->view('validar', $data);

		if($this->session->userdata("director")==1 ){
    	$this->load->view('validar', array('success'=> $success,'validate' => validation($permits, $this->Dashboard_model), 'role' => $this->session->userdata("title"),'data' => $this->Dashboard_model->getAllnonValidateData()));
		}
		elseif(!in_array('-1',$this->session->userdata('encargado_unidad')) && !in_array('-1',$this->session->userdata('encargado_finanzas_unidad')) ){
			$this->load->view('validar', array('success'=> $success,'validate' => validation($permits, $this->Dashboard_model), 'role' => $this->session->userdata("title"),'data' => $this->Dashboard_model->getnonValidatebyUnit($this->session->userdata('encargado_unidad'))));
		}
		elseif(!in_array('-1',$this->session->userdata('encargado_unidad'))){
			$this->load->view('validar', array('success'=> $success,'validate' => validation($permits, $this->Dashboard_model), 'role' => $this->session->userdata("title"),'data' => $this->Dashboard_model->getnonValidatebyUnitByType($this->session->userdata('encargado_unidad'), 1)));
		}
		elseif(!in_array('-1',$this->session->userdata('encargado_finanzas_unidad'))){
			$this->load->view('validar', array('success'=> $success, 'validate' => validation($permits, $this->Dashboard_model), 'role' => $this->session->userdata("title"),'data' => $this->Dashboard_model->getnonValidatebyUnitByType($this->session->userdata('encargado_finanzas_unidad'), 2)));
		}

	}

	public function validate_reject(){
		$this->load->library('session');
		$this->load->model('Dashboard_model');
		$success =2;
		if($this->input->post('Validar')){
			$data = $this->input->post();
			unset($data['Validar']);
			if(count($data) >0){
					if (!$this->checkIfAlreadyValidate($data)){
						foreach($data as $data_id){
							$this->Dashboard_model->validateData($data_id);
						}
						$success =1;
					}
					else{
						$success =0;
					}
			}
		}
		elseif($this->input->post('Rechazar')){
			$data = $this->input->post();
			unset($data['Rechazar']);
			if(count($data) >0){
					if (!$this->checkIfAlreadyValidate($data)){
						foreach($data as $data_id){
							$this->Dashboard_model->rejectData($data_id);
						}
						$success =1;
					}
					else{
						$success =0;
					}
			}
		}
		$this->session->set_flashdata('success', $success);
		//echo $this->session->flashdata('success');
		redirect('validar');}

	private function checkIfAlreadyValidate($data)
	{
		$this->load->model('Dashboard_model');
		$isValidate = FALSE;
		foreach($data as $data_id){
			$isValidate = $isValidate || $this->Dashboard_model->checkIfValidate($data_id);
		}
		return $isValidate;

	}

	public function menuConfigurar()
	{
		$this->load->library('session');
		$this->load->model('Dashboard_model');
    	$permits = array('director' => $this->session->userdata("director"),
								'title' =>$this->session->userdata("title"));

    	if(!$permits['director']){
    		redirect('inicio');
    	}

	    $this->load->view('menu-configurar', array('validate' => validation($permits, $this->Dashboard_model),
																									'role' => $permits['title']));
	}

	public function configurarMetricas()
	{
	    $this->load->model('Organization_model');
			$this->load->model('Dashboard_model');
			$this->load->library('session');
    	$permits = array('director' => $this->session->userdata("director"),
								'title' =>$this->session->userdata("title"));

    	if(!$permits['director']){
    		redirect('inicio');
    	}
		$success = $this->session->flashdata('success');
		if(is_null($success))
			$success=2;

		$this->load->model('Metrics_model');
		$metrics= $this->Metrics_model->getAllMetrics();
	    $this->load->view('configurar-metricas',array(	'departments' => getAllOrgsByDpto($this->Organization_model),
														'metrics'=>$metrics,
														'success' => $success,
														'role' => $permits['title'],
														'validate' => validation($permits, $this->Dashboard_model)));
	}

	public function agregarMetrica(){
		$this->load->library('session');
		$this->load->model('Unit_model');
		$this->load->library('form_validation');
	    $this->form_validation->set_rules('unidad_medida', 'UnidadMedida', 'required|alphaSpace');
	    $this->form_validation->set_rules('category', 'Category', 'required|numeric');
	    $this->form_validation->set_rules('name', 'Name', 'required|alphaSpace');
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
			$this->form_validation->set_rules('unidad', 'UnidadMedida', 'required|alphaSpace');
	    	$this->form_validation->set_rules('tipo', 'Type', 'required|numeric');
	    	$this->form_validation->set_rules('metrica', 'Metric', 'required|alphaSpace');
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

	private function getTitle($permits_array){
		$this->load->model("Permits_model");
		$title="";
		$count=0;
		if($permits_array['director'])
				$title= $title."Director";
		elseif($permits_array['asistente_dcc'])
				$title= $title."Asistente DCC";
		elseif(!in_array("-1", $permits_array['encargado_unidad'])){
				$title= $title.rtrim("Encargado de unidad");
		}
		elseif(!in_array("-1", $permits_array['encargado_finanzas_unidad'])){
				$title= $title.rtrim("Encargado de finanzas <br> de unidad");
		}
		elseif(!in_array("-1", $permits_array['asistente_unidad'])){
			$title= $title.rtrim("Asistente de unidad");
		}
		elseif(!in_array("-1", $permits_array['asistente_finanzas_unidad'])){
			$title= $title.rtrim("Asistente de finanzas");
		}
		elseif($permits_array['visualizador'])
				$title= $title."Visualizador";

		return $title;

	}

}
?>
