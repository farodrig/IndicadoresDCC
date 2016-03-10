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

	function get_or_create($data){
		return get_or_create($this, $data, 'id');
	}

	function updateMetric($data){
		$this->load->model('Metorg_model');
		$this->load->model('Unit_model');

		$metorg = $this->Metorg_model->getMetOrg(array('id' =>[$data['id_metorg']], 'limit' => 1))[0];
		if(isset($metorg)){
			$metric_id = $metorg->metric;
			$id_unidad = $this->Unit_model->get_or_create(array('name'=>$data['unidad_medida']));

			$datos = array(
				'category'=>$data['category'],
				'unit'=>$id_unidad,
				'name'=> $data['name_metrica']
			);
			$this->db->where('id', $metric_id);
			return $this->db->update($this->title, $datos);
		}
		return false;
	}

	function getAllMetrics(){
		$this->db->select('MetOrg.org, MetOrg.id as metorg, Metric.name, Category.name as category, Unit.name as unit');
		$this->db->from('Metric');
		$this->db->join('MetOrg', 'MetOrg.metric = Metric.id');
		$this->db->join('Category', 'Category.id = Metric.category');
		$this->db->join('Unit', 'Unit.id = Metric.unit');
		$q = $this->db->get();
		$data=[];
		if($q->num_rows() > 0){
			foreach ($q->result() as $row){
				$data[$row->org][]= array(
					'metorg' => $row->metorg,
					'name' => ucwords($row->name),
					'category' => ucwords($row->category),
					'unit' => ucwords($row->unit)
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
