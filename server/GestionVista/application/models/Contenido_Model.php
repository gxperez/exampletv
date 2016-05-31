<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class Contenido_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerContenido(){
		$this->load->database();
		$query = $this->db->query("SELECT * FROM contenido");
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaContenido[] = new Contenido( $row['ContenidoID'], $row['Nombre'], $row['Descripcion'], $row['SliderMaestroID'], $row['Duracion'], $row['Estado'], $row['Guid'], $row['UsuarioModificaID'], $row['FechaModifica']); 
 
		}
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

		$this->db->insert("contenido", $obj); 			

	}

	public function actualizar($obj){

		$this->db->where("ContenidoID", $obj["ContenidoID"]); 
		$result = $this->db->get("contenido");
		if ($result->num_rows() == 1)
		{
			$Contenido =  current($result->result()); 
			$this->db->where("ContenidoID", $Contenido->ContenidoID);
			$rs = $this->db->update("contenido", $Contenido); 
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