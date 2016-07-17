<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SendEmail extends CI_Controller{

    function __construct()    {
        parent::__construct();
        $this->load->library('session');
    }

    public function contact() {
        $work = true;
        if ($this->input->method() == "post") {
            $this->load->library('email');

            $this->email->from($this->input->post('email'), $this->input->post('name'));
            $this->email->to('UDasboard@gmail.com');

            $this->email->subject($this->input->post('topic'));
            $this->email->message($this->input->post('message'));

            if (!$this->email->send()) {
                $work = false;
            } else {
                redirect('inicio');
            }
        }
        $this->load->view('contact', array('work' => $work));
    }

    public function testEmail($to = null){
        if (!is_cli() || is_null($to))
            return;
        $this->load->helper('email');
        if (!valid_email($to)){
            echo "email no válido".PHP_EOL;
            return;
        }
        echo $to.PHP_EOL;
        $this->load->library('email');

        $this->email->from("UDasboard@gmail.com", "U-Dashboard");
        $this->email->to($to);

        $this->email->subject("test");
        $this->email->message("U-Dashboard manda mails :D!!");
        echo ((!$this->email->send(false)) ? "false" : "true").PHP_EOL;
        echo $this->email->print_debugger();
    }

    public function sendToValidateEmails(){
        if (!is_cli())
            return;
        $fodaURL = base_url()."fodaStrategy";
        $metricURL = base_url()."validar";
        $positions = ['coordinador', 'director'];
        $users = $this->getUsers($positions);
        $result = true;
        foreach ($users as $user){
            $this->load->helper('email');
            if (!valid_email($user->email)){
                echo "email de ".$user->name.": ".$user->email." no válido".PHP_EOL;
                $result = false;
                continue;
            }
            $message = "Hola ".$user->name.", se requiere su validación de los siguientes elementos:".PHP_EOL;
            if (stristr($user->short_name, $positions[0])) {
                $this->load->model('Foda_model');
                $this->load->model('Strategy_model');

                $fodas = $this->getToValidate($this->Foda_model, 'getFoda', $user->org);
                $plans = $this->getToValidate($this->Strategy_model, 'getStrategicPlan', $user->org);
                $metric = $this->areMetricsToValidate([$user->org], ['proposed_value!='=>[null]]);
            }
            else if (stristr($user->short_name, $positions[1])){
                $this->load->model('Organization_model');
                $fodas = [];
                $plans = [];
                $metric = $this->areMetricsToValidate($this->Organization_model->getAllIds(), ['proposed_x_value!='=>[''], 'proposed_target!='=>[null], 'proposed_expected!='=>[null]]);
            }
            if(!count($plans) && !count($fodas) && !$metric)
                continue;

            if(count($plans)){
                $message .= " - Plan Estratégico: Tienes información que validar de ".$user->org_name." (".$this->getComaSeparatedYears($plans).")".PHP_EOL;
            }
            if (count($fodas)){
                $message .= " - FODA : Tienes información que validar de ".$user->org_name." (".$this->getComaSeparatedYears($fodas).")".PHP_EOL;
            }
            if ($metric){
                $message .= " - Tienes valores de métricas que validar.".PHP_EOL;
                $message .= "Los valores de las métricas se validan en ".$metricURL.PHP_EOL;
            }
            if(count($plans) || count($fodas))
                $message .= "El FODA y el plan estratégico se validan en ".$fodaURL.PHP_EOL;

            $this->load->library('email');

            $this->email->from("UDasboard@gmail.com", "U-Dashboard");
            $this->email->to($user->email);
            $this->email->subject("Validaciones pendientes U-Dashboard");
            $this->email->message($message);

            $result = $this->email->send(false) && $result;
            echo $this->email->print_debugger();
        }
        echo PHP_EOL.($result ? 1 : 0).PHP_EOL;
    }

    private function getToValidate($model, $fun, $org){
        return $model->$fun(['org'=>[$org], 'validated'=>[0], 'order'=>[['year', 'ASC']]]);
    }

    private function getComaSeparatedYears($arr){
        $result = "";
        foreach ($arr as $element){
            $result .= $element->year.",";
        }
        return substr($result, 0, -1);
    }

    private function areMetricsToValidate($orgs, $data){
        $this->load->model('Values_model');
        $this->load->model('Metorg_model');
        foreach ($orgs as $org) {
            $m = [];
            $metrics = $this->Metorg_model->getMetOrg(['org' => [$org]]);
            foreach ($metrics as $metric) {
                $m[] = $metric->id;
            }
            foreach ($data as $key => $dato) {
                $filter = [];
                $filter[$key] = $dato;
                $filter['metorg'] = $m;
                $filter['state'] = [0, -1];
                print_r($this->Values_model->getValue($filter));
                if (count($this->Values_model->getValue($filter)))
                    return true;
            }
        }
        return false;
    }

    private function getUsers($positions){
        $this->load->model('Organization_model');
        $this->load->model('User_model');
        $result = [];
        foreach ($positions as $position){
            $users = $this->User_model->getUsersByPositionName($position);
            foreach ($users as $user) {
                $org = $this->Organization_model->getByID($user->org);
                $user->org_name = $org->getName();
                $result[] = $user;
            }
        }
        return $result;
    }
}