<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class Bloques_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerBloques(){
		$this->load->database();
		$query = $this->db->get(bloques);			
			
		$listaBloques = $query->result(); 
		return $listaBloques;
 	}
	
	public function obtenerBloquesJson(){
		$this->load->database();
		$query = $this->db->get(bloques);	
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaBloques[] = $row; 
		}
			return json_encode($listaBloques);
  }	

	public function insertar($obj){
		$this->load->database();
		$this->db->insert("bloques", $obj);
	}

	public function actualizar($obj){

		$this->load->database();
		$this->db->where("BloqueID", $obj["BloqueID"]); 
		$result = $this->db->get("bloques");
		if ($result->num_rows() == 1)
		{
			$Bloques =  current($result->result()); 
			foreach ($Bloques as $key => $value) {
				if($key == "BloqueID"){ continue; }							

				if( array_key_exists($key, $obj)){					
					$Bloques->$key = $obj[$key];
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
		$this->db->where("BloqueID", $id); 
		$result = $this->db->get("bloques");		

		if ($result->num_rows() == 0)
		{
			return null; 
		}
		return current($result->result()); 
	}
	
 }
 ?>