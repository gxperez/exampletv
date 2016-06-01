<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class SeccionTemplateFuentes_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerSeccionTemplateFuentes(){
		$this->load->database();
		$query = $this->db->get(seccion_template_fuentes);			
			
		$listaSeccionTemplateFuentes = $query->result(); 
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
		$this->load->database();
		$this->db->insert("seccion_template_fuentes", $obj);
	}

	public function actualizar($obj){

		$this->load->database();
		$this->db->where("SeccionTemplateFuentesID", $obj["SeccionTemplateFuentesID"]); 
		$result = $this->db->get("seccion_template_fuentes");
		if ($result->num_rows() == 1)
		{
			$SeccionTemplateFuentes =  current($result->result()); 
			foreach ($SeccionTemplateFuentes as $key => $value) {
				if($key == "SeccionTemplateFuentesID"){ continue; }							

				if( array_key_exists($key, $obj)){					
					$SeccionTemplateFuentes->$key = $obj[$key];
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