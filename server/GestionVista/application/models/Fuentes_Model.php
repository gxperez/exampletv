<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class Fuentes_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerFuentes(){
		$this->load->database();
		$query = $this->db->query("SELECT * FROM fuentes");
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaFuentes[] = new Fuentes( $row['FuenteID'], $row['FuenteTipo'], $row['FuenteTipoID'], $row['RepresentacionTipo'], $row['Url'], $row['GuidRelacionalJson'], $row['ContentByID'], $row['ContenidoTexto'], $row['EsManual'], $row['Estado'], $row['UsuarioModificaID'], $row['FechaModifica']); 
 
		}
			return $listaFuentes;
 	}
	
	public function obtenerFuentesJson(){
		$this->load->database();
		$query = $this->db->get(fuentes);	
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaFuentes[] = $row; 
		}
			return json_encode($listaFuentes);
  }	

	public function insertar($obj){

		$this->db->insert("fuentes", $obj); 			

	}

	public function actualizar($obj){

		$this->db->where("FuenteID", $obj["FuenteID"]); 
		$result = $this->db->get("fuentes");
		if ($result->num_rows() == 1)
		{
			$Fuentes =  current($result->result()); 
			$this->db->where("FuenteID", $Fuentes->FuenteID);
			$rs = $this->db->update("fuentes", $Fuentes); 
			return $rs; 
		} else {
			return false;
		}
	}

	public function ObtenerPorID($id){
		$this->db->where("FuenteID", $id); 
		$result = $this->db->get("fuentes");		

		if ($result->num_rows() == 0)
		{
			return null; 
		}

		return current($result->result()); 
	}
	
 }
 ?>