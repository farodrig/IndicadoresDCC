<?php
class Permits_model extends CI_Model
{

    function getAllPermits($user){ //Retorna false si usuario no existe en sistema => redireccionar a login
        $query = "SELECT * FROM Permits WHERE user= ?";

        $q = $this->db->query($query, array($user));
        if($q->num_rows()==1)
            return $this->buildPermits($q);
        else
            return false;
    }

    function buildPermits($q) 
    {
        $this->load->library('Permits_library');
        $row = $q->result();
        foreach ($q->result() as $row)
        {
            $parameters = array
            (
                'user' => $row->user,
                'director' => $row->director,
                'visualizador' => $row->visualizer,
                'asistente_unidad' => $row->assistant_unidad,
                'asistente_finanzas_unidad' => $row->finances_assistant_unidad,
                'asistente_dcc' => $row->dcc_assistant,
                'encargado_unidad' => $row->in_charge_unidad
            );

            $permits = new Permits_library();
            $permits = $permits->initialize($parameters);

        }

        return $permits;
    }

}