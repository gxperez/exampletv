<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class SeccionTemplateFuentes_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerSeccionTemplateFuentes(){
		$this->load->database();
		$query = $this->db->query("SELECT * FROM seccion_template_fuentes");
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaSeccionTemplateFuentes[] = new SeccionTemplateFuentes( $row['SeccionTemplateFuentesID'], $row['SeccionTemplateID'], $row['FuenteID'], $row['Secuencia'], $row[' Tiempo'], $row['Estado'], $row['UsuarioModificaID'], $row['FechaModificacion']); 
 
		}
			return $listaSeccionTemplateFuentes;
 	}
	
	public function obtenerSeccionTemplateFuentesJson(){
		$this->load->database();
		$query = $this->db->get(seccion_template_fuentes);	
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaSeccionTemplateFuentes[] = $row; 
		}
			return json_encode($listaSeccionTemplateFuentes);
  }	

	public function insertar($obj){

		$this->db->insert("seccion_template_fuentes", $obj); 			

	}

	public function actualizar($obj){

		$this->db->where("SeccionTemplateFuentesID", $obj["SeccionTemplateFuentesID"]); 
		$result = $this->db->get("seccion_template_fuentes");
		if ($result->num_rows() == 1)
		{
			$SeccionTemplateFuentes =  current($result->result()); 
			$this->db->where("SeccionTemplateFuentesID", $SeccionTemplateFuentes->SeccionTemplateFuentesID);
			$rs = $this->db->update("seccion_template_fuentes", $SeccionTemplateFuentes); 
			return $rs; 
		} else {
			return false;
		}
	}

	public function ObtenerPorID($id){
		$this->db->where("SeccionTemplateFuentesID", $id); 
		$result = $this->db->get("seccion_template_fuentes");		

		if ($result->num_rows() == 0)
		{
			return null; 
		}

		return current($result->result()); 
	}
	
 }
 ?>