<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permits_library
{
  private $user;
	private $director;
	private $visualizador;
	private $asistente_unidad;
	private $asistente_finanzas_unidad;
  private $encargado_finanzas_unidad;
	private $encargado_unidad;
	private $asistente_dcc;


	function initialize($parameters)
	{
		$this->user = element('user', $parameters);
		$this->director = element('director', $parameters);
        $this->visualizador = element('visualizador', $parameters);
        $this->asistente_unidad = element('asistente_unidad', $parameters);
        $this->asistente_finanzas_unidad = element('asistente_finanzas_unidad', $parameters);
        $this->encargado_finanzas_unidad = element('encargado_finanzas_unidad', $parameters);
        $this->encargado_unidad = element('encargado_unidad', $parameters);
        $this->asistente_dcc = element('asistente_dcc', $parameters);
		return $this;
	}

 /**
     * @return the $user
     */
    public function getUser()
    {
        return $this->user;
    }

     /**
     * @return the $director
     */
    public function getDirector()
    {
        return $this->director;
    }

     /**
     * @return the $visualizador
     */
    public function getVisualizador()
    {
        return $this->visualizador;
    }

     /**
     * @return the $asistente_unidad
     */
    public function getAsistenteUnidad()
    {
        return $this->asistente_unidad;
    }

    /**
        * @return the $encargado_finanzas_unidad
        */
       public function getEncargadoFinanzasUnidad()
       {
           return $this->encargado_finanzas_unidad;
       }

 /**
     * @return the $asistente_finanzas_unidad
     */
    public function getAsistenteFinanzasUnidad()
    {
        return $this->asistente_finanzas_unidad;
    }

/**
     * @return the $encargado_unidad
     */
    public function getEncargadoUnidad()
    {
        return $this->encargado_unidad;
    }

 /**
     * @return the $asistente_dcc
     */
    public function getAsistenteDCC()
    {
        return $this->asistente_dcc;
    }


}
