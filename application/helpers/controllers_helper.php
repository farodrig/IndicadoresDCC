<?php

function alphaSpace($str){
    if (preg_match("^([a-zA-Zñáéíóú]\s?)+^", $str, $data) && $data[0]==$str){
        return true;
    }
    else{
        $this->form_validation->set_message('alphaSpace', 'El campo {field} contiene caracteres no alfabeticos o espacios');
        return false;
    }
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
  if($permits_array['director'])
    return $model->getValidate(-1);
  elseif(!in_array(-1,$permits_array['encargado_unidad']) && !in_array(-1,$permits_array['encargado_finanzas_unidad']))
    return $model->getValidate(-1);
  elseif(!in_array(-1,$permits_array['encargado_unidad']))
      return $model->getValidate($permits_array['encargado_unidad']);
  elseif(!in_array(-1,$permits_array['encargado_finanzas_unidad']))
      return $model->getValidate($permits_array['encargado_finanzas_unidad']);
  return  false;
}
