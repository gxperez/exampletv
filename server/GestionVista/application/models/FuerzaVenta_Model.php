<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class FuerzaVenta_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerFuerzaVenta(){
		$this->load->database();
		$query = $this->db->get(fuerza_venta);			
			
		$listaFuerzaVenta = $query->result(); 
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
		$this->load->database();
		$this->db->insert("fuerza_venta", $obj);
	}

	public function actualizar($obj){

		$this->load->database();
		$this->db->where("GUID_FV", $obj["GUID_FV"]); 
		$result = $this->db->get("fuerza_venta");
		if ($result->num_rows() == 1)
		{
			$FuerzaVenta =  current($result->result()); 
			foreach ($FuerzaVenta as $key => $value) {
				if($key == "GUID_FV"){ continue; }							

				if( array_key_exists($key, $obj)){					
					$FuerzaVenta->$key = $obj[$key];
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