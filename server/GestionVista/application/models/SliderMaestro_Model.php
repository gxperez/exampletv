<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class SliderMaestro_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerSliderMaestro(){
		$this->load->database();
		$query = $this->db->get(slider_maestro);			
			
		$listaSliderMaestro = $query->result(); 
		return $listaSliderMaestro;
 	}
	
	public function obtenerSliderMaestroJson(){
		$this->load->database();
		$query = $this->db->get(slider_maestro);	
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaSliderMaestro[] = $row; 
		}
			return json_encode($listaSliderMaestro);
  }	

	public function insertar($obj){
		$this->load->database();
		$this->db->insert("slider_maestro", $obj);
	}

	public function actualizar($obj){

		$this->load->database();
		$this->db->where("SliderMaestroID", $obj["SliderMaestroID"]); 
		$result = $this->db->get("slider_maestro");
		if ($result->num_rows() == 1)
		{
			$SliderMaestro =  current($result->result()); 
			foreach ($SliderMaestro as $key => $value) {
				if($key == "SliderMaestroID"){ continue; }							

				if( array_key_exists($key, $obj)){					
					$SliderMaestro->$key = $obj[$key];
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
		$this->db->where("SliderMaestroID", $id); 
		$result = $this->db->get("slider_maestro");		

		if ($result->num_rows() == 0)
		{
			return null; 
		}
		return current($result->result()); 
	}
	
 }
 ?>