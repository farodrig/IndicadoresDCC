<?php

function getGeneric($model, $db_name, $columns, $data){
    foreach ($columns as $column){
        if (array_key_exists($column, $data)){
            $model->db->group_start();
            foreach ($data[$column] as $value){
                $model->db->or_where($column, $value);
            }
            $model->db->group_end();
        }
    }
    if (array_key_exists('limit', $data)){
        $model->db->limit($data['limit']);
    }
    if (array_key_exists('order', $data)){
        foreach($data['order'] as $order){
            $model->db->order_by($order[0], $order[1]);
       }
    }
    $query = $model->db->get($db_name);
    return $query->result();
}