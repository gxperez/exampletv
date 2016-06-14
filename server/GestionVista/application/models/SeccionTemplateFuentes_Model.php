<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class SeccionTemplateFuentes_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerSeccionTemplateFuentes(){
		$this->load->database();
		$query = $this->db->get(seccion_template_fuentes);			
			
		$listaSeccionTemplateFuentes = $query->result(); 
		return $listaSeccionTemplateFuentes;
 	}

 	public function obtenerSeccionTemplateFuentesPorCampo($campo, $valor = "", $limit = 0, $page = 20){
		$this->load->database();
		$list = $this->db->field_data("seccion_template_fuentes");
		$fieldList = array();
		foreach ($list as  $value) {
			$fieldList[] = $value->name;		
		}

		$this->db->select($fieldList,FALSE);
		$this->db->where(" Estado !=" , "-1" );
		$this->db->like($campo, trim($valor));
		$data = $this->db->get_compiled_select("seccion_template_fuentes", $limit, $page);

		$arrFill = array("vQuery"=> $data, "vLimit" => $limit, "vPage"=> $page);
		$stored_procedure = "call sp_PaginarResultQuery( ?, ?, ?);";
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaSeccionTemplateFuentes = $query->result();
 		return $listaDispositivo;
 	}

 	public function obtenerSeccionTemplateFuentesPaginado($limit, $row, $condicion = " Estado != -1"){
		$this->load->database();
		$arrFill = array("vLimit" => $limit, "vPage"=> $row, "vCondicion"=> $condicion);
		$stored_procedure = "call sp_PaginarResultTabla("seccion_template_fuentes", ?, ?, ?);";		
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaDispositivo = $query->result(); 
		return $listaDispositivo;
 	}
	
	public function obtenerSeccionTemplateFuentesJson(){
		$this->load->database();
		$query = $this->db->get(seccion_template_fuentes);	
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaSeccionTemplateFuentes[] = $row; 
		}
			return json_encode($listaSeccionTemplateFuentes);
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
		$listaCampos = $this->db->field_data("seccion_template_fuentes");
		$this->db->insert("seccion_template_fuentes", $obj);
		return $this->db->insert_id();
	}

	public function actualizar($obj){
		$this->load->database();

		$SeccionTemplateFuentesEnt = $this->ObtenerPorID($obj["SeccionTemplateFuentesID"]);
        	if($SeccionTemplateFuentesEnt == null){ 
		        return false; 
        	}
        	$update = array();
        	foreach ($SeccionTemplateFuentesEnt as $key => $value) {
        		if($key != "SeccionTemplateFuentesID"){
	        		if(array_key_exists($key, $obj) && $value != $obj[$key]){
	        			$update[$key] = $obj[$key];        			
	        		}           			
        		}    		
        	}
        	$update["FechaModifica"] = date("Y-m-d H:i:s");
        	$this->db->where("SeccionTemplateFuentesID", $obj["SeccionTemplateFuentesID"]);
			$rs = $this->db->update("seccion_template_fuentes", $update);
			if($rs){
				return $SeccionTemplateFuentesEnt; 
			}
			return $rs; 		
	}

	public function cambiarEstado($obj, $estado){
		$this->load->database();
		$seccion_template_fuentesEnt = $this->ObtenerPorID($obj["seccion_template_fuentes"]);

		if($dispositivoEnt == null){ 
		        return false; 
        }        
        $update["FechaModifica"] = date("Y-m-d H:i:s");
        $update["Estado"] = $estado; 
        $this->db->where("SeccionTemplateFuentesID", $obj["SeccionTemplateFuentesID"]);
		$rs = $this->db->update("seccion_template_fuentes", $update);	

		if($rs){
			return $seccion_template_fuentesEnt; 
		}
			return $rs; 
	}

	public function ObtenerPorID($id){
		$this->db->where("SeccionTemplateFuentesID", $id); 
		$result = $this->db->get("seccion_template_fuentes");		

		if ($result->num_rows() == 0)
		{
			return null; 
		}
		return current($result->result()); 
	}
	
 }
 ?>