<?php

function getPermits($permits, $separator){
    $result = array();
    $helper = array('VIS'=>'visualizador',
                    'VEF'=>'ver_foda',
                    'MOF'=>'modif_foda',
                    'VAF'=>'valid_foda',
                    'ENF'=>'encargado_finanzas',
                    'ASF'=>'asistente_finanzas',
                    'ENP'=>'encargado_unidad',
                    'ASP'=>'asistente_unidad',
    );
    foreach ($permits as $permit) {
        $permisos = explode($separator, $permit->permit);
        foreach($permisos as $permiso){
            $permiso = trim($permiso);
            if (array_key_exists($permiso, $helper)){
                $result[$helper[$permiso]][] = $permit->org;
            }
        }
    }
    foreach($helper as $key){
        if(!array_key_exists($key, $result))
            $result[$key] = array();
    }
    return $result;
}

function getTitle($user, $permits) {
    $title = "";
    if ($user->isAdmin) {
        $title = "Administrador";
    } elseif (count($permits['encargado_unidad'])) {
        $title = trim("Encargado de unidad");
    } elseif (count($permits['encargado_finanzas'])) {
        $title = trim("Encargado de finanzas");
    } elseif (count($permits['asistente_unidad'])) {
        $title = trim("Asistente de unidad");
    } elseif (count($permits['asistente_finanzas'])) {
        $title = trim("Asistente de finanzas");
    } elseif (count($permits['visualizador'])) {
        $title = "Visualizador";
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

 function validation($permits_array, $model){
  if($permits_array['admin'])
    return $model->getValidate(-1);
  elseif(count($permits_array['encargado_unidad']) && count($permits_array['encargado_finanzas']))
    return $model->getValidate(array_merge($permits_array['encargado_unidad'], $permits_array['encargado_finanzas']));
  elseif(count($permits_array['encargado_unidad']))
      return $model->getValidate($permits_array['encargado_unidad']);
  elseif(count($permits_array['encargado_finanzas']))
      return $model->getValidate($permits_array['encargado_finanzas']);
  return false;
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