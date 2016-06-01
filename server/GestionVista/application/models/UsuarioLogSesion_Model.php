<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class UsuarioLogSesion_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerUsuarioLogSesion(){
		$this->load->database();
		$query = $this->db->get(usuario_log_sesion);			
			
		$listaUsuarioLogSesion = $query->result(); 
		return $listaUsuarioLogSesion;
 	}
	
	public function obtenerUsuarioLogSesionJson(){
		$this->load->database();
		$query = $this->db->get(usuario_log_sesion);	
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaUsuarioLogSesion[] = $row; 
		}
			return json_encode($listaUsuarioLogSesion);
  }	

	public function insertar($obj){
		$this->load->database();
		$this->db->insert("usuario_log_sesion", $obj);
	}

	public function actualizar($obj){

		$this->load->database();
		$this->db->where("usuario_log_sesionID", $obj["usuario_log_sesionID"]); 
		$result = $this->db->get("usuario_log_sesion");
		if ($result->num_rows() == 1)
		{
			$UsuarioLogSesion =  current($result->result()); 
			foreach ($UsuarioLogSesion as $key => $value) {
				if($key == "usuario_log_sesionID"){ continue; }							

				if( array_key_exists($key, $obj)){					
					$UsuarioLogSesion->$key = $obj[$key];
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
		$this->db->where("usuario_log_sesionID", $id); 
		$result = $this->db->get("usuario_log_sesion");		

		if ($result->num_rows() == 0)
		{
			return null; 
		}
		return current($result->result()); 
	}
	
 }
 ?>