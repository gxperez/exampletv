<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class GrupoTv_Model extends CI_Model { 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerGrupoTv(){
		$this->load->database();
		$query = $this->db->get("grupo_tv");			
			
		$listaGrupoTv = $query->result(); 
		return $listaGrupoTv;
 	}

 	public function validarGrupoTv($obj){
 		$this->load->database();
 		$this->db->where("DispositivoID", $obj["DispositivoID"]); 
 		$this->db->where("GrupoID", $obj["GrupoID"]); 
 		$this->db->where("Estado", 1); 
 		$query = $this->db->get("grupo_tv");	

 		if ($query->num_rows() >= 1)
		{
			return false;
		}
		

		return true;			
		// $listaGrupoTv = $query->result(); 

 	}

 	public function obtenerListaGrupoTvPorGrupoID(){
 		$this->load->database();

 		$query = "select gt.GrupoTvID, g.GrupoID, g.Descripcion, f.GUID_FV, f.GUIDDependencia, f.Nombre as FuerzaVenta, f.Estado, 
 f.Nivel,
 D.DispositivoID, 
 D.Mac,
 D.Nombre  from grupo_tv as gt 
 inner join Grupo as g on g.GrupoID = gt.GrupoID
 and g.Estado = 1
 inner join Dispositivo as d on d.DispositivoID = gt.DispositivoID
 and d.Estado = 1
 inner join fuerza_venta_dispositivo as fv on fv.DispositivoID = d.DispositivoID 
 and   fv.Estado = 1
 inner join fuerza_venta as f on f.GUID_FV = fv.GUID_FV
 and f.Estado = 1 where gt.Estado = 1";

 			$result = $this->db->query($query);
		$listaGrupoTv = $result->result();

		$restGrupoTv = array();		

		// Recorremos para dar formato al resultado

		foreach ($listaGrupoTv as $itm) {
			if(!array_key_exists($itm->GrupoID, $restGrupoTv) ){
				$restGrupoTv[$itm->GrupoID]  = []; 	
			}

			$restGrupoTv[$itm->GrupoID][]= $itm; 			
		}

 		return $restGrupoTv;

 	}

 	public function obtenerGrupoTvPorCampo($campo, $valor = "", $limit = 0, $page = 20){
		$this->load->database();
		$list = $this->db->field_data("grupo_tv");
		$fieldList = array();
		foreach ($list as  $value) {
			$fieldList[] = $value->name;		
		}

		$this->db->select($fieldList,FALSE);
		$this->db->where(" Estado !=" , "-1" );
		$this->db->like($campo, trim($valor));
		$data = $this->db->get_compiled_select("grupo_tv", $limit, $page);

		$arrFill = array("vQuery"=> $data, "vLimit" => $limit, "vPage"=> $page);
		$stored_procedure = "call sp_PaginarResultQuery( ?, ?, ?);";
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaGrupoTv = $query->result();
 		return $listaDispositivo;
 	}

 	public function obtenerGrupoTvPaginado($limit, $row, $condicion = " Estado != -1"){
		$this->load->database();
		$arrFill = array("vLimit" => $limit, "vPage"=> $row, "vCondicion"=> $condicion);
		$stored_procedure = "call sp_PaginarResultTabla('grupo_tv', ?, ?, ?, null);";		
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaDispositivo = $query->result(); 
		return $listaDispositivo;
 	}
	
	public function obtenerGrupoTvJson(){
		$this->load->database();
		$query = $this->db->get(grupo_tv);	
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaGrupoTv[] = $row; 
		}
			return json_encode($listaGrupoTv);
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
		$listaCampos = $this->db->field_data("grupo_tv");
		$this->db->insert("grupo_tv", $obj);
		return $this->db->insert_id();
	}

	public function actualizarPorGrupoIDyDispositivoID($obj){

		$this->load->database();
		$this->db->where("DispositivoID", $obj["DispositivoID"]); 
		$this->db->where("GrupoID", $obj["GrupoID"]); 
		$result = $this->db->get("grupo_tv");		

		if ($result->num_rows() == 0)
		{
			return true; 
		}

		$GrupoTvEnt = current($result->result()); 


		 
        	if($GrupoTvEnt == null){ 
		        return false; 
        	}

        	$update = array();
        	foreach ($GrupoTvEnt as $key => $value) {
        		if($key != "GrupoTvID"){
	        		if(array_key_exists($key, $obj) && $value != $obj[$key]){
	        			$update[$key] = $obj[$key];        			
	        		}           			
        		}    		
        	}

        	$update["FechaModifica"] = date("Y-m-d H:i:s");
        	$this->db->where("DispositivoID", $obj["DispositivoID"]);
        	$this->db->where("GrupoID", $obj["GrupoID"]);

			$rs = $this->db->update("grupo_tv", $update);
			if($rs){
				return $GrupoTvEnt; 
			}
			return $rs; 		
	}

	public function actualizar($obj){
		$this->load->database();

		$GrupoTvEnt = $this->ObtenerPorID($obj["GrupoTvID"]);
        	if($GrupoTvEnt == null){ 
		        return false; 
        	}
        	$update = array();
        	foreach ($GrupoTvEnt as $key => $value) {
        		if($key != "GrupoTvID"){
	        		if(array_key_exists($key, $obj) && $value != $obj[$key]){
	        			$update[$key] = $obj[$key];        			
	        		}           			
        		}    		
        	}
        	$update["FechaModifica"] = date("Y-m-d H:i:s");
        	$this->db->where("GrupoTvID", $obj["GrupoTvID"]);
			$rs = $this->db->update("grupo_tv", $update);
			if($rs){
				return $GrupoTvEnt; 
			}
			return $rs; 		
	}

	public function cambiarEstado($obj, $estado){
		$this->load->database();
		$grupo_tvEnt = $this->ObtenerPorID($obj["grupo_tv"]);

		if($dispositivoEnt == null){ 
		        return false; 
        }        
        $update["FechaModifica"] = date("Y-m-d H:i:s");
        $update["Estado"] = $estado; 
        $this->db->where("GrupoTvID", $obj["GrupoTvID"]);
		$rs = $this->db->update("grupo_tv", $update);	

		if($rs){
			return $grupo_tvEnt; 
		}
			return $rs; 
	}

	public function GenerarFiltroPorMac($mac){

		$this->load->database();
		$filtrosDefinidos = array("@Region"=>"Todos","@Centro"=>"Todos","@Zona"=>"Todos");


		$sql = "select CASE when (fv.Nivel = 1) then '@Region' 
 when (fv.Nivel = 2 or  fv.Nivel = 3 or fv.Nivel = 4 ) then
 '@Centro' 
 when fv.Nivel = 5 then '@Zona'
 else 
 '@Ruta'
 END
 as Variable, s.DispositivoID, fv.Nombre,  fv.Descripcion, fv.Nivel
 	from fuerza_venta_dispositivo as fvd
 inner join Fuerza_venta as fv
 on fv.GUID_FV = fvd.GUID_FV and fvd.Estado = 1 and fv.Estado = 1
 inner join dispositivo as s on s.DispositivoID = fvd.DispositivoID and s.Estado = 1
 where s.Mac = '{$mac}';"; 

 		$query = $this->db->query($sql);

 		if ($query->num_rows() > 0)
		{

			$res = current($query->result()); 

			if(array_key_exists($res->Variable, $filtrosDefinidos)){				
				$filtrosDefinidos[$res->Variable] = $res->Nombre;
			}
			return array("Existe"=> true, "getString"=> json_encode($filtrosDefinidos) , "FuerzaVenta"=> $res->Nombre ); 
		}
		return array("Existe"=> false, "getString"=> json_encode($filtrosDefinidos), "FuerzaVenta"=> "Sin Asignar"); 

	}

	public function obtenerGrupoPorMacTv($mac){
		$this->load->database();

		$sql = "select gt.GrupoTvID, GrupoID, gt.DispositivoID, d.Nombre, Fn_ObtenerFuerzaGUIDFVPorDispositivo(gt.DispositivoID) as GUID   from grupo_Tv as gt
inner join dispositivo as d
on d.DispositivoID = gt.DispositivoID
 where gt.Estado = 1 and Mac ='{$mac}';"; 

 		$query = $this->db->query($sql);
 		// Este es el Resultado final del sistema.
		$listaDispositivo = $query->result(); 
		return $listaDispositivo;

	}

	public function ObtenerPorID($id){
		$this->load->database();
		$this->db->where("GrupoTvID", $id); 
		$result = $this->db->get("grupo_tv");		

		if ($result->num_rows() == 0)
		{
			return null; 
		}
		return current($result->result()); 
	}
	
 }
 ?>