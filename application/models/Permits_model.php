<?php
class Permits_model extends CI_Model
{
    //Retorna todos los roles de un usuario
    function getAllPermits($user){ //Retorna false si usuario no existe en sistema => redireccionar a login
        $query = "SELECT * FROM Permits WHERE user= ?";

        $q = $this->db->query($query, array($user));
        if($q->num_rows()==1)
            return $this->buildPermits($q);
        else
            return false;
    }

    //Construye los permisos
    function buildPermits($q)
    {
        $this->load->library('Permits_library');
        $row = $q->result();
        foreach ($q->result() as $row)
        {
            $parameters = array
            (
                'user' => $row->user,
                'director' => $row->director, //Se puede hacer esto porque es una flag
                'visualizador' => $row->visualizer, //Se puede hacer esto porque es una flag
                'asistente_unidad' => explode(" ", $row->assistant_unidad),
                'asistente_finanzas_unidad' => explode(" ", $row->finances_assistant_unidad),
                'encargado_finanzas_unidad' => explode(" ", $row->in_charge_unidad_finances),
                'asistente_dcc' => $row->dcc_assistant, //Se puede hacer esto porque es una flag
                'encargado_unidad' => explode(" ", $row->in_charge_unidad)
            );

            $permits = new Permits_library();
            $permits = $permits->initialize($parameters);

        }

        return $permits;
    }

    //Permite obtner el nombre de una organizacion
    function getName($org){
      $query = "SELECT org.name AS name FROM Organization AS org WHERE org.id= ?";
      $q = $this->db->query($query, array($org));

      if($q->num_rows()==1){
          $row = $q->result()[0];
          return ucwords($row->name);
      }
      else
          return false;

    }

}
