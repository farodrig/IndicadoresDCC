<?php
class Dashboard_model extends CI_Model{

    function __construct(){
        parent::__construct();
        $this->title = "Dashboard";
        $this->graphic = "Graphic";
        $this->value = "Value";
        $this->aggregation = "Aggregation_Type";
        $this->type = "Serie_Type";
        $this->org = "Organization";
        $this->serie = "Serie";
    }

    function getSerieType($data){
        return getGeneric($this, $this->type, $data);
    }

    function getAggregationType($data){
        return getGeneric($this, $this->aggregation, $data);
    }

    function getAllGraphicByOrg($org, $all){
        $this->db->select($this->graphic.'.id, '.$this->graphic.'.title, see_x, min_year, max_year, position, display');
        $this->db->from($this->graphic);
        $this->db->join($this->title, $this->graphic.'.dashboard = '.$this->title.'.id');
        $this->db->where($this->title.'.org', $org);
        if(!$all)
            $this->db->where($this->graphic.'.display', 1);
        $this->db->order_by($this->graphic.'.position', 'ASC');
        $q = $this->db->get();
        return $q->result();
    }

    function getAllSeriesByGraph($graph){
        return getGeneric($this, $this->serie, ['graphic'=>[$graph]]);
    }

    function getOrCreateDashboard($org){
        $this->db->from($this->title);
        $this->db->where('org', $org);
        $q = $this->db->get();
        if(count($q->result())==0){
            $organization = getGeneric($this, $this->org, ['id'=>[$org]]);
            if(count($organization)==0)
                return false;
            $organization = $organization[0];
            $this->db->insert($this->title, ['org'=>$org, 'title'=>'Dashboard '.$organization->name]);
            $id = $this->db->insert_id();
            $q = $this->db->get_where($this->title, array('id' => $id));
            return (count($q->result())==1 ? $q->row() : false);
        }
        return $q->row();
    }

    function addGraphic($title, $max, $min, $pos, $x, $dashboard, $display){
        $data = ['title'=>$title, 'max_year'=>$max, 'min_year'=>$min, 'position'=>$pos, 'see_x'=>$x, 'dashboard'=>$dashboard, 'display'=>$display];
        return ($this->db->insert($this->graphic, $data)) ? $this->db->insert_id() : false;
    }

    function modifyGraphic($id, $title, $max, $min, $pos, $x, $dashboard, $display){
        $this->db->where('id', $id);
        $data = ['title'=>$title, 'max_year'=>$max, 'min_year'=>$min, 'position'=>$pos, 'see_x'=>$x, 'dashboard'=>$dashboard, 'display'=>$display];
        return $this->db->update($this->graphic, $data);
    }

    function deleteGraphic($id){
        return $this->db->delete($this->graphic, ['id'=>$id]);
    }

    function addSerie($graphic, $metorg, $type, $x, $year, $color){
        $data = ['graphic'=>$graphic, 'metorg'=>$metorg, 'type'=>$type, 'x_aggregation'=>$x, 'year_aggregation'=>$year, 'color'=>$color];
        return ($this->db->insert($this->serie, $data)) ? $this->db->insert_id() : false;
    }

    function modifySerie($id, $graphic, $metorg, $type, $x, $year, $color){
        $this->db->where('id', $id);
        $data = ['graphic'=>$graphic, 'metorg'=>$metorg, 'type'=>$type, 'x_aggregation'=>$x, 'year_aggregation'=>$year, 'color'=>$color];
        return $this->db->update($this->serie, $data);
    }

    function deleteSerie($id){
        return $this->db->delete($this->serie, ['id'=>$id]);
    }

    function getAllXValuesByMetorg($metorg){
        $this->db->select('x_value, proposed_x_value');
        $this->db->from('Value');
        $this->db->join('MetOrg', 'MetOrg.id = Value.metorg');
        $this->db->where('MetOrg.id', $metorg);
        $this->db->where('state !=', -1);
        $this->db->order_by('x_value ASC');
        $this->db->order_by('proposed_x_value ASC');
        $q = $this->db->get();
        $values = $q->result();
        $result = [];
        foreach ($values as $value){
            $value->x_value = is_null($value->x_value) ? $value->proposed_x_value : $value->x_value;
            if (is_null($value->x_value) && is_null($value->proposed_x_value))
                continue;
            if (!is_null($value->x_value) && !in_array($value->x_value, $result))
                $result[] = $value->x_value;
            if (!is_null($value->proposed_x_value) && !in_array($value->proposed_x_value, $result))
                $result[] = $value->proposed_x_value;
        }
        return $result;
    }

    function getGraphicData($id){
        $graphic = getGeneric($this, $this->graphic, ['id' => [$id]]);
        if (count($graphic) == 0)
            return false;
        $graphic = $graphic[0];
        $this->load->model('Organization_model');
        $this->load->model('Metrics_model');
        $series = $this->getAllSeriesByGraph($graphic->id);
        $aux_series = [];
        $x_values = [];
        foreach ($series as $serie) {
            $metorg = $this->Metorg_model->getMetOrg(['id' => [$serie->metorg]])[0];
            $org = $this->Organization_model->getByID($metorg->org);
            $metric = $this->Metrics_model->getMetric(['id' => [$metorg->metric]])[0];
            if (!property_exists($graphic, 'y_name')) {
                $graphic->y_name = $metric->y_name;
                $graphic->y_unit = getGeneric($this, 'Unit', ['id' => [$metric->y_unit]])[0]->name;
                $graphic->x_name = ($graphic->see_x ? $metric->x_name : "Año");
            }

            $serie->name = $metric->name;
            $serie->org = $org->getName();
            $type = getGeneric($this, $this->type, ['id' => [$serie->type]])[0];
            $serie->type = $type->name;
            if ($graphic->see_x) {
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

            if (!$graphic->see_x) {
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
        $this->load->model('Organization_model');
        $this->load->model('Metrics_model');
        $series = $this->getAllSeriesByGraph($graphic->id);
        $aux_series = [];
        foreach ($series as $serie) {
            $metorg = $this->Metorg_model->getMetOrg(['id' => [$serie->metorg]])[0];
            $org = $this->Organization_model->getByID($metorg->org);
            $metric = $this->Metrics_model->getMetric(['id' => [$metorg->metric]])[0];
            if (!property_exists($graphic, 'y_name')) {
                $graphic->y_name = $metric->y_name;
                $graphic->y_unit = getGeneric($this, 'Unit', ['id' => [$metric->y_unit]])[0]->name;
                $graphic->x_name = ($graphic->see_x ? $metric->x_name : "Año");
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
}
