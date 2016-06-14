<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class GrupoTv_Model extends CI_Model { 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerGrupoTv(){
		$this->load->database();
		$query = $this->db->get(grupo_tv);			
			
		$listaGrupoTv = $query->result(); 
		return $listaGrupoTv;
 	}

 	public function obtenerGrupoTvPorCampo($campo, $valor = "", $limit = 0, $page = 20){
		$this->load->database();
		$list = $this->db->field_data("grupo_tv");
		$fieldList = array();
		foreach ($list as  $value) {
			$fieldList[] = $value->name;		
		}

		$this->db->select($fieldList,FALSE);
		$this->db->where(" Estado !=" , "-1" );
		$this->db->like($campo, trim($valor));
		$data = $this->db->get_compiled_select("grupo_tv", $limit, $page);

		$arrFill = array("vQuery"=> $data, "vLimit" => $limit, "vPage"=> $page);
		$stored_procedure = "call sp_PaginarResultQuery( ?, ?, ?);";
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaGrupoTv = $query->result();
 		return $listaDispositivo;
 	}

 	public function obtenerGrupoTvPaginado($limit, $row, $condicion = " Estado != -1"){
		$this->load->database();
		$arrFill = array("vLimit" => $limit, "vPage"=> $row, "vCondicion"=> $condicion);
		$stored_procedure = "call sp_PaginarResultTabla("grupo_tv", ?, ?, ?);";		
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaDispositivo = $query->result(); 
		return $listaDispositivo;
 	}
	
	public function obtenerGrupoTvJson(){
		$this->load->database();
		$query = $this->db->get(grupo_tv);	
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaGrupoTv[] = $row; 
		}
			return json_encode($listaGrupoTv);
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
		$listaCampos = $this->db->field_data("grupo_tv");
		$this->db->insert("grupo_tv", $obj);
		return $this->db->insert_id();
	}

	public function actualizar($obj){
		$this->load->database();

		$GrupoTvEnt = $this->ObtenerPorID($obj["GrupoTvID"]);
        	if($GrupoTvEnt == null){ 
		        return false; 
        	}
        	$update = array();
        	foreach ($GrupoTvEnt as $key => $value) {
        		if($key != "GrupoTvID"){
	        		if(array_key_exists($key, $obj) && $value != $obj[$key]){
	        			$update[$key] = $obj[$key];        			
	        		}           			
        		}    		
        	}
        	$update["FechaModifica"] = date("Y-m-d H:i:s");
        	$this->db->where("GrupoTvID", $obj["GrupoTvID"]);
			$rs = $this->db->update("grupo_tv", $update);
			if($rs){
				return $GrupoTvEnt; 
			}
			return $rs; 		
	}

	public function cambiarEstado($obj, $estado){
		$this->load->database();
		$grupo_tvEnt = $this->ObtenerPorID($obj["grupo_tv"]);

		if($dispositivoEnt == null){ 
		        return false; 
        }        
        $update["FechaModifica"] = date("Y-m-d H:i:s");
        $update["Estado"] = $estado; 
        $this->db->where("GrupoTvID", $obj["GrupoTvID"]);
		$rs = $this->db->update("grupo_tv", $update);	

		if($rs){
			return $grupo_tvEnt; 
		}
			return $rs; 
	}

	public function ObtenerPorID($id){
		$this->db->where("GrupoTvID", $id); 
		$result = $this->db->get("grupo_tv");		

		if ($result->num_rows() == 0)
		{
			return null; 
		}
		return current($result->result()); 
	}
	
 }
 ?>