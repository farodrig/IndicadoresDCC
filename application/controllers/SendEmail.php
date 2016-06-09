<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MySession extends CI_Controller{

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
}