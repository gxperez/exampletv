<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class TemplatePages_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerTemplatePages(){
		$this->load->database();
		$query = $this->db->get(template_pages);			
			
		$listaTemplatePages = $query->result(); 
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
		$this->load->database();
		$this->db->insert("template_pages", $obj);
	}

	public function actualizar($obj){

		$this->load->database();
		$this->db->where("TemplatePagesID", $obj["TemplatePagesID"]); 
		$result = $this->db->get("template_pages");
		if ($result->num_rows() == 1)
		{
			$TemplatePages =  current($result->result()); 
			foreach ($TemplatePages as $key => $value) {
				if($key == "TemplatePagesID"){ continue; }							

				if( array_key_exists($key, $obj)){					
					$TemplatePages->$key = $obj[$key];
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