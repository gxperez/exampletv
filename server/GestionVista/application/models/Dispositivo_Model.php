<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class Dispositivo_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerDispositivo(){
		$this->load->database();
		$query = $this->db->query("SELECT * FROM dispositivo");
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaDispositivo[] = new Dispositivo( $row['DispositivoID'], $row['Nombre'], $row['Descripcion'], $row['DispositivoTipo'], $row['Marca'], $row['Estatus'], $row['Mac'], $row['IP'], $row['FechaCrea'], $row['UltimaSesion']); 
 
		}
			return $listaDispositivo;
 	}
	
	public function obtenerDispositivoJson(){
		$this->load->database();
		$query = $this->db->get(dispositivo);	
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaDispositivo[] = $row; 
		}
			return json_encode($listaDispositivo);
  }	

	public function insertar($obj){

		$this->db->insert("dispositivo", $obj); 			

	}

	public function actualizar($obj){

		$this->db->where("DispositivoID", $obj["DispositivoID"]); 
		$result = $this->db->get("dispositivo");
		if ($result->num_rows() == 1)
		{
			$Dispositivo =  current($result->result()); 
			$this->db->where("DispositivoID", $Dispositivo->DispositivoID);
			$rs = $this->db->update("dispositivo", $Dispositivo); 
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