<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class PlanConfig_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerPlanConfig(){
		$this->load->database();
		$query = $this->db->get(plan_config);			
			
		$listaPlanConfig = $query->result(); 
		return $listaPlanConfig;
 	}

 	public function obtenerPlanConfigPorCampo($campo, $valor = "", $limit = 0, $page = 20){
		$this->load->database();
		$list = $this->db->field_data("plan_config");
		$fieldList = array();
		foreach ($list as  $value) {
			$fieldList[] = $value->name;		
		}

		$this->db->select($fieldList,FALSE);
		$this->db->where(" Estado !=" , "-1" );
		$this->db->like($campo, trim($valor));
		$data = $this->db->get_compiled_select("plan_config", $limit, $page);

		$arrFill = array("vQuery"=> $data, "vLimit" => $limit, "vPage"=> $page);
		$stored_procedure = "call sp_PaginarResultQuery( ?, ?, ?);";
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaPlanConfig = $query->result();
 		return $listaPlanConfig;
 	}

 	public function obtenerPlanConfigPaginado($limit, $row, $condicion = " Estado != -1"){
		$this->load->database();
		$arrFill = array("vLimit" => $limit, "vPage"=> $row, "vCondicion"=> $condicion);
		$stored_procedure = "call sp_PaginarResultTabla('plan_config', ?, ?, ?, null);";		
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaPlanConfig = $query->result(); 
		return $listaPlanConfig;
 	}
	
	public function obtenerPlanConfigJson(){
		$this->load->database();
		$query = $this->db->get(plan_config);	
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaPlanConfig[] = $row; 
		}
			return json_encode($listaPlanConfig);
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
		$listaCampos = $this->db->field_data("plan_config");
		$this->db->insert("plan_config", $obj);
		return $this->db->insert_id();
	}

	public function actualizar($obj){
		$this->load->database();

		$PlanConfigEnt = $this->ObtenerPorID($obj["PlanConfigID"]);
        	if($PlanConfigEnt == null){ 
		        return false; 
        	}
        	$update = array();
        	foreach ($PlanConfigEnt as $key => $value) {
        		if($key != "PlanConfigID"){
	        		if(array_key_exists($key, $obj) && $value != $obj[$key]){
	        			$update[$key] = $obj[$key];        			
	        		}           			
        		}    		
        	}
        	$update["FechaModifica"] = date("Y-m-d H:i:s");
        	$this->db->where("PlanConfigID", $obj["PlanConfigID"]);
			$rs = $this->db->update("plan_config", $update);
			if($rs){
				return $PlanConfigEnt; 
			}
			return $rs; 		
	}

	public function cambiarEstado($obj, $estado){
		$this->load->database();
		$plan_configEnt = $this->ObtenerPorID($obj["plan_config"]);

		if($plan_configEnt == null){ 
		        return false; 
        }        
        $update["FechaModifica"] = date("Y-m-d H:i:s");
        $update["Estado"] = $estado; 
        $this->db->where("PlanConfigID", $obj["PlanConfigID"]);
		$rs = $this->db->update("plan_config", $update);	

		if($rs){
			return $plan_configEnt; 
		}
			return $rs; 
	}

	public function ObtenerPorID($id){
		$this->db->where("PlanConfigID", $id); 
		$result = $this->db->get("plan_config");		

		if ($result->num_rows() == 0)
		{
			return null; 
		}
		return current($result->result()); 
	}
	
 }
 ?>