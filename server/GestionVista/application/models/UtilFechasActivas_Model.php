<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class UtilFechasActivas_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerUtilFechasActivas(){
		$this->load->database();
		$query = $this->db->get(util_fechas_activas);			
			
		$listaUtilFechasActivas = $query->result(); 
		return $listaUtilFechasActivas;
 	}
	
	public function obtenerUtilFechasActivasJson(){
		$this->load->database();
		$query = $this->db->get(util_fechas_activas);	
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaUtilFechasActivas[] = $row; 
		}
			return json_encode($listaUtilFechasActivas);
  }	

	public function insertar($obj){
		$this->load->database();
		$this->db->insert("util_fechas_activas", $obj);
	}

	public function actualizar($obj){

		$this->load->database();
		$this->db->where("Fecha", $obj["Fecha"]); 
		$result = $this->db->get("util_fechas_activas");
		if ($result->num_rows() == 1)
		{
			$UtilFechasActivas =  current($result->result()); 
			foreach ($UtilFechasActivas as $key => $value) {
				if($key == "Fecha"){ continue; }							

				if( array_key_exists($key, $obj)){					
					$UtilFechasActivas->$key = $obj[$key];
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
		$this->db->where("Fecha", $id); 
		$result = $this->db->get("util_fechas_activas");		

		if ($result->num_rows() == 0)
		{
			return null; 
		}
		return current($result->result()); 
	}
	
 }
 ?>