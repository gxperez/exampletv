<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class TemplatePages_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerTemplatePages(){
		$this->load->database();
		$query = $this->db->get("template_pages");			
			
		$listaTemplatePages = $query->result(); 
		return $listaTemplatePages;
 	}

 	public function obtenerTemplatePagesPorCampo($campo, $valor = "", $limit = 0, $page = 20){
		$this->load->database();
		$list = $this->db->field_data("template_pages");
		$fieldList = array();
		foreach ($list as  $value) {
			$fieldList[] = $value->name;		
		}

		$this->db->select($fieldList,FALSE);
		$this->db->where(" Estado !=" , "-1" );
		$this->db->like($campo, trim($valor));
		$data = $this->db->get_compiled_select("template_pages", $limit, $page);

		$arrFill = array("vQuery"=> $data, "vLimit" => $limit, "vPage"=> $page);
		$stored_procedure = "call sp_PaginarResultQuery( ?, ?, ?);";
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaTemplatePages = $query->result();
 		return $listaDispositivo;
 	}

 	public function obtenerTemplatePagesPaginado($limit, $row, $condicion = " Estado != -1"){
		$this->load->database();
		$arrFill = array("vLimit" => $limit, "vPage"=> $row, "vCondicion"=> $condicion);
		$stored_procedure = "call sp_PaginarResultTabla('template_pages', ?, ?, ?, null);";		
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaDispositivo = $query->result(); 
		return $listaDispositivo;
 	}
	
	public function obtenerTemplatePagesJson(){
		$this->load->database();
		$query = $this->db->get("template_pages");	
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaTemplatePages[] = $row; 
		}
			return json_encode($listaTemplatePages);
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
		$listaCampos = $this->db->field_data("template_pages");
		$this->db->insert("template_pages", $obj);
		return $this->db->insert_id();
	}

	public function actualizar($obj){
		$this->load->database();

		$TemplatePagesEnt = $this->ObtenerPorID($obj["TemplatePagesID"]);
        	if($TemplatePagesEnt == null){ 
		        return false; 
        	}
        	$update = array();
        	foreach ($TemplatePagesEnt as $key => $value) {
        		if($key != "TemplatePagesID"){
	        		if(array_key_exists($key, $obj) && $value != $obj[$key]){
	        			$update[$key] = $obj[$key];        			
	        		}           			
        		}    		
        	}
        	
        	$update["FechaModificacion"] = date("Y-m-d H:i:s");
        	$this->db->where("TemplatePagesID", $obj["TemplatePagesID"]);
			$rs = $this->db->update("template_pages", $update);
			if($rs){
				return $TemplatePagesEnt; 
			}
			return $rs; 		
	}

	public function cambiarEstado($obj, $estado){
		$this->load->database();
		$template_pagesEnt = $this->ObtenerPorID($obj["template_pages"]);

		if($dispositivoEnt == null){ 
		        return false; 
        }        
        $update["FechaModifica"] = date("Y-m-d H:i:s");
        $update["Estado"] = $estado; 
        $this->db->where("TemplatePagesID", $obj["TemplatePagesID"]);
		$rs = $this->db->update("template_pages", $update);	

		if($rs){
			return $template_pagesEnt; 
		}
			return $rs; 
	}

	public function ObtenerPorIDSliderMaestro($sliderMaestroID){
		$this->load->database();
		$this->db->where("Estado", 1);
		$this->db->where("SliderMaestroID", $sliderMaestroID );		
		$this->db->order_by("Posicion");		
		$result = $this->db->get("template_pages");		
		if ($result->num_rows() == 0)
		{
			return null; 
		}

		return $result->result(); 		
	}

	public function ObtenerPorID($id){
		$this->load->database();
		$this->db->where("TemplatePagesID", $id); 
		$result = $this->db->get("template_pages");		

		if ($result->num_rows() == 0)
		{
			return null; 
		}
		return current($result->result()); 
	}
	
 }
 ?>