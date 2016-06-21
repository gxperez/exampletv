<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class SessionDispositivoLog_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerSessionDispositivoLog(){
		$this->load->database();
		$query = $this->db->get('session_dispositivo_log');						

		$listaSessionDispositivoLog = $query->result(); 
		return $listaSessionDispositivoLog;
 	}

 	public function obtenerSessionDispositivoLogPorCampo($campo, $valor = "", $limit = 0, $page = 20){
		$this->load->database();
		$list = $this->db->field_data("session_dispositivo_log");
		$fieldList = array();
		foreach ($list as  $value) {
			$fieldList[] = $value->name;		
		}

		$this->db->select($fieldList,FALSE);
		$this->db->where(" Estado !=" , "-1" );
		$this->db->like($campo, trim($valor));
		$data = $this->db->get_compiled_select("session_dispositivo_log", $limit, $page);

		$arrFill = array("vQuery"=> $data, "vLimit" => $limit, "vPage"=> $page);
		$stored_procedure = "call sp_PaginarResultQuery( ?, ?, ?);";
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaSessionDispositivoLog = $query->result();
 		return $listaSessionDispositivoLog;
 	}

 	public function obtenerSessionDispositivoLogPaginado($limit, $row, $condicion = " Estado != -1"){
		$this->load->database();
		$arrFill = array("vLimit" => $limit, "vPage"=> $row, "vCondicion"=> $condicion);
		$stored_procedure = "call sp_PaginarResultTabla('session_dispositivo_log', ?, ?, ?);";		
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaSessionDispositivoLog = $query->result(); 
		return $listaSessionDispositivoLog;
 	}
	
	public function obtenerSessionDispositivoLogJson(){
		$this->load->database();
		$query = $this->db->get(session_dispositivo_log);	
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaSessionDispositivoLog[] = $row; 
		}
			return json_encode($listaSessionDispositivoLog);
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
		$listaCampos = $this->db->field_data("session_dispositivo_log");
		$this->db->insert("session_dispositivo_log", $obj);
		return $this->db->insert_id();
	}

	public function actualizar($obj){
		$this->load->database();

		$SessionDispositivoLogEnt = $this->ObtenerPorID($obj["sessionDispositivoLogID"]);
        	if($SessionDispositivoLogEnt == null){ 
		        return false; 
        	}
        	$update = array();
        	foreach ($SessionDispositivoLogEnt as $key => $value) {
        		if($key != "sessionDispositivoLogID"){
	        		if(array_key_exists($key, $obj) && $value != $obj[$key]){
	        			$update[$key] = $obj[$key];        			
	        		}           			
        		}    		
        	}
        	$update["FechaModifica"] = date("Y-m-d H:i:s");
        	$this->db->where("sessionDispositivoLogID", $obj["sessionDispositivoLogID"]);
			$rs = $this->db->update("session_dispositivo_log", $update);
			if($rs){
				return $SessionDispositivoLogEnt; 
			}
			return $rs; 		
	}

	public function cambiarEstado($obj, $estado){
		$this->load->database();
		$session_dispositivo_logEnt = $this->ObtenerPorID($obj["session_dispositivo_log"]);

		if($session_dispositivo_logEnt == null){ 
		        return false; 
        }        
        $update["FechaModifica"] = date("Y-m-d H:i:s");
        $update["Estado"] = $estado; 
        $this->db->where("sessionDispositivoLogID", $obj["sessionDispositivoLogID"]);
		$rs = $this->db->update("session_dispositivo_log", $update);	

		if($rs){
			return $session_dispositivo_logEnt; 
		}
			return $rs; 
	}

	public function ObtenerPorID($id){
		$this->db->where("sessionDispositivoLogID", $id); 
		$result = $this->db->get("session_dispositivo_log");		

		if ($result->num_rows() == 0)
		{
			return null; 
		}
		return current($result->result()); 
	}
	
 }
 ?>