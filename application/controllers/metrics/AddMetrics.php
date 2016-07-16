<?php

/**
 * Created by PhpStorm.
 * User: farodrig
 * Date: 16-07-16
 * Time: 16:20
 */
class AddMetrics extends CI_Controller{

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('Dashboard_model');
        $this->load->model('Dashboardconfig_model');
        $this->load->model('Metorg_model');
        $this->load->model('Metrics_model');
        if (is_null($this->session->rut))
            redirect('salir');
    }

    // funcion que lista todas las metricas y las deja como objeto cada una por lo tanto se puede recorrer el arreglo
    // y llamar a cada valor del arreglo como liberia ejemplo mas abajo
    // esto sirve para cuando se llama de una vista para completar por ejemplo una tabla
    function index(){
        $org= $this->input->get('org');//Es el id de área, unidad, etc que se este considerando
        if (is_null($org))
            redirect('inicio');

        $permits = $this->session->userdata();
        $prod = in_array($org, $permits['foda']['edit']) || in_array($org, $permits['metaP']['edit']);
        $finan = in_array($org, $permits['valorF']['edit']) || in_array($org, $permits['metaF']['edit']);

        //Se obtienen las metricas correspondientes a los permisos del usuario, junto con las mediciones correspondientes
        if ($prod && $finan){
            $cat = 0;
        } elseif ($prod) {
            $cat = 1;
        } elseif ($finan) {
            $cat = 2;
        } else {
            redirect('inicio');
        }
        $all_metrics      = $this->Dashboard_model->getAllMetrics($org, $cat, 0);
        $all_metrics = !$all_metrics ? [] : $all_metrics;
        $metrics = [];
        foreach ($all_metrics as $metric){
            $metric->x_values = $this->Dashboard_model->getAllXValuesByMetorg($metric->metorg);
            $metrics[$metric->metorg] = $metric;
        }
        $all_measurements = $this->separeteValidData($this->Dashboard_model->getAllMeasurementsByUser($org, $cat, $permits['rut']));
        $res = defaultResult($permits, $this->Dashboard_model);
        $res['editP'] = in_array($org, $permits['foda']['edit']);
        $res['editF'] = in_array($org, $permits['valorF']['edit']);
        $res['editMetaP'] = in_array($org, $permits['metaP']['edit']);
        $res['editMetaF'] = in_array($org, $permits['metaF']['edit']);
        $res['valid'] = $all_measurements[0];
        $res['no_valid'] = $all_measurements[1];
        $res['metrics']      = $metrics;
        $res['route']        = getRoute($this, $org);
        $res['org']  = $org;
        $res['success']      = $this->session->flashdata('success') === null ? 2 : $this->session->flashdata('success');
        $this->load->view('add-data', $res);
    }

    //Función encargada de llamar a las funciones correspondientes del modelo, para poder actualizar valores en la base de datos
    function addData() {
        $org = $this->input->post("org");
        if (is_null($org)) {
            redirect('inicio');
        }

        $permits = $this->session->userdata();
        if (!(in_array($org, $permits['foda']['edit']) || in_array($org, $permits['metaP']['edit']) || in_array($org, $permits['valorF']['edit']) || in_array($org, $permits['metaF']['edit']))) {
            $this->session->set_flashdata('success', 0);
            redirect('formAgregarDato?org='.$org);
        }

        //Se validan los datos ingresados
        $this->form_validation->set_rules('year', 'Year', 'required|exact_length[4]|numeric');
        $this->form_validation->set_rules('metorg[]', 'Value', 'numeric');

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('success', 0);
            redirect('formAgregarDato?org='.$org);
        }

        $borrar = ($this->input->post('borrar')) ? 1 : 0;
        $user    = $this->session->userdata("rut");
        $year = $this->input->post('year');
        $success = true;

        if($borrar){
            foreach ($this->input->post('delete') as $valId){
                if(!$valId)
                    continue;
                $metorg = $this->Metorg_model->getMetOrgDataByValue($valId);
                $validation = ($metorg->category==1 ? in_array($org, $permits['metaP']['validate']) : in_array($org, $permits['metaF']['validate']));
                $success = $success && $this->Dashboard_model->deleteValue($valId, $user, $validation);
            }
            $success = ($success) ? 1:0;
            $this->session->set_flashdata('success', $success);
            redirect('formAgregarDato?org='.$org);
        }
        for($i = 0; $i<count($this->input->post('metorg')); $i++){
            $id_metorg = $this->input->post('metorg')[$i];
            $metorg = $this->Metorg_model->getMetOrg(array('id'=>[$id_metorg]))[0];
            $metric = $this->Metrics_model->getMetric(array('id'=>[$metorg->metric]))[0];
            $valId = $this->input->post('valId')[$i];
            $valueY = (strcmp($this->input->post('valueY')[$i], "null")==0 || strcmp($this->input->post('valueY')[$i], "")==0 ? null : $this->input->post('valueY')[$i]);
            $valueX = (strcmp($this->input->post('valueX')[$i], "null")==0 ? null : $this->input->post('valueX')[$i]);
            $target = (strcmp($this->input->post('target')[$i], "null")==0 || strcmp($this->input->post('target')[$i], "")==0 ? null : $this->input->post('target')[$i]);
            $expected = (strcmp($this->input->post('expected')[$i], "null")==0 || strcmp($this->input->post('expected')[$i], "")==0 ? null : $this->input->post('expected')[$i]);

            if($metric->category==1){
                $validValue = in_array($org, $permits['foda']['validate']);
                $validMeta = in_array($org, $permits['metaP']['validate']);
                if(!in_array($org, $permits['foda']['edit'])){
                    $valueY = null;
                }
                if(!in_array($org, $permits['metaP']['edit'])){
                    $valueX = null;
                    $target = null;
                    $expected = null;
                }
            }
            else{
                $validValue = in_array($org, $permits['valorF']['validate']);
                $validMeta = in_array($org, $permits['metaF']['validate']);
                if(!in_array($org, $permits['valorF']['edit'])){
                    $valueY = null;
                }
                if(!in_array($org, $permits['metaF']['edit'])){
                    $valueX = null;
                    $target = null;
                    $expected = null;
                }
            }

            if((!is_null($valueY) && !is_numeric($valueY)) || (!is_null($target) && !is_numeric($target)) || (!is_null($expected) && !is_numeric($expected))){
                $success = false;
                continue;
            }
            //si no hay valores saltamos los datos
            if ((strcmp($valueY, "")==0 || (is_null($valueY))) && (strcmp($valueX, "")==0 || is_null($valueX)) && (strcmp($expected, "")==0 ||  is_null($expected)) && (strcmp($target, "")==0 || is_null($target))) {
                continue;
            }

            //si no se tiene un id para el valor
            if (!$valId){
                if (strcmp($metric->x_name, "")==0)
                    $valueX = "";
                elseif (is_null($valueX)){
                    $success = false;
                    continue;
                }
                $oldVal = $this->Dashboard_model->getValue(array('metorg'=>[$id_metorg], 'year'=>[$year], 'x_value'=>[$valueX], 'state'=>[1]));
                //Si existia un valor previo, se debe hacer un update de la data, sino, simplemente se inserta.
                if (count($oldVal)==1){
                    $oldVal = $oldVal[0];
                    if ($valueY != $oldVal->value || $expected != $oldVal->expected || $target != $oldVal->target) {
                        if ($validMeta && $validValue)
                            $success = $success && $this->Dashboard_model->updateData($id_metorg, $year, $oldVal->x_value, $valueY, $valueX, $target, $expected, $user, 1);
                        elseif ($validMeta){
                            $success = $success && $this->Dashboard_model->updateData($id_metorg, $year, $oldVal->x_value, null, $valueX, $target, $expected, $user, 1);
                            $success = $success && $this->Dashboard_model->updateData($id_metorg, $year, $oldVal->x_value, $valueY, null, null, null, $user, 0);
                        }
                        elseif ($validValue){
                            $success = $success && $this->Dashboard_model->updateData($id_metorg, $year, $oldVal->x_value, $valueY, null, null, null, $user, 1);
                            $success = $success && $this->Dashboard_model->updateData($id_metorg, $year, $oldVal->x_value, null, $valueX, $target, $expected, $user, 0);
                        }
                        else
                            $success = $success && $this->Dashboard_model->updateData($id_metorg, $year, $oldVal->x_value, $valueY, $valueX, $target, $expected, $user, 0);
                    }
                }
                else if (count($oldVal)==0){
                    if ($validMeta && $validValue)
                        $success = $success && $this->Dashboard_model->insertData($id_metorg, $year, $valueY, $valueX, $target, $expected, $user, 1);
                    elseif ($validMeta){
                        $success = $success && $this->Dashboard_model->insertData($id_metorg, $year, null, $valueX, $target, $expected, $user, 1);
                        $success = $success && $this->Dashboard_model->insertData($id_metorg, $year, $valueY, null, null, null, $user, 0);
                    }
                    elseif ($validValue){
                        $success = $success && $this->Dashboard_model->insertData($id_metorg, $year, $valueY, null, null, null, $user, 1);
                        $success = $success && $this->Dashboard_model->insertData($id_metorg, $year, null, $valueX, $target, $expected, $user, 0);
                    }
                    else
                        $success = $success && $this->Dashboard_model->insertData($id_metorg, $year, $valueY, $valueX, $target, $expected, $user, 0);
                }
                //hubo un error, nunca debería haber más de 1 validado.
                else{
                    $success = false;
                    debug($oldVal);
                }
            }
            //si se tiene valId, ya existia el valor por tanto se debe actualizar
            else if($valId){
                $oldVal = $this->Dashboard_model->getValue(array('id'=>[$valId]))[0];
                if($oldVal->state == 0){
                    $oldVal->value = $oldVal->proposed_value != null ? $oldVal->proposed_value : $oldVal->value ;
                    $oldVal->expected = $oldVal->proposed_expected != null ? $oldVal->proposed_expected : $oldVal->expected ;
                    $oldVal->target = $oldVal->proposed_target != null ? $oldVal->proposed_target : $oldVal->target;
                    $oldVal->x_value = $oldVal->proposed_x_value !== null ? $oldVal->proposed_x_value : $oldVal->target;
                    $valueX = $valueX != null ? $valueX : "";
                }
                if ($valueY != $oldVal->value || strcmp($valueX, $oldVal->x_value)!=0 || $expected != $oldVal->expected || $target != $oldVal->target) {
                    if ($validMeta && $validValue)
                        $success = $success && $this->Dashboard_model->updateData($id_metorg, $year, $oldVal->x_value, $valueY, $valueX, $target, $expected, $user, 1);
                    elseif ($validMeta){
                        $success = $success && $this->Dashboard_model->updateData($id_metorg, $year, $oldVal->x_value, null, $valueX, $target, $expected, $user, 1);
                        $success = $success && $this->Dashboard_model->updateData($id_metorg, $year, $oldVal->x_value, $valueY, null, null, null, $user, 0);
                    }
                    elseif ($validValue){
                        $success = $success && $this->Dashboard_model->updateData($id_metorg, $year, $oldVal->x_value, $valueY, null, null, null, $user, 1);
                        $success = $success && $this->Dashboard_model->updateData($id_metorg, $year, $oldVal->x_value, null, $valueX, $target, $expected, $user, 0);
                    }
                    else {
                        $success = $success && $this->Dashboard_model->updateData($id_metorg, $year, $oldVal->x_value, $valueY, $valueX, $target, $expected, $user, 0);
                    }
                }
            }
            else{
                $success = false;
            }
        }
        $success = ($success) ? 1:0;
        $this->session->set_flashdata('success', $success);
        redirect('formAgregarDato?org='.$org);
    }

    private function separeteValidData($datos){
        $valid_data = [];
        $no_valid_data = [];
        if (!$datos)
            return [[],[]];
        foreach ($datos as $dato){
            if (is_null($dato->x_value))
                $dato->x_value = $dato->p_x;
            if($dato->state==0){
                $dato->value = is_null($dato->p_v) ? $dato->value : $dato->p_v;
                $dato->expected = is_null($dato->p_e) ? $dato->expected : $dato->p_e;
                $dato->target = is_null($dato->p_t) ? $dato->target : $dato->p_t;
                $x_val = $dato->x_value;
                $dato->x_value = is_null($dato->p_x) ? $dato->x_value : $dato->p_x;
                $no_valid_data[$dato->metorg][$dato->year][$x_val] = (array) $dato;
            }
            elseif ($dato->state == 1){
                $valid_data[$dato->metorg][$dato->year][$dato->x_value] = (array) $dato;
                if(!array_key_exists($dato->metorg, $no_valid_data) || !array_key_exists($dato->year, $no_valid_data[$dato->metorg]) || !array_key_exists($dato->x_value, $no_valid_data[$dato->metorg][$dato->year]))
                    $no_valid_data[$dato->metorg][$dato->year][$dato->x_value] = (array) $dato;
            }
        }
        return [$valid_data, $no_valid_data];
    }
}