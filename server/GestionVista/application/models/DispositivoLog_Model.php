<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class DispositivoLog_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
	public function obtenerDispositivoLog(){
		$this->load->database();
		$query = $this->db->get(dispositivo_log);			
			
		$listaDispositivoLog = $query->result(); 
		return $listaDispositivoLog;
 	}

 	public function obtenerDispositivoLogPorCampo($campo, $valor = "", $limit = 0, $page = 20){
		$this->load->database();
		$list = $this->db->field_data("dispositivo_log");
		$fieldList = array();
		foreach ($list as  $value) {
			$fieldList[] = $value->name;		
		}

		$this->db->select($fieldList,FALSE);
		$this->db->where(" Estado !=" , "-1" );
		$this->db->like($campo, trim($valor));
		$data = $this->db->get_compiled_select("dispositivo_log", $limit, $page);

		$arrFill = array("vQuery"=> $data, "vLimit" => $limit, "vPage"=> $page);
		$stored_procedure = "call sp_PaginarResultQuery( ?, ?, ?);";
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaDispositivoLog = $query->result();
 		return $listaDispositivo;
 	}

 	public function obtenerDispositivoLogPaginado($limit, $row, $condicion = " Estado != -1"){
		$this->load->database();
		$arrFill = array("vLimit" => $limit, "vPage"=> $row, "vCondicion"=> $condicion);
		$stored_procedure = "call sp_PaginarResultTabla('dispositivo_log', ?, ?, ?, null);";		
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaDispositivo = $query->result(); 
		return $listaDispositivo;
 	}
	
	public function obtenerDispositivoLogJson(){
		$this->load->database();
		$query = $this->db->get(dispositivo_log);	
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaDispositivoLog[] = $row; 
		}
			return json_encode($listaDispositivoLog);
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
		$listaCampos = $this->db->field_data("dispositivo_log");
		$this->db->insert("dispositivo_log", $obj);
		return $this->db->insert_id();
	}

	public function actualizar($obj){
		$this->load->database();

		$DispositivoLogEnt = $this->ObtenerPorID($obj["Dispositivo_log_ID"]);
        	if($DispositivoLogEnt == null){ 
		        return false; 
        	}
        	$update = array();
        	foreach ($DispositivoLogEnt as $key => $value) {
        		if($key != "Dispositivo_log_ID"){
	        		if(array_key_exists($key, $obj) && $value != $obj[$key]){
	        			$update[$key] = $obj[$key];        			
	        		}           			
        		}    		
        	}
        	$update["FechaModifica"] = date("Y-m-d H:i:s");
        	$this->db->where("Dispositivo_log_ID", $obj["Dispositivo_log_ID"]);
			$rs = $this->db->update("dispositivo_log", $update);
			if($rs){
				return $DispositivoLogEnt; 
			}
			return $rs; 		
	}

	public function cambiarEstado($obj, $estado){
		$this->load->database();
		$dispositivo_logEnt = $this->ObtenerPorID($obj["dispositivo_log"]);

		if($dispositivoEnt == null){ 
		        return false; 
        }        
        $update["FechaModifica"] = date("Y-m-d H:i:s");
        $update["Estado"] = $estado; 
        $this->db->where("Dispositivo_log_ID", $obj["Dispositivo_log_ID"]);
		$rs = $this->db->update("dispositivo_log", $update);	

		if($rs){
			return $dispositivo_logEnt; 
		}
			return $rs; 
	}

	public function ObtenerPorID($id){
		$this->db->where("Dispositivo_log_ID", $id); 
		$result = $this->db->get("dispositivo_log");		

		if ($result->num_rows() == 0)
		{
			return null; 
		}
		return current($result->result()); 
	}
	
 }
 ?>