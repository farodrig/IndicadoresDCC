<?php

function defaultResult($permits, $model){
    return array('validate'  => validation($permits, $model),
                 'validator' => count(array_merge($permits['foda']['validate'], $permits['metaP']['validate'], $permits['valorF']['validate'], $permits['metaF']['validate'])) > 0,
                 'admin'	 => (count($permits['conf']['edit']) + count($permits['conf']['view'])) > 0,
                 'foda'		 => seeFODAStrategy($permits),
                 'budget'    => seeBudget($permits),
                 'role'      => $permits['title']
    );
    
}

function getPermits($permits){
    $result = array();
    $resources = array('foda', 'metaP', 'valorF', 'metaF', 'pos', 'conf', 'permit');
    $actions = array('view', 'edit', 'validate');
    foreach ($permits as $permit) {
        if($permit->view){
            $result[$resources[$permit->resource-1]]['view'][] = $permit->org;
        }
        if($permit->edit){
            $result[$resources[$permit->resource-1]]['edit'][] = $permit->org;
        }
        if($permit->validate){
            $result[$resources[$permit->resource-1]]['validate'][] = $permit->org;
        }
    }
    foreach($resources as $resource){
        if(!array_key_exists($resource, $result))
            $result[$resource] = array();
        foreach ($actions as $action)
            if(!array_key_exists($action, $result[$resource]))
                $result[$resource][$action] = array();
    }
    return $result;
}

function getTitle($user) {
    $title = "";
    if ($user->isAdmin) {
        $title = "Administrador";
    }
    return $title;
}

function getAllOrgsByDpto($model){
    $roots = $model->getDepartment();
    $result = array();
    $type = $model->getTypeById($roots[0]->getType());
    if($type['name']=="Soporte"){
        $roots = array_reverse($roots);
    }
    foreach ($roots as $root){
        $areas = $model->getAllChilds($root->getId());
        $ars = array();
        foreach ($areas as $area){
            array_push($ars, array('area'=>$area, 'unidades'=>$model->getAllUnidades($area->getId())));
        }
        array_push($result, array('type'=> $model->getTypeById($root->getType()), 'department'=>$root, 'areas'=>$ars));
    }
    return $result;
}

 function validation($permits, $model){
    if($permits['admin'])
        return $model->getValidate(-1, $permits);
    return $model->getValidate(array_merge($permits['foda']['validate'], $permits['metaP']['validate'], $permits['valorF']['validate'], $permits['metaF']['validate']), $permits);
}

function getRoute($controller, $id){
    $controller->load->model('Organization_model');

    $aux=$id;
    $aux_id = 0;
    $i = 1;
    $organization = null;

    while($aux_id!=$aux){
        $org = $controller->Organization_model->getByID($aux);
        if ($i==1)
            $organization = $org;
        $route[$i] = ucwords($org->getName());

        $aux_id = $aux;
        $aux = $org->getParent();
        $i++;
    }
    //Elimina DCC de la ruta para elementos q no sean el DCC
    if($id!=0 && $id!=1)
        $i--;

    //Añade el tipo de organización al final de la ruta
    $type = $controller->Organization_model->getTypeById($organization->getType());
    if(!is_null($type) && $type['name']!="")
        $route[$i] = $type['name'];
    return $route;
}

function alphaSpace($str){
    if (preg_match("^([a-zA-ZñáéíóúÁÉÍÓÚÑü]\s?)+^", $str, $data) && $data[0]==$str){
        return true;
    }
    return false;
}

function alphaNumericSpace($str){
    if (preg_match("^([a-zA-Z0-9ñáéíóúÁÉÍÓÚÑü]\s?)+^", $str, $data) && $data[0]==$str){
        return true;
    }
    return false;
}

function date_validator($str){
    if (preg_match("/^(0[1-9]|1[0-9]|2[0-9]|3(0|1))-(0[1-9]|1[0-2])-\d{4}$/", $str, $data) && $data[0]==$str){
        return true;
    }
    return false;
}

function color_validator($str){
    if (preg_match("/^#([a-fA-F0-9]{6})$/", $str, $data) && $data[0]==$str){
        return true;
    }
    return false;
}

function seeBudget($permits){
    $value = count($permits['valorF']['view']) + count($permits['valorF']['edit']) + count($permits['valorF']['validate']);
    $expected = count($permits['metaF']['view']) + count($permits['metaF']['edit']) + count($permits['metaF']['validate']);
    return ($value + $expected) > 0;
}

function seeFODAStrategy($permits){
    return (count($permits['foda']['view']) + count($permits['foda']['edit']) + count($permits['conf']['validate'])) > 0;
}

function array2csv($array, $title, $x_name, $y_name, $all){
    if (count($array) == 0)
        return null;
    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    if (strpos($user_agent, "Win") !== FALSE)
        $eol = "\r\n";
    else
        $eol = "\n";

    ob_start();
    $df = fopen("php://output", 'w');
    fwrite($df, "[" . $title . "]" . $eol);
    if($all && $x_name==""){
        fwrite($df, "Métrica,Año,". $y_name . ",Esperado,Meta" . $eol);
        foreach ($array as $row) {
            $a = $row->metric . "," . $row->year . "," . $row->value . ',' . $row->expected . ',' . $row->target . $eol;
            fwrite($df, $a);
        }
    }
    elseif ($all && $x_name!=""){
        fwrite($df, "Métrica,Año,". $x_name .",". $y_name . ",Esperado,Meta" . $eol);
        foreach ($array as $row) {
            $a = $row->metric . "," . $row->year . "," .$row->x_value.",". $row->value . ',' . $row->expected . ',' . $row->target . $eol;
            fwrite($df, $a);
        }
    }
    else{
        fwrite($df, "Métrica," . $x_name . "," . $y_name . ",Esperado,Meta" . $eol);
        foreach ($array as $row) {
            $a = $row->metric . "," . $row->x . ',' . $row->value . ',' . $row->expected . ',' . $row->target . $eol;
            fwrite($df, $a);
        }
    }
    fclose($df);
    return ob_get_clean();
}

function download_send_headers($filename){
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