<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class FuerzaVenta_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerFuerzaVenta(){
		$this->load->database();
		$query = $this->db->get("fuerza_venta");			
			
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
 		return $listaFuerzaVenta;
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
		$arrFill = array("vLimit" => $limit, "vPage"=> $row, "vCondicion"=> $condicion, 'vOrder'=> "Nivel ");
		$stored_procedure = "call sp_PaginarResultTabla('fuerza_venta', ?, ?, ?, ?);";		
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaDispositivo = $query->result(); 
		return $listaDispositivo;
 	}

 	public function desactivarFuerzaVenta(){
 		$this->load->database();
 		$this->db->where("Estado", 1); 		
		$rs = $this->db->update("fuerza_venta", array('Estado' => -1, "FechaFin"=> date("Y-m-d h:i:s") ) );
		return $rs;
 	}

 	public function tabularNodos($array, $usuarioID, $fecha){

 		$tbArray = array();

 		foreach ($array as $key => $value) {
 			if($value->Estado === true){

 				$hasPersona = false; 

 				$dtaPersona = array('Nombre' => '', "CodigoEmpleado"=> "", "FotoPerfil"=> null );

 			if(array_key_exists("Persona", $value)) {  					

 			 if( count($value->Persona) > 0){ 			 	

 			 	if($value->Persona[0] != null){

	 				$dtaPersona = array('Nombre' => $value->Persona[0]->Nombre, "CodigoEmpleado"=> $value->Persona[0]->CodigoEmpleado, "FotoPerfil"=> $value->Persona[0]->Foto);
	 				$hasPersona = true; 

 			 	}



 				}
 				}

 				

	 			$tbArray[] = array('GUID_FV' => $value->GuidFv , 'GUIDDependencia'=> $value->GuidDependencia, 'Nombre'=> $value->Nombre, 'Descripcion'=> $value->Descripcion, "Nivel"=> $value->Nivel, "UsuarioCreaID"=>  $usuarioID,  "FechaCrea" => $fecha, "Estado"=> 1, "Persona"=>  $dtaPersona["Nombre"], "CodigoEmpleado"=> $dtaPersona["CodigoEmpleado"], "Foto"=> $dtaPersona["FotoPerfil"] );

	 			// Foto de Empleados.

	 			//*********************************

	 			$childrenArr =  array();

	 	 		if(array_key_exists("children", $value)){
	 	 		 	 // Existe el key: en el objeto.
	 	 			$childrenArr = $this->tabularNodos($value->children, $usuarioID, $fecha);
	 	 		}  	 		
	 	 		$tbArray = array_merge($tbArray, $childrenArr); 
 			}
 	 	} 	

 	 	return $tbArray; 

 	}

 	 public function activarNuevaFuerzaVenta($jsonObj, $usuarioID){
 	 	$this->load->database(); 	 	
 	 	// Recorrido del nuevo query para insertar masivo.
 	 	// Arreglo
 	 	$lista = $this->tabularNodos($jsonObj, $usuarioID, date("Y-m-d h:i:s")); 

 	 	$this->db->trans_begin();
 	 	foreach ($lista as $k=> $val) {
 	 		$listQuery = $this->db->insert_string('fuerza_venta', $val); 	 	 	 		
 	 		$this->db->query($listQuery);			 	 		
 	 	 } 			

			if ($this->db->trans_status() === FALSE)
			{
			        $this->db->trans_rollback();
			        return false; 
			}
			else
			{				
			        $this->db->trans_commit();
			        return true; 
			}
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

	// FuerzaVenta: Obtener FuerzaVenta Por Mac e Imagen
	public function ObtenerFotoPorMac($mac){
		$this->load->database();
		
		$qry = "select d.Mac, fv.Nombre, fv.Foto  from fuerza_venta_dispositivo as fvd
inner join dispositivo as d on fvd.DispositivoID = d.DispositivoID and d.Estado = 1 and fvd.Estado = 1
inner join fuerza_venta as fv on fv.GUID_FV = fvd.GUID_FV and fv.Estado = 1
where Mac = '$mac' "; 

		
		$query = $this->db->query($qry);
 		// $res = $query->result();  		 		
		return current($query->result()); 
	}
	
 }
 ?>