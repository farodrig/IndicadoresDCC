<?php

/**
 * Created by PhpStorm.
 * User: farodrig
 * Date: 16-07-16
 * Time: 18:55
 */
class Budget_model extends CI_Model{

    function __construct(){
        parent::__construct();
        $this->load->model('Dashboard_model');
        $this->load->model('Values_model');
        $this->load->model('Metorg_model');
    }

    function getMeasures($org){
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

    function getMeasure($org, $year, $order){
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

    function validateValue($valueId, $valValue, $valMeta, $left_value){
        $old_value = $this->Values_model->getValue(['id' => [$valueId]]);
        if (!count($old_value))
            return false;

        $old_value = $old_value[0];
        $old_value->value = (is_null($old_value->value) ? 0 : $old_value->value);
        $old_value->target = (is_null($old_value->target) ? 0 : $old_value->target);
        $old_value->expected = (is_null($old_value->expected) ? 0 : $old_value->expected);

        $e = $old_value->expected;
        $t = $old_value->target;
        $v = $old_value->value;

        if ($valMeta && !is_null($left_value->proposed_expected)) {
            $e = $old_value->expected - $left_value->expected + $left_value->proposed_expected;
        }
        if ($valMeta && !is_null($left_value->proposed_target)) {
            $t = $old_value->target - $left_value->target + $left_value->proposed_target;
        }
        if ($valValue && !is_null($left_value->proposed_value)){
            $v = $old_value->value - $left_value->value + $left_value->proposed_value;
        }
        return $this->Values_model->updateData($old_value->metorg, $old_value->year, "", $v, "", $t, $e, $this->session->userdata("rut"), 1);
    }

    function updateCreateValue($org, $year, $val, $expected, $target, $validValue, $validMeta){
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
        $old_value = $this->Values_model->getValue(['metorg' => [$metorg], 'year' => [$year], 'state' => [0]]);
        if (!($validValue || $validMeta) && count($old_value)) {
            $old_value = $old_value[0];
            if ((strcmp($val, "") == 0 || is_null($val)) && !is_null($old_value->proposed_value) && !$validValue)
                $old_val = $old_value->proposed_value;
            if ((strcmp($expected, "") == 0 || is_null($expected)) && !is_null($old_value->proposed_expected) && !$validMeta)
                $old_expected = $old_value->proposed_expected;
            if ((strcmp($target, "") == 0 || is_null($target)) && !is_null($old_value->proposed_target) && !$validMeta)
                $old_target = $old_value->proposed_target;
            //Si existe un valor previo no validado es eliminado antes de proponer el otro, de forma que solo exista un dato por validar
            if (!$this->Values_model->deleteData($old_value->id, true, true))
                return false;
        }
        $old_value = $this->Values_model->getValue(['metorg' => [$metorg], 'year' => [$year], 'state' => [1]]);
        if (count($old_value)) {
            if ($validMeta && $validValue)
                return $this->Values_model->updateData($metorg, $year, "", $val, "", $target, $expected, $this->session->userdata("rut"), 1);
            elseif ($validMeta){
                $val = (is_null($val) ? $old_val : $val);
                $done = $this->Values_model->updateData($metorg, $year, "", null, "", $target, $expected, $this->session->userdata("rut"), 1);
                return $done && $this->Values_model->updateData($metorg, $year, "", $val, "", $old_target, $old_expected, $this->session->userdata("rut"), 0);
            }
            elseif ($validValue){
                $target = (is_null($target) ? $old_target : $target);
                $expected = (is_null($expected) ? $old_expected : $expected);
                $done = $this->Values_model->updateData($metorg, $year, "", $val, "", null, null, $this->session->userdata("rut"), 1);
                return $done && $this->Values_model->updateData($metorg, $year, "", $old_value, "", $target, $expected, $this->session->userdata("rut"), 0);
            }
            else {
                $val = (is_null($val) ? $old_val : $val);
                $target = (is_null($target) ? $old_target : $target);
                $expected = (is_null($expected) ? $old_expected : $expected);
                return $this->Values_model->updateData($metorg, $year, "", $val, "", $target, $expected, $this->session->userdata("rut"), 0);
            }
        }
        if ($validMeta && $validValue)
            return $this->Values_model->insertData($metorg, $year, $val, "", $target, $expected, $this->session->userdata("rut"), 1);
        elseif ($validMeta){
            $val = (is_null($val) ? $old_val : $val);
            $done = $this->Values_model->insertData($metorg, $year, null, "", $target, $expected, $this->session->userdata("rut"), 1);
            return $done && $this->Values_model->insertData($metorg, $year, $val, "", $old_target, $old_expected, $this->session->userdata("rut"), 0);
        }
        elseif ($validValue){
            $target = (is_null($target) ? $old_target : $target);
            $expected = (is_null($expected) ? $old_expected : $expected);
            $done = $this->Values_model->insertData($metorg, $year, $val, "", null, null, $this->session->userdata("rut"), 1);
            return $done && $this->Values_model->insertData($metorg, $year, $old_value, "", $target, $expected, $this->session->userdata("rut"), 0);
        }
        else {
            $val = (is_null($val) ? $old_val : $val);
            $target = (is_null($target) ? $old_target : $target);
            $expected = (is_null($expected) ? $old_expected : $expected);
            return $this->Values_model->insertData($metorg, $year, $val, "", $target, $expected, $this->session->userdata("rut"), 0);
        }
    }


}