<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class BloqueContenido_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerBloqueContenido(){
		$this->load->database();
		$query = $this->db->query("SELECT * FROM bloque_contenido");
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaBloqueContenido[] = new BloqueContenido( $row['BloqueContenidoID'], $row['BloqueID'], $row['ContenidoID'], $row['Estado'], $row['UsuarioModificaID'], $row['FechaModifica']); 
 
		}
			return $listaBloqueContenido;
 	}
	
	public function obtenerBloqueContenidoJson(){
		$this->load->database();
		$query = $this->db->get('bloque_contenido');	
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaBloqueContenido[] = $row; 
		}
			return json_encode($listaBloqueContenido);
  }	

	public function insertar($obj){
		$this->load->database();
		$this->db->insert("bloque_contenido", $obj); 			

	}

	public function actualizar($obj){
		$this->load->database();
		$this->db->where("BloqueContenidoID", $obj["BloqueContenidoID"]); 
		$result = $this->db->get("bloque_contenido");
		if ($result->num_rows() == 1)
		{
			$BloqueContenido =  current($result->result()); 
			$this->db->where("BloqueContenidoID", $BloqueContenido->BloqueContenidoID);
			$rs = $this->db->update("bloque_contenido", $BloqueContenido); 
			return $rs; 
		} else {
			return false;
		}
	}

	public function ObtenerPorID($id){
		$this->load->database();
		$this->db->where("BloqueContenidoID", $id); 
		$result = $this->db->get("bloque_contenido");		

		if ($result->num_rows() == 0)
		{
			return null; 
		}

		return current($result->result()); 
	}
	
 }
 ?>