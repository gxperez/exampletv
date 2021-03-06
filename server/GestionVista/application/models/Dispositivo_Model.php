<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class Dispositivo_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerDispositivo(){
		$this->load->database();
		$query = $this->db->get('dispositivo');			
			
		$listaDispositivo = $query->result(); 
		return $listaDispositivo;
 	}

 	public function obtenerDispositivoPorCampo($campo, $valor = "", $limit = 0, $page = 20){
		$this->load->database();
		$list = $this->db->field_data("dispositivo");

		$fieldList = array();
		foreach ($list as  $value) {
			$fieldList[] = $value->name;		
		}		

		$this->db->select($fieldList,FALSE);		
		$this->db->where(" Estado != " ,'-1' );
		$this->db->like($campo, trim($valor));	

		$data = $this->db->get_compiled_select("dispositivo", $limit, $page);

		$arrFill = array('vQuery'=> $data, 'vLimit' => $limit, 'vPage'=> $page);

	//	echo $data; 

	//	exit(); 

		$stored_procedure = "call sp_PaginarResultQuery( ?, ?, ?);";		
		$query = $this->db->query($stored_procedure, $arrFill);

		$listaDispositivo = $query->result(); 
 		return $listaDispositivo;		
 	}

 	public function obtenerDispositivoPaginado($limit, $row, $condicion = " Estado != -1"){
		$this->load->database();

		$arrFill = array('vLimit' => $limit, 'vPage'=> $row, 'vCondicion'=> $condicion);

		$stored_procedure = "call sp_PaginarResultTabla('dispositivo', ?, ?, ?, null);";		
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaDispositivo = $query->result(); 
		return $listaDispositivo;
 	}
	
	public function obtenerDispositivoJson(){
		$this->load->database();
		$query = $this->db->get("dispositivo");	
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaDispositivo[] = $row; 
		}
			return json_encode($listaDispositivo);
  }	

	public function insertar($obj){
		$this->load->database();
		$listaCampos = $this->db->field_data("dispositivo");
// Filtrar y Validar LA EXISTENCIA DE LOS Campos en la Entidad.
		$this->db->insert("dispositivo", $obj);
		return $this->db->insert_id();
	}

	public function cambiarEstado($obj, $estado){
		$this->load->database();
		$dispositivoEnt = $this->ObtenerPorID($obj['DispositivoID']);

		if($dispositivoEnt == null){ 
		        return false; 
        }        
        $update['UltimaSesion'] = date('Y-m-d H:i:s');
        $update['Estado'] = $estado; 
        $this->db->where('DispositivoID', $obj['DispositivoID']);
		$rs = $this->db->update('dispositivo', $update);	

		if($rs){
			return $dispositivoEnt; 
		}
			return $rs; 
	}

	public function actualizar($obj){
		$this->load->database();

		$dispositivoEnt = $this->ObtenerPorID($obj['DispositivoID']);
        	if($dispositivoEnt == null){ 
		        return false; 
        	}
        	$update = array();
        	foreach ($dispositivoEnt as $key => $value) {
        		if($key != "DispositivoID"){
	        		if(array_key_exists($key, $obj) && $value != $obj[$key]){
	        			$update[$key] = $obj[$key];        			
	        		}           			
        		}    		
        	}
        	$update['UltimaSesion'] = date('Y-m-d H:i:s');
        	$this->db->where('DispositivoID', $obj['DispositivoID']);
			$rs = $this->db->update('dispositivo', $update);
			if($rs){
				return $dispositivoEnt; 
			}
			return $rs; 		
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

	public function ObtenerPorID($id){
		$this->load->database();
		$this->db->where("DispositivoID", $id); 
		$result = $this->db->get("dispositivo");		
		if ($result->num_rows() == 0)
		{
			return null; 
		}
		return current($result->result()); 
	}
	
 }
 ?>