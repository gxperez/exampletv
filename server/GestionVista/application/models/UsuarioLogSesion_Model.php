<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class UsuarioLogSesion_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerUsuarioLogSesion(){
		$this->load->database();
		$query = $this->db->query("SELECT * FROM usuario_log_sesion");
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaUsuarioLogSesion[] = new UsuarioLogSesion( $row['usuario_log_sesionID'], $row['nombreUsuario'], $row['email'], $row['clave'], $row['fechaCrea'], $row['ultimaSesion'], $row['estatus'], $row['GUID'], $row['ipUser']); 
 
		}
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

		$this->db->insert("usuario_log_sesion", $obj); 			

	}

	public function actualizar($obj){

		$this->db->where("usuario_log_sesionID", $obj["usuario_log_sesionID"]); 
		$result = $this->db->get("usuario_log_sesion");
		if ($result->num_rows() == 1)
		{
			$UsuarioLogSesion =  current($result->result()); 
			$this->db->where("usuario_log_sesionID", $UsuarioLogSesion->usuario_log_sesionID);
			$rs = $this->db->update("usuario_log_sesion", $UsuarioLogSesion); 
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