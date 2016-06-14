<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class Programacion_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerProgramacion(){
		$this->load->database();
		$query = $this->db->get(programacion);			
			
		$listaProgramacion = $query->result(); 
		return $listaProgramacion;
 	}

 	public function obtenerProgramacionPorCampo($campo, $valor = "", $limit = 0, $page = 20){
		$this->load->database();
		$list = $this->db->field_data("programacion");
		$fieldList = array();
		foreach ($list as  $value) {
			$fieldList[] = $value->name;		
		}

		$this->db->select($fieldList,FALSE);
		$this->db->where(" Estado !=" , "-1" );
		$this->db->like($campo, trim($valor));
		$data = $this->db->get_compiled_select("programacion", $limit, $page);

		$arrFill = array("vQuery"=> $data, "vLimit" => $limit, "vPage"=> $page);
		$stored_procedure = "call sp_PaginarResultQuery( ?, ?, ?);";
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaProgramacion = $query->result();
 		return $listaDispositivo;
 	}

 	public function obtenerProgramacionPaginado($limit, $row, $condicion = " Estado != -1"){
		$this->load->database();
		$arrFill = array("vLimit" => $limit, "vPage"=> $row, "vCondicion"=> $condicion);
		$stored_procedure = "call sp_PaginarResultTabla("programacion", ?, ?, ?);";		
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaDispositivo = $query->result(); 
		return $listaDispositivo;
 	}
	
	public function obtenerProgramacionJson(){
		$this->load->database();
		$query = $this->db->get(programacion);	
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaProgramacion[] = $row; 
		}
			return json_encode($listaProgramacion);
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
		$listaCampos = $this->db->field_data("programacion");
		$this->db->insert("programacion", $obj);
		return $this->db->insert_id();
	}

	public function actualizar($obj){
		$this->load->database();

		$ProgramacionEnt = $this->ObtenerPorID($obj["ProgramacionID"]);
        	if($ProgramacionEnt == null){ 
		        return false; 
        	}
        	$update = array();
        	foreach ($ProgramacionEnt as $key => $value) {
        		if($key != "ProgramacionID"){
	        		if(array_key_exists($key, $obj) && $value != $obj[$key]){
	        			$update[$key] = $obj[$key];        			
	        		}           			
        		}    		
        	}
        	$update["FechaModifica"] = date("Y-m-d H:i:s");
        	$this->db->where("ProgramacionID", $obj["ProgramacionID"]);
			$rs = $this->db->update("programacion", $update);
			if($rs){
				return $ProgramacionEnt; 
			}
			return $rs; 		
	}

	public function cambiarEstado($obj, $estado){
		$this->load->database();
		$programacionEnt = $this->ObtenerPorID($obj["programacion"]);

		if($dispositivoEnt == null){ 
		        return false; 
        }        
        $update["FechaModifica"] = date("Y-m-d H:i:s");
        $update["Estado"] = $estado; 
        $this->db->where("ProgramacionID", $obj["ProgramacionID"]);
		$rs = $this->db->update("programacion", $update);	

		if($rs){
			return $programacionEnt; 
		}
			return $rs; 
	}

	public function ObtenerPorID($id){
		$this->db->where("ProgramacionID", $id); 
		$result = $this->db->get("programacion");		

		if ($result->num_rows() == 0)
		{
			return null; 
		}
		return current($result->result()); 
	}
	
 }
 ?>