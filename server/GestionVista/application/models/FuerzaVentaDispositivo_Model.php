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
		$stored_procedure = "call sp_PaginarResultTabla("fuerza_venta_dispositivo", ?, ?, ?);";		
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