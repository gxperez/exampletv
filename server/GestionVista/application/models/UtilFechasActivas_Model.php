<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class UtilFechasActivas_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerUtilFechasActivas(){
		$this->load->database();
		$query = $this->db->get(util_fechas_activas);			
			
		$listaUtilFechasActivas = $query->result(); 
		return $listaUtilFechasActivas;
 	}

 	public function obtenerUtilFechasActivasPorCampo($campo, $valor = "", $limit = 0, $page = 20){
		$this->load->database();
		$list = $this->db->field_data("util_fechas_activas");
		$fieldList = array();
		foreach ($list as  $value) {
			$fieldList[] = $value->name;		
		}

		$this->db->select($fieldList,FALSE);
		$this->db->where(" Estado !=" , "-1" );
		$this->db->like($campo, trim($valor));
		$data = $this->db->get_compiled_select("util_fechas_activas", $limit, $page);

		$arrFill = array("vQuery"=> $data, "vLimit" => $limit, "vPage"=> $page);
		$stored_procedure = "call sp_PaginarResultQuery( ?, ?, ?);";
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaUtilFechasActivas = $query->result();
 		return $listaDispositivo;
 	}

 	public function obtenerUtilFechasActivasPaginado($limit, $row, $condicion = " Estado != -1"){
		$this->load->database();
		$arrFill = array("vLimit" => $limit, "vPage"=> $row, "vCondicion"=> $condicion);
		$stored_procedure = "call sp_PaginarResultTabla("util_fechas_activas", ?, ?, ?);";		
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaDispositivo = $query->result(); 
		return $listaDispositivo;
 	}
	
	public function obtenerUtilFechasActivasJson(){
		$this->load->database();
		$query = $this->db->get(util_fechas_activas);	
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaUtilFechasActivas[] = $row; 
		}
			return json_encode($listaUtilFechasActivas);
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
		$listaCampos = $this->db->field_data("util_fechas_activas");
		$this->db->insert("util_fechas_activas", $obj);
		return $this->db->insert_id();
	}

	public function actualizar($obj){
		$this->load->database();

		$UtilFechasActivasEnt = $this->ObtenerPorID($obj["Fecha"]);
        	if($UtilFechasActivasEnt == null){ 
		        return false; 
        	}
        	$update = array();
        	foreach ($UtilFechasActivasEnt as $key => $value) {
        		if($key != "Fecha"){
	        		if(array_key_exists($key, $obj) && $value != $obj[$key]){
	        			$update[$key] = $obj[$key];        			
	        		}           			
        		}    		
        	}
        	$update["FechaModifica"] = date("Y-m-d H:i:s");
        	$this->db->where("Fecha", $obj["Fecha"]);
			$rs = $this->db->update("util_fechas_activas", $update);
			if($rs){
				return $UtilFechasActivasEnt; 
			}
			return $rs; 		
	}

	public function cambiarEstado($obj, $estado){
		$this->load->database();
		$util_fechas_activasEnt = $this->ObtenerPorID($obj["util_fechas_activas"]);

		if($dispositivoEnt == null){ 
		        return false; 
        }        
        $update["FechaModifica"] = date("Y-m-d H:i:s");
        $update["Estado"] = $estado; 
        $this->db->where("Fecha", $obj["Fecha"]);
		$rs = $this->db->update("util_fechas_activas", $update);	

		if($rs){
			return $util_fechas_activasEnt; 
		}
			return $rs; 
	}

	public function ObtenerPorID($id){
		$this->db->where("Fecha", $id); 
		$result = $this->db->get("util_fechas_activas");		

		if ($result->num_rows() == 0)
		{
			return null; 
		}
		return current($result->result()); 
	}
	
 }
 ?>