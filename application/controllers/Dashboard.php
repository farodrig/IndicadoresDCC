<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');}

class Dashboard extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->model('Dashboard_model');
		$this->load->model('Dashboardconfig_model');
		$this->load->model('Metorg_model');
		$this->load->model('Metrics_model');
		if (is_null($this->session->rut))
			redirect('salir');
	}

    //Función que genera un arreglo de dos arreglos. El primero contiene todas las mediciones
    //que se reciben en el argumento $m. Y el segundo contiene todos los años que presentan mediciones
    function _parseMeasurements($measures) {
        $result = array();
        if($measures) {
            foreach ($measures as $measure) {
                $data['id'] = $measure->getIdMeasurement();
                $data['metorg'] = $measure->getMetOrg();
                $data['valueY'] = $measure->getValueY();
                $data['valueX'] = $measure->getValueX();
                $data['target'] = $measure->getTarget();
                $data['expected'] = $measure->getExpected();
                $data['year'] = $measure->getYear();

                $result[$data['metorg']][$data['year']][] = $data;
            }
        }
        return $result;
    }

    // funcion que lista todas las metricas y las deja como objeto cada una por lo tanto se puede recorrer el arreglo
    // y llamar a cada valor del arreglo como liberia ejemplo mas abajo
    // esto sirve para cuando se llama de una vista para completar por ejemplo una tabla
	function formAddData(){
		$org= $this->input->get('org');//Es el id de área, unidad, etc que se este considerando
		if (is_null($org))
            redirect('inicio');

        $permits = $this->session->userdata();
        $prod = in_array($org, $permits['foda']['edit']) || in_array($org, $permits['metaP']['edit']);
        $finan = in_array($org, $permits['valorF']['edit']) || in_array($org, $permits['metaF']['edit']);

		//Se obtienen las metricas correspondientes a los permisos del usuario, junto con las mediciones correspondientes
		if ($prod && $finan){
			$cat = 0;
		} elseif ($prod) {
			$cat = 1;
		} elseif ($finan) {
			$cat = 2;
		} else {
			redirect('inicio');
		}
		$all_metrics      = $this->Dashboard_model->getAllMetrics($org, $cat, 0);
		$all_metrics = !$all_metrics ? [] : $all_metrics;
        $metrics = [];
		foreach ($all_metrics as $metric){
			$metric->x_values = $this->Dashboard_model->getAllXValuesByMetorg($metric->metorg);
            $metrics[$metric->metorg] = $metric;
		}
		$all_measurements = $this->Dashboard_model->getAllMeasurements($org, $cat);
		$res = defaultResult($permits, $this->Dashboard_model);
        $res['editP'] = in_array($org, $permits['foda']['edit']);
        $res['editF'] = in_array($org, $permits['valorF']['edit']);
        $res['editMetaP'] = in_array($org, $permits['metaP']['edit']);
        $res['editMetaF'] = in_array($org, $permits['metaF']['edit']);
		$res['measurements'] = $this->_parseMeasurements($all_measurements);
		$res['metrics']      = $metrics;
		$res['route']        = getRoute($this, $org);
		$res['org']  = $org;
		$res['success']      = $this->session->flashdata('success') === null ? 2 : $this->session->flashdata('success');
		$this->load->view('add-data', $res);
	}

	//Función encargada de llamar a las funciones correspondientes del modelo, para poder actualizar valores en la base de datos
	function addData() {
		$org = $this->input->post("org");
		if (is_null($org)) {
            redirect('inicio');
        }

        $permits = $this->session->userdata();
        if (!(in_array($org, $permits['foda']['edit']) || in_array($org, $permits['metaP']['edit']) || in_array($org, $permits['valorF']['edit']) || in_array($org, $permits['metaF']['edit']))) {
            $this->session->set_flashdata('success', 0);
            redirect('formAgregarDato?org='.$org);
        }

		//Se validan los datos ingresados
		$this->form_validation->set_rules('year', 'Year', 'required|exact_length[4]|numeric');
		$this->form_validation->set_rules('metorg[]', 'Value', 'numeric');

		if (!$this->form_validation->run()) {
			$this->session->set_flashdata('success', 0);
            redirect('formAgregarDato?org='.$org);
        }

        $borrar = ($this->input->post('borrar')) ? 1 : 0;
        $user    = $this->session->userdata("rut");
		$year = $this->input->post('year');
		$success = true;

		if($borrar){
			foreach ($this->input->post('delete') as $valId){
				if(!$valId)
					continue;
                $metorg = $this->Metorg_model->getMetOrgDataByValue($valId);
                $validation = ($metorg->category==1 ? in_array($org, $permits['metaP']['validate']) : in_array($org, $permits['metaF']['validate']));
				$success = $success && $this->Dashboard_model->deleteValue($valId, $user, $validation);
			}
			$success = ($success) ? 1:0;
			$this->session->set_flashdata('success', $success);
            redirect('formAgregarDato?org='.$org);
        }
		for($i = 0; $i<count($this->input->post('metorg')); $i++){
			$id_metorg = $this->input->post('metorg')[$i];
            $metorg = $this->Metorg_model->getMetOrg(array('id'=>[$id_metorg]))[0];
            $metric = $this->Metrics_model->getMetric(array('id'=>[$metorg->metric]))[0];
            $valId = $this->input->post('valId')[$i];
            $valueY = (strcmp($this->input->post('valueY')[$i], "null")==0 ? null : $this->input->post('valueY')[$i]);
            $valueX = (strcmp($this->input->post('valueX')[$i], "null")==0 ? null : $this->input->post('valueX')[$i]);
            $target = (strcmp($this->input->post('target')[$i], "null")==0 ? null : $this->input->post('target')[$i]);
            $expected = (strcmp($this->input->post('expected')[$i], "null")==0 ? null : $this->input->post('expected')[$i]);

            if($metric->category==1){
                $validValue = in_array($org, $permits['foda']['validate']);
                $validMeta = in_array($org, $permits['metaP']['validate']);
                if(!in_array($org, $permits['foda']['edit'])){
                    $valueY = null;
                }
                if(!in_array($org, $permits['metaP']['edit'])){
					$valueX = null;
                    $target = null;
                    $expected = null;
                }
            }
            else{
                $validValue = in_array($org, $permits['valorF']['validate']);
                $validMeta = in_array($org, $permits['metaF']['validate']);
                if(!in_array($org, $permits['valorF']['edit'])){
                    $valueY = null;
                }
                if(!in_array($org, $permits['metaF']['edit'])){
					$valueX = null;
                    $target = null;
                    $expected = null;
                }
            }
            if((!is_null($valueY) && !is_numeric($valueY)) || (!is_null($target) && !is_numeric($target)) || (!is_null($expected) && !is_numeric($expected))){
                continue;
            }
			//si no hay valores saltamos los datos
			if ((strcmp($valueY, "")==0 || (is_null($valueY))) && (strcmp($valueX, "")==0 || is_null($valueX)) && (strcmp($expected, "")==0 ||  is_null($expected)) && (strcmp($target, "")==0 || is_null($target))) {
				$success = false;
				continue;
			}

			//si no se tiene un id para el valor
			if (!$valId){
                if (strcmp($metric->x_name, "")==0)
                    $valueX = "";
                elseif (is_null($valueX)){
                    continue;
                }
				$oldVal = $this->Dashboard_model->getValue(array('metorg'=>[$id_metorg], 'year'=>[$year], 'x_value'=>[$valueX], 'state'=>[1]));
				//Si existia un valor previo, se debe hacer un update de la data, sino, simplemente se inserta.
				if (count($oldVal)==1){
					$oldVal = $oldVal[0];
					if ($valueY != $oldVal->value || $expected != $oldVal->expected || $target != $oldVal->target) {
                        if ($validMeta && $validValue)
                            $success = $success && $this->Dashboard_model->updateData($id_metorg, $year, $oldVal->x_value, $valueY, $valueX, $target, $expected, $user, 1);
                        elseif ($validMeta){
                            $success = $success && $this->Dashboard_model->updateData($id_metorg, $year, $oldVal->x_value, null, $valueX, $target, $expected, $user, 1);
                            $success = $success && $this->Dashboard_model->updateData($id_metorg, $year, $oldVal->x_value, $valueY, null, null, null, $user, 0);
                        }
                        elseif ($validValue){
                            $success = $success && $this->Dashboard_model->updateData($id_metorg, $year, $oldVal->x_value, $valueY, null, null, null, $user, 1);
                            $success = $success && $this->Dashboard_model->updateData($id_metorg, $year, $oldVal->x_value, null, $valueX, $target, $expected, $user, 0);
                        }
                        else
                            $success = $success && $this->Dashboard_model->updateData($id_metorg, $year, $oldVal->x_value, $valueY, $valueX, $target, $expected, $user, 0);
                    }
				}
				else if (count($oldVal)==0){
                    if ($validMeta && $validValue)
                        $success = $success && $this->Dashboard_model->insertData($id_metorg, $year, $valueY, $valueX, $target, $expected, $user, 1);
                    elseif ($validMeta){
                        $success = $success && $this->Dashboard_model->insertData($id_metorg, $year, null, $valueX, $target, $expected, $user, 1);
                        $success = $success && $this->Dashboard_model->insertData($id_metorg, $year, $valueY, null, null, null, $user, 0);
                    }
                    elseif ($validValue){
                        $success = $success && $this->Dashboard_model->insertData($id_metorg, $year, $valueY, null, null, null, $user, 1);
                        $success = $success && $this->Dashboard_model->insertData($id_metorg, $year, null, $valueX, $target, $expected, $user, 0);
                    }
                    else
                        $success = $success && $this->Dashboard_model->insertData($id_metorg, $year, $valueY, $valueX, $target, $expected, $user, 0);
				}
				//hubo un error, nunca debería haber más de 1 validado.
				else{
					$success = false;
					debug($oldVal);
				}
			}
			//si se tiene valId, ya existia el valor por tanto se debe actualizar
			else if($valId){
				$oldVal = $this->Dashboard_model->getValue(array('id'=>[$valId]))[0];
				if ($valueY != $oldVal->value || strcmp($valueX, $oldVal->x_value)!=0 || $expected != $oldVal->expected || $target != $oldVal->target) {
					if ($validMeta && $validValue)
						$success = $success && $this->Dashboard_model->updateData($id_metorg, $year, $oldVal->x_value, $valueY, $valueX, $target, $expected, $user, 1);
					elseif ($validMeta){
						$success = $success && $this->Dashboard_model->updateData($id_metorg, $year, $oldVal->x_value, null, $valueX, $target, $expected, $user, 1);
						$success = $success && $this->Dashboard_model->updateData($id_metorg, $year, $oldVal->x_value, $valueY, null, null, null, $user, 0);
					}
					elseif ($validValue){
						$success = $success && $this->Dashboard_model->updateData($id_metorg, $year, $oldVal->x_value, $valueY, null, null, null, $user, 1);
						$success = $success && $this->Dashboard_model->updateData($id_metorg, $year, $oldVal->x_value, null, $valueX, $target, $expected, $user, 0);
					}
					else
						$success = $success && $this->Dashboard_model->updateData($id_metorg, $year, $oldVal->x_value, $valueY, $valueX, $target, $expected, $user, 0);
				}
			}
			else{
				$success = false;
			}
		}
		$success = ($success) ? 1:0;
		$this->session->set_flashdata('success', $success);
        redirect('formAgregarDato?org='.$org);
    }

	//Función para exportar datos de tabla al lado de los gráficos en archivo csv
	function exportData() {
		function build_sorter($key) {
			return function ($a, $b) use ($key) {
				return strnatcmp($a->$key, $b->$key);
			};
		}

        $permits = $this->session->userdata();
        $prod = count($permits['foda']['view'], $permits['metaP']['view']);
        $finan = array_merge($permits['valorF']['view'], $permits['metaF']['view']);
        if ((count($prod) + count($finan)) <= 0)
            redirect('inicio');

		$this->form_validation->set_rules('graphic', 'Gráfico', 'required|numeric|greater_than_equal_to[0]');
		$this->form_validation->set_rules('all', 'Todo', 'required|numeric|in_list[0,1]');

		if (!$this->form_validation->run()) {
			redirect('inicio');
		}

		$graphic = $this->input->post('graphic');
		$all = (strcmp($this->input->post('all'), "1")==0 ? true : false);

		$graphic = ($all ? $this->Dashboard_model->getAllGraphicData($graphic) : $this->Dashboard_model->getGraphicData($graphic));
		$key = ($all ? 'year' : 'x');
		$data = [];
		$metric = (object) ["x_name"=>"Año"];
		foreach ($graphic->series as $serie){
			$prename = ($all || strcmp($serie->aggregation,"")==0 ? "" : $serie->aggregation . " de ");
			$metorg = $this->Metorg_model->getMetOrg(['id'=>[$serie->metorg]])[0];
			$metric = $this->Metrics_model->getMetric(['id'=>[$metorg->metric]])[0];
			foreach ($serie->values as $value) {
				if(!key_exists('target', $value))
					continue;
				$value->metric =  $prename . $serie->name . " de " . $serie->org;
				$data[] = $value;
			}
		}
		$graphic->x_name = ($all ? $metric->x_name : $graphic->x_name);
		$title = $graphic->title." Periodo (".$graphic->min_year." - ".$graphic->max_year.")";
		usort($data, build_sorter($key));

		download_send_headers(str_replace(" ", "_", $title)."_".date("d-m-Y").".csv");

		echo(array2csv($data,$title, $graphic->x_name, $graphic->y_name, $all));
		return;
	}
	
	//función que permite mostrar un dashboard. Se recibe el id de la organizacion correspondiente
	// y a partir de eso se obtienen las métricas y mediciones asociadas
	function showDashboard(){
		$org      = $this->input->get("org");//$this->input->post("direccion");//Se recibe por POST, es el id de área, unidad, etc que se este considerando
		if(is_null($org))
			redirect('inicio');

		$permits = $this->session->userdata();
		$show_all = $this->input->get('all');
		$prod = array_merge($permits['foda']['edit'], $permits['metaP']['edit']);
		$finan = array_merge($permits['valorF']['edit'], $permits['metaF']['edit']);
        if (count($permits['foda']['view']) + count($permits['metaP']['view']) + count($permits['valorF']['view']) + count($permits['metaF']['view']) <= 0)
            redirect('inicio');
		//Permite ver si se esta en la pantalla de mostrar todos los gráficos o no
        $show_all = (is_null($show_all) ? 0 : $show_all);

		$add = (count($prod) + count($finan)) > 0;

		$graphics = $this->getAllDashboardData($org, $show_all);
		$show_button = ($show_all) ? true : $this->Dashboard_model->showButton($org);
		$aggregation = [];
		foreach ($this->Dashboardconfig_model->getAggregationType([]) as $type){
			$aggregation[$type->id] = $type;
		}
		$result = defaultResult($permits, $this->Dashboard_model);
		$result['add_data'] = $add;
		$result['show_all'] = $show_all;
		$result['show_button'] = $show_button;
		$result['aggregation'] = $aggregation;
		$result['route'] = getRoute($this, $org);
		$result['graphics'] = $graphics;
		$result['org'] = $org;
		$this->load->view('dashboard', $result);
	}

	private function getAllDashboardData($org, $all){
		$graphics = $this->Dashboardconfig_model->getAllGraphicByOrg($org, $all);
		$aux_graphs = [];
		foreach ($graphics as $graphic){
			$aux_graphs[] = $this->Dashboard_model->getGraphicData($graphic->id);
		}
		return $aux_graphs;
	}
}
