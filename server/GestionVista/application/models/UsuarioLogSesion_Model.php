<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class UsuarioLogSesion_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerUsuarioLogSesion(){
		$this->load->database();
		$query = $this->db->get(usuario_log_sesion);			
			
		$listaUsuarioLogSesion = $query->result(); 
		return $listaUsuarioLogSesion;
 	}

 	public function obtenerUsuarioLogSesionPorCampo($campo, $valor = "", $limit = 0, $page = 20){
		$this->load->database();
		$list = $this->db->field_data("usuario_log_sesion");
		$fieldList = array();
		foreach ($list as  $value) {
			$fieldList[] = $value->name;		
		}

		$this->db->select($fieldList,FALSE);
		$this->db->where(" Estado !=" , "-1" );
		$this->db->like($campo, trim($valor));
		$data = $this->db->get_compiled_select("usuario_log_sesion", $limit, $page);

		$arrFill = array("vQuery"=> $data, "vLimit" => $limit, "vPage"=> $page);
		$stored_procedure = "call sp_PaginarResultQuery( ?, ?, ?);";
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaUsuarioLogSesion = $query->result();
 		return $listaDispositivo;
 	}

 	public function obtenerUsuarioLogSesionPaginado($limit, $row, $condicion = " Estado != -1"){
		$this->load->database();
		$arrFill = array("vLimit" => $limit, "vPage"=> $row, "vCondicion"=> $condicion);
		$stored_procedure = "call sp_PaginarResultTabla("usuario_log_sesion", ?, ?, ?);";		
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaDispositivo = $query->result(); 
		return $listaDispositivo;
 	}
	
	public function obtenerUsuarioLogSesionJson(){
		$this->load->database();
		$query = $this->db->get(usuario_log_sesion);	
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaUsuarioLogSesion[] = $row; 
		}
			return json_encode($listaUsuarioLogSesion);
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
		$listaCampos = $this->db->field_data("usuario_log_sesion");
		$this->db->insert("usuario_log_sesion", $obj);
		return $this->db->insert_id();
	}

	public function actualizar($obj){
		$this->load->database();

		$UsuarioLogSesionEnt = $this->ObtenerPorID($obj["usuario_log_sesionID"]);
        	if($UsuarioLogSesionEnt == null){ 
		        return false; 
        	}
        	$update = array();
        	foreach ($UsuarioLogSesionEnt as $key => $value) {
        		if($key != "usuario_log_sesionID"){
	        		if(array_key_exists($key, $obj) && $value != $obj[$key]){
	        			$update[$key] = $obj[$key];        			
	        		}           			
        		}    		
        	}
        	$update["FechaModifica"] = date("Y-m-d H:i:s");
        	$this->db->where("usuario_log_sesionID", $obj["usuario_log_sesionID"]);
			$rs = $this->db->update("usuario_log_sesion", $update);
			if($rs){
				return $UsuarioLogSesionEnt; 
			}
			return $rs; 		
	}

	public function cambiarEstado($obj, $estado){
		$this->load->database();
		$usuario_log_sesionEnt = $this->ObtenerPorID($obj["usuario_log_sesion"]);

		if($dispositivoEnt == null){ 
		        return false; 
        }        
        $update["FechaModifica"] = date("Y-m-d H:i:s");
        $update["Estado"] = $estado; 
        $this->db->where("usuario_log_sesionID", $obj["usuario_log_sesionID"]);
		$rs = $this->db->update("usuario_log_sesion", $update);	

		if($rs){
			return $usuario_log_sesionEnt; 
		}
			return $rs; 
	}

	public function ObtenerPorID($id){
		$this->db->where("usuario_log_sesionID", $id); 
		$result = $this->db->get("usuario_log_sesion");		

		if ($result->num_rows() == 0)
		{
			return null; 
		}
		return current($result->result()); 
	}
	
 }
 ?>