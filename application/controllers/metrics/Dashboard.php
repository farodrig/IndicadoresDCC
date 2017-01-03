<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');}

class Dashboard extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->library('form_validation');
        $this->load->model('Dashboard_model');
		$this->load->model('Values_model');
		$this->load->model('Metorg_model');
		$this->load->model('Metrics_model');
		if (is_null($this->session->rut))
			redirect('salir');
	}

    //función que permite mostrar un dashboard. Se recibe el id de la organizacion correspondiente
    // y a partir de eso se obtienen las métricas y mediciones asociadas
    function index(){
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
        foreach ($this->Dashboard_model->getAggregationType([]) as $type){
            $aggregation[$type->id] = $type;
        }
        $result = defaultResult($permits, $this->Values_model);
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
        $graphics = $this->Dashboard_model->getAllGraphicByOrg($org, $all);
        $aux_graphs = [];
        foreach ($graphics as $graphic){
            $graph = $this->Dashboard_model->getGraphicData($graphic->id);
            if (!count($graph->series))
            	continue;
            $aux_graphs[] = $graph;
        }
        return $aux_graphs;
    }

	//Función para exportar datos de tabla al lado de los gráficos en archivo csv
	function exportData() {
		function build_sorter($key) {
			return function ($a, $b) use ($key) {
				return strnatcmp($a->$key, $b->$key);
			};
		}

        $permits = $this->session->userdata();
        $prod  = array_merge($permits['foda']['view'], $permits['metaP']['view']);
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
}
