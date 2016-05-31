<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class SeccionTemplate_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerSeccionTemplate(){
		$this->load->database();
		$query = $this->db->query("SELECT * FROM seccion_template");
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaSeccionTemplate[] = new SeccionTemplate( $row['SeccionTemplateID'], $row['TemplatePagesID'], $row['ContenidoTipo'], $row['Posicion'], $row['Encabezado'], $row['FuenteID'], $row['Estado'], $row['UsuarioModificaID'], $row['FechaModificacion']); 
 
		}
			return $listaSeccionTemplate;
 	}
	
	public function obtenerSeccionTemplateJson(){
		$this->load->database();
		$query = $this->db->get(seccion_template);	
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaSeccionTemplate[] = $row; 
		}
			return json_encode($listaSeccionTemplate);
  }	

	public function insertar($obj){

		$this->db->insert("seccion_template", $obj); 			

	}

	public function actualizar($obj){

		$this->db->where("SeccionTemplateID", $obj["SeccionTemplateID"]); 
		$result = $this->db->get("seccion_template");
		if ($result->num_rows() == 1)
		{
			$SeccionTemplate =  current($result->result()); 
			$this->db->where("SeccionTemplateID", $SeccionTemplate->SeccionTemplateID);
			$rs = $this->db->update("seccion_template", $SeccionTemplate); 
			return $rs; 
		} else {
			return false;
		}
	}

	public function ObtenerPorID($id){
		$this->db->where("SeccionTemplateID", $id); 
		$result = $this->db->get("seccion_template");		

		if ($result->num_rows() == 0)
		{
			return null; 
		}

		return current($result->result()); 
	}
	
 }
 ?>