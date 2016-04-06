<?php
class Dashboardconfig_model extends CI_Model{

	public $serie;
	public $type;
	public $graphic;
	public $aggregation;

	public function __construct(){
		parent::__construct();
		$this->serie = "Serie";
		$this->type = "Serie_Type";
		$this->graphic = "Graphic";
		$this->aggregation = "Aggregation_Type";
	}

	function getSerieType($data){
		return getGeneric($this, $this->type, $data);
	}
	
	function getAggregationType($data){
		return getGeneric($this, $this->aggregation, $data);
	}

	function getMinMaxYears($id,$id_org){
		$query = "SELECT g.id AS graph, g.type AS type, g.min_year AS min_year, g.max_year AS max_year, g.position AS position
			FROM Dashboard AS d, GraphDash as gd, Graphic AS g
			WHERE d.org=? AND gd.dashboard=d.id AND g.id=gd.graphic AND g.metorg=?";

		$q = $this->db->query($query, array($id_org, $id));
		if($q->num_rows() > 0){
			$graph = $q->result()[0]; //toda info de grafico para la metrica en el dashboard, deberia haber un resultado

			return array(
					'id' => $graph->graph,
					'min' => $graph->min_year,
					'max' => $graph->max_year,
					'type' => $graph->type,
					'checked' => $graph->position
					);
		}

		else{
			$query = "SELECT d.id AS id FROM Dashboard AS d WHERE d.org=".$id_org;
			$q = $this->db->query($query);
			if($q->num_rows() > 0)
				$id_dash = $q->result()[0];


			$query = "SELECT year FROM Value WHERE metorg=".$id;
			$q = $this->db->query($query);
			if($q->num_rows() > 0){
				foreach ($q->result() as $row){
					$years[]=$row->year;
				}
				return array(
					'id' => -1,
					'min' => min($years),
					'max' => max($years),
					'type' => 2,
					'checked' => 0
				);
			}
			$current_year = intval(date("Y"));
			return array(
				'id' => -1,
				'min' => $current_year-10, //Si no hay medidas escribimos los ultimos 10 años
				'max' => $current_year,
				'type' => 2,
				'checked' => 0
			);
		}
	}

	function getAllMetricsUnidades(){
		$this->load->model('Organization_model');
		$areas = $this->Organization_model->getAllAreas();
		if(count($areas) <= 0)
			return false;

		$result=[];
		foreach ($areas as $a) {
			$unidades = $this->Organization_model->getAllUnidades($a->getId());
			if(count($unidades) <= 0)
				continue;

			foreach ($unidades as $u) { //Sacamos las metricas para cada unidad

				$this->db->select('MetOrg.id, Metric.y_name, Metric.x_name');
				$this->db->from('Metric');
				$this->db->join('MetOrg', 'MetOrg.metric = Metric.id');
				$this->db->where('MetOrg.org', $u->getId());
				$q = $this->db->get();

				if($q->num_rows() <= 0)
					continue;

				$mets_unidad=[];
				foreach ($q->result() as $met) {
					$mets_unidad[]=array(
						'metorg' => $met->id,
						'name' => $met->y_name
					);
				}
				$result[$u->getId()] = $mets_unidad;
			}
		}
		return $result;
	}

	function getAllMetricsArea(){
		$this->load->model('Organization_model');
		$areas = $this->Organization_model->getAllAreas();
		if(count($areas) <= 0)
			return false;

		$result=[];
		foreach ($areas as $a) {

			$this->db->select('MetOrg.id, Metric.y_name, Metric.x_name');
			$this->db->from('Metric');
			$this->db->join('MetOrg', 'MetOrg.metric = Metric.id');
			$this->db->where('MetOrg.org', $a->getId());
			$q = $this->db->get();
			if($q->num_rows() > 0){
				foreach ($q->result() as $met) {
					$mets_area[]=array(
						'metorg' => $met->id,
						'name' => $met->y_name
					);
				}
				$result[$a->getId()] = $mets_area;
			}

			$unidades = $this->Organization_model->getAllUnidades($a->getId());
			if(count($unidades) <= 0)
				continue;

			foreach ($unidades as $u) { //Sacamos las metricas para cada unidad
				$this->db->select('MetOrg.id, Metric.y_name, Metric.x_name');
				$this->db->from('Metric');
				$this->db->join('MetOrg', 'MetOrg.metric = Metric.id');
				$this->db->where('MetOrg.org', $u->getId());
				$q = $this->db->get();

				if($q->num_rows() <= 0)
					continue;

				$mets_unidad=[];
				foreach ($q->result() as $met) {
					$mets_unidad[]=array(
						'metorg' => $met->id,
						'name' => $met->y_name
					);
				}
				$result[$u->getId()] = $mets_unidad;
			}
		}
		return $result;
	}

	function getAllMetricsDCC(){
		$metrics = $this->getAllMetricsArea();

		if(!$metrics)
			$metrics=[];

		$this->db->select('MetOrg.id, MetOrg.org, Metric.y_name, Metric.x_name');
		$this->db->from('Metric');
		$this->db->join('MetOrg', 'MetOrg.metric = Metric.id');
		$this->db->where('MetOrg.org', 0);
		$this->db->or_where('MetOrg.org', 1);
		$q = $this->db->get();
		if($q->num_rows() > 0){
			$r_1=[];
			$r_0=[];
			foreach ($q->result() as $metric) {
				if($metric->org==1)
					$r_1[] = array('metorg' => $metric->id,
								'name' => $metric->y_name );
				else
					$r_0[] = array('metorg' => $metric->id,
								'name' => $metric->y_name );
			}
			$metrics[1] = $r_1;
			$metrics[0] = $r_0;
		}
		return $metrics;
	}

	function getAllAreasUnidad(){

		$query = "SELECT org.id AS id, org.name AS name, org.parent AS parent FROM Organization AS org
			WHERE (org.parent = 1 OR org.parent=0) AND NOT org.id=1 AND NOT org.id=0";
		$q = $this->db->query($query);
		if($q->num_rows() > 0)
			$areas = $q->result();
		else
			return false;

		foreach ($areas as $a) {
			$id_area = $a->id;

			$query = "SELECT org.id AS id, org.name AS name FROM Organization AS org WHERE org.parent=".$id_area;
			$q = $this->db->query($query);
			if($q->num_rows() > 0)
				$unidades = $this->buildUnidades($q);
			else{
				$unidades=[];
			}


			$result[$id_area] = array(
							'name' => $a->name,
							'type' => $a->parent==0 ? "Soporte" : "Operación",
							'parent' => $a->parent,
							'id' => $id_area,
							'unidades' => $unidades);
		}

		return $result;
	}

	function buildUnidades($q)
	{
		$this->load->library('Dashboard_library');
		$row = $q->result();
		foreach ($q->result() as $row)
		{
			$parameters = array
			(
				'id' => $row->id,
				'name' => $row->name
			);

			$org_array[] = $parameters;

		}

		return $org_array;
	}

	function addGraph($data){
		if($data['id_graph']!=-1){
			$query = "UPDATE Graphic SET type=?, metorg=?, min_year=?, max_year=?, position=? WHERE id=?";
			$q = $this->db->query($query, array($data['type'], $data['id_met'], $data['from'], $data['to'], $data['position'], $data['id_graph']));

		}
		else{
			//Se agrega a la tabla grafico
			$query = "INSERT INTO Graphic (type, metorg, min_year, max_year, position) VALUES (?, ?, ? ,? ,?)";
			$q = $this->db->query($query, array($data['type'], $data['id_met'], $data['from'], $data['to'], $data['position']));

			$query = "SELECT g.id FROM Graphic AS g WHERE g.type=? AND g.metorg=? AND g.min_year=? AND g.max_year=? AND g.position=?";
			$q = $this->db->query($query, array($data['type'], $data['id_met'], $data['from'], $data['to'], $data['position']));
			if($q->num_rows() > 0)
				$id_graph= $q->result()[0];

			//Se ve el id del dashboard
			$query = "SELECT d.id FROM Dashboard AS d WHERE d.org=".$data['id_org'];
			$q = $this->db->query($query);
			if($q->num_rows() > 0)
				$id_dash= $q->result()[0];
			else{
				$query = "SELECT o.name AS name FROM Organization AS o, MetOrg AS mo WHERE o.id=mo.org AND o.id=".$data['id_org'];
				$q = $this->db->query($query);
				if($q->num_rows() > 0)
					$name= $q->result()[0];

				$query = "INSERT INTO Dashboard (org, title) VALUES (?, ?)";
				$q = $this->db->query($query, array($data['id_org'], "Dashboard ".$name->name));

				$query = "SELECT d.id FROM Dashboard AS d WHERE d.org=".$data['id_org'];
				$q = $this->db->query($query);
				if($q->num_rows() > 0)
					$id_dash= $q->result()[0];
			}

			// Se relaciona dashboard y grafico

			$query = "INSERT INTO GraphDash (dashboard, graphic) VALUES (?, ?)";
			$q = $this->db->query($query, array($id_dash->id, $id_graph->id));
		}
	}
}
