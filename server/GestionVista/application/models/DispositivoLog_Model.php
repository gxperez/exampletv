<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class DispositivoLog_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerDispositivoLog(){
		$this->load->database();
		$query = $this->db->get(dispositivo_log);			
			
		$listaDispositivoLog = $query->result(); 
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
		$this->load->database();
		$this->db->insert("dispositivo_log", $obj);
	}

	public function actualizar($obj){

		$this->load->database();
		$this->db->where("Dispositivo_log_ID", $obj["Dispositivo_log_ID"]); 
		$result = $this->db->get("dispositivo_log");
		if ($result->num_rows() == 1)
		{
			$DispositivoLog =  current($result->result()); 
			foreach ($DispositivoLog as $key => $value) {
				if($key == "Dispositivo_log_ID"){ continue; }							

				if( array_key_exists($key, $obj)){					
					$DispositivoLog->$key = $obj[$key];
				}
			}
			
			$this->db->where("BloqueContenidoID", $BloqueContenido->BloqueContenidoID);
			$rs = $this->db->update("bloque_contenido", $BloqueContenido); 			
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