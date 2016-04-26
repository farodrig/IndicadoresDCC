<?php
class Dashboard_model extends CI_Model
{

    public $title;
    public $value;
    public $graphic;
    public $aggregation;
    public $type;

    function __construct()
    {
        parent::__construct();
        $this->title = "Dashboard";
        $this->graphic = "Graphic";
        $this->value = "Value";
        $this->aggregation = "Aggregation_Type";
        $this->type = "Serie_Type";
    }

    function getAllXValuesByMetorg($metorg){
        $this->db->select('x_value');
        $this->db->from('Value');
        $this->db->join('MetOrg', 'MetOrg.id = Value.metorg');
        $this->db->where('MetOrg.id', $metorg);
        $this->db->where('state !=', -1);
        $this->db->order_by('x_value ASC');
        $this->db->distinct();
        $q = $this->db->get();
        $values = $q->result();
        $result = [];
        foreach ($values as $value){
            $result[] = $value->x_value;
        }
        return $result;
    }

    function getGraphicData($id){
        $graphic = getGeneric($this, $this->graphic, ['id' => [$id]]);
        if (count($graphic) == 0)
            return false;
        $graphic = $graphic[0];
        $this->load->model('Dashboardconfig_model');
        $this->load->model('Organization_model');
        $this->load->model('Metrics_model');
        $series = $this->Dashboardconfig_model->getAllSeriesByGraph($graphic->id);
        $aux_series = [];
        $x_values = [];
        foreach ($series as $serie) {
            $metorg = $this->Metorg_model->getMetOrg(['id' => [$serie->metorg]])[0];
            $org = $this->Organization_model->getByID($metorg->org);
            $metric = $this->Metrics_model->getMetric(['id' => [$metorg->metric]])[0];
            if (!property_exists($graphic, 'y_name')) {
                $graphic->y_name = $metric->y_name;
                $graphic->y_unit = getGeneric($this, 'Unit', ['id' => [$metric->y_unit]])[0]->name;
                $graphic->x_name = ($graphic->ver_x ? $metric->x_name : "Año");
            }

            $serie->name = $metric->name;
            $serie->org = $org->getName();
            $type = getGeneric($this, $this->type, ['id' => [$serie->type]])[0];
            $serie->type = $type->name;
            if ($graphic->ver_x) {
                $serie->aggregation = getGeneric($this, $this->aggregation, ['id' => [$serie->year_aggregation]])[0]->name;
                $select = $this->getSelectFunction($serie->year_aggregation);
                $this->db->reset_query();
                $this->db->select("x_value as x");
                $this->db->group_by("x_value");
            } else {
                $serie->aggregation = getGeneric($this, $this->aggregation, ['id' => [$serie->x_aggregation]])[0]->name;
                $select = $this->getSelectFunction($serie->x_aggregation);
                $this->db->reset_query();
                $this->db->select("year as x");
                $this->db->group_by("year");
            }

            if ($select) {
                $this->db->$select('value');
                $this->db->$select('expected');
                $this->db->$select('target');
            }
            $this->db->from($this->value);
            $this->db->where($this->value . '.metorg', $serie->metorg);
            $this->db->where($this->value . '.year >=', $graphic->min_year);
            $this->db->where($this->value . '.year <=', $graphic->max_year);
            $this->db->where($this->value . '.state', 1);

            $this->db->order_by('x', 'ASC');
            $q = $this->db->get();
            $values = $q->result();

            if (!$graphic->ver_x) {
                for ($i = $graphic->min_year; $i <= $graphic->max_year; $i++) {
                    if (!in_array($i, $x_values))
                        $x_values[] = $i . "";
                }
            } else {
                foreach ($values as $value) {
                    if (!in_array($value->x, $x_values))
                        $x_values[] = $value->x;
                }
            }
            $serie->values = $values;
            $aux_series[] = $serie;
        }
        natcasesort($x_values);
        foreach ($aux_series as $serie) {
            $aux_values = [];
            foreach ($x_values as $x_value) {
                $exist = false;
                foreach ($serie->values as $value) {
                    if (strcmp($value->x, $x_value) == 0) {
                        $exist = true;
                        $aux_values[] = $value;
                        break;
                    }
                }
                if (!$exist)
                    $aux_values[] = (Object)['x' => $x_value . "", 'value' => "0"];
            }
            $serie->values = $aux_values;
        }
        $graphic->x_values = $x_values;
        $graphic->series = $aux_series;
        return $graphic;
    }

    function getAllGraphicData($id){
        $graphic = getGeneric($this, $this->graphic, ['id' => [$id]]);
        if (count($graphic) == 0)
            return false;
        $graphic = $graphic[0];
        $this->load->model('Dashboardconfig_model');
        $this->load->model('Organization_model');
        $this->load->model('Metrics_model');
        $series = $this->Dashboardconfig_model->getAllSeriesByGraph($graphic->id);
        $aux_series = [];
        foreach ($series as $serie) {
            $metorg = $this->Metorg_model->getMetOrg(['id' => [$serie->metorg]])[0];
            $org = $this->Organization_model->getByID($metorg->org);
            $metric = $this->Metrics_model->getMetric(['id' => [$metorg->metric]])[0];
            if (!property_exists($graphic, 'y_name')) {
                $graphic->y_name = $metric->y_name;
                $graphic->y_unit = getGeneric($this, 'Unit', ['id' => [$metric->y_unit]])[0]->name;
                $graphic->x_name = ($graphic->ver_x ? $metric->x_name : "Año");
            }

            $serie->name = $metric->name;
            $serie->org = $org->getName();
            $type = getGeneric($this, $this->type, ['id' => [$serie->type]])[0];
            $serie->type = $type->name;
            $this->db->from($this->value);
            $this->db->where($this->value . '.metorg', $serie->metorg);
            $this->db->where($this->value . '.year >=', $graphic->min_year);
            $this->db->where($this->value . '.year <=', $graphic->max_year);
            $this->db->where($this->value . '.state', 1);
            $this->db->order_by('year', 'ASC');
            $q = $this->db->get();
            $values = $q->result();
            $serie->values = $values;
            $aux_series[] = $serie;
        }
        $graphic->series = $aux_series;
        return $graphic;
    }

    private function getSelectFunction($aggreg){
        $aggreg = getGeneric($this, $this->aggregation, ['id' => [$aggreg]]);
        if (count($aggreg) == 0)
            return false;
        $aggreg = $aggreg[0];
        if (strcmp($aggreg->name, "Suma") == 0)
            return "select_sum";
        elseif (strcmp($aggreg->name, "Promedio") == 0)
            return "select_avg";
        elseif (strcmp($aggreg->name, "Máximo") == 0)
            return "select_max";
        elseif (strcmp($aggreg->name, "Mínimo") == 0)
            return "select_min";
        elseif (strcmp($aggreg->name, "") == 0)
            return "select";
        return false;
    }

    //Si hay más graficos por mostrar que los mostrados por defecto entrega true, para poner un boton y permitir mostrar los restantes.
    function showButton($id){
        $this->db->from($this->graphic);
        $this->db->join($this->title, $this->title.'.id = '.$this->graphic.'.dashboard');
        $this->db->where($this->graphic.'.display', 0);
        $this->db->where($this->title.'.org', $id);
        $q = $this->db->get();
        return count($q->result())>0;
    }

    function getValue($data){
        return getGeneric($this, $this->value, $data);
    }

    function getValidate($id_metorg, $permits){
        $valor = (count($permits['foda']['validate']) + count($permits['valorF']['validate'])) > 0;
        $meta = (count($permits['metaF']['validate']) + count($permits['metaP']['validate'])) > 0;
        if (!$valor && !$meta)
            return false;

        $prod = count($permits['foda']['validate']) + count($permits['metaP']['validate']);
        $fin = count($permits['valorF']['validate']) + count($permits['metaF']['validate']);
        if ($prod && $fin) {
            //no restringir la categoria
        } elseif ($prod) {
            $cat = 1;
        } elseif ($fin) {
            $cat = 2;
        }

        foreach ($id_metorg as $id) {
            $this->db->from('Value');
            $this->db->join('MetOrg', 'MetOrg.id = Value.metorg');
            $this->db->join('Metric', 'Metric.id = MetOrg.metric');
            $this->db->where('MetOrg.org', $id);
            $this->db->group_start();
            if ($meta) {
                $this->db->or_where('Value.state', -1);
            }
                $this->db->or_where('Value.state', 0);
            $this->db->group_end();
            if (isset($cat)) {
                $this->db->where('Metric.category', $cat);
            }
            $this->db->group_start();
            if ($valor){
                $this->db->or_where('Value.proposed_value !=', null);
            }
            if ($meta){
                $this->db->or_where('Value.state', -1);
                $this->db->or_where('Value.proposed_x_value !=', null);
                $this->db->or_where('Value.proposed_target !=', null);
                $this->db->or_where('Value.proposed_expected !=', null);
            }
            $this->db->group_end();
            $q = $this->db->get();
            if ($q->num_rows() > 0)
                return true;
        }
        return false;
    }

    function getBudgetMeasures($org)
    {
        $this->db->select('Value.id, Value.metorg AS org, value, target, expected, year, state, proposed_value as p_v, proposed_target as p_t, proposed_expected as p_e');
        $this->db->from('Value');
        $this->db->join('MetOrg', 'MetOrg.id = Value.metorg');
        $this->db->where('MetOrg.org', $org);
        $this->db->where('MetOrg.metric', 1);
        $this->db->where('state !=', -1);
        $this->db->order_by('year ASC');
        $this->db->order_by('state ASC');
        $q = $this->db->get();
        if ($q->num_rows() > 0)
            return $q->result();
        return false;
    }

    function getBudgetMeasure($org, $year, $order)
    {
        $this->db->select('Value.id, Value.metorg AS org, value, target, expected, year, state, proposed_value as p_v, proposed_target as p_t, proposed_expected as p_e');
        $this->db->from('Value');
        $this->db->join('MetOrg', 'MetOrg.id = Value.metorg');
        $this->db->where('MetOrg.org', $org);
        $this->db->where('MetOrg.metric', 1);
        $this->db->where('year', $year);
        $this->db->order_by('state', $order);
        $q = $this->db->get();
        return ($q->num_rows() > 0 ? $q->row() : false);
    }

    function updateCreateBudgetValue($org, $year, $val, $expected, $target, $validValue, $validMeta)
    {
        $this->load->model('Metorg_model');
        $q = $this->Metorg_model->getMetOrg(array('org' => [$org], 'metric' => [1]));
        if (count($q) != 1) {
            $q = $this->Metorg_model->addMetOrg(array('org' => $org, 'metric' => 1));
            if(!$q)
                return false;
            $q = $this->Metorg_model->getMetOrg(array('org' => [$org], 'metric' => [1]));
        }
        $metorg = $q[0]->id;
        $old_val = null;
        $old_expected = null;
        $old_target = null;
        $old_value = getGeneric($this, $this->value, ['metorg' => [$metorg], 'year' => [$year], 'state' => [0]]);
        if (!($validValue || $validMeta) && count($old_value)) {
            $old_value = $old_value[0];
            if ((strcmp($val, "") == 0 || is_null($val)) && !is_null($old_value->proposed_value) && !$validValue)
                $old_val = $old_value->proposed_value;
            if ((strcmp($expected, "") == 0 || is_null($expected)) && !is_null($old_value->proposed_expected) && !$validMeta)
                $old_expected = $old_value->proposed_expected;
            if ((strcmp($target, "") == 0 || is_null($target)) && !is_null($old_value->proposed_target) && !$validMeta)
                $old_target = $old_value->proposed_target;
            //Si existe un valor previo no validado es eliminado antes de proponer el otro, de forma que solo exista un dato por validar
            if (!$this->deleteData($old_value->id, true, true))
                return false;
        }
        $old_value = getGeneric($this, $this->value, ['metorg' => [$metorg], 'year' => [$year], 'state' => [1]]);
        if (count($old_value)) {
            if ($validMeta && $validValue)
                return $this->Dashboard_model->updateData($metorg, $year, "", $val, "", $target, $expected, $this->session->userdata("rut"), 1);
            elseif ($validMeta){
                $val = (is_null($val) ? $old_val : $val);
                $done = $this->Dashboard_model->updateData($metorg, $year, "", null, "", $target, $expected, $this->session->userdata("rut"), 1);
                return $done && $this->Dashboard_model->updateData($metorg, $year, "", $val, "", $old_target, $old_expected, $this->session->userdata("rut"), 0);
            }
            elseif ($validValue){
                $target = (is_null($target) ? $old_target : $target);
                $expected = (is_null($expected) ? $old_expected : $expected);
                $done = $this->Dashboard_model->updateData($metorg, $year, "", $val, "", null, null, $this->session->userdata("rut"), 1);
                return $done && $this->Dashboard_model->updateData($metorg, $year, "", $old_value, "", $target, $expected, $this->session->userdata("rut"), 0);
            }
            else {
                $val = (is_null($val) ? $old_val : $val);
                $target = (is_null($target) ? $old_target : $target);
                $expected = (is_null($expected) ? $old_expected : $expected);
                return $this->Dashboard_model->updateData($metorg, $year, "", $val, "", $target, $expected, $this->session->userdata("rut"), 0);
            }
        }
        if ($validMeta && $validValue)
            return $this->Dashboard_model->insertData($metorg, $year, $val, "", $target, $expected, $this->session->userdata("rut"), 1);
        elseif ($validMeta){
            $val = (is_null($val) ? $old_val : $val);
            $done = $this->Dashboard_model->insertData($metorg, $year, null, "", $target, $expected, $this->session->userdata("rut"), 1);
            return $done && $this->Dashboard_model->insertData($metorg, $year, $val, "", $old_target, $old_expected, $this->session->userdata("rut"), 0);
        }
        elseif ($validValue){
            $target = (is_null($target) ? $old_target : $target);
            $expected = (is_null($expected) ? $old_expected : $expected);
            $done = $this->Dashboard_model->insertData($metorg, $year, $val, "", null, null, $this->session->userdata("rut"), 1);
            return $done && $this->Dashboard_model->insertData($metorg, $year, $old_value, "", $target, $expected, $this->session->userdata("rut"), 0);
        }
        else {
            $val = (is_null($val) ? $old_val : $val);
            $target = (is_null($target) ? $old_target : $target);
            $expected = (is_null($expected) ? $old_expected : $expected);
            return $this->Dashboard_model->insertData($metorg, $year, $val, "", $target, $expected, $this->session->userdata("rut"), 0);
        }
    }

    function getAllMetrics($org, $category, $all)
    {
        $this->db->select('MetOrg.id as metorg, Metric.y_name, Metric.x_name, X.name as x_unit, Y.name as y_unit, Metric.name, Metric.category');
        $this->db->from('Metric');
        $this->db->join('MetOrg', 'MetOrg.metric = Metric.id');
        $this->db->join('Unit as X', 'X.id = Metric.x_unit');
        $this->db->join('Unit as Y', 'Y.id = Metric.y_unit');
        $this->db->join('Organization', 'Organization.id = MetOrg.org');
        $this->db->where('Organization.id', $org);
        if(!$all)
            $this->db->where('Metric.id !=', 1);

        if (!is_null($category) && $category != 0)
            $this->db->where('Metric.category', $category);

        $q = $this->db->get();
        return ($q->num_rows() > 0 ? $q->result() : false);
    }

    function getAllMeasurements($id, $category)
    {
        $this->db->select('MetOrg.id');
        $this->db->from('Metric');
        $this->db->join('MetOrg', 'MetOrg.metric = Metric.id');
        $this->db->join('Organization', 'MetOrg.org = Organization.id');
        $this->db->where('Organization.id', $id);
        $this->db->where('Metric.id !=', 1);
        
        if ($category != 0)
            $this->db->where('Metric.category', $category);

        $q = $this->db->get();
        $size = $q->num_rows();
        if ($size <= 0) {
            return false;
        }
        $rows = $q->result();

        $this->db->select('id, metorg AS org, value, x_value, target, expected, year');
        $this->db->from('Value');
        $this->db->where('state', 1);
        $this->db->group_start();
        for ($i = 0; $i < $size; $i++) {
            $this->db->or_where('metorg', $rows[$i]->id);
        }
        $this->db->group_end();
        $this->db->order_by('year ASC');
        $q = $this->db->get();
        return ($q->num_rows() > 0 ? $this->buildAllMeasuresments($q->result()) : false);
    }

    function buildAllMeasuresments($rows){
        $this->load->library('Dashboard_library');
        foreach ($rows as $row) {
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

    function deleteValue($valId, $user, $validation){
        $values = getGeneric($this, $this->value, ['id'=>[$valId]]);
        if (!count($values))
            return false;
        $value = $values[0];
        if ($validation) {
            $values = getGeneric($this, $this->value, ['metorg'=>[$value->metorg], 'year'=>[$value->year], 'x_value'=>[$value->x_value], 'id !='=>[$value->id], 'order'=>[['state', "ASC"]]]);
            $done = $this->db->delete($this->value, array('id' => $valId));
            foreach ($values as $v){
                $done = $done && $this->db->delete($this->value, array('id' => $v->id));
            }
            return $done;
        }

        $value->state = -1;
        $value->updater = $user;
        $value->dateup = date('Y-m-d H:i:s');
        unset($value->id, $value->validator, $value->dateval);
        return $this->db->insert($this->value, $value);
    }

    function deleteData($id, $validVal, $validMet){
        if ($validMet) {
            $this->db->where('id', $id);
            return $this->db->delete($this->value);
        }
        return false;
    }

    function insertData($id_met, $year, $valueY, $valueX, $target, $expected, $user, $validation)
    { //Inserta datos en la tabla de mediciones
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
        } else {
            $data['proposed_value'] = $valueY;
            $data['proposed_x_value'] = $valueX;
            $data['proposed_target'] = $target;
            $data['proposed_expected'] = $expected;
            if (is_null($valueY) && is_null($valueX) && is_null($target) && is_null($expected))
                return true;
        }
        return ($this->db->insert($this->value, $data)) ? $this->db->insert_id() : false;
    }

    function updateData($id_met, $year, $old_x_value, $valueY, $valueX, $target, $expected, $user, $validation)
    { //Aqui hay que guardar datos antiguos
        $values = $this->getValue(array('metorg' => [$id_met], 'year' => [$year], 'x_value' => [$old_x_value], 'state' => [1]));
        if (count($values) <= 0)
            return false;
        $row = $values[0];
        $data = array(
            'metorg' => $id_met,
            'year' => $year,
            'updater' => $user,
            'dateup' => date('Y-m-d H:i:s')
        );
        if (!$validation) {
            $data['value'] = $row->value;
            $data['x_value'] = $row->x_value;
            $data['target'] = $row->target;
            $data['expected'] = $row->expected;
            $data['state'] = 0;
            if (!is_null($valueY) && $row->value != $valueY)
                $data['proposed_value'] = $valueY;
            if (!is_null($target) && $row->target != $target)
                $data['proposed_target'] = $target;
            if (!is_null($expected) && $row->expected != $expected)
                $data['proposed_expected'] = $expected;
            if (!is_null($valueX) && strcmp($row->x_value, $valueX) != 0)
                $data['proposed_x_value'] = $valueX;
            if (!array_key_exists('proposed_value', $data) && !array_key_exists('proposed_target', $data) && !array_key_exists('proposed_expected', $data) && !array_key_exists('proposed_x_value', $data))
                return true;
        } else {
            $data['value'] = (is_null($valueY) ? $row->value : $valueY);
            $data['x_value'] = (is_null($valueX) ? $row->x_value : $valueX);
            $data['target'] = (is_null($target) ? $row->target : $target);
            $data['expected'] = (is_null($expected) ? $row->expected : $expected);
            $data['state'] = 1;
            $data['validator'] = $user;
            $data['dateval'] = date('Y-m-d H:i:s');
        }
        if ($data['state'] == 0) {
            $result = (($this->db->insert($this->value, $data)) ? true : false);
            if (!is_null($valueX) && $old_x_value != $valueX) {
                $this->db->from($this->value);
                $this->db->where('state', 1);
                $this->db->where('id !=', $row->id);
                $this->db->where('metorg', $id_met);
                $this->db->where('x_value', $old_x_value);
                $this->updateValuesWith($this->db->get()->result(), array('proposed_x_value' => $valueX), 0);
            }
        } else {
            $this->db->where('id', $row->id);
            $result = $this->db->update($this->value, $data);
            if (strcmp($row->x_value, $valueX) != 0) {
                $values = $this->getValue(array('metorg' => [$id_met], 'x_value' => [$old_x_value], 'id !=' => [$row->id]));
                $this->updateValuesWith($values, array('x_value' => $valueX), 1);
            }
            if (strcmp($row->expected, $expected) != 0 || strcmp($row->target, $target) != 0 || strcmp($row->value, $valueY) != 0) {
                $values = $this->getValue(array('metorg' => [$id_met], 'year' => [$year], 'x_value' => [$old_x_value], 'id !=' => [$row->id]));
                $this->updateValuesWith($values, array('value' => $valueY, 'expected' => $expected, 'target' => $target, 'x_value' => $valueX), 1);
            }
        }
        return $result;
    }

    function updateValuesWith($values, $data, $validated)
    {
        $this->load->library('session');
        $result = true;
        foreach ($values as $value) {
            if ($validated) {
                $this->db->where('id', $value->id);
                $result = $result && $this->db->update($this->value, $data);
            } else {
                $data['metorg'] = $value->metorg;
                $data['year'] = $value->year;
                $data['updater'] = $this->session->userdata('rut');
                $data['dateup'] = date('Y-m-d H:i:s');
                $data['value'] = $value->value;
                $data['x_value'] = $value->x_value;
                $data['target'] = $value->target;
                $data['expected'] = $value->expected;
                $data['state'] = 0;
                $result = $result && (($this->db->insert($this->value, $data)) ? true : false);
            }
        }
        return $result;
    }

    function validateData($id, $validVal, $validMet)
    {
        $this->load->library('session');
        $query = $this->db->get_where('Value', array('id' => $id));
        $value = $query->row();

        $old_x = (is_null($value->x_value) ? $value->proposed_x_value : $value->x_value);
        if ($value->state == -1) {
            $values = getGeneric($this, $this->value, ['metorg'=>[$value->metorg], 'year'=>[$value->year], 'x_value'=>[$value->x_value], 'id !='=>[$value->id], 'order'=>[['state', "ASC"]]]);
            $done = $this->deleteData($value->id, $validVal, $validMet);
            foreach ($values as $v){
                $done = $done && $this->deleteData($v->id, $validVal, $validMet);
            }
            return $done;
        }
        $value2 = clone $value;
        $data = array(
            'state' => 1,
            'validator' => $this->session->userdata('rut')
        );
        $this->load->model('Metorg_model');
        $metorg = $this->Metorg_model->getMetOrgDataByValue($value->id);
        if ($metorg->x_name==""){
            $data['x_value'] = "";
            $data['proposed_x_value'] = null;
            $value2->proposed_x_value = null;
        }
        if ($validVal && !is_null($value->proposed_value)) {
            $data['value'] = $value->proposed_value;
            $data['proposed_value'] = null;
            $value2->proposed_value = null;
        }
        if ($validMet && !is_null($value->proposed_target)) {
            $data['target'] = $value->proposed_target;
            $data['proposed_target'] = null;
            $value2->proposed_target = null;
        }
        if ($validMet && !is_null($value->proposed_expected)) {
            $data['expected'] = $value->proposed_expected;
            $data['proposed_expected'] = null;
            $value2->proposed_expected = null;
        }
        if ($validMet && !is_null($value->proposed_x_value)) {
            $data['x_value'] = $value->proposed_x_value;
            $data['proposed_x_value'] = null;
            $value2->proposed_x_value = null;
        }
        if((!array_key_exists('x_value', $data) || is_null($data['x_value'])) && (!array_key_exists('value', $data) || is_null($data['value'])) && (!array_key_exists('expected', $data) || is_null($data['expected'])) && (!array_key_exists('target', $data) || is_null($data['target'])))
            return false;
        if(!is_null($value2->proposed_value) || !is_null($value2->proposed_target) || !is_null($value2->proposed_expected) || !is_null($value2->proposed_x_value)){
            $data2 = array(
                'metorg' => $value->metorg,
                'year' => $value->year,
                'updater' => $value->updater,
                'dateup' => $value->dateup,
                'value' => $value->value,
                'x_value' => $value->x_value,
                'target' => $value->target,
                'expected' => $value->expected,
                'state' => 0,
                'proposed_value' => $value2->proposed_value,
                'proposed_target' => $value2->proposed_target,
                'proposed_expected' => $value2->proposed_expected,
                'proposed_x_value' => $value2->proposed_x_value
            );
            $data['proposed_value'] = null;
            $data['proposed_target'] = null;
            $data['proposed_expected'] = null;
            $data['proposed_x_value'] = null;
            $this->db->insert($this->value, $data2);
        }
        $data['dateval'] = date('Y-m-d H:i:s');
        $this->db->reset_query();
        $this->db->where('id', $id);
        $q = $this->db->update($this->value, $data);
        $id = $this->_overrideData($value->metorg, $value->year, $old_x);
        $query = $this->db->get_where($this->value, array('id' => $id));
        $value = $query->row();
        $values = $this->getValue(array('metorg' => [$value->metorg], 'year' => [$value->year], 'x_value' => [$old_x, null], 'id !=' => [$id]));
        $this->updateValuesWith($values, array('value' => $value->value, 'expected' => $value->expected, 'target' => $value->target, 'x_value' => $value->x_value), 1);
        return $q;
    }

    function _overrideData($metorg, $year, $xVal)
    {
        $this->db->order_by('dateval', 'DESC');
        $q = $this->db->get_where($this->value, array('metorg' => $metorg, 'year' => $year, 'x_value' => $xVal, 'state' => 1));
        $newData = $q->row();
        $q = $this->db->get_where($this->value, array('id !=' => $newData->id, 'state' => 1, 'year' => $newData->year, 'x_value' => $xVal, 'metorg' => $newData->metorg));
        foreach ($q->result() as $olderData) {
            $this->deleteData($olderData->id, true, true);
        }
        return $newData->id;
    }

    function checkIfValidate($id)
    {
        $q = $this->db->get_where('Value', array('id' => $id));
        if ($q->num_rows() > 0) {
            $row = $q->row();
            return ($row->state == 1) ? false : true;
        }
        return false;
    }


    function getAllNonValidatedData($orgs, $type)
    {
        $orgs = (is_null($orgs)) ? [] : $orgs;
        $this->db->select('Value.id as data_id, Value.metorg, Value.state AS s, Value.value, Value.x_value, Value.target, Value.expected, Value.proposed_value as p_v, Value.proposed_target as p_t, Value.proposed_expected as p_e, Value.proposed_x_value as p_x, Value.year');
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
        if (count($orgs) > 0) {
            $this->db->group_start();
            foreach ($orgs as $org) {
                $this->db->or_where('MetOrg.org', $org);
            }
            $this->db->group_end();
        }
        $q = $this->db->get();
        return $q->result();
    }
}