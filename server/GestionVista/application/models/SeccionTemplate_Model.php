<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class SeccionTemplate_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerSeccionTemplate(){
		$this->load->database();
		$query = $this->db->get(seccion_template);			
			
		$listaSeccionTemplate = $query->result(); 
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
		$this->load->database();
		$this->db->insert("seccion_template", $obj);
	}

	public function actualizar($obj){

		$this->load->database();
		$this->db->where("SeccionTemplateID", $obj["SeccionTemplateID"]); 
		$result = $this->db->get("seccion_template");
		if ($result->num_rows() == 1)
		{
			$SeccionTemplate =  current($result->result()); 
			foreach ($SeccionTemplate as $key => $value) {
				if($key == "SeccionTemplateID"){ continue; }							

				if( array_key_exists($key, $obj)){					
					$SeccionTemplate->$key = $obj[$key];
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