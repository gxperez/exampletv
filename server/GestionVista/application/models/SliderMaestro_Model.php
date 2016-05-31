<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class SliderMaestro_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerSliderMaestro(){
		$this->load->database();
		$query = $this->db->query("SELECT * FROM slider_maestro");
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaSliderMaestro[] = new SliderMaestro( $row['SliderMaestroID'], $row['Duracion'], $row['Estado'], $row['UsuarioModificaID'], $row['FechaModifica']); 
 
		}
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

		$this->db->insert("slider_maestro", $obj); 			

	}

	public function actualizar($obj){

		$this->db->where("SliderMaestroID", $obj["SliderMaestroID"]); 
		$result = $this->db->get("slider_maestro");
		if ($result->num_rows() == 1)
		{
			$SliderMaestro =  current($result->result()); 
			$this->db->where("SliderMaestroID", $SliderMaestro->SliderMaestroID);
			$rs = $this->db->update("slider_maestro", $SliderMaestro); 
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