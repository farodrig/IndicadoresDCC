<?php
class Metrics_model extends CI_Model{

	public $title;
	public $MO;
	public $Unit;
	public $Category;

	public function __construct(){
		// Call the CI_Model constructor
		parent::__construct();
		$this->title = "Metric";
		$this->MO = "MetOrg";
		$this->Unit = "Unit";
		$this->Category = "Category";
	}

	function getMetric($data){
		return getGeneric($this, $this->title, $data);
	}

	function get_or_create($data){
		return get_or_create($this, $data, 'id');
	}

	function updateMetric($data){
		$this->load->model('Metorg_model');
		$this->load->model('Unit_model');

		//Obtiene el metOrg, si no existe hay un error con los datos y retorna falso
		$metorg = $this->Metorg_model->getMetOrg(array('id' =>[$data['metorg']], 'limit' => 1))[0];
		if(!isset($metorg)){
			return false;
		}
		$id_unit_y = $this->Unit_model->get_or_create(array('name'=>$data['y_unit']));
		$id_unit_x = $this->Unit_model->get_or_create(array('name'=>$data['x_unit']));

		$datos = array(
			'category'=>$data['category'],
			'y_unit'=>$id_unit_y,
			'y_name'=> $data['y_name'],
			'x_unit'=>$id_unit_x,
			'x_name'=> $data['x_name'],
		);
		$this->db->where('id', $metorg->metric);
		return $this->db->update($this->title, $datos);
	}

	function getAllMetrics(){
		$this->db->select('MetOrg.org, MetOrg.id as metorg, Metric.y_name, Metric.x_name, Category.name as category, Unit.name as unit, XUnit.name as x_unit');
		$this->db->from('Metric');
		$this->db->join('MetOrg', 'MetOrg.metric = Metric.id');
		$this->db->join('Category', 'Category.id = Metric.category');
		$this->db->join('Unit', 'Unit.id = Metric.y_unit');
		$this->db->join('Unit as XUnit', 'XUnit.id = Metric.x_unit');
		$q = $this->db->get();
		$data=[];
		if($q->num_rows() > 0){
			foreach ($q->result() as $row){
				$data[$row->org][]= array(
					'metorg' => $row->metorg,
					'y_name' => ucwords($row->y_name),
					'category' => ucwords($row->category),
					'y_unit' => ucwords($row->unit),
					'x_name' => ucwords($row->x_name),
					'x_unit' => ucwords($row->x_unit)
				);
			}
		}
		return $data;
	}

    function buildAllMetric($q){
        $me = array();
        foreach ($q->result() as $row){
            array_push($me, $this->buildMetric($row));
        }
        return $me;
    }

    function buildMetric($row){
        $this->load->library('Metrics_library');
        $parameters = array(
            'id' => $row->id,
            'category' => $row->category,
            'unit' => $row->unit,
            'name' => $row->name
        );
        $me = new Metrics_library();
        return $me->initialize($parameters);
    }
}
