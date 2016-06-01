<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class Programacion_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerProgramacion(){
		$this->load->database();
		$query = $this->db->get(programacion);			
			
		$listaProgramacion = $query->result(); 
		return $listaProgramacion;
 	}
	
	public function obtenerProgramacionJson(){
		$this->load->database();
		$query = $this->db->get(programacion);	
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaProgramacion[] = $row; 
		}
			return json_encode($listaProgramacion);
  }	

	public function insertar($obj){
		$this->load->database();
		$this->db->insert("programacion", $obj);
	}

	public function actualizar($obj){

		$this->load->database();
		$this->db->where("ProgramacionID", $obj["ProgramacionID"]); 
		$result = $this->db->get("programacion");
		if ($result->num_rows() == 1)
		{
			$Programacion =  current($result->result()); 
			foreach ($Programacion as $key => $value) {
				if($key == "ProgramacionID"){ continue; }							

				if( array_key_exists($key, $obj)){					
					$Programacion->$key = $obj[$key];
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
		$this->db->where("ProgramacionID", $id); 
		$result = $this->db->get("programacion");		

		if ($result->num_rows() == 0)
		{
			return null; 
		}
		return current($result->result()); 
	}
	
 }
 ?>