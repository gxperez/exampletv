<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class Programacion_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerProgramacion(){
		$this->load->database();
		$query = $this->db->query("SELECT * FROM programacion");
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaProgramacion[] = new Programacion( $row['ProgramacionID'], $row['Descripcion'], $row['EsRegular'], $row['FechaEjecutaInicio'], $row['FechaEjecutaFin'], $row['Estado'], $row['Guid'], $row['UsuarioModificaID'], $row['FechaModifica']); 
 
		}
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

		$this->db->insert("programacion", $obj); 			

	}

	public function actualizar($obj){

		$this->db->where("ProgramacionID", $obj["ProgramacionID"]); 
		$result = $this->db->get("programacion");
		if ($result->num_rows() == 1)
		{
			$Programacion =  current($result->result()); 
			$this->db->where("ProgramacionID", $Programacion->ProgramacionID);
			$rs = $this->db->update("programacion", $Programacion); 
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