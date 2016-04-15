<?php
class Dashboard_model extends CI_Model
{

    public $title;
    public $GD;
    public $value;
    public $graphic;
    public $aggregation;
    public $type;

    function __construct()
    {
        parent::__construct();
        $this->title = "Dashboard";
        $this->GD = "GraphDash";
        $this->graphic = "Graphic";
        $this->value = "Value";
        $this->aggregation = "Aggregation_Type";
        $this->type = "Serie_Type";
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
            $this->db->where($this->value . '.state', 1);
            $this->db->where($this->value . '.year >=', $graphic->min_year);
            $this->db->where($this->value . '.year <=', $graphic->max_year);
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
            $this->db->from($this->value);
            $this->db->where($this->value . '.metorg', $serie->metorg);
            $this->db->where($this->value . '.state', 1);
            $this->db->where($this->value . '.year >=', $graphic->min_year);
            $this->db->where($this->value . '.year <=', $graphic->max_year);
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
        $this->db->from($this->GD);
        $this->db->join($this->title, $this->title.'.id = '.$this->GD.'.dashboard');
        $this->db->where($this->GD.'.display', 0);
        $this->db->where($this->title.'.org', $id);
        $q = $this->db->get();
        return count($q->result())>0;
    }

    function getValue($data){
        return getGeneric($this, $this->value, $data);
    }

    function getValidate($id_metorg){
        if ($id_metorg == -1) {
            $datos = getGeneric($this, $this->value, ['state' => [-1, 0]]);
            return (count($datos) > 0);
        }
        $this->load->library('session');
        $encargado_unidad = $this->session->userdata('encargado_unidad');
        $encargado_finanzas = $this->session->userdata("encargado_finanzas");
        if (count($encargado_unidad) && count($encargado_finanzas)) {
            //no restringir la categoria
        } elseif (count($encargado_unidad)) {
            $cat = 1;
        } elseif (count($encargado_finanzas)) {
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
            if (isset($cat)) {
                $this->db->where('Metric.category', $cat);
            }
            $q = $this->db->get();
            return ($q->num_rows() > 0);
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
        if ($q->num_rows() > 0)
            return $q->row();
        return false;
    }

    function updateCreateBudgetValue($org, $year, $y_value, $expected, $target, $validation)
    {
        $this->load->model('Metorg_model');
        $q = $this->Metorg_model->getMetOrg(array('org' => [$org], 'metric' => [1]));
        if (count($q) != 1) {
            return false;
        }
        $id = $q[0]->id;
        $old_value = getGeneric($this, $this->value, ['metorg' => [$id], 'year' => [$year], 'state' => [0]]);
        if (!$validation && count($old_value)) {
            $old_value = $old_value[0];
            if (strcmp($y_value, "") == 0 && !is_null($old_value->proposed_value))
                $y_value = $old_value->proposed_value;
            if (strcmp($expected, "") == 0 && !is_null($old_value->proposed_expected))
                $expected = $old_value->proposed_expected;
            if (strcmp($target, "") == 0 && !is_null($old_value->proposed_target))
                $target = $old_value->proposed_target;
            //Si existe un valor previo no validado es eliminado antes de proponer el otro, de forma que solo exista un dato por validar
            if (!$this->deleteData($old_value->id))
                return false;
        }
        $old_value = getGeneric($this, $this->value, ['metorg' => [$id], 'year' => [$year], 'state' => [1]]);
        if (count($old_value)) {
            return $this->updateData($id, $year, "", $y_value, "", $target, $expected, $this->session->userdata("rut"), $validation);
        }
        return $this->insertData($id, $year, $y_value, "", $target, $expected, $this->session->userdata("rut"), $validation);
    }

    function getAllMetrics($id, $category, $all)
    {
        $this->db->select('MetOrg.id as metorg, Metric.y_name, Metric.x_name, X.name as x_unit, Y.name as y_unit, Metric.name, Metric.category');
        $this->db->from('Metric');
        $this->db->join('MetOrg', 'MetOrg.metric = Metric.id');
        $this->db->join('Unit as X', 'X.id = Metric.x_unit');
        $this->db->join('Unit as Y', 'Y.id = Metric.y_unit');
        $this->db->join('Organization', 'Organization.id = MetOrg.org');
        $this->db->where('Organization.id', $id);
        if(!$all)
            $this->db->where('Metric.id !=', 1);

        if (!is_null($category) && $category != 0)
            $this->db->where('Metric.category', $category);

        $q = $this->db->get();
        if ($q->num_rows() > 0)
            return $q->result();
        return false;
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
        if ($q->num_rows() > 0)
            return $this->buildAllMeasuresments($q->result());
        return false;
    }

    function buildAllMeasuresments($rows)
    {
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
        if ($validation) {
            return $this->db->delete($this->value, array('id' => $valId));
        }
        $data = array('state' => -1,
            'updater' => $user,
            'dateup' => date('Y-m-d H:i:s'),
            'modified' => 1);
        $this->db->where('id', $valId);
        return $this->db->update($this->value, $data);
    }

    function deleteData($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->value);
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
            $data['modified'] = 1;
            if (strcmp($row->value, $valueY) != 0)
                $data['proposed_value'] = $valueY;
            if (strcmp($row->target, $target) != 0)
                $data['proposed_target'] = $target;
            if (strcmp($row->expected, $expected) != 0)
                $data['proposed_expected'] = $expected;
            if (strcmp($row->x_value, $valueX) != 0)
                $data['proposed_x_value'] = $valueX;
        } else {
            $data['value'] = $valueY;
            $data['x_value'] = $valueX;
            $data['target'] = $target;
            $data['expected'] = $expected;
            $data['state'] = 1;
            $data['validator'] = $user;
            $data['dateval'] = date('Y-m-d H:i:s');
        }
        if ($data['state'] == 0) {
            $result = (($this->db->insert($this->value, $data)) ? true : false);
            if ($old_x_value != $valueX) {
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
                $data['modified'] = 1;
                $result = $result && (($this->db->insert($this->value, $data)) ? true : false);
            }
        }
        return $result;
    }

    function validateData($id)
    {
        $this->load->library('session');
        $query = $this->db->get_where('Value', array('id' => $id));
        $value = $query->row();
        $old_x = $value->x_value;
        if ($value->state == -1)
            return $this->deleteData($id);
        $data = array(
            'state' => 1,
            'validator' => $this->session->userdata('rut'),
            'modified' => 0
        );
        if (!is_null($value->proposed_value)) {
            $data['value'] = $value->proposed_value;
            $data['proposed_value'] = null;
        }
        if (!is_null($value->proposed_target)) {
            $data['target'] = $value->proposed_target;
            $data['proposed_target'] = null;
        }
        if (!is_null($value->proposed_expected)) {
            $data['expected'] = $value->proposed_expected;
            $data['proposed_expected'] = null;
        }
        if (!is_null($value->proposed_x_value)) {
            $data['x_value'] = $value->proposed_x_value;
            $data['proposed_x_value'] = null;
        }
        $this->db->where('id', $id);
        $this->db->set('dateval', 'NOW()', FALSE);
        $q = $this->db->update($this->value, $data);
        $this->_overrrideData($value->metorg, $value->year);
        $query = $this->db->get_where('Value', array('id' => $id));
        $value = $query->row();
        $values = $this->getValue(array('metorg' => [$value->metorg], 'year' => [$value->year], 'x_value' => [$old_x], 'id !=' => [$id]));
        $this->updateValuesWith($values, array('value' => $value->value, 'expected' => $value->expected, 'target' => $value->target, 'x_value' => $value->x_value), 1);
        return $q;
    }

    function _overrrideData($metorg, $year)
    {
        $this->db->order_by('dateval', 'DESC');
        $q = $this->db->get_where('Value', array('metorg' => $metorg, 'year' => $year, 'state' => 1));
        $newData = $q->row();
        $q = $this->db->get_where('Value', array('id !=' => $newData->id, 'state' => 1, 'year' => $newData->year, 'metorg' => $newData->metorg));
        foreach ($q->result() as $olderData) {
            $this->deleteData($olderData->id);
        }
    }

    function checkIfValidate($id)
    {
        $q = $this->db->get_where('Value', array('id' => $id));
        if ($q->num_rows() > 0) {
            $row = $q->row();;
            return ($row->state == 1) ? false : true;
        }
        return false;
    }


    function getAllNonValidatedData($orgs, $type)
    {
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