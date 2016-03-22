<?php

function getGeneric($model, $db_name, $data){
    foreach ($data as $key=>$value){
        if (strcmp($key,'limit')==0){
            $model->db->limit($data['limit']);
        }
        else if (strcmp($key,'order')==0){
            foreach($data['order'] as $order){
                $model->db->order_by($order[0], $order[1]);
            }
        }
        else if (is_array($data[$key])){
            $model->db->group_start();
            foreach ($data[$key] as $value){
                $model->db->or_where($key, $value);
            }
            $model->db->group_end();
        }
    }
    $query = $model->db->get($db_name);
    return $query->result();
}

function get_or_create($model, $data, $column){
    //get
    $q = $model->db->get_where($model->title, $data, 1);
    if($q->num_rows())
        return (isset($column)) ? $q->result_array()[0][$column] : $q->result_array()[0];
    //create
    return ($model->db->insert($model->title, $data)) ?  $model->db->insert_id() : false;
}