<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class Contenido_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerContenido(){
		$this->load->database();
		$query = $this->db->get(contenido);			
			
		$listaContenido = $query->result(); 
		return $listaContenido;
 	}

 	public function obtenerContenidoPorCampo($campo, $valor = "", $limit = 0, $page = 20){
		$this->load->database();
		$list = $this->db->field_data("contenido");
		$fieldList = array();
		foreach ($list as  $value) {
			$fieldList[] = $value->name;		
		}

		$this->db->select($fieldList,FALSE);
		$this->db->where(" Estado !=" , "-1" );
		$this->db->like($campo, trim($valor));
		$data = $this->db->get_compiled_select("contenido", $limit, $page);

		$arrFill = array("vQuery"=> $data, "vLimit" => $limit, "vPage"=> $page);
		$stored_procedure = "call sp_PaginarResultQuery( ?, ?, ?);";
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaContenido = $query->result();
 		return $listaDispositivo;
 	}

 	public function obtenerContenidoPaginado($limit, $row, $condicion = " Estado != -1"){
		$this->load->database();
		$arrFill = array("vLimit" => $limit, "vPage"=> $row, "vCondicion"=> $condicion);
		$stored_procedure = "call sp_PaginarResultTabla("contenido", ?, ?, ?);";		
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaDispositivo = $query->result(); 
		return $listaDispositivo;
 	}
	
	public function obtenerContenidoJson(){
		$this->load->database();
		$query = $this->db->get(contenido);	
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaContenido[] = $row; 
		}
			return json_encode($listaContenido);
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
		$listaCampos = $this->db->field_data("contenido");
		$this->db->insert("contenido", $obj);
		return $this->db->insert_id();
	}

	public function actualizar($obj){
		$this->load->database();

		$ContenidoEnt = $this->ObtenerPorID($obj["ContenidoID"]);
        	if($ContenidoEnt == null){ 
		        return false; 
        	}
        	$update = array();
        	foreach ($ContenidoEnt as $key => $value) {
        		if($key != "ContenidoID"){
	        		if(array_key_exists($key, $obj) && $value != $obj[$key]){
	        			$update[$key] = $obj[$key];        			
	        		}           			
        		}    		
        	}
        	$update["FechaModifica"] = date("Y-m-d H:i:s");
        	$this->db->where("ContenidoID", $obj["ContenidoID"]);
			$rs = $this->db->update("contenido", $update);
			if($rs){
				return $ContenidoEnt; 
			}
			return $rs; 		
	}

	public function cambiarEstado($obj, $estado){
		$this->load->database();
		$contenidoEnt = $this->ObtenerPorID($obj["contenido"]);

		if($dispositivoEnt == null){ 
		        return false; 
        }        
        $update["FechaModifica"] = date("Y-m-d H:i:s");
        $update["Estado"] = $estado; 
        $this->db->where("ContenidoID", $obj["ContenidoID"]);
		$rs = $this->db->update("contenido", $update);	

		if($rs){
			return $contenidoEnt; 
		}
			return $rs; 
	}

	public function ObtenerPorID($id){
		$this->db->where("ContenidoID", $id); 
		$result = $this->db->get("contenido");		

		if ($result->num_rows() == 0)
		{
			return null; 
		}
		return current($result->result()); 
	}
	
 }
 ?>