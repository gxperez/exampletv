<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class BloqueContenido_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerBloqueContenido(){
		$this->load->database();
		$query = $this->db->get(bloque_contenido);			
			
		$listaBloqueContenido = $query->result(); 
		return $listaBloqueContenido;
 	}

 	public function obtenerBloqueContenidoPorCampo($campo, $valor = "", $limit = 0, $page = 20){
		$this->load->database();
		$list = $this->db->field_data("bloque_contenido");
		$fieldList = array();
		foreach ($list as  $value) {
			$fieldList[] = $value->name;		
		}

		$this->db->select($fieldList,FALSE);
		$this->db->where(" Estado !=" , "-1" );
		$this->db->like($campo, trim($valor));
		$data = $this->db->get_compiled_select("bloque_contenido", $limit, $page);

		$arrFill = array("vQuery"=> $data, "vLimit" => $limit, "vPage"=> $page);
		$stored_procedure = "call sp_PaginarResultQuery( ?, ?, ?);";
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaBloqueContenido = $query->result();
 		return $listaDispositivo;
 	}

 	public function obtenerBloqueContenidoPaginado($limit, $row, $condicion = " Estado != -1"){
		$this->load->database();
		$arrFill = array("vLimit" => $limit, "vPage"=> $row, "vCondicion"=> $condicion);
		$stored_procedure = "call sp_PaginarResultTabla("bloque_contenido", ?, ?, ?);";		
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaDispositivo = $query->result(); 
		return $listaDispositivo;
 	}
	
	public function obtenerBloqueContenidoJson(){
		$this->load->database();
		$query = $this->db->get(bloque_contenido);	
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaBloqueContenido[] = $row; 
		}
			return json_encode($listaBloqueContenido);
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
		$listaCampos = $this->db->field_data("bloque_contenido");
		$this->db->insert("bloque_contenido", $obj);
		return $this->db->insert_id();
	}

	public function actualizar($obj){
		$this->load->database();

		$BloqueContenidoEnt = $this->ObtenerPorID($obj["BloqueContenidoID"]);
        	if($BloqueContenidoEnt == null){ 
		        return false; 
        	}
        	$update = array();
        	foreach ($BloqueContenidoEnt as $key => $value) {
        		if($key != "BloqueContenidoID"){
	        		if(array_key_exists($key, $obj) && $value != $obj[$key]){
	        			$update[$key] = $obj[$key];        			
	        		}           			
        		}    		
        	}
        	$update["FechaModifica"] = date("Y-m-d H:i:s");
        	$this->db->where("BloqueContenidoID", $obj["BloqueContenidoID"]);
			$rs = $this->db->update("bloque_contenido", $update);
			if($rs){
				return $BloqueContenidoEnt; 
			}
			return $rs; 		
	}

	public function cambiarEstado($obj, $estado){
		$this->load->database();
		$bloque_contenidoEnt = $this->ObtenerPorID($obj["bloque_contenido"]);

		if($dispositivoEnt == null){ 
		        return false; 
        }        
        $update["FechaModifica"] = date("Y-m-d H:i:s");
        $update["Estado"] = $estado; 
        $this->db->where("BloqueContenidoID", $obj["BloqueContenidoID"]);
		$rs = $this->db->update("bloque_contenido", $update);	

		if($rs){
			return $bloque_contenidoEnt; 
		}
			return $rs; 
	}

	public function ObtenerPorID($id){
		$this->db->where("BloqueContenidoID", $id); 
		$result = $this->db->get("bloque_contenido");		

		if ($result->num_rows() == 0)
		{
			return null; 
		}
		return current($result->result()); 
	}
	
 }
 ?>