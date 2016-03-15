<?php
class Dashboard_model extends CI_Model{

    public $title;
    public $GD;
    public $value;
    function __construct() {
        parent::__construct();
        $this->title = "Dashboard";
        $this->GD = "GraphDash";
        $this->value = "Value";
    }

    //Si hay más graficos por mostrar que los mostrados por defecto entrega true, para poner un boton y permitir mostrar los restantes.
    function showButton($id){
        $this->db->from('Graphic');
        $this->db->join('GraphDash', 'Graphic.id = GraphDash.graphic');
        $this->db->join('Dashboard', 'Dashboard.id = GraphDash.dashboard');
        $this->db->where('Graphic.position', 1);
        $this->db->where('Dashboard.org', $id);
        $q = $this->db->get();
        $num_show = $q->num_rows();

        $this->db->from('Graphic');
        $this->db->join('GraphDash', 'Graphic.id = GraphDash.graphic');
        $this->db->join('Dashboard', 'Dashboard.id = GraphDash.dashboard');
        $this->db->where('Dashboard.org', $id);
        $q = $this->db->get();

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
        return false;
    }


    function getValidate($id_metorg){
        if($id_metorg==-1){
            $datos = getGeneric($this, $this->value, ['id', 'state'], ['state'=>[-1,0]]);
            return (count($datos) > 0);
        }
        $this->load->library('session');
        $encargado_unidad = $this->session->userdata('encargado_unidad');
        $encargado_finanzas_unidad = $this->session->userdata("encargado_finanzas_unidad");
        if (!in_array(-1,$encargado_unidad) && !in_array(-1, $encargado_finanzas_unidad)){
            //no restringir la categoria
        }
        elseif (!in_array(-1,$encargado_unidad)) {
          $cat = 1;
        }
        elseif(!in_array(-1, $encargado_finanzas_unidad)){
          $cat = 2;
        }
        foreach ($id_metorg as $id) {
            $this->db->from('Value');
            $this->db->join('MetOrg', 'MetOrg.id = Value.metorg');
            $this->db->join('Metric', 'Metric.id = MetOrg.metric');
            $this->db->where('MetOrg.org', $id);
            $this->db->group_start();
            $this->db->where('Value.state', -1);
            $this->db->or_where('Value.state', 0);
            $this->db->group_end();
            if (isset($cat)){
                $this->db->where('Metric.category', $cat);
            }
            $q = $this->db->get();
            if($q->num_rows() > 0)
                return true;
        }
        return false;
    }

    function getBudgetMeasures($org, $metric){
        $this->db->select('Value.id, Value.metorg AS org, value, x_value, target, expected, year');
        $this->db->from('Value');
        $this->db->join('MetOrg', 'MetOrg.id = Value.metorg');
        $this->db->where('MetOrg.org', $org);
        $this->db->where('MetOrg.metric', $metric);
        $this->db->group_start();
            $this->db->where('state', 1);
            $this->db->or_where('modified', 1);
        $this->db->group_end();
        $this->db->order_by('year ASC');
        $this->db->order_by('state DESC');
        $q = $this->db->get();
        if($q->num_rows() > 0)
            return $q->result();
        return false;
    }

    function getAllMetrics($id, $category){
        $this->db->select('MetOrg.id as metorg, Metric.y_name, Metric.x_name, X.name as x_unit, Y.name as y_unit');
        $this->db->from('Metric');
        $this->db->join('MetOrg', 'MetOrg.metric = Metric.id');
        $this->db->join('Unit as X', 'X.id = Metric.x_unit');
        $this->db->join('Unit as Y', 'Y.id = Metric.y_unit');
        $this->db->join('Organization', 'Organization.id = MetOrg.org');
        $this->db->where('Organization.id', $id);

        if($category!=0)
            $this->db->where('Metric.category', $category);

        $q = $this->db->get();
        if($q->num_rows() > 0)
            return $q->result();
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

        $this->db->select('id, metorg AS org, value, x_value, target, expected, year');
        $this->db->from('Value');
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
            return $this->buildAllMeasuresments($q->result());
        return false;
    }

    function buildAllMeasuresments($rows){
        $this->load->library('Dashboard_library');
        $years=[];
        foreach ($rows as $row){
            $years[$row->org] = [];
        }
        foreach ($rows as $row){
            $parameters = array(
                'id' => $row->id,
                'metorg' => $row->org,
                'valueY' => $row->value,
                'valueX' => $row->x_value,
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
                'y_name' => $row->y_name,
                'x_name' => $row->x_name
            );

            $metrica = new Dashboard_library();
            $metrica_array[] = $metrica->initialize($parameters);
        }
        return $metrica_array;
    }

    function deleteValue($id_met, $year, $user, $validation){
      if($validation){
          $query = "DELETE FROM Value WHERE metorg= ? AND year = ?";
          $q = $this->db->query($query, array($id_met, $year));
          return $q;
      }

      $query = " UPDATE Value SET state = -1, updater = ? , dateup = NOW(), modified = 1
      WHERE metorg = ? AND year = ?";

      $q = $this->db->query($query, array($user, $id_met, $year));
      return $q;
    }

    function insertData($id_met, $year, $valueY, $valueX, $target, $expected, $user, $validation){ //Inserta datos en la tabla de mediciones
        $query = "INSERT INTO Value (metorg, state, value, x_value, target, expected, year, updater, dateup, proposed_value, proposed_x_value, proposed_target, proposed_expected)
                    VALUES (?, ?, ?, ?, ?, ? ,?, ?, ?, ?, ?, ?, ?)";
        $q = $this->db->query($query, array($id_met, $validation ,$valueY, $valueX, $target, $expected, $year, $user, date('Y-m-d H:i:s'), $valueY, $valueX, $target, $expected));

        return $q;
    }

    function updateData($id_met, $year, $valueY, $valueX, $target, $expected, $user, $validation){ //Aqui hay que guardar datos antiguos
        if(!$validation){
            $query = "SELECT value, x_value, target, expected, state AS s FROM Value WHERE metorg = ? AND year = ?";
            $q = $this->db->query($query, array($id_met, $year));
            if($q->num_rows() <= 0)
              return false;

            $row = $q->result()[0];
            $old_value = $row->value;
            $old_x_value = $row->x_value;
            $old_target = $row->target;
            $old_expected = $row->expected;
            $state = $row->s;
            $val_date = null;
        }
        else{
            $old_value = $valueY;
            $old_x_value = $valueX;
            $old_target = $target;
            $old_expected = $expected;
            $val_date = date('Y-m-d H:i:s');
            $state = 1;
        }

        if($state==0 || $state==-1){
            $query = "INSERT INTO Value (metorg, state, value, x_value,  target, expected, year, updater, dateup, proposed_value, proposed_x_value, proposed_target, proposed_expected, modified)
                      VALUES (?, ?, ?, ?, ? ,?, ?, ?, NOW(),?,?,?,?,1)";

            $q = $this->db->query($query, array($id_met, $state, $old_value, $old_x_value, $old_target, $old_expected, $year, $user, $valueY, $valueX, $target, $expected));

            return $q;
        }

        $query = " UPDATE Value SET state = ?, value =?, x_value = ? , target = ?, expected = ?, updater = ? , dateup = NOW(), dateval = ?, proposed_value = ?, proposed_x_value = ?, proposed_target = ?, proposed_expected = ?, modified = 1
          WHERE metorg = ? AND year = ?";

        $q = $this->db->query($query, array($validation, $old_value, $old_x_value, $old_target, $old_expected, $user, $val_date, $valueY, $valueX, $target, $expected, $id_met, $year));
        return $q;
    }

	function deleteData($id){
		$this->db->where('id', $id);
		$q=$this->db->delete('Value');
		return $q;
	}

	function validateData($id){
        $this->load->library('session');
		$query = $this->db->get_where('Value',array('id' => $id));
		$measure = $query->row();
        if($measure->state==-1)
            return $this->deleteData($id);
		$data = array(
            'state' => 1,
            'value'=> $measure->proposed_value,
            'target'=> $measure->proposed_target,
            'expected'=> $measure->proposed_expected,
            'validator' => $this->session->userdata('user'),
            'modified' => 0
        );
		$this->db->where('id', $id);
        $this->db->set('dateval', 'NOW()', FALSE);
		$q=$this->db->update('Value',$data);
		$this->_overrrideData($measure->metorg, $measure->year);
		return $q;
	}

	function rejectData($id){
        $this->load->library('session');
		$query = $this->db->get_where('Value',array('id' => $id));
		$measure = $query->row();
        $query = $this->db->get_where('Value',array('year' => $measure->year, 'metorg' => $measure->metorg, 'state'=>1));

        if($query->num_rows()>0)
            return $this->deleteData($id);
		$data = array(
            'state' => 1,
			'proposed_value'=> $measure->value,
            'proposed_x_value'=> $measure->x_value,
			'proposed_target'=> $measure->target,
			'proposed_expected'=> $measure->expected,
            'validator' => $this->session->userdata('user'),
            'modified' => 0
        );
		$this->db->where('id', $id);
        $this->db->set('dateval', 'NOW()', FALSE);
		$q=$this->db->update('Value',$data);
		$this->_overrrideData($measure->metorg, $measure->year);
		return $q;
	}

    function _overrrideData($metorg, $year){
        $this->db->order_by('dateval', 'DESC');
        $q = $this->db->get_where('Value',array('metorg' => $metorg, 'year' => $year, 'state' => 1));
        $newData = $q->row();
        $q =  $this->db->get_where('Value',array('id !='=> $newData->id,'state' =>1 ,'year'=> $newData->year,'metorg'=> $newData->metorg));
        foreach ($q->result() as $olderData){
            $this->deleteData($olderData->id);
        }
    }

	function checkIfValidate($id){
		$q = $this->db->get_where('Value',array('id' => $id));
        if($q->num_rows()>0){
            $q =  $this->db->get_where('Value',array('id'=> $id,'state' =>1));
            return ($q->num_rows()>0);
		}
        return true;
	}


    function getAllNonValidatedData($orgs, $type){
        $orgs = (is_null($orgs)) ? [] : $orgs;
        $this->db->select('Value.id as data_id, Value.state AS s, Value.value, Value.x_value, Value.target, Value.expected, Value.proposed_value as p_v, Value.proposed_target as p_t, Value.proposed_expected as p_e, Value.proposed_x_value as p_x, Value.modified, Value.year');
        $this->db->select('User.name, Organization.name as org_name, Metric.y_name, Metric.x_name, Y.name as type_y, X.name as type_x, Category.name as category, Permits.dcc_assistant as adcc');
        $this->db->from('Value');
        $this->db->join('User', 'User.id = Value.updater');
        $this->db->join('MetOrg', 'MetOrg.id = Value.metorg');
        $this->db->join('Organization', 'MetOrg.org = Organization.id');
        $this->db->join('Metric', 'MetOrg.metric = Metric.id');
        $this->db->join('Unit as X', 'X.id = Metric.x_unit');
        $this->db->join('Unit as Y', 'Y.id = Metric.y_unit');
        $this->db->join('Category', 'Metric.category = Category.id');
        $this->db->join('Permits', 'Permits.user = User.id');
        $this->db->group_start();
        $this->db->where('Value.state', 0);
        $this->db->or_where('Value.state', -1);
        $this->db->group_end();
        if (!is_null($type))
            $this->db->where('Category.id', $type);
        if (count($orgs)>0){
            $this->db->group_start();
            foreach ($orgs as $org) {
                $this->db->or_where('MetOrg.org', $org);
            }
            $this->db->group_end();
        }
        $q = $this->db->get();
        return $q->result();
    }


    function getDashboardMetrics($id, $category, $all){
        $dashboard = getGeneric($this, $this->title, ['id', 'org'], array('org'=>[$id], 'limit'=>1));
        if(count($dashboard) <= 0)
            return false;
        $dashboard = $dashboard[0]->id;
        $graphs = getGeneric($this, $this->GD, ['id', 'graphic', 'dashboard'], array('dashboard'=>[$dashboard]));
        if(($size=count($graphs)) <= 0)
            return false;

        $this->db->select('Graphic.metorg as org, Graphic.type, Graphic.min_year, Graphic.max_year, Unit.name');
        $this->db->from('Graphic');
        $this->db->join('MetOrg', 'MetOrg.id = Graphic.metorg');
        $this->db->join('Metric', 'MetOrg.metric = Metric.id');
        $this->db->join('Unit', 'Unit.id = Metric.y_unit');
        if(!$all)
            $this->db->where('Graphic.position !=', 0);
        if($category!=0)
            $this->db->where('Metric.category', $category);

        $this->db->group_start();
        for($i=0; $i<$size; $i++){
            $this->db->or_where('Graphic.id', $graphs[$i]->graphic);
        }
        $this->db->group_end();
        $q = $this->db->get();
        return ($q->num_rows() > 0) ? $this->buildDashboardMetrics($q) : false;
    }


    function buildDashboardMetrics($q){
        $this->load->library('Dashboard_library');
        foreach ($q->result() as $row){
            $parameters = array(
                'met_org' => $row->org,
                'type' => $row->type,
                'min_year' => $row->min_year,
                'max_year' => $row->max_year,
                'unit' => ucwords($row->name)
            );

            $metrica = new Dashboard_library();
            $metrica_array[] = $metrica->initializeDashboardMetrics($parameters);
        }
        return $metrica_array;
    }

    function getDashboardMeasurements($metorgs){
        $result=[];
        foreach ($metorgs as $met) {
            $metric = getGeneric($this, 'MetOrg', ['id', 'org'], array('id'=>[$met->getMetOrg()], 'limit'=>1));
            if(count($metric) <= 0)
                return false; //Si llego acá hay problemas
            $metric = $metric[0]->metric;

            $y_name = getGeneric($this, 'Metric', ['id', 'org'], array('id'=>[$metric], 'limit'=>1));
            if(count($y_name) <= 0)
                return false;
            $y_name = $y_name[0]->y_name;

            $this->db->select('id, metorg as org, value, x_value, target, expected, year');
            $this->db->from('Value');
            $this->db->group_start();
                $this->db->group_start();
                    $this->db->where('modified', 0);
                    $this->db->where('state', 1);
                $this->db->group_end();
                $this->db->or_where('modified', 1);
            $this->db->group_end();
            $this->db->where('metorg', $met->getMetOrg());
            $this->db->where('year >=', $met->getMinYear());
            $this->db->where('year <=', $met->getMaxYear());
            $this->db->order_by('year', 'ASC');
            $this->db->order_by('state', 'DESC');
            $q = $this->db->get();
            if($q->num_rows() > 0){
                $rows = $this->buildAllMeasuresments($q->result());
                $result[] = array(
                    'id' => $met->getMetOrg(),
                    'name' => $y_name,
                    'measurements' => $rows
                );
            }
        }
        return $result;
    }

    function getAllData($id_org, $id_met){
        $this->db->select('value, x_value, target, expected, Value.year');
        $this->db->from('Value');
        $this->db->from('GraphDash');
        $this->db->join('MetOrg', 'MetOrg.id = Value.metorg');
        $this->db->join('Dashboard', 'GraphDash.dashboard = Dashboard.id');
        $this->db->join('Graphic', 'Graphic.id = GraphDash.graphic');
        $this->db->join('Organization', 'Organization.id = Dashboard.org');
        $this->db->group_start();
            $this->db->where('modified', 1);
            $this->db->or_where('state', 1);
        $this->db->group_end();
        $this->db->where('Organization.id', $id_org);
        $this->db->where('Graphic.metorg', $id_met);
        $this->db->where('Value.metorg', $id_met);
        $this->db->where('Value.year <= Graphic.max_year');
        $this->db->where('Value.year >= Graphic.min_year');
        $this->db->order_by('year', 'ASC');
        $this->db->order_by('state', 'DESC');

        $q= $this->db->get();
        if($q->num_rows() <= 0)
            return false;

        $data = $this->buildDataCSV($q);
        $this->db->select('y_name');
        $this->db->from('Metric');
        $this->db->join('MetOrg', 'MetOrg.metric = Metric.id');
        $this->db->where('MetOrg.id', $id_met);
        $q= $this->db->get();
        $name = $q->result()[0];
        $this->download_send_headers("data_export_" . date("Y-m-d") . ".csv");
        echo $this->array2csv($data, $name->y_name);
        die();
        debug($user_agent);
    }

    function buildDataCSV($q){
        $this->load->library('Dashboard_library');
        $years = [];
        foreach ($q->result() as $row){
            if(in_array($row->year, $years))
                continue;
            $parameters = array(
                'valueY' => $row->value,
                'valueX' => $row->x_value,
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
        if (count($array) == 0)
            return null;
        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        if(strpos($user_agent, "Win") !== FALSE)
            $eol = "\r\n";
        else
            $eol = "\n";

        ob_start();
        $df = fopen("php://output", 'w');
        fwrite($df, "[".$name."]".$eol);
        fwrite($df, "Año,Valor Y,Esperado,Meta".$eol);
        foreach ($array as $row) {
            $a = $row->getYear().','.$row->getValueY().','.$row->getTarget().','.$row->getExpected().$eol;
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
}
