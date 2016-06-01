<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class FuerzaVentaDispositivo_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerFuerzaVentaDispositivo(){
		$this->load->database();
		$query = $this->db->get(fuerza_venta_dispositivo);			
			
		$listaFuerzaVentaDispositivo = $query->result(); 
		return $listaFuerzaVentaDispositivo;
 	}
	
	public function obtenerFuerzaVentaDispositivoJson(){
		$this->load->database();
		$query = $this->db->get(fuerza_venta_dispositivo);	
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaFuerzaVentaDispositivo[] = $row; 
		}
			return json_encode($listaFuerzaVentaDispositivo);
  }	

	public function insertar($obj){
		$this->load->database();
		$this->db->insert("fuerza_venta_dispositivo", $obj);
	}

	public function actualizar($obj){

		$this->load->database();
		$this->db->where("FuerzaVentaDispositivoID", $obj["FuerzaVentaDispositivoID"]); 
		$result = $this->db->get("fuerza_venta_dispositivo");
		if ($result->num_rows() == 1)
		{
			$FuerzaVentaDispositivo =  current($result->result()); 
			foreach ($FuerzaVentaDispositivo as $key => $value) {
				if($key == "FuerzaVentaDispositivoID"){ continue; }							

				if( array_key_exists($key, $obj)){					
					$FuerzaVentaDispositivo->$key = $obj[$key];
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
		$this->db->where("FuerzaVentaDispositivoID", $id); 
		$result = $this->db->get("fuerza_venta_dispositivo");		

		if ($result->num_rows() == 0)
		{
			return null; 
		}
		return current($result->result()); 
	}
	
 }
 ?>