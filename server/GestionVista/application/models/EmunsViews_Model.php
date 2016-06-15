<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class EmunsViews_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}

	public function AllEnums(){		
		$this->load->database();
		$tablas = $this->db->list_tables();
		$listaEnums = array();
		// Listdo de Enums para las Vustas

		foreach ($tablas as $key => $value) {
			if( (strpos("vw_", $value) !== FALSE) && (strpos("_rep_", $value) === FALSE) ){
				$query= $this->db->get($value); 
				$listaEnums[$value] =  current($query->result()); 
			}
		}		
		return $listaEnums;
 	}

 	public function getEnumsEstado($delete = false){
 		$arrEstatus = array();
 		if($delete){
 			$arrEstatus = array('Eliminar' => -1,
		 		'Inactivo' => 0,
		 		'Activo' => 1
 	 		); 	
 			return $arrEstatus;
 		}

 		$arrEstatus = array(
			'Inactivo' => 0,
			'Activo' => 1
 	 	); 
 			return $arrEstatus;
 	} 

 public function getEnum($nombre){
	$this->load->database();
 	$view = "vw_". $nombre; 
 	$Enums = array();

 	if ($this->db->table_exists($view))
	{
		

		$query = $this->db->get($view);    
		$Enums = $query->result(); 
	}
	return  current($Enums); 
 }

}