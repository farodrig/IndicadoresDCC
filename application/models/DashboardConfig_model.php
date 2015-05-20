<?php
class DashboardConfig_model extends CI_Model
{
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


			$query = "SELECT m.year AS year FROM Measure AS m WHERE m.metorg=".$id;
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

			return array(
				'id' => -1,
				'min' => 2005, //Si no hay medidas escribimos los ultimos 10 años
				'max' => 2015,
				'type' => 2,
				'checked' => 0
				);
		}
	}

	function getAllMetricsUnidades()
	{
		$query = "SELECT org.id AS id FROM Organization AS org WHERE (org.parent = 1 AND org.id<>1) OR (org.parent = 0 AND org.id<>0)";
		$q = $this->db->query($query);
		if($q->num_rows() > 0)
			$areas = $q->result();
		else
			return false;

		$result=[];
		foreach ($areas as $a) {
			$id_area = $a->id;

			$query = "SELECT org.id AS id FROM Organization AS org WHERE org.parent=".$id_area;
			$q = $this->db->query($query);
			if($q->num_rows() > 0){
				$unidades = $q->result();

				foreach ($unidades as $u) { //Sacamos las metricas para cada unidad
					$mets_unidad=[];
					$query = "SELECT mo.id AS metorg, m.name AS name FROM MetOrg AS mo, Metric AS m WHERE m.id = mo.metric AND mo.org=".$u->id;
					$q = $this->db->query($query);
					if($q->num_rows() > 0){
						$met_unidad = $q->result();

						foreach ($met_unidad as $met) {
							$mets_unidad[]=array(
								'metorg' => $met->metorg,
								'name' => $met->name
								);
						}


					$result[$u->id] = $mets_unidad;
					}
				}
			}	
		}

		return $result;
	}

	function getAllMetricsArea()
	{
		$query = "SELECT org.id AS id FROM Organization AS org WHERE (org.parent = 1 AND org.id<>1) OR (org.parent = 0 AND org.id<>0)"; //Selecciona areas
		$q = $this->db->query($query);
		if($q->num_rows() > 0)
			$areas = $q->result();
		else
			return false;

		$result=[];
		foreach ($areas as $a) {
			$id_area = $a->id;

			$mets_area=[];
			$query = "SELECT mo.id AS metorg, m.name AS name FROM MetOrg AS mo, Metric AS m WHERE m.id = mo.metric AND mo.org=".$a->id;
			$q = $this->db->query($query);
			if($q->num_rows() > 0){
				$met_area = $q->result();

				foreach ($met_area as $met) {
					$mets_area[]=array(
								'metorg' => $met->metorg,
								'name' => $met->name
								);
				}

				$result[$a->id] = $mets_area;
			}
			

			$query = "SELECT org.id AS id FROM Organization AS org WHERE org.parent=".$id_area;
			$q = $this->db->query($query);
			if($q->num_rows() > 0){
				$unidades = $q->result();

				foreach ($unidades as $u) { //Sacamos las metricas para cada unidad
					$mets_unidad=[];
					$query = "SELECT mo.id AS metorg, m.name AS name FROM MetOrg AS mo, Metric AS m WHERE m.id = mo.metric AND mo.org=".$u->id;
					$q = $this->db->query($query);
					if($q->num_rows() > 0){
						$met_unidad = $q->result();

						foreach ($met_unidad as $met) {
							$mets_unidad[]=array(
								'metorg' => $met->metorg,
								'name' => $met->name
								);
						}

						$result[$u->id] = $mets_unidad;

					}
				}
			}
	
		}

		return $result;
	}

	function getAllMetricsDCC() 
	{
		$metrics = $this->getAllMetricsArea();

		if(!$metrics)
			$metrics=[];

		$query = "SELECT mo.id AS id, m.name, mo.org FROM MetOrg AS mo, Metric AS m WHERE (mo.org=1 OR mo.org=0) AND mo.metric=m.id";
		$q = $this->db->query($query);
		if($q->num_rows() > 0){
			$metrics_dcc = $q->result();
			foreach ($metrics_dcc as $metric) {
				if($metric->org==1)
					$r_1[] = array('metorg' => $metric->id,
								'name' => $metric->name );
				else
					$r_0[] = array('metorg' => $metric->id,
								'name' => $metric->name );
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
							'type' => $a->parent,
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
				$query = "SELECT o.name AS name FROM Organization AS o, MetOrg AS mo WHERE o.id=mo.org AND mo.id=".$data['id_met'];
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