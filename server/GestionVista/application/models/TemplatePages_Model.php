<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class TemplatePages_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerTemplatePages(){
		$this->load->database();
		$query = $this->db->query("SELECT * FROM template_pages");
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaTemplatePages[] = new TemplatePages( $row['TemplatePagesID'], $row['SliderMaestroID'], $row['EsquemaTipo'], $row['MostrarHeader'], $row['Duracion'], $row['TransicionTipoIni'], $row['TransicionTipoFin'], $row['Estado'], $row['UsuarioModificaID'], $row['FechaModificacion']); 
 
		}
			return $listaTemplatePages;
 	}
	
	public function obtenerTemplatePagesJson(){
		$this->load->database();
		$query = $this->db->get(template_pages);	
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaTemplatePages[] = $row; 
		}
			return json_encode($listaTemplatePages);
  }	

	public function insertar($obj){

		$this->db->insert("template_pages", $obj); 			

	}

	public function actualizar($obj){

		$this->db->where("TemplatePagesID", $obj["TemplatePagesID"]); 
		$result = $this->db->get("template_pages");
		if ($result->num_rows() == 1)
		{
			$TemplatePages =  current($result->result()); 
			$this->db->where("TemplatePagesID", $TemplatePages->TemplatePagesID);
			$rs = $this->db->update("template_pages", $TemplatePages); 
			return $rs; 
		} else {
			return false;
		}
	}

	public function ObtenerPorID($id){
		$this->db->where("TemplatePagesID", $id); 
		$result = $this->db->get("template_pages");		

		if ($result->num_rows() == 0)
		{
			return null; 
		}

		return current($result->result()); 
	}
	
 }
 ?>