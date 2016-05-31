<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class DispositivoLog_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerDispositivoLog(){
		$this->load->database();
		$query = $this->db->query("SELECT * FROM dispositivo_log");
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaDispositivoLog[] = new DispositivoLog( $row['Dispositivo_log_ID'], $row['DispositivoID'], $row['Estatus'], $row['FechaHoraInicio'], $row['FechaHoraFin'], $row['FechaCrea']); 
 
		}
			return $listaDispositivoLog;
 	}
	
	public function obtenerDispositivoLogJson(){
		$this->load->database();
		$query = $this->db->get(dispositivo_log);	
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaDispositivoLog[] = $row; 
		}
			return json_encode($listaDispositivoLog);
  }	

	public function insertar($obj){

		$this->db->insert("dispositivo_log", $obj); 			

	}

	public function actualizar($obj){

		$this->db->where("Dispositivo_log_ID", $obj["Dispositivo_log_ID"]); 
		$result = $this->db->get("dispositivo_log");
		if ($result->num_rows() == 1)
		{
			$DispositivoLog =  current($result->result()); 
			$this->db->where("Dispositivo_log_ID", $DispositivoLog->Dispositivo_log_ID);
			$rs = $this->db->update("dispositivo_log", $DispositivoLog); 
			return $rs; 
		} else {
			return false;
		}
	}

	public function ObtenerPorID($id){
		$this->db->where("Dispositivo_log_ID", $id); 
		$result = $this->db->get("dispositivo_log");		

		if ($result->num_rows() == 0)
		{
			return null; 
		}

		return current($result->result()); 
	}
	
 }
 ?>