<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class SeccionTemplate_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerSeccionTemplate(){
		$this->load->database();
		$query = $this->db->get("seccion_template");			
			
		$listaSeccionTemplate = $query->result(); 
		return $listaSeccionTemplate;
 	}

 	public function obtenerSeccionTemplatePorCampo($campo, $valor = "", $limit = 0, $page = 20){
		$this->load->database();
		$list = $this->db->field_data("seccion_template");
		$fieldList = array();
		foreach ($list as  $value) {
			$fieldList[] = $value->name;		
		}

		$this->db->select($fieldList,FALSE);
		$this->db->where(" Estado !=" , "-1" );
		$this->db->like($campo, trim($valor));
		$data = $this->db->get_compiled_select("seccion_template", $limit, $page);

		$arrFill = array("vQuery"=> $data, "vLimit" => $limit, "vPage"=> $page);
		$stored_procedure = "call sp_PaginarResultQuery( ?, ?, ?);";
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaSeccionTemplate = $query->result();
 		return $listaDispositivo;
 	}

 	public function obtenerSeccionTemplatePaginado($limit, $row, $condicion = " Estado != -1"){
		$this->load->database();
		$arrFill = array("vLimit" => $limit, "vPage"=> $row, "vCondicion"=> $condicion);
		$stored_procedure = "call sp_PaginarResultTabla('seccion_template', ?, ?, ?, null);";		
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaDispositivo = $query->result(); 
		return $listaDispositivo;
 	}
	
	public function obtenerSeccionTemplateJson(){
		$this->load->database();
		$query = $this->db->get("seccion_template");	
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaSeccionTemplate[] = $row; 
		}
			return json_encode($listaSeccionTemplate);
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
		$listaCampos = $this->db->field_data("seccion_template");
		$this->db->insert("seccion_template", $obj);
		return $this->db->insert_id();
	}

	public function actualizar($obj){
		$this->load->database();

		$SeccionTemplateEnt = $this->ObtenerPorID($obj["SeccionTemplateID"]);
        	if($SeccionTemplateEnt == null){ 
		        return false; 
        	}
        	$update = array();
        	foreach ($SeccionTemplateEnt as $key => $value) {
        		if($key != "SeccionTemplateID"){
	        		if(array_key_exists($key, $obj) && $value != $obj[$key]){
	        			$update[$key] = $obj[$key];        			
	        		}           			
        		}    		
        	}
        	$update["FechaModificacion"] = date("Y-m-d H:i:s");
        	$this->db->where("SeccionTemplateID", $obj["SeccionTemplateID"]);
			$rs = $this->db->update("seccion_template", $update);
			if($rs){
				return $SeccionTemplateEnt; 
			}
			return $rs; 		
	}

	public function cambiarEstado($obj, $estado){
		$this->load->database();
		$seccion_templateEnt = $this->ObtenerPorID($obj["seccion_template"]);

		if($dispositivoEnt == null){ 
		        return false; 
        }        
        $update["FechaModifica"] = date("Y-m-d H:i:s");
        $update["Estado"] = $estado; 
        $this->db->where("SeccionTemplateID", $obj["SeccionTemplateID"]);
		$rs = $this->db->update("seccion_template", $update);	

		if($rs){
			return $seccion_templateEnt; 
		}
			return $rs; 
	}


	public function obtenerSeccionPorTemplatePagesID($id){
		$this->load->database();

		$this->db->where("TemplatePagesID", $id); 
		$this->db->where("Estado", 1); 		
		$result = $this->db->get("seccion_template");	

		$listaSeccionTemplate = $result->result(); 

		$result = array();

		foreach ($listaSeccionTemplate as $value) {
			$result["pos_" . $value->Posicion] = $value; 

		}
		return $result;	



	}

	public function ObtenerPorID($id){
		$this->load->database();
		$this->db->where("SeccionTemplateID", $id); 
		$result = $this->db->get("seccion_template");		

		if ($result->num_rows() == 0)
		{
			return null; 
		}
		return current($result->result()); 
	}
	
 }
 ?>