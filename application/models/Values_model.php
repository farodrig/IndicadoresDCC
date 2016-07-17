<?php

/**
 * Created by PhpStorm.
 * User: farodrig
 * Date: 16-07-16
 * Time: 19:08
 */
class Values_model extends CI_Model{

    function __construct(){
        parent::__construct();
        $this->title = "Value";
        $this->graphic = "Graphic";
        $this->aggregation = "Aggregation_Type";
        $this->type = "Serie_Type";
    }

    function getValue($data){
        return getGeneric($this, $this->title, $data);
    }

    //Inserta datos en la tabla de valores
    function insertData($id_met, $year, $valueY, $valueX, $target, $expected, $user, $validation){
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
            $data['x_value'] = $valueX;
            $data['proposed_value'] = $valueY;
            $data['proposed_x_value'] = $valueX;
            $data['proposed_target'] = $target;
            $data['proposed_expected'] = $expected;
            if (is_null($valueY) && is_null($valueX) && is_null($target) && is_null($expected))
                return true;
        }
        return ($this->db->insert($this->title, $data)) ? $this->db->insert_id() : false;
    }

    //Aqui hay que guardar datos antiguos
    function updateData($id_met, $year, $old_x_value, $valueY, $valueX, $target, $expected, $user, $validation){
        $values = $this->getValue(array('metorg' => [$id_met], 'year' => [$year], 'x_value' => [$old_x_value], 'state' => [1]));
        if (count($values) <= 0) {
            $valueX = $valueX==null ? $old_x_value : $valueX;
            return $this->insertData($id_met, $year, $valueY, $valueX, $target, $expected, $user, $validation);
        }
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
            $result = (($this->db->insert($this->title, $data)) ? true : false);
            if (!is_null($valueX) && $old_x_value != $valueX) {
                $this->db->from($this->title);
                $this->db->where('state', 1);
                $this->db->where('id !=', $row->id);
                $this->db->where('metorg', $id_met);
                $this->db->where('x_value', $old_x_value);
                $this->updateValuesWith($this->db->get()->result(), array('proposed_x_value' => $valueX), 0);
            }
        } else {
            $this->db->where('id', $row->id);
            $result = $this->db->update($this->title, $data);
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

    private function updateValuesWith($values, $data, $validated){
        $this->load->library('session');
        $result = true;
        foreach ($values as $value) {
            if ($validated) {
                $this->db->where('id', $value->id);
                $result = $result && $this->db->update($this->title, $data);
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
                $result = $result && (($this->db->insert($this->title, $data)) ? true : false);
            }
        }
        return $result;
    }

    function validateData($id, $validVal, $validMet){
        $this->load->library('session');
        $query = $this->db->get_where($this->title, array('id' => $id));
        $value = $query->row();

        $old_x = $value->x_value;
        if ($value->state == -1) {
            $values = $this->getValue(['metorg'=>[$value->metorg], 'year'=>[$value->year], 'x_value'=>[$value->x_value], 'id !='=>[$value->id], 'order'=>[['state', "ASC"]]]);
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
            $this->db->insert($this->title, $data2);
        }
        $data['dateval'] = date('Y-m-d H:i:s');
        $this->db->reset_query();
        $this->db->where('id', $id);
        $q = $this->db->update($this->title, $data);
        if (!is_null($old_x))
            $this->overrideData($value->metorg, $value->year, $old_x, $id);
        $query = $this->db->get_where($this->title, array('id' => $id));
        $value = $query->row();
        $values = $this->getValue(array('metorg' => [$value->metorg], 'year' => [$value->year], 'x_value' => [$old_x, null], 'id !=' => [$id]));
        $this->updateValuesWith($values, array('value' => $value->value, 'expected' => $value->expected, 'target' => $value->target, 'x_value' => $value->x_value), 1);
        return $q;
    }

    private function overrideData($metorg, $year, $xVal, $valID){
        $this->db->order_by('dateval', 'DESC');
        $q = $this->db->get_where($this->title, array('metorg' => $metorg, 'year' => $year, 'x_value' => $xVal, 'state' => 1, 'id!=' => $valID));
        foreach ($q->result() as $olderData) {
            $this->deleteData($olderData->id, true, true);
        }
    }

    function deleteValue($valId, $user, $validation){
        $values = $this->getValue(['id'=>[$valId]]);
        if (!count($values))
            return false;
        $value = $values[0];
        if ($validation) {
            $values = $this->getValue(['metorg'=>[$value->metorg], 'year'=>[$value->year], 'x_value'=>[$value->x_value], 'id !='=>[$value->id], 'order'=>[['state', "ASC"]]]);
            $done = $this->db->delete($this->title, array('id' => $valId));
            foreach ($values as $v){
                $done = $done && $this->db->delete($this->title, array('id' => $v->id));
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
            return $this->db->delete($this->title);
        }
        return false;
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
            $this->db->from($this->title);
            $this->db->join('MetOrg', 'MetOrg.id = Value.metorg');
            $this->db->join('Metric', 'Metric.id = MetOrg.metric');
            $this->db->where('MetOrg.org', $id);
            $this->db->group_start();
            if ($meta) {
                $this->db->or_where('state', -1);
            }
            $this->db->or_where('state', 0);
            $this->db->group_end();
            if (isset($cat)) {
                $this->db->where('category', $cat);
            }
            $this->db->group_start();
            if ($valor){
                $this->db->or_where('proposed_value !=', null);
            }
            if ($meta){
                $this->db->or_where('state', -1);
                $this->db->or_where('proposed_x_value !=', null);
                $this->db->or_where('proposed_target !=', null);
                $this->db->or_where('proposed_expected !=', null);
            }
            $this->db->group_end();
            $q = $this->db->get();
            if ($q->num_rows() > 0)
                return true;
        }
        return false;
    }

    function getAllValuesByUserByOrg($org, $category, $user){
        $this->db->select('Value.id, metorg, state, value, x_value, target, expected, year, proposed_value as p_v, proposed_target as p_t, proposed_expected as p_e, proposed_x_value as p_x');
        $this->db->from($this->title);
        $this->db->join('MetOrg', 'MetOrg.id = Value.metorg');
        $this->db->join('Metric', 'MetOrg.metric = Metric.id');
        $this->db->join('Organization', 'MetOrg.org = Organization.id');
        $this->db->where('Organization.id', $org);
        $this->db->where('Metric.id !=', 1);
        if ($category != 0)
            $this->db->where('category', $category);

        $this->db->group_start();
            $this->db->or_where('state', 1);
            $this->db->or_where('updater', $user);
        $this->db->group_end();
        $this->db->order_by('dateup ASC');
        $q = $this->db->get();
        return ($q->num_rows() > 0 ? $q->result() : false);
    }

    function checkIfValidate($id){
        $q = $this->db->get_where($this->title, array('id' => $id));
        if ($q->num_rows() > 0) {
            $row = $q->row();
            return ($row->state == 1) ? false : true;
        }
        return false;
    }
}