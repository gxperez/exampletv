<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class Dispositivo_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerDispositivo(){
		$this->load->database();
		$query = $this->db->get('dispositivo');			
			
		$listaDispositivo = $query->result(); 
		return $listaDispositivo;
 	}
	
	public function obtenerDispositivoJson(){
		$this->load->database();
		$query = $this->db->get("dispositivo");	
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaDispositivo[] = $row; 
		}
			return json_encode($listaDispositivo);
  }	

	public function insertar($obj){
		$this->load->database();
// Filtrar y Validar LA EXISTENCIA DE LOS Campos en la Entidad.
		$this->db->insert("dispositivo", $obj);
		return $this->db->insert_id();
	}

	public function actualizar($obj){

		$this->load->database();
		$this->db->where("DispositivoID", $obj["DispositivoID"]); 
		$result = $this->db->get("dispositivo");
		if ($result->num_rows() == 1)
		{
			$Dispositivo =  current($result->result()); 
			foreach ($Dispositivo as $key => $value) {
				if($key == "DispositivoID"){ continue; }							

				if( array_key_exists($key, $obj)){					
					$Dispositivo->$key = $obj[$key];
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
		$this->db->where("DispositivoID", $id); 
		$result = $this->db->get("dispositivo");		

		if ($result->num_rows() == 0)
		{
			return null; 
		}
		return current($result->result()); 
	}
	
 }
 ?>