<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class FuerzaVenta_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerFuerzaVenta(){
		$this->load->database();
		$query = $this->db->query("SELECT * FROM fuerza_venta");
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaFuerzaVenta[] = new FuerzaVenta( $row['GUID_FV'], $row['GUIDDependencia'], $row['Nombre'], $row['Descripcion'], $row['Nivel'], $row['Estatus'], $row['FechaCrea'], $row['FechaFin']); 
 
		}
			return $listaFuerzaVenta;
 	}
	
	public function obtenerFuerzaVentaJson(){
		$this->load->database();
		$query = $this->db->get(fuerza_venta);	
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaFuerzaVenta[] = $row; 
		}
			return json_encode($listaFuerzaVenta);
  }	

	public function insertar($obj){

		$this->db->insert("fuerza_venta", $obj); 			

	}

	public function actualizar($obj){

		$this->db->where("GUID_FV", $obj["GUID_FV"]); 
		$result = $this->db->get("fuerza_venta");
		if ($result->num_rows() == 1)
		{
			$FuerzaVenta =  current($result->result()); 
			$this->db->where("GUID_FV", $FuerzaVenta->GUID_FV);
			$rs = $this->db->update("fuerza_venta", $FuerzaVenta); 
			return $rs; 
		} else {
			return false;
		}
	}

	public function ObtenerPorID($id){
		$this->db->where("GUID_FV", $id); 
		$result = $this->db->get("fuerza_venta");		

		if ($result->num_rows() == 0)
		{
			return null; 
		}

		return current($result->result()); 
	}
	
 }
 ?>