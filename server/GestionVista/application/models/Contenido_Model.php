<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class Contenido_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}
 
 
	public function obtenerContenido(){
		$this->load->database();
		$query = $this->db->get("contenido");			
			
		$listaContenido = $query->result(); 
		return $listaContenido;
 	}

 	public function obtenerContenidoActivos(){
 		$this->load->database();
 		$this->db->where("Estado", 1); 
		$query = $this->db->get("contenido");			
		$listaContenido = $query->result();
		return $listaContenido;		
 	}

 	public function obtenerContenidoPorCampo($campo, $valor = "", $limit = 0, $page = 20){
		$this->load->database();
		$list = $this->db->field_data("contenido");
		$fieldList = array();
		foreach ($list as  $value) {
			$fieldList[] = $value->name;		
		}

		$this->db->select($fieldList,FALSE);
		$this->db->where(" Estado !=" , "-1" );
		$this->db->like($campo, trim($valor));
		$data = $this->db->get_compiled_select("contenido", $limit, $page);

		$arrFill = array("vQuery"=> $data, "vLimit" => $limit, "vPage"=> $page);
		$stored_procedure = "call sp_PaginarResultQuery( ?, ?, ?);";
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaContenido = $query->result();
 		return $listaDispositivo;
 	}

 	public function obtenerContenidoPaginado($limit, $row, $condicion = " Estado != -1"){
		$this->load->database();
		$arrFill = array("vLimit" => $limit, "vPage"=> $row, "vCondicion"=> $condicion);
		$stored_procedure = "call sp_PaginarResultTabla('contenido', ?, ?, ?, null);";		
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaDispositivo = $query->result(); 
		return $listaDispositivo;
 	}
	
	public function obtenerContenidoJson(){
		$this->load->database();
		$query = $this->db->get(contenido);	
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$listaContenido[] = $row; 
		}
			return json_encode($listaContenido);
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
		$listaCampos = $this->db->field_data("contenido");
		$this->db->insert("contenido", $obj);
		return $this->db->insert_id();
	}

	public function actualizar($obj){
		$this->load->database();

		$ContenidoEnt = $this->ObtenerPorID($obj["ContenidoID"]);

        	if($ContenidoEnt == null){ 
		        return false; 
        	}
        	$update = array();
        	foreach ($ContenidoEnt as $key => $value) {
        		if($key != "ContenidoID"){
	        		if(array_key_exists($key, $obj) && $value != $obj[$key]){
	        			$update[$key] = $obj[$key];        			
	        		}           			
        		}    		
        	}
        	
        	$update["FechaModifica"] = date("Y-m-d H:i:s");
        	$this->db->where("ContenidoID", $obj["ContenidoID"]);
			$rs = $this->db->update("contenido", $update);
			if($rs){
				return $ContenidoEnt; 
			}
			return $rs; 		
	}

	public function cambiarEstado($obj, $estado){
		$this->load->database();
		$contenidoEnt = $this->ObtenerPorID($obj["contenido"]);

		if($dispositivoEnt == null){ 
		        return false; 
        }        
        $update["FechaModifica"] = date("Y-m-d H:i:s");
        $update["Estado"] = $estado; 
        $this->db->where("ContenidoID", $obj["ContenidoID"]);
		$rs = $this->db->update("contenido", $update);	

		if($rs){
			return $contenidoEnt; 
		}
			return $rs; 
	}

	public function ObtenerPorID($id){
		$this->load->database();
		$this->db->where("ContenidoID", $id); 
		$result = $this->db->get("contenido");		

		if ($result->num_rows() == 0)
		{
			return null; 
		}
		return current($result->result()); 
	}

	public function obtenerProgramaHoy(){
		$this->load->database();
		$result = $this->db->get("vw_rep_programacion_contenido_hoy");

		// Logica para arrmar los contenidos en el Portal.

		$listaContenido = array();
		foreach ($result->result() as $row)
		{
			if(!array_key_exists($row->GrupoID, $listaContenido)){
				$listaContenido[$row->GrupoID] = array();
			}			

			$listaContenido[$row->GrupoID][] = $row; 			
		}

		return $listaContenido;
	}

	public function obtenerProgramaHoyPorGrupoID( $GrupoID ){
		$this->load->database();

		$this->db->where("GrupoID", $GrupoID); 
		$result = $this->db->get("vw_rep_programacion_contenido_hoy");

		// Logica para arrmar los contenidos en el Portal.
		$listaContenido = array();
		foreach ($result->result() as $row)
		{

			$listaContenido[] = $row; 			
		}

		return $listaContenido;
	}


	public function obtenerContenidoHoyPorGrupo(){

		$this->load->database();

		$sql = "select cc.Guid, cc.Duracion, cc.Descripcion, 
bcc.GrupoID, bcc.Orden, tp.EsquemaTipo,
tp.TemplatePagesID,
tp.Duracion as DuracionPages, 
tp.TransicionTipoIni, 
tp.TransicionTipoFin, 
tp.MostrarHeader, 
st.Encabezado, 
st.ContenidoTipo, 
st.Posicion, 
FF.FuenteID
 from 
programacion as p 
inner join vw_programacion_bloque_semana as vwPS 
on vwPS.ProgramacionID= p.ProgramacionID and vwPS.Estado = 1

inner join bloque_contenido as bcc on bcc.BloqueID = vwPS.BloqueID
inner join contenido as cc on cc.ContenidoID = bcc.ContenidoID
and cc.Estado = 1
inner join template_pages as tp on tp.SliderMaestroID = cc.SliderMaestroID
and tp.Estado = 1
inner join seccion_template as st on st.TemplatePagesID = tp.TemplatePagesID
 and st.Estado = 1
inner join Fuentes as FF on FF.FuenteID = st.FuenteID and FF.Estado = 1
where p.Estado = 1
and (now() BETWEEN p.FechaEjecutaInicio and p.FechaEjecutaFin)
AND vwPS.DiaSemana = dayofweek(NOW())
group by cc.Guid, cc.Duracion, cc.Descripcion, 
bcc.GrupoID, bcc.Orden, tp.EsquemaTipo,
tp.TemplatePagesID,
tp.Duracion, 
tp.TransicionTipoIni, 
tp.TransicionTipoFin, 
tp.MostrarHeader, 
st.Encabezado, 
st.ContenidoTipo, 
st.Posicion, 
FF.FuenteID;"; 

		$query = $this->db->query($sql);
		$listaContenido = array();

		// Logica para arrmar los contenidos en el Portal.
		$listaContenido = array();
		foreach ($query->result() as $row)
		{
			if(!array_key_exists($row->Guid, $listaContenido)){
				$listaContenido[$row->Guid] = array();
			}
			if(!array_key_exists($row->GrupoID, $listaContenido[$row->Guid] )){				
				$listaContenido[$row->Guid][$row->GrupoID] = array();

			}
			// GUID->GrupoID			
			$listaContenido[$row->Guid][$row->GrupoID][] = $row;

		}
		return $listaContenido;
	}


	public function obtenerContenidoHoyPorGrupoPorGrupoID($id){

		$this->load->database();

		$sql = "select cc.Guid, cc.Duracion, cc.Descripcion, 
bcc.GrupoID, bcc.Orden, tp.EsquemaTipo,
tp.TemplatePagesID,
tp.Duracion as DuracionPages, 
tp.TransicionTipoIni, 
tp.TransicionTipoFin, 
tp.MostrarHeader, 
st.Encabezado, 
st.ContenidoTipo, 
st.Posicion, 
FF.FuenteID
 from 
programacion as p 
inner join vw_programacion_bloque_semana as vwPS 
on vwPS.ProgramacionID= p.ProgramacionID and vwPS.Estado = 1

inner join bloque_contenido as bcc on bcc.BloqueID = vwPS.BloqueID
inner join contenido as cc on cc.ContenidoID = bcc.ContenidoID
and cc.Estado = 1
inner join template_pages as tp on tp.SliderMaestroID = cc.SliderMaestroID
and tp.Estado = 1
inner join seccion_template as st on st.TemplatePagesID = tp.TemplatePagesID
 and st.Estado = 1
inner join Fuentes as FF on FF.FuenteID = st.FuenteID and FF.Estado = 1
where p.Estado = 1 and bcc.GrupoID = {$id}
and (now() BETWEEN p.FechaEjecutaInicio and p.FechaEjecutaFin)
AND vwPS.DiaSemana = dayofweek(NOW())
group by cc.Guid, cc.Duracion, cc.Descripcion, 
bcc.GrupoID, bcc.Orden, tp.EsquemaTipo,
tp.TemplatePagesID,
tp.Duracion, 
tp.TransicionTipoIni, 
tp.TransicionTipoFin, 
tp.MostrarHeader, 
st.Encabezado, 
st.ContenidoTipo, 
st.Posicion, 
FF.FuenteID;"; 

		$query = $this->db->query($sql);
		$listaContenido = array();

		// Logica para arrmar los contenidos en el Portal.
		$listaContenido = array();
		foreach ($query->result() as $row)
		{
			if(!array_key_exists($row->Guid, $listaContenido)){
				$listaContenido[$row->Guid] = array();
			}
			if(!array_key_exists($row->GrupoID, $listaContenido[$row->Guid] )){				
				$listaContenido[$row->Guid][$row->GrupoID] = array();

			}
			// GUID->GrupoID			
			$listaContenido[$row->Guid][$row->GrupoID][] = $row;

		}
		return $listaContenido;

	}

	public function ObtenerContenidoHoyFuentes(){
		$this->load->database();
		// Semana de Querys.		

		$sqlQuery = "select FF.FuenteID, FF.Descripcion, FF.EsManual, FF.FuenteTipo, FF.ContenidoTexto, FF.Url
 					from 
			programacion as p 
				inner join vw_programacion_bloque_semana as vwPS 
			on vwPS.ProgramacionID= p.ProgramacionID and vwPS.Estado = 1
				inner join bloque_contenido as bcc on bcc.BloqueID = vwPS.BloqueID
				inner join contenido as cc on cc.ContenidoID = bcc.ContenidoID
			and cc.Estado = 1
				inner join template_pages as tp on tp.SliderMaestroID = cc.SliderMaestroID
			and tp.Estado = 1
			inner join seccion_template as st on st.TemplatePagesID = tp.TemplatePagesID
 		and st.Estado = 1
		inner join Fuentes as FF on FF.FuenteID = st.FuenteID and FF.Estado = 1
		where p.Estado = 1
		and (now() BETWEEN p.FechaEjecutaInicio and p.FechaEjecutaFin)
			AND vwPS.DiaSemana = dayofweek(NOW())
		group by FF.FuenteID, FF.Descripcion, FF.EsManual, FF.FuenteTipo, FF.ContenidoTexto"; 

		$query = $this->db->query($sqlQuery);
		$listaContenido = $query->result();		

		foreach ($query->result() as $row)
		{
			// Ajustes en General.
			if(!array_key_exists($row->FuenteID, $listaContenido)){
				$listaContenido[$row->FuenteID] = array();
			}
			$listaContenido[$row->FuenteID][] = $row; 
		}
		return $listaContenido; 
	}


public function ObtenerContenidoHoyFuentesPorGrupoID($grupoID){
		$this->load->database();
		// Semana de Querys.		

		$sqlQuery = "select FF.FuenteID, FF.Descripcion, FF.EsManual, FF.FuenteTipo, FF.Url
 					from 
			programacion as p 
				inner join vw_programacion_bloque_semana as vwPS 
			on vwPS.ProgramacionID= p.ProgramacionID and vwPS.Estado = 1
				inner join bloque_contenido as bcc on bcc.BloqueID = vwPS.BloqueID
				inner join contenido as cc on cc.ContenidoID = bcc.ContenidoID
			and cc.Estado = 1
				inner join template_pages as tp on tp.SliderMaestroID = cc.SliderMaestroID
			and tp.Estado = 1
			inner join seccion_template as st on st.TemplatePagesID = tp.TemplatePagesID
 		and st.Estado = 1
		inner join Fuentes as FF on FF.FuenteID = st.FuenteID and FF.Estado = 1
		where p.Estado = 1 and bcc.GrupoID = {$grupoID}
		and (now() BETWEEN p.FechaEjecutaInicio and p.FechaEjecutaFin)
			AND vwPS.DiaSemana = dayofweek(NOW())
		group by FF.FuenteID, FF.Descripcion, FF.EsManual, FF.FuenteTipo, FF.Url;"; 

		$query = $this->db->query($sqlQuery);
		$listaContenido = array();

		foreach ($query->result() as $row)
		{
			// Ajustes en General.
			if(!array_key_exists($row->FuenteID, $listaContenido)){
				$listaContenido[$row->FuenteID] = array();
			}
			$listaContenido[$row->FuenteID][] = $row; 
		}
		return $listaContenido; 
	}

	
 }
 ?>