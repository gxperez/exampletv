<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class Bloques_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerBloques(){
		$this->load->database();
		$query = $this->db->get(bloques);			
			
		$listaBloques = $query->result(); 
		return $listaBloques;
 	}

 	public function generarBloques($ProgramacionID){
 		$this->load->database();

 		// Excelente. Dios es BUeno todo el tiempo. Enfocate
		$this->db->select("*");
		$this->db->where("ProgramacionID",  $ProgramacionID);
		$query = $this->db->get("vw_programacion_bloque_semana");	
		
		$listaBloques = $query->result();

		return $listaBloques; 
 	}

 	public function obtenerListaBloqueActivos(){
 		$this->load->database();

 		$sql = "select b.BloqueID, b.ProgramacionID, b.FrecuenciaTipo, (select Descripcion from vw_frecuencia_desc where Id = b.FrecuenciaTipo limit 1) FrecuenciaTipoDesc,
 b.HoraInicio, b.HoraFin, b.Estado from bloques as b
 where b.Estado = 1;"; 

 		$query = $this->db->query($sql);
		$listaBloques = $query->result();


		return $listaBloques; 



 	}

 	public function obtenerBloquesPorCampo($campo, $valor = "", $limit = 0, $page = 20){
		$this->load->database();
		$list = $this->db->field_data("bloques");
		$fieldList = array();
		foreach ($list as  $value) {
			$fieldList[] = $value->name;		
		}

		$this->db->select($fieldList,FALSE);
		$this->db->where(" Estado !=" , "-1" );
		$this->db->like($campo, trim($valor));
		$data = $this->db->get_compiled_select("bloques", $limit, $page);

		$arrFill = array("vQuery"=> $data, "vLimit" => $limit, "vPage"=> $page);
		$stored_procedure = "call sp_PaginarResultQuery( ?, ?, ?);";
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaBloques = $query->result();
 		return $listaDispositivo;
 	}

 	public function obtenerBloquesPaginado($limit, $row, $condicion = " Estado != -1"){
		$this->load->database();
		$arrFill = array("vLimit" => $limit, "vPage"=> $row, "vCondicion"=> $condicion);
		$stored_procedure = "call sp_PaginarResultTabla('bloques', ?, ?, ?, null);";		
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaDispositivo = $query->result(); 
		return $listaDispositivo;
 	}
	
	public function obtenerBloquesJson(){
		$this->load->database();
		$query = $this->db->get(bloques);	
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaBloques[] = $row; 
		}
			return json_encode($listaBloques);
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
		$listaCampos = $this->db->field_data("bloques");
		$this->db->insert("bloques", $obj);
		return $this->db->insert_id();
	}

	public function actualizar($obj){
		$this->load->database();

		$BloquesEnt = $this->ObtenerPorID($obj["BloqueID"]);
        	if($BloquesEnt == null){ 
		        return false; 
        	}
        	$update = array();
        	foreach ($BloquesEnt as $key => $value) {
        		if($key != "BloqueID"){
	        		if(array_key_exists($key, $obj) && $value != $obj[$key]){
	        			$update[$key] = $obj[$key];        			
	        		}           			
        		}    		
        	}
        	$update["FechaModificacion"] = date("Y-m-d H:i:s");
        	$this->db->where("BloqueID", $obj["BloqueID"]);
			$rs = $this->db->update("bloques", $update);
			if($rs){
				return $BloquesEnt; 
			}
			return $rs; 		
	}

	public function cambiarEstado($obj, $estado){
		$this->load->database();
		$bloquesEnt = $this->ObtenerPorID($obj["bloques"]);

		if($dispositivoEnt == null){ 
		        return false; 
        }        
        $update["FechaModifica"] = date("Y-m-d H:i:s");
        $update["Estado"] = $estado; 
        $this->db->where("BloqueID", $obj["BloqueID"]);
		$rs = $this->db->update("bloques", $update);	

		if($rs){
			return $bloquesEnt; 
		}
			return $rs; 
	}

	public function ObtenerPorID($id){
		$this->db->where("BloqueID", $id); 
		$result = $this->db->get("bloques");		

		if ($result->num_rows() == 0)
		{
			return null; 
		}
		return current($result->result()); 
	}
	
 }
 ?>