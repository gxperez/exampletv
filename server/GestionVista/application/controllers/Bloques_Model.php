<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class Bloques_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerBloques(){
		$this->load->database();
		$query = $this->db->query("SELECT * FROM bloques");
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaBloques[] = new Bloques( $row['BloqueID'], $row['ProgramacionID'], $row['FrecuenciaTipo'], $row['FrecuenciaNumero'], $row['Estado'], $row['HoraInicio'], $row['HoraFin'], $row['UsuarioModificaID'], $row['FechaModificacion']); 
 
		}
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

		$this->db->insert("bloques", $obj); 			

	}

	public function actualizar($obj){

		$this->db->where("BloqueID", $obj["BloqueID"]); 
		$result = $this->db->get("bloques");
		if ($result->num_rows() == 1)
		{
			$Bloques =  current($result->result()); 
			$this->db->where("BloqueID", $Bloques->BloqueID);
			$rs = $this->db->update("bloques", $Bloques); 
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