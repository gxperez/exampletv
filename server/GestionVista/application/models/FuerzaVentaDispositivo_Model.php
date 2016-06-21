<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class FuerzaVentaDispositivo_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerFuerzaVentaDispositivo(){
		$this->load->database();
		$query = $this->db->get(fuerza_venta_dispositivo);			
			
		$listaFuerzaVentaDispositivo = $query->result(); 
		return $listaFuerzaVentaDispositivo;
 	}


 	public function validarExistencia( $objeto ){
		$this->load->database(); 
 		// Validar por Query.

 		$this->db->where("GUID_FV", trim($objeto["GUID_FV"]) ); 
 		$this->db->where("DispositivoID", $objeto["DispositivoID"] ); 
 		$this->db->where("Estado", 1);  		
		$result = $this->db->get("fuerza_venta_dispositivo");

		if ($result->num_rows() > 0){
			return true; 
		}

		return false; 
 	}

 	public function obtenerDispositivoRelacion(){
 		$this->load->database(); 

 		$query = "select D.DispositivoID,  D.Mac, D.Nombre,
        D.Descripcion, D.Estado,  F.Nombre as FuerzaVenta, true as Dis_Register,          
        ifnull(F.Descripcion, 'N/A') as Descripcion  from dispositivo as D left join 
 fuerza_venta_dispositivo as FD 
 on D.DispositivoID = FD.DispositivoID 
 and FD.Estado = 1
 left join fuerza_venta as F on
 F.GUID_FV = FD.GUID_FV
 and F.Estado = 1
 where D.Estado = 1"; 
 		$rest = $this->db->query($query);

		$listaFuerzaVentaDispositivo = $rest->result();

		return $listaFuerzaVentaDispositivo; 
 	}

 	public function obtenerFuerzaVentaRelacion(){
 		$this->load->database(); 

 		$query = "select f.GUID_FV, f.GUIDDependencia, f.Nombre as FuerzaVenta, f.Estado, 
 f.Nivel,
 D.DispositivoID, 
 D.Mac,
 D.Nombre        	
from fuerza_venta  as f left join 
 fuerza_venta_dispositivo as FD 
 on f.GUID_FV = FD.GUID_FV 
 and FD.Estado = 1
 left join dispositivo as d on
 d.DispositivoID = FD.DispositivoID
 and d.Estado = 1
 where f.Estado = 1
 order by f.Nivel, f.Nombre"; 
			 		$rest = $this->db->query($query);
					$listaFuerzaVentaDispositivo = $rest->result();
		return $listaFuerzaVentaDispositivo; 
 	}

 	public function formatNivelObject($list){

 		$arr = array();

 		foreach ($list as $key => $value) { 			
			if(! array_key_exists($value->Nivel , $arr) ){
				$arr[$value->Nivel] = array();				
			}
			$arr[$value->Nivel][] = $value;  			
 		}
		return $arr; 
 	}



 	public function obtenerFuerzaVentaDispositivoPorCampo($campo, $valor = "", $limit = 0, $page = 20){
		$this->load->database();
		$list = $this->db->field_data("fuerza_venta_dispositivo");
		$fieldList = array();
		foreach ($list as  $value) {
			$fieldList[] = $value->name;		
		}

		$this->db->select($fieldList,FALSE);
		$this->db->where(" Estado !=" , "-1" );
		$this->db->like($campo, trim($valor));
		$data = $this->db->get_compiled_select("fuerza_venta_dispositivo", $limit, $page);

		$arrFill = array("vQuery"=> $data, "vLimit" => $limit, "vPage"=> $page);
		$stored_procedure = "call sp_PaginarResultQuery( ?, ?, ?);";
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaFuerzaVentaDispositivo = $query->result();
 		return $listaDispositivo;
 	}

 	public function obtenerFuerzaVentaDispositivoPaginado($limit, $row, $condicion = " Estado != -1"){
		$this->load->database();
		$arrFill = array("vLimit" => $limit, "vPage"=> $row, "vCondicion"=> $condicion);
		$stored_procedure = "call sp_PaginarResultTabla('fuerza_venta_dispositivo', ?, ?, ?);";		
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaDispositivo = $query->result(); 
		return $listaDispositivo;
 	}
	
	public function obtenerFuerzaVentaDispositivoJson(){
		$this->load->database();
		$query = $this->db->get(fuerza_venta_dispositivo);	
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaFuerzaVentaDispositivo[] = $row; 
		}
			return json_encode($listaFuerzaVentaDispositivo);
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
		$listaCampos = $this->db->field_data("fuerza_venta_dispositivo");
		$this->db->insert("fuerza_venta_dispositivo", $obj);
		return $this->db->insert_id();
	}

	public function actualizar($obj){
		$this->load->database();

		$FuerzaVentaDispositivoEnt = $this->ObtenerPorID($obj["FuerzaVentaDispositivoID"]);
        	if($FuerzaVentaDispositivoEnt == null){ 
		        return false; 
        	}
        	$update = array();
        	foreach ($FuerzaVentaDispositivoEnt as $key => $value) {
        		if($key != "FuerzaVentaDispositivoID"){
	        		if(array_key_exists($key, $obj) && $value != $obj[$key]){
	        			$update[$key] = $obj[$key];        			
	        		}           			
        		}    		
        	}
        	$update["FechaModifica"] = date("Y-m-d H:i:s");
        	$this->db->where("FuerzaVentaDispositivoID", $obj["FuerzaVentaDispositivoID"]);
			$rs = $this->db->update("fuerza_venta_dispositivo", $update);
			if($rs){
				return $FuerzaVentaDispositivoEnt; 
			}
			return $rs; 		
	}

	public function cambiarEstado($obj, $estado){
		$this->load->database();
		$fuerza_venta_dispositivoEnt = $this->ObtenerPorID($obj["fuerza_venta_dispositivo"]);

		if($dispositivoEnt == null){ 
		        return false; 
        }        
        $update["FechaModifica"] = date("Y-m-d H:i:s");
        $update["Estado"] = $estado; 
        $this->db->where("FuerzaVentaDispositivoID", $obj["FuerzaVentaDispositivoID"]);
		$rs = $this->db->update("fuerza_venta_dispositivo", $update);	

		if($rs){
			return $fuerza_venta_dispositivoEnt; 
		}
			return $rs; 
	}

	public function eliminar($obj){

		$this->db->where("DispositivoID", $obj["DispositivoID"]);
		$this->db->where("GUID_FV", $obj["GUID_FV"]);
		// Esta fue tu mejor temporada.
		return $this->db->delete("fuerza_venta_dispositivo"); 
	}


	public function ObtenerPorID($id){
		$this->db->where("FuerzaVentaDispositivoID", $id); 
		$result = $this->db->get("fuerza_venta_dispositivo");		

		if ($result->num_rows() == 0)
		{
			return null; 
		}
		return current($result->result()); 
	}
	
 }
 ?>