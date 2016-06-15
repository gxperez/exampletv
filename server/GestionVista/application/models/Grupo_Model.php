<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class Grupo_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerGrupo(){
		$this->load->database();
		$query = $this->db->get(grupo);			
			
		$listaGrupo = $query->result(); 
		return $listaGrupo;
 	}

 	public function obtenerGrupoPorCampo($campo, $valor = "", $limit = 0, $page = 20){
		$this->load->database();
		$list = $this->db->field_data("grupo");
		$fieldList = array();
		foreach ($list as  $value) {
			$fieldList[] = $value->name;		
		}

		$this->db->select($fieldList,FALSE);
		$this->db->where(" Estado !=" , "-1" );
		$this->db->like($campo, trim($valor));
		$data = $this->db->get_compiled_select("grupo", $limit, $page);

		$arrFill = array("vQuery"=> $data, "vLimit" => $limit, "vPage"=> $page);
		$stored_procedure = "call sp_PaginarResultQuery( ?, ?, ?);";
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaGrupo = $query->result();
 		return $listaDispositivo;
 	}

 	public function obtenerGrupoPaginado($limit, $row, $condicion = " Estado != -1"){
		$this->load->database();
		$arrFill = array("vLimit" => $limit, "vPage"=> $row, "vCondicion"=> $condicion);
		$stored_procedure = "call sp_PaginarResultTabla('grupo', ?, ?, ?);";		
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaDispositivo = $query->result(); 
		return $listaDispositivo;
 	}
	
	public function obtenerGrupoJson(){
		$this->load->database();
		$query = $this->db->get(grupo);	
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaGrupo[] = $row; 
		}
			return json_encode($listaGrupo);
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
		$listaCampos = $this->db->field_data("grupo");
		$this->db->insert("grupo", $obj);
		return $this->db->insert_id();
	}

	public function actualizar($obj){
		$this->load->database();

		$GrupoEnt = $this->ObtenerPorID($obj["GrupoID"]);
        	if($GrupoEnt == null){ 
		        return false; 
        	}
        	$update = array();
        	foreach ($GrupoEnt as $key => $value) {
        		if($key != "GrupoID"){
	        		if(array_key_exists($key, $obj) && $value != $obj[$key]){
	        			$update[$key] = $obj[$key];        			
	        		}           			
        		}    		
        	}
        	$update["FechaModifica"] = date("Y-m-d H:i:s");
        	$this->db->where("GrupoID", $obj["GrupoID"]);
			$rs = $this->db->update("grupo", $update);
			if($rs){
				return $GrupoEnt; 
			}
			return $rs; 		
	}

	public function cambiarEstado($obj, $estado){
		$this->load->database();
		$grupoEnt = $this->ObtenerPorID($obj["grupo"]);

		if($dispositivoEnt == null){ 
		        return false; 
        }        
        $update["FechaModifica"] = date("Y-m-d H:i:s");
        $update["Estado"] = $estado; 
        $this->db->where("GrupoID", $obj["GrupoID"]);
		$rs = $this->db->update("grupo", $update);	

		if($rs){
			return $grupoEnt; 
		}
			return $rs; 
	}

	public function ObtenerPorID($id){
		$this->db->where("GrupoID", $id); 
		$result = $this->db->get("grupo");		

		if ($result->num_rows() == 0)
		{
			return null; 
		}
		return current($result->result()); 
	}
	
 }
 ?>