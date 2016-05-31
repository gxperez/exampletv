<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class UtilFechasActivas_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerUtilFechasActivas(){
		$this->load->database();
		$query = $this->db->query("SELECT * FROM util_fechas_activas");
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaUtilFechasActivas[] = new UtilFechasActivas( $row['Fecha'], $row['DiaSemana'], $row['Abreviado'], $row['Literal'], $row['DiaNombre'], $row['Dia'], $row['Mes'], $row['Anio']); 
 
		}
			return $listaUtilFechasActivas;
 	}
	
	public function obtenerUtilFechasActivasJson(){
		$this->load->database();
		$query = $this->db->get(util_fechas_activas);	
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaUtilFechasActivas[] = $row; 
		}
			return json_encode($listaUtilFechasActivas);
  }	

	public function insertar($obj){

		$this->db->insert("util_fechas_activas", $obj); 			

	}

	public function actualizar($obj){

		$this->db->where("Fecha", $obj["Fecha"]); 
		$result = $this->db->get("util_fechas_activas");
		if ($result->num_rows() == 1)
		{
			$UtilFechasActivas =  current($result->result()); 
			$this->db->where("Fecha", $UtilFechasActivas->Fecha);
			$rs = $this->db->update("util_fechas_activas", $UtilFechasActivas); 
			return $rs; 
		} else {
			return false;
		}
	}

	public function ObtenerPorID($id){
		$this->db->where("Fecha", $id); 
		$result = $this->db->get("util_fechas_activas");		

		if ($result->num_rows() == 0)
		{
			return null; 
		}

		return current($result->result()); 
	}
	
 }
 ?>