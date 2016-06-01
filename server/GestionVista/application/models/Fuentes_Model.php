<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class Fuentes_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerFuentes(){
		$this->load->database();
		$query = $this->db->get(fuentes);			
			
		$listaFuentes = $query->result(); 
		return $listaFuentes;
 	}
	
	public function obtenerFuentesJson(){
		$this->load->database();
		$query = $this->db->get(fuentes);	
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaFuentes[] = $row; 
		}
			return json_encode($listaFuentes);
  }	

	public function insertar($obj){
		$this->load->database();
		$this->db->insert("fuentes", $obj);
	}

	public function actualizar($obj){

		$this->load->database();
		$this->db->where("FuenteID", $obj["FuenteID"]); 
		$result = $this->db->get("fuentes");
		if ($result->num_rows() == 1)
		{
			$Fuentes =  current($result->result()); 
			foreach ($Fuentes as $key => $value) {
				if($key == "FuenteID"){ continue; }							

				if( array_key_exists($key, $obj)){					
					$Fuentes->$key = $obj[$key];
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
		$this->db->where("FuenteID", $id); 
		$result = $this->db->get("fuentes");		

		if ($result->num_rows() == 0)
		{
			return null; 
		}
		return current($result->result()); 
	}
	
 }
 ?>