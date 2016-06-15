<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class FuerzaVenta_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerFuerzaVenta(){
		$this->load->database();
		$query = $this->db->get(fuerza_venta);			
			
		$listaFuerzaVenta = $query->result(); 
		return $listaFuerzaVenta;
 	}

 	public function obtenerFuerzaVentaPorCampo($campo, $valor = "", $limit = 0, $page = 20){
		$this->load->database();
		$list = $this->db->field_data("fuerza_venta");
		$fieldList = array();
		foreach ($list as  $value) {
			$fieldList[] = $value->name;		
		}

		$this->db->select($fieldList,FALSE);
		$this->db->where(" Estado !=" , "-1" );
		$this->db->like($campo, trim($valor));
		$data = $this->db->get_compiled_select("fuerza_venta", $limit, $page);

		$arrFill = array("vQuery"=> $data, "vLimit" => $limit, "vPage"=> $page);
		$stored_procedure = "call sp_PaginarResultQuery( ?, ?, ?);";
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaFuerzaVenta = $query->result();
 		return $listaDispositivo;
 	}

 	public function obtenerFuerzaVentaResumenActivo(){
 		// Query para La Fuerza de Venta
 		$this->load->database();

 		$CQuery = "SELECT  FechaCrea, now() as FechaFin  FROM bis_gestionvista.fuerza_venta where Estado = 1 group by FechaCrea;";
 		$query = $this->db->query($CQuery);
 		$res = $query->result();  		
 		return current($res); 
 	}

 	public function obtenerFuerzaVentaResumenBack(){
 		// Query para La Fuerza de Venta
 		$this->load->database();

 		$CQuery = "SELECT  FechaCrea, now() as FechaFin  FROM bis_gestionvista.fuerza_venta where Estado != 1 group by FechaCrea;";
 		$query = $this->db->query($CQuery);
 		$res = $query->result();  		
 		return $res; 
 	}

 	public function obtenerFuerzaVentaPaginado($limit, $row, $condicion = " Estado != -1"){
		$this->load->database();
		$arrFill = array("vLimit" => $limit, "vPage"=> $row, "vCondicion"=> $condicion);
		$stored_procedure = "call sp_PaginarResultTabla('fuerza_venta', ?, ?, ?);";		
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaDispositivo = $query->result(); 
		return $listaDispositivo;
 	}
	
	public function obtenerFuerzaVentaJson(){
		$this->load->database();
		$query = $this->db->get(fuerza_venta);	
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaFuerzaVenta[] = $row; 
		}
			return json_encode($listaFuerzaVenta);
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
		$listaCampos = $this->db->field_data("fuerza_venta");
		$this->db->insert("fuerza_venta", $obj);
		return $this->db->insert_id();
	}

	public function actualizar($obj){
		$this->load->database();

		$FuerzaVentaEnt = $this->ObtenerPorID($obj["GUID_FV"]);
        	if($FuerzaVentaEnt == null){ 
		        return false; 
        	}
        	$update = array();
        	foreach ($FuerzaVentaEnt as $key => $value) {
        		if($key != "GUID_FV"){
	        		if(array_key_exists($key, $obj) && $value != $obj[$key]){
	        			$update[$key] = $obj[$key];        			
	        		}           			
        		}    		
        	}
        	$update["FechaModifica"] = date("Y-m-d H:i:s");
        	$this->db->where("GUID_FV", $obj["GUID_FV"]);
			$rs = $this->db->update("fuerza_venta", $update);
			if($rs){
				return $FuerzaVentaEnt; 
			}
			return $rs; 		
	}

	public function cambiarEstado($obj, $estado){
		$this->load->database();
		$fuerza_ventaEnt = $this->ObtenerPorID($obj["fuerza_venta"]);

		if($dispositivoEnt == null){ 
		        return false; 
        }        
        $update["FechaModifica"] = date("Y-m-d H:i:s");
        $update["Estado"] = $estado; 
        $this->db->where("GUID_FV", $obj["GUID_FV"]);
		$rs = $this->db->update("fuerza_venta", $update);	

		if($rs){
			return $fuerza_ventaEnt; 
		}
			return $rs; 
	}

	public function ObtenerPorID($id){
		$this->db->where("GUID_FV", $id); 
		$result = $this->db->get("fuerza_venta");		

		if ($result->num_rows() == 0)
		{
			return null; 
		}
		return current($result->result()); 
	}
	
 }
 ?>