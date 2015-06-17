<?php
class Metrics_model extends CI_Model{


	function addMetric($data){
		//REcuerda insertar en MetOrg
		$this->db->insert('Metric', $data);
		$q = $q =  $this->db->select('id')
							->from('Metric')
							->where($data)
							->get();
		if($q->num_rows() > 0){
			return $metric_id = $q->result_array()[0]['id'];
		}
		else
			return false;

	}

	function deleteMetric($data){
		$query = "SELECT m.metric AS id FROM MetOrg AS m WHERE m.id=".$data['id_metorg'];
		$q = $this->db->query($query);
		if($q->num_rows() > 0){
			$metric_id = $q->result()[0];
		}
		else
			return false;
		$query = "DELETE FROM Metric WHERE id=".$metric_id->id;
		$q = $this->db->query($query);
		$query = "DELETE FROM MetOrg WHERE id=".$data['id_metorg'];
		$q = $this->db->query($query);

		return $q;
	}

	function updateMetric($data){
		$query= "SELECT m.metric AS id FROM MetOrg AS m WHERE m.id=".$data['id_metorg'];
		$q = $this->db->query($query);
		if($q->num_rows() > 0){
			$metric_id = $q->result()[0];

			$query = "SELECT id FROM Unit WHERE name='".$data['unidad_medida']."'";
			$q = $this->db->query($query);
			if($q->num_rows() > 0){
				$id_unidad = $q->result()[0];
			}
			else{
				$query = "INSERT INTO Unit (name) VALUES ('".$data['unidad_medida']."')";
				$q = $this->db->query($query);
				$query = "SELECT id FROM Unit WHERE name='".$data['unidad_medida']."'";
				$q = $this->db->query($query);
				if($q->num_rows() > 0){
					$id_unidad = $q->result()[0];
				}
			}
			$query = "UPDATE Metric SET category=?, unit=?, name=? WHERE id = ?";
			$q = $this->db->query($query, array($data['category'], $id_unidad->id, $data['name_metrica'], $metric_id->id));

			return $q;
		}
        else
            return false;
	}

	function getAllMetrics(){
		$query = "SELECT mo.org AS org, mo.id AS metorg, m.name AS name, c.name AS category, u.name AS unit
					FROM Metric AS m, MetOrg AS mo, Unit AS u, Category AS c
		 			WHERE mo.metric=m.id AND u.id=m.unit AND c.id=m.category";
		$q = $this->db->query($query);
		$data=[];
		if($q->num_rows() > 0){
			foreach ($q->result() as $row){
					$data[$row->org][]= array('metorg' => $row->metorg,
																	'name' => ucwords($row->name),
																	'category' => ucwords($row->category),
																	'unit' => ucwords($row->unit));
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
