<?php
class Dashboard_model extends CI_Model
{
    function showButton($id){
        $query = "SELECT * FROM Graphic AS g, GraphDash AS gd, Dashboard AS d
                WHERE g.id=gd.graphic AND gd.dashboard=d.id AND d.org = ? AND g.position=1";
        $q = $this->db->query($query, array($id));
        $num_show = $q->num_rows();

        $query = "SELECT * FROM Graphic AS g, GraphDash AS gd, Dashboard AS d
                WHERE g.id=gd.graphic AND gd.dashboard=d.id AND d.org = ?";
        $q = $this->db->query($query, array($id));

        return $q->num_rows()>$num_show;
    }

    //Entrega el nombre de la categoría que tiene un metorg asociada indirectamente
    function getMetType($id_metorg){
        $this->db->select('Category.name');
        $this->db->from('Metric');
        $this->db->join('MetOrg', 'MetOrg.metric = Metric.id');
        $this->db->join('Category', 'Category.id = Metric.category');
        $this->db->where('MetOrg.id', $id_metorg);
        $q = $this->db->get();
        if($q->num_rows() == 1){
            $row= $q->result()[0];
            return $row->name;
        }
        else
            return false;
    }


    function getValidate($id_metorg){
        $this->load->library('session');
        if($id_metorg==-1){
            $query = "SELECT * FROM Measure AS m WHERE m.state=0 OR m.state=-1";
            $q = $this->db->query($query);
            return ($q->num_rows() > 0);
        }
        else{
            $encargado_unidad = $this->session->userdata('encargado_unidad');
            $encargado_finanzas_unidad = $this->session->userdata("encargado_finanzas_unidad");
            if(!in_array(-1, $encargado_unidad) && !in_array(-1, $encargado_finanzas_unidad)){
              $text = "";
            }
            elseif (!in_array(-1,$encargado_unidad)) {
              $text = " AND met.category = 1";
            }
            else{
              $text = " AND met.category = 2";
            }
            foreach ($id_metorg as $id) {
                $query="SELECT * FROM Measure AS m, MetOrg AS mo, Metric AS met
                        WHERE (m.state=0 OR m.state = -1) AND met.id=mo.metric AND mo.id=m.metorg AND mo.org=".$id.$text;
                $q = $this->db->query($query);
                if($q->num_rows() > 0)
                    return true;
            }
            return false;
        }
    }

    function getAllMetrics($id, $category){
        $this->db->select('MetOrg.id, Metric.name');
        $this->db->from('Metric');
        $this->db->join('MetOrg', 'MetOrg.metric = Metric.id');
        $this->db->join('Organization', 'Organization.id = MetOrg.org');
        $this->db->where('Organization.id', $id);

        if($category!=0)
            $this->db->where('Metric.category', $category);

        $q = $this->db->get();
        if($q->num_rows() > 0)
            return $this->buildAllMetrics($q);
        return false;
    }

    function getAllMeasurements($id, $category){
        $this->db->select('MetOrg.id');
        $this->db->from('Metric');
        $this->db->join('MetOrg', 'MetOrg.metric = Metric.id');
        $this->db->join('Organization', 'MetOrg.org = Organization.id');
        $this->db->where('Organization.id', $id);

        if($category!=0)
            $this->db->where('Metric.category', $category);

        $q = $this->db->get();
        $size=$q->num_rows();
        if($size <= 0){
            return false;
        }
        $rows = $q->result();

        $this->db->select('Measure.id, Measure.metorg AS org, Measure.old_value AS val, Measure.old_target AS target, Measure.old_expected AS expected, Measure.year');
        $this->db->from('Measure');
        $this->db->group_start();
            $this->db->where('state', 1);
            $this->db->or_where('modified', 1);
        $this->db->group_end();
        $this->db->group_start();
        for($i=0; $i<$size; $i++){
            $this->db->or_where('metorg', $rows[$i]->id);
        }
        $this->db->group_end();
        $this->db->order_by('year ASC');
        $this->db->order_by('state DESC');
        $q = $this->db->get();
        if($q->num_rows() > 0)
            return $this->buildAllMeasuresments($q);
        else
            return false;
    }

    function getAllMetricOrgIds($id){
        $this->load->model('Metorg_model');
        $this->load->library('Dashboard_library');
        $q = $this->Metorg_model->getMetOrg(array('org'=>[$id]));
        if(count($q) > 0){
            foreach ($q as $row){
                $parameters=  array(
                    'id' => $row->id
                );
                $id = new Dashboard_library();
                $id_array[] = $id->initializeIds($parameters);
            }
        }
        return $id_array;

    }

    function buildAllMetrics($q){
        $this->load->library('Dashboard_library');
        $row = $q->result();
        foreach ($q->result() as $row){
            $parameters = array(
                'id' => $row->id,
                'name' => $row->name
            );

            $metrica = new Dashboard_library();
            $metrica_array[] = $metrica->initialize($parameters);
        }
        return $metrica_array;
    }

    function buildAllMeasuresments($q){
        $this->load->library('Dashboard_library');
        $row = $q->result();
        $years=[];
        foreach ($q->result() as $row){
          $years[$row->org] = [];
        }
        foreach ($q->result() as $row){
            if(in_array($row->year, $years[$row->org]))
              continue;

            $parameters = array(
                'id' => $row->id,
                'metorg' => $row->org,
                'value' => $row->val,
                'target' => $row->target,
                'expected' => $row->expected,
                'year' => $row->year
            );

            $years[$row->org][] = $row->year;

            $measurement = new Dashboard_library();
            $measurement_array[] = $measurement->initializeMeasurement($parameters);
        }
        return $measurement_array;
    }

    function deleteMeasure($id_met, $year, $user, $validation){
      if($validation){
          $query = "DELETE FROM Measure WHERE metorg= ? AND year = ?";
          $q = $this->db->query($query, array($id_met, $year));
          return $q;
      }

      $query = " UPDATE Measure SET state = -1, updater = ? , dateup = NOW(), modified = 1
      WHERE metorg = ? AND year = ?";

      $q = $this->db->query($query, array($user, $id_met, $year));

      return $q;
    }

    function insertData($id_met, $year, $value, $target, $expected, $user, $validation){ //Inserta datos en la tabla de mediciones

        $query = "INSERT INTO Measure (metorg, state, value, target, expected, year, updater, dateup, old_value, old_target, old_expected)
                    VALUES (?, ?, ?, ?, ? ,?, ?, NOW(),?,?,?)";

        $q = $this->db->query($query, array($id_met, $validation ,$value, $target, $expected, $year, $user,$value, $target, $expected));

        return $q;
    }

    function updateData($id_met, $year, $value, $target, $expected, $user, $validation){ //Aqui hay que guardar datos antiguos
        if(!$validation){
            $query = "SELECT value AS val, target AS tar, expected AS exp, state AS s FROM Measure WHERE metorg = ? AND year = ?";
            $q = $this->db->query($query, array($id_met, $year));
            if($q->num_rows() > 0){
                $row = $q->result()[0];
                $old_value = $row->val;
                $old_target = $row->tar;
                $old_expected = $row->exp;
                $state = $row->s;
            }
            else{
              return false;
            }
        }
        else{
          $old_value = $value;
          $old_target = $target;
          $old_expected = $expected;
          $state = 1;
        }

        if($state==0 || $state==-1){
            $query = "INSERT INTO Measure (metorg, state, value, target, expected, year, updater, dateup, old_value, old_target,
            old_expected, modified)
                      VALUES (?, ?, ?, ?, ? ,?, ?, NOW(),?,?,?,1)";

            $q = $this->db->query($query, array($id_met, $state ,$value, $target, $expected, $year, $user,$old_value, $old_target, $old_expected));

            return $q;
        }

        $query = " UPDATE Measure SET state = ?, value =? , target = ?, expected = ?, updater = ? , dateup = NOW(),
          old_value = ?, old_target = ?, old_expected = ?, modified = 1
          WHERE metorg = ? AND year = ?";

        $q = $this->db->query($query, array($validation, $value, $target, $expected, $user,$old_value,
        $old_target, $old_expected, $id_met, $year));

        return $q;
    }

	function deleteData($id){
		$this->db->where('id', $id);
		$q=$this->db->delete('Measure');
		return $q;
	}

	function validateData($id){
        $this->load->library('session');
		$query = $this->db->get_where('Measure',array('id' => $id));
		$measure = $query->row();
        if($measure->state==-1)
            return $this->deleteData($id);
		$data = array(
            'state' => 1,
            'old_value'=> $measure->value,
            'old_target'=> $measure->target,
            'old_expected'=> $measure->expected,
            'validator' => $this->session->userdata('user'),
            'modified' => 0
        );
		$this->db->where('id', $id);
        $this->db->set('dateval', 'NOW()', FALSE);
		$q=$this->db->update('Measure',$data);
		$this->_overrrideData($measure->metorg, $measure->year);
		return $q;
	}

	function rejectData($id){
        $this->load->library('session');
		$query = $this->db->get_where('Measure',array('id' => $id));
		$measure = $query->row();
        $query = $this->db->get_where('Measure',array('year' => $measure->year, 'metorg' => $measure->metorg));

        if($query->num_rows()>0)
            return $this->deleteData($id);
		$data = array(
            'state' => 1,
			'value'=> $measure->old_value,
			'target'=> $measure->old_target,
			'expected'=> $measure->old_expected,
            'validator' => $this->session->userdata('user'),
            'modified' => 0
        );
		$this->db->where('id', $id);
        $this->db->set('dateval', 'NOW()', FALSE);
		$q=$this->db->update('Measure',$data);
		$this->_overrrideData($measure->metorg, $measure->year);
		return $q;
	}

	function checkIfValidate($id){
		$q = $this->db->get_where('Measure',array('id' => $id));
        if($q->num_rows()>0){
            $q =  $this->db->get_where('Measure',array('id'=> $id,'state' =>1));
            return ($q->num_rows()>0);
		}
        return true;
	}

	function _overrrideData($metorg, $year){
		$q = $this->db->get_where('Measure',array('metorg' => $metorg, 'year' => $year, 'state' => 1));
		$newData = $q->row();
		$q =  $this->db->get_where('Measure',array('id !='=> $newData->id,'state' =>1 ,'year'=> $newData->year,'metorg'=> $newData->metorg));
		foreach ($q->result() as $olderData){
			$this->deleteData($olderData->id);
		}
	}

	function _getAllnonValidateDataUnidad($id_org){
		$querry = "SELECT  m.id AS data_id ,u.name AS name, org.name AS org_name, metric.name AS metric, unit.name AS type, m.value AS value, m.target AS target, m.expected AS expected,
              m.old_value AS o_v, m.old_target AS o_t, m.old_expected AS o_e, c.name AS category, p.assistant_unidad AS au,
              p.finances_assistant_unidad AS fau, p.dcc_assistant AS adcc, m.year AS year, m.modified AS mod, m.state AS s
					  FROM  Measure AS m, User AS u, MetOrg AS mo, Metric as metric, Organization AS org, Unit AS unit, Category AS c, Permits AS p
					  WHERE (m.state =0 or m.state=-1) AND m.updater = u.id AND m.metorg = mo.id AND mo.org = org.id AND mo.metric =metric.id AND
            metric.unit = unit.id AND c.id=metric.category AND u.id=p.user AND mo.org =?" ;
        $q = $this->db->query($querry,array($id_org));

        if($q->num_rows() > 0){
			 foreach($q->result() as $row){
				 $data[]= $row;
			 }
		 	 return $data;
		}
	}


	function getnonValidatebyUnit($array_idorg){
		$arr = array();
		foreach($array_idorg as $id){
			$colums = $this->_getAllnonValidateDataUnidad($id);
			if($colums!=null){
				$arr = array_merge ($colums, $arr);
			}
		}
		return $arr;

	}

	function _getAllnonValidateDataUnidadByType($id_org,$type){
		$querry = "SELECT  m.id AS data_id ,u.name AS name, org.name AS org_name, metric.name AS metric, unit.name AS type, m.value AS value, m.target AS target, m.expected AS expected,
              m.old_value AS o_v, m.old_target AS o_t, m.old_expected AS o_e, c.name AS category, p.assistant_unidad AS au,
              p.finances_assistant_unidad AS fau, p.dcc_assistant AS adcc,m.modified, m.year AS year, m.state AS s
					  FROM  Measure AS m, User AS u, MetOrg AS mo, Metric as metric, Organization AS org, Unit AS unit, Category AS c, Permits AS p
					  WHERE (m.state =0 OR m.state = -1) AND m.updater = u.id AND m.metorg = mo.id AND mo.org = org.id AND mo.metric =metric.id AND
            metric.unit = unit.id AND c.id=metric.category AND u.id=p.user  AND c.id= $type  AND mo.org =?";
        $q = $this->db->query($querry,array($id_org));

        if($q->num_rows() > 0){
            foreach($q->result() as $row){
			    $data[]= $row;
            }
		 	return $data;
        }
	}



	function getnonValidatebyUnitByType($array_idorg,$type){
		$arr = array();
		foreach($array_idorg as $id){
			$colums = $this->_getAllnonValidateDataUnidadByType($id,$type);
			if($colums!=null){
				$arr = array_merge ($colums, $arr);
			}
		}
		return $arr;
	}


	function getAllnonValidateData(){
		$querry = "SELECT  m.id AS data_id ,u.name AS name, org.name AS org_name, metric.name AS metric, unit.name AS type, m.value AS value, m.target AS target, m.expected AS expected,
              m.old_value AS o_v, m.old_target AS o_t, m.old_expected AS o_e, c.name AS category, p.assistant_unidad AS au,
              p.finances_assistant_unidad AS fau, p.dcc_assistant AS adcc,m.modified, m.year AS year, m.state AS s
					  FROM  Measure AS m, User AS u, MetOrg AS mo, Metric as metric, Organization AS org, Unit AS unit, Category AS c, Permits AS p
					  WHERE (m.state =0 OR m.state=-1) AND m.updater = u.id AND m.metorg = mo.id AND mo.org = org.id AND mo.metric =metric.id AND
            metric.unit = unit.id AND c.id=metric.category AND u.id=p.user";
        $q = $this->db->query($querry);

		if($q->num_rows() > 0){
            foreach($q->result() as $row){
			    $data[]= $row;
            }
		 	return $data;
		}
	}


    function getDashboardMetrics($id, $category){
        $query = "SELECT d.id AS id FROM Dashboard AS d WHERE d.org=".$id;
        $q = $this->db->query($query);
        if($q->num_rows() > 0)
            $dashboard = $q->result()[0]->id;
        else
            return false;

        $query = "SELECT gd.graphic AS graph FROM GraphDash AS gd WHERE gd.dashboard=".$dashboard;
        $q = $this->db->query($query);
        if(($size=$q->num_rows()) > 0)
            $graphs = $q->result();
        else
            return false;

        $g_id = "(";
        for($i=0; $i<$size-1; $i++){
            $id = $graphs[$i]->graph;
            $g_id = $g_id."g.id= ".$id." OR ";
        }
        $g_id = $g_id."g.id =".$graphs[$size-1]->graph.")";

        if($category==0){
            $query = "SELECT g.metorg AS org, g.type AS type, g.min_year AS min_year, g.max_year AS max_year, u.name AS unit
                        FROM Graphic AS g, MetOrg AS mo, Metric AS m, Unit AS u
                        WHERE g.position<>0 AND g.metorg=mo.id AND mo.metric=m.id AND u.id=m.unit AND ".$g_id;
            $q = $this->db->query($query);
        }
        else{

            $query = "SELECT g.metorg AS org, g.type AS type, g.min_year AS min_year, g.max_year AS max_year, u.name AS unit
                        FROM Graphic AS g, MetOrg AS mo, Metric AS m, Unit AS u
                        WHERE g.position<>0 AND g.metorg=mo.id AND mo.metric=m.id AND u.id=m.unit AND m.category=? AND ".$g_id;
            $q = $this->db->query($query, array($category));
        }

        return ($q->num_rows() > 0) ? $this->buildDashboardMetrics($q) : false;
    }

    function buildDashboardMetrics($q){
        $this->load->library('Dashboard_library');
        $row = $q->result();
        foreach ($q->result() as $row){
            $parameters = array(
                'met_org' => $row->org,
                'type' => $row->type,
                'min_year' => $row->min_year,
                'max_year' => $row->max_year,
                'unit' => ucwords($row->unit)
            );

            $metrica = new Dashboard_library();
            $metrica_array[] = $metrica->initializeDashboardMetrics($parameters);
        }
        return $metrica_array;
    }

    function getDashboardMeasurements($metorgs){
        $result=[];
        foreach ($metorgs as $met) {
            $query = "SELECT m.metric AS metric FROM MetOrg AS m WHERE m.id= ".$met->getMetOrg();
            $q = $this->db->query($query);
            if(($size=$q->num_rows()) > 0)
                $metric = $q->result()[0]->metric;
            else
                return false; //Si llego aca hay problemas

            $query = "SELECT m.name AS name FROM Metric AS m WHERE m.id=".$metric;
            $q = $this->db->query($query);
            if(($size=$q->num_rows()) > 0)
                $name = $q->result()[0]->name;
            else
                return false;

            $query = "SELECT m.id AS id, m.metorg AS org, m.old_value AS val, m.old_target AS target, m.old_expected AS expected, m.year AS year
                    FROM Measure AS m
                    WHERE ((m.modified = 0 AND m.state=1) OR m.modified=1) AND m.metorg= ? AND m.year>= ? AND m.year<=? ORDER BY m.year ASC, m.state DESC";
            $q = $this->db->query($query, array($met->getMetOrg(), $met->getMinYear(), $met->getMaxYear()));
            if(($size=$q->num_rows()) > 0){
                $rows = $this->buildAllMeasuresments($q);
                $result[] = array(
                    'id' => $met->getMetOrg(),
                    'name' => $name,
                    'measurements' => $rows
                );
            }
        }
        return $result;
    }

    function getAllData($id_org, $id_met){

        $query = "SELECT m.old_value AS value, m.old_target AS target, m.old_expected AS expected, m.year AS year
         FROM Organization AS org, Dashboard AS d, GraphDash AS gd, Graphic AS g, Measure AS m
         WHERE d.org=org.id AND org.id = ? AND gd.dashboard=d.id AND g.id = gd.graphic AND g.metorg=? AND m.metorg=? AND (m.state=1 OR m.modified=1)
         AND m.year>=g.min_year AND m.year<=g.max_year ORDER BY m.year ASC, m.state DESC";

         $q= $this->db->query($query, array($id_org, $id_met, $id_met));

         if(($size=$q->num_rows()) > 0){
             $data = $this->buildDataCSV($q);

             $query = "SELECT m.name AS name FROM Metric AS m, MetOrg AS mo WHERE mo.metric=m.id AND mo.id=".$id_met;
             $q= $this->db->query($query);
             $name = $q->result()[0];

             $this->download_send_headers("data_export_" . date("Y-m-d") . ".csv");
             echo $this->array2csv($data, $name->name);
             die();
             debug($user_agent);
         }
         else
             return false;
    }

    function buildDataCSV($q){
        $this->load->library('Dashboard_library');
        $row = $q->result();
        $years = [];
        foreach ($q->result() as $row){
            if(in_array($row->year, $years))
                continue;
            $parameters = array(
                'value' => $row->value,
                'target' => $row->target,
                'expected' => $row->expected,
                'year' => $row->year
            );

            $years[] = $row->year;
            $metrica = new Dashboard_library();
            $metrica_array[] = $metrica->initializeData($parameters);
        }
        return $metrica_array;
    }

    function array2csv($array,$name){
        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        if(strpos($user_agent, "Win") !== FALSE)
            $eol = "\r\n";
        else
            $eol = "\n";

        if (count($array) == 0) {
            return null;
        }
        ob_start();
        $df = fopen("php://output", 'w');
        fwrite($df, "[".$name."]".$eol);
        fwrite($df, "Año,Valor,Esperado,Meta".$eol);
        foreach ($array as $row) {
            $a = $row->getYear().','.$row->getValue().','.$row->getTarget().','.$row->getExpected().$eol;
            fwrite($df, $a);
        }
        fclose($df);
        return ob_get_clean();
    }

    function download_send_headers($filename) {
        // disable caching
        $now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");

        // force download
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");

        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename={$filename}");
        header("Content-Transfer-Encoding: binary");
    }

    function getAllDashboardMetrics($id, $category){
        $query = "SELECT d.id AS id FROM Dashboard AS d WHERE d.org=".$id;
        $q = $this->db->query($query);
        if($q->num_rows() > 0)
            $dashboard = $q->result()[0]->id;
        else
            return false;

        $query = "SELECT gd.graphic AS graph FROM GraphDash AS gd WHERE gd.dashboard=".$dashboard;
        $q = $this->db->query($query);
        if(($size=$q->num_rows()) > 0)
            $graphs = $q->result();
        else
            return false;

        $g_id = "(";
        for($i=0; $i<$size-1; $i++){
            $id = $graphs[$i]->graph;
            $g_id = $g_id."g.id= ".$id." OR ";
        }
        $g_id = $g_id."g.id =".$graphs[$size-1]->graph.")";

        $query = "SELECT g.metorg AS org, g.type AS type, g.min_year AS min_year, g.max_year AS max_year, u.name AS unit
                    FROM Graphic AS g, MetOrg AS mo, Metric AS m, Unit AS u
                    WHERE g.metorg=mo.id AND mo.metric=m.id AND u.id=m.unit AND ".$g_id;

        if($category==0){
            $query = "SELECT g.metorg AS org, g.type AS type, g.min_year AS min_year, g.max_year AS max_year, u.name AS unit
                        FROM Graphic AS g, MetOrg AS mo, Metric AS m, Unit AS u
                        WHERE g.metorg=mo.id AND mo.metric=m.id AND u.id=m.unit AND ".$g_id;
        }
        else{

            $query = "SELECT g.metorg AS org, g.type AS type, g.min_year AS min_year, g.max_year AS max_year, u.name AS unit
                        FROM Graphic AS g, MetOrg AS mo, Metric AS m, Unit AS u
                        WHERE g.metorg=mo.id AND mo.metric=m.id AND u.id=m.unit AND m.category=".$category." AND ".$g_id;
        }
        $q = $this->db->query($query);
        return ($q->num_rows() > 0) ? $this->buildDashboardMetrics($q) : false;
    }
}
