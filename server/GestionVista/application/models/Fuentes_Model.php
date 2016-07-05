<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class Fuentes_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerFuentes(){
		$this->load->database();
		$query = $this->db->get(fuentes);			
			
		$listaFuentes = $query->result(); 
		return $listaFuentes;
 	}

 	public function obtenerFuentesPorCampo($campo, $valor = "", $limit = 0, $page = 20){
		$this->load->database();
		$list = $this->db->field_data("fuentes");
		$fieldList = array();
		foreach ($list as  $value) {
			$fieldList[] = $value->name;		
		}

		$this->db->select($fieldList,FALSE);
		$this->db->where(" Estado !=" , "-1" );
		$this->db->like($campo, trim($valor));
		$data = $this->db->get_compiled_select("fuentes", $limit, $page);

		$arrFill = array("vQuery"=> $data, "vLimit" => $limit, "vPage"=> $page);
		$stored_procedure = "call sp_PaginarResultQuery( ?, ?, ?);";
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaFuentes = $query->result();
 		return $listaDispositivo;
 	}

 	public function obtenerFuentesPaginado($limit, $row, $condicion = " Estado != -1"){
		$this->load->database();
		$arrFill = array("vLimit" => $limit, "vPage"=> $row, "vCondicion"=> $condicion);
		$stored_procedure = "call sp_PaginarResultTabla('fuentes', ?, ?, ?, null);";		
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaDispositivo = $query->result(); 
		return $listaDispositivo;
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

	public function existeValorCampo($tabla_campo, $val){
		$this->load->database();
		$dbStr = explode(".", $tabla_campo); 
		$this->db->where($dbStr[1], trim($val) ); 
		$result = $this->db->get($dbStr[0]);
		if ($result->num_rows() > 0)
		{		
			return false;
		}		
		return true; 
	}


	public function insertar($obj){
		$this->load->database();
		$listaCampos = $this->db->field_data("fuentes");
		$this->db->insert("fuentes", $obj);
		return $this->db->insert_id();
	}

	public function actualizar($obj){
		$this->load->database();

		$FuentesEnt = $this->ObtenerPorID($obj["FuenteID"]);
        	if($FuentesEnt == null){ 
		        return false; 
        	}
        	$update = array();
        	foreach ($FuentesEnt as $key => $value) {
        		if($key != "FuenteID"){
	        		if(array_key_exists($key, $obj) && $value != $obj[$key]){
	        			$update[$key] = $obj[$key];        			
	        		}           			
        		}    		
        	}
        	$update["FechaModifica"] = date("Y-m-d H:i:s");
        	$this->db->where("FuenteID", $obj["FuenteID"]);
			$rs = $this->db->update("fuentes", $update);
			if($rs){
				return $FuentesEnt; 
			}
			return $rs; 		
	}

	public function cambiarEstado($obj, $estado){
		$this->load->database();
		$fuentesEnt = $this->ObtenerPorID($obj["FuenteID"]);

		if($fuentesEnt == null){ 
		        return false; 
        }        
        $update["FechaModifica"] = date("Y-m-d H:i:s");
        $update["Estado"] = $estado; 
        $this->db->where("FuenteID", $obj["FuenteID"]);
		$rs = $this->db->update("fuentes", $update);	

		if($rs){
			return $fuentesEnt; 
		}
			return $rs; 
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