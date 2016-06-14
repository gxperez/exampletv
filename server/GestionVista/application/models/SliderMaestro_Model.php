<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class SliderMaestro_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerSliderMaestro(){
		$this->load->database();
		$query = $this->db->get(slider_maestro);			
			
		$listaSliderMaestro = $query->result(); 
		return $listaSliderMaestro;
 	}

 	public function obtenerSliderMaestroPorCampo($campo, $valor = "", $limit = 0, $page = 20){
		$this->load->database();
		$list = $this->db->field_data("slider_maestro");
		$fieldList = array();
		foreach ($list as  $value) {
			$fieldList[] = $value->name;		
		}

		$this->db->select($fieldList,FALSE);
		$this->db->where(" Estado !=" , "-1" );
		$this->db->like($campo, trim($valor));
		$data = $this->db->get_compiled_select("slider_maestro", $limit, $page);

		$arrFill = array("vQuery"=> $data, "vLimit" => $limit, "vPage"=> $page);
		$stored_procedure = "call sp_PaginarResultQuery( ?, ?, ?);";
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaSliderMaestro = $query->result();
 		return $listaDispositivo;
 	}

 	public function obtenerSliderMaestroPaginado($limit, $row, $condicion = " Estado != -1"){
		$this->load->database();
		$arrFill = array("vLimit" => $limit, "vPage"=> $row, "vCondicion"=> $condicion);
		$stored_procedure = "call sp_PaginarResultTabla("slider_maestro", ?, ?, ?);";		
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaDispositivo = $query->result(); 
		return $listaDispositivo;
 	}
	
	public function obtenerSliderMaestroJson(){
		$this->load->database();
		$query = $this->db->get(slider_maestro);	
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaSliderMaestro[] = $row; 
		}
			return json_encode($listaSliderMaestro);
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
		$listaCampos = $this->db->field_data("slider_maestro");
		$this->db->insert("slider_maestro", $obj);
		return $this->db->insert_id();
	}

	public function actualizar($obj){
		$this->load->database();

		$SliderMaestroEnt = $this->ObtenerPorID($obj["SliderMaestroID"]);
        	if($SliderMaestroEnt == null){ 
		        return false; 
        	}
        	$update = array();
        	foreach ($SliderMaestroEnt as $key => $value) {
        		if($key != "SliderMaestroID"){
	        		if(array_key_exists($key, $obj) && $value != $obj[$key]){
	        			$update[$key] = $obj[$key];        			
	        		}           			
        		}    		
        	}
        	$update["FechaModifica"] = date("Y-m-d H:i:s");
        	$this->db->where("SliderMaestroID", $obj["SliderMaestroID"]);
			$rs = $this->db->update("slider_maestro", $update);
			if($rs){
				return $SliderMaestroEnt; 
			}
			return $rs; 		
	}

	public function cambiarEstado($obj, $estado){
		$this->load->database();
		$slider_maestroEnt = $this->ObtenerPorID($obj["slider_maestro"]);

		if($dispositivoEnt == null){ 
		        return false; 
        }        
        $update["FechaModifica"] = date("Y-m-d H:i:s");
        $update["Estado"] = $estado; 
        $this->db->where("SliderMaestroID", $obj["SliderMaestroID"]);
		$rs = $this->db->update("slider_maestro", $update);	

		if($rs){
			return $slider_maestroEnt; 
		}
			return $rs; 
	}

	public function ObtenerPorID($id){
		$this->db->where("SliderMaestroID", $id); 
		$result = $this->db->get("slider_maestro");		

		if ($result->num_rows() == 0)
		{
			return null; 
		}
		return current($result->result()); 
	}
	
 }
 ?>