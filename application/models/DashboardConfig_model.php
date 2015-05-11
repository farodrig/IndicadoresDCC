<?php
class DashboardConfig_model extends CI_Model
{

	function getAllMetricsUnidades()
	{
		$query = "SELECT org.id AS id FROM Organization AS org WHERE org.parent = 1 AND org.id<>1";
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
		$query = "SELECT org.id AS id FROM Organization AS org WHERE org.parent = 1 AND org.id<>1";
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
			}
			$result[$a->id] = $mets_area;

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

	function getAllAreasUnidad(){

		$query = "SELECT org.id AS id, org.name AS name FROM Organization AS org WHERE org.parent = 1 AND NOT org.id=1";
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

   

}