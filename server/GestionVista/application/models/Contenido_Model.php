<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class Contenido_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerContenido(){
		$this->load->database();
		$query = $this->db->get(contenido);			
			
		$listaContenido = $query->result(); 
		return $listaContenido;
 	}
	
	public function obtenerContenidoJson(){
		$this->load->database();
		$query = $this->db->get(contenido);	
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaContenido[] = $row; 
		}
			return json_encode($listaContenido);
  }	

	public function insertar($obj){
		$this->load->database();
		$this->db->insert("contenido", $obj);
	}

	public function actualizar($obj){

		$this->load->database();
		$this->db->where("ContenidoID", $obj["ContenidoID"]); 
		$result = $this->db->get("contenido");
		if ($result->num_rows() == 1)
		{
			$Contenido =  current($result->result()); 
			foreach ($Contenido as $key => $value) {
				if($key == "ContenidoID"){ continue; }							

				if( array_key_exists($key, $obj)){					
					$Contenido->$key = $obj[$key];
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
		$this->db->where("ContenidoID", $id); 
		$result = $this->db->get("contenido");		

		if ($result->num_rows() == 0)
		{
			return null; 
		}
		return current($result->result()); 
	}
	
 }
 ?>