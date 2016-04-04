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
    
    function getValue($data){
        return getGeneric($this, $this->value, $data);
    }

    function getValidate($id_metorg){
        if($id_metorg==-1){
            $datos = getGeneric($this, $this->value, ['state'=>[-1,0]]);
            return (count($datos) > 0);
        }
        $this->load->library('session');
        $encargado_unidad = $this->session->userdata('encargado_unidad');
        $encargado_finanzas = $this->session->userdata("encargado_finanzas");
        if (count($encargado_unidad) && count($encargado_finanzas)){
            //no restringir la categoria
        }
        elseif (count($encargado_unidad)) {
          $cat = 1;
        }
        elseif(count($encargado_finanzas)){
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
            return ($q->num_rows() > 0);
        }
        return false;
    }

    function getBudgetMeasures($org){
        $this->db->select('Value.id, Value.metorg AS org, value, target, expected, year, state, proposed_value as p_v, proposed_target as p_t, proposed_expected as p_e');
        $this->db->from('Value');
        $this->db->join('MetOrg', 'MetOrg.id = Value.metorg');
        $this->db->where('MetOrg.org', $org);
        $this->db->where('MetOrg.metric', 1);
        $this->db->where('state !=', -1);
        $this->db->order_by('year ASC');
        $this->db->order_by('state ASC');
        $q = $this->db->get();
        if($q->num_rows() > 0)
            return $q->result();
        return false;
    }

    function getBudgetMeasure($org, $year){
        $this->db->select('Value.id, Value.metorg AS org, value, target, expected, year, state, proposed_value as p_v, proposed_target as p_t, proposed_expected as p_e');
        $this->db->from('Value');
        $this->db->join('MetOrg', 'MetOrg.id = Value.metorg');
        $this->db->where('MetOrg.org', $org);
        $this->db->where('MetOrg.metric', 1);
        $this->db->where('year', $year);
        $this->db->order_by('state DESC');
        $q = $this->db->get();
        if($q->num_rows() > 0)
            return $q->row();
        return false;
    }

    function updateCreateBudgetValue($org, $year, $y_value, $expected, $target, $validation){
        $this->load->model('Metorg_model');
        $q = $this->Metorg_model->getMetOrg(array('org'=>[$org], 'metric'=>[1]));
        if(count($q) != 1){
            return false;
        }
        $id = $q[0]->id;
        $old_value = getGeneric($this, $this->value, ['metorg'=>[$id], 'year'=>[$year], 'state'=>[0]]);
        if(!$validation && count($old_value)){
            $old_value = $old_value[0];
            if(strcmp($y_value, "")==0 && !is_null($old_value->proposed_value))
                $y_value = $old_value->proposed_value;
            if(strcmp($expected, "")==0 && !is_null($old_value->proposed_expected))
                $expected = $old_value->proposed_expected;
            if(strcmp($target, "")==0 && !is_null($old_value->proposed_target))
                $target = $old_value->proposed_target;
            //Si existe un valor previo no validado es eliminado antes de proponer el otro, de forma que solo exista un dato por validar
            if(!$this->deleteData($old_value->id))
                return false;
        }
        $old_value = getGeneric($this, $this->value, ['metorg'=>[$id], 'year'=>[$year], 'state'=>[1]]);
        if(count($old_value)) {
            return $this->updateData($id, $year, "", $y_value, "", $target, $expected, $this->session->userdata("rut"), $validation);
        }
        return $this->insertData($id, $year, $y_value, "", $target, $expected, $this->session->userdata("rut"), $validation);
    }

    function getAllMetrics($id, $category){
        $this->db->select('MetOrg.id as metorg, Metric.y_name, Metric.x_name, X.name as x_unit, Y.name as y_unit, Metric.name, Metric.category');
        $this->db->from('Metric');
        $this->db->join('MetOrg', 'MetOrg.metric = Metric.id');
        $this->db->join('Unit as X', 'X.id = Metric.x_unit');
        $this->db->join('Unit as Y', 'Y.id = Metric.y_unit');
        $this->db->join('Organization', 'Organization.id = MetOrg.org');
        $this->db->where('Organization.id', $id);

        if(!is_null($category) && $category!=0)
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
        $this->db->where('state', 1);
        $this->db->group_start();
        for($i=0; $i<$size; $i++){
            $this->db->or_where('metorg', $rows[$i]->id);
        }
        $this->db->group_end();
        $this->db->order_by('year ASC');
        $q = $this->db->get();
        if($q->num_rows() > 0)
            return $this->buildAllMeasuresments($q->result());
        return false;
    }

    function buildAllMeasuresments($rows){
        $this->load->library('Dashboard_library');
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

    function deleteValue($valId, $user, $validation){
        if($validation){
          return $this->db->delete($this->value, array('id'=>$valId));
        }
        $data = array('state'=>-1,
                      'updater'=>$user, 
                      'dateup'=>date('Y-m-d H:i:s'),
                      'modified'=>1);
        $this->db->where('id', $valId);
        return $this->db->update($this->value, $data);
    }

    function deleteData($id){
        $this->db->where('id', $id);
        return $this->db->delete($this->value);
    }

    function insertData($id_met, $year, $valueY, $valueX, $target, $expected, $user, $validation){ //Inserta datos en la tabla de mediciones
        $data = array('metorg' => $id_met,
                     'state' => $validation,
                     'year' => $year,
                     'updater' => $user,
                     'dateup' => date('Y-m-d H:i:s')
        );
        if ($validation) {
            $data['validator'] = $user;
            $data['dateval'] = date('Y-m-d H:i:s');
            $data['value'] = $valueY;
            $data['x_value'] = $valueX;
            $data['target'] = $target;
            $data['expected'] = $expected;
        }
        else{
            $data['proposed_value'] = $valueY;
            $data['proposed_x_value'] = $valueX;
            $data['proposed_target'] = $target;
            $data['proposed_expected'] = $expected;
        }
        return ($this->db->insert($this->value, $data)) ? $this->db->insert_id() : false;
    }

    function updateData($id_met, $year, $old_x_value, $valueY, $valueX, $target, $expected, $user, $validation){ //Aqui hay que guardar datos antiguos
        $values = $this->getValue(array('metorg'=>[$id_met], 'year'=>[$year], 'x_value'=>[$old_x_value], 'state'=>[1]));
        if (count($values)<=0)
            return false;
        $row = $values[0];
        $data = array(
            'metorg'=>$id_met,
            'year'=>$year,
            'updater'=>$user,
            'dateup'=>date('Y-m-d H:i:s')
        );
        if(!$validation){
            $data['value'] = $row->value;
            $data['x_value']= $row->x_value;
            $data['target'] = $row->target;
            $data['expected'] = $row->expected;
            $data['state'] = 0;
            $data['modified'] = 1;
            if(strcmp($row->value, $valueY)!=0)
                $data['proposed_value'] = $valueY;
            if(strcmp($row->target, $target)!=0)
                $data['proposed_target'] = $target;
            if(strcmp($row->expected, $expected)!=0)
                $data['proposed_expected'] = $expected;
            if(strcmp($row->x_value, $valueX)!=0)
                $data['proposed_x_value'] = $valueX;
        }
        else{
            $data['value'] = $valueY;
            $data['x_value']= $valueX;
            $data['target'] = $target;
            $data['expected'] = $expected;
            $data['state'] = 1;
            $data['validator'] = $user;
            $data['dateval'] = date('Y-m-d H:i:s');
        }
        if($data['state']==0){
            $result = (($this->db->insert($this->value, $data)) ? true : false);
            if($old_x_value!=$valueX){
                $this->db->from($this->value);
                $this->db->where('state', 1);
                $this->db->where('id !=', $row->id);
                $this->db->where('metorg', $id_met);
                $this->db->where('x_value', $old_x_value);
                $this->updateValuesWith($this->db->get()->result(), array('proposed_x_value'=>$valueX), 0);
            }
        }
        else{
            $this->db->where('id', $row->id);
            $result = $this->db->update($this->value, $data);
            if(strcmp($row->x_value, $valueX)!=0) {
                $values = $this->getValue(array('metorg' => [$id_met], 'x_value' => [$old_x_value], 'id !=' => [$row->id]));
                $this->updateValuesWith($values, array('x_value' => $valueX), 1);
            }
            if(strcmp($row->expected, $expected)!=0 || strcmp($row->target, $target)!=0 || strcmp($row->value, $valueY)!=0){
                $values = $this->getValue(array('metorg' => [$id_met], 'year'=>[$year],'x_value' => [$old_x_value], 'id !=' => [$row->id]));
                $this->updateValuesWith($values, array('value' => $valueY, 'expected' => $expected, 'target' => $target, 'x_value' => $valueX), 1);
            }
        }
        return $result;
    }

    function updateValuesWith($values, $data, $validated){
        $this->load->library('session');
        $result = true;
        foreach ($values as $value){
            if($validated){
                $this->db->where('id', $value->id);
                $result = $result && $this->db->update($this->value, $data);
            }
            else{
                $data['metorg'] = $value->metorg;
                $data['year'] = $value->year;
                $data['updater'] = $this->session->userdata('rut');
                $data['dateup'] = date('Y-m-d H:i:s');
                $data['value'] = $value->value;
                $data['x_value'] = $value->x_value;
                $data['target'] = $value->target;
                $data['expected'] = $value->expected;
                $data['state'] = 0;
                $data['modified'] = 1;
                $result = $result && (($this->db->insert($this->value, $data)) ? true : false);
            }
        }
        return $result;
    }

	function validateData($id){
        $this->load->library('session');
		$query = $this->db->get_where('Value',array('id' => $id));
		$value = $query->row();
        $old_x = $value->x_value;
        if($value->state==-1)
            return $this->deleteData($id);
		$data = array(
            'state' => 1,
            'validator' => $this->session->userdata('rut'),
            'modified' => 0
        );
        if(!is_null($value->proposed_value)) {
            $data['value'] = $value->proposed_value;
            $data['proposed_value'] = null;
        }
        if(!is_null($value->proposed_target)) {
            $data['target'] = $value->proposed_target;
            $data['proposed_target'] = null;
        }
        if(!is_null($value->proposed_expected)) {
            $data['expected'] = $value->proposed_expected;
            $data['proposed_expected'] = null;
        }
        if(!is_null($value->proposed_x_value)) {
            $data['x_value'] = $value->proposed_x_value;
            $data['proposed_x_value'] = null;
        }
        $this->db->where('id', $id);
        $this->db->set('dateval', 'NOW()', FALSE);
		$q=$this->db->update($this->value,$data);
		$this->_overrrideData($value->metorg, $value->year);
        $query = $this->db->get_where('Value',array('id' => $id));
        $value = $query->row();
        $values = $this->getValue(array('metorg'=>[$value->metorg], 'year'=>[$value->year], 'x_value'=>[$old_x], 'id !='=>[$id]));
        $this->updateValuesWith($values, array('value'=>$value->value, 'expected'=>$value->expected, 'target'=>$value->target, 'x_value'=>$value->x_value), 1);
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
            $row =  $q->row();;
            return ($row->state==1) ? false : true ;
		}
        return false;
	}


    function getAllNonValidatedData($orgs, $type){
        $orgs = (is_null($orgs)) ? [] : $orgs;
        $this->db->select('Value.id as data_id, Value.metorg, Value.state AS s, Value.value, Value.x_value, Value.target, Value.expected, Value.proposed_value as p_v, Value.proposed_target as p_t, Value.proposed_expected as p_e, Value.proposed_x_value as p_x, Value.modified, Value.year');
        $this->db->select('User.name, Organization.name as org_name, Metric.y_name, Metric.x_name, Y.name as type_y, X.name as type_x, Category.name as category');
        $this->db->from('Value');
        $this->db->join('User', 'User.id = Value.updater');
        $this->db->join('MetOrg', 'MetOrg.id = Value.metorg');
        $this->db->join('Organization', 'MetOrg.org = Organization.id');
        $this->db->join('Metric', 'MetOrg.metric = Metric.id');
        $this->db->join('Unit as X', 'X.id = Metric.x_unit');
        $this->db->join('Unit as Y', 'Y.id = Metric.y_unit');
        $this->db->join('Category', 'Metric.category = Category.id');
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
        $dashboard = getGeneric($this, $this->title, array('org'=>[$id], 'limit'=>1));
        if(count($dashboard) <= 0)
            return false;
        $dashboard = $dashboard[0]->id;
        $graphs = getGeneric($this, $this->GD, array('dashboard'=>[$dashboard]));
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
            $metric = getGeneric($this, 'MetOrg', array('id'=>[$met->getMetOrg()], 'limit'=>1));
            if(count($metric) <= 0)
                return false; //Si llego acá hay problemas
            $metric = $metric[0]->metric;

            $y_name = getGeneric($this, 'Metric', array('id'=>[$metric], 'limit'=>1));
            if(count($y_name) <= 0)
                return false;
            $y_name = $y_name[0]->y_name;

            $this->db->select('id, metorg as org, value, x_value, target, expected, year');
            $this->db->from('Value');
            $this->db->where('state', 1);
            $this->db->where('metorg', $met->getMetOrg());
            $this->db->where('year >=', $met->getMinYear());
            $this->db->where('year <=', $met->getMaxYear());
            $this->db->order_by('year', 'ASC');
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
        $this->db->or_where('state', 1);
        $this->db->where('Organization.id', $id_org);
        $this->db->where('Graphic.metorg', $id_met);
        $this->db->where('Value.metorg', $id_met);
        $this->db->where('Value.year <= Graphic.max_year');
        $this->db->where('Value.year >= Graphic.min_year');
        $this->db->order_by('year', 'ASC');
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
