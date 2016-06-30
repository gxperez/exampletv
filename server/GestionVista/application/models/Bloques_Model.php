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
		$this->db->where("Estado",  1);
		$query = $this->db->get("vw_programacion_bloque_semana");	
		
		$listaBloques = $query->result();

		return $listaBloques; 
 	}

 	public function getDiasPorFrecuenciaTipo($frecuenciaTipo){
 			$this->load->database(); 		
 			// Ajustes del lado del servidor.
 			$this->db->select("DiaSemana"); 
 			$this->db->where("id", $frecuenciaTipo);  			
 			$query = $this->db->get("vw_frecuencia_desc");						
			$listaBloques = $query->result(); 

			$array = array(); 

			foreach ($listaBloques as $key => $value) {
				$array[] = $value->DiaSemana; 
			}

			return $array;
 	}

 	public function validarHoraBloque($obj){ 		
 		$this->load->database();
 		// Query de Validacion.
 		$diasSemana = $this->getDiasPorFrecuenciaTipo($obj["FrecuenciaTipo"]); 		

 		$this->db->select("count(BloqueID) as Choques, NombreDia, HoraInicio, HoraFin");
 		$this->db->where_in("DiaSemana", $diasSemana); 
 		$this->db->where("ProgramacionID", $obj["ProgramacionID"]); 
 		$this->db->where("Estado", 1);
 		$this->db->where("( ('{$obj["HoraInicio"]}' between `HoraInicio` and HoraFin) or ('{$obj["HoraFin"]}' between `HoraInicio` and HoraFin) or  (`HoraInicio` between '{$obj["HoraInicio"]}' and '{$obj["HoraFin"]}') or (`HoraFin` between '{$obj["HoraInicio"]}' and '{$obj["HoraFin"]}') )");

 		$this->db->group_by(array("NombreDia", "HoraInicio", "HoraFin") );  		
 		$result = $this->db->get("vw_programacion_bloque_semana"); 


		if ($result->num_rows() == 0)
		{
			return array('res' => true , 'msg'=> ""); 
		}

		$arrRes = array('res'=> false, 'msg'=> "Existen choques de hora: ");

		foreach ($result->result() as $key => $value) {
			$arrRes["msg"] .= " (" . $value->NombreDia . " de ". $value->HoraInicio. "-". $value->HoraFin. " ); "; 
		}
		return $arrRes; 
 	}

 	public function validarHoraBloqueUpdate($obj){ 		
 		$this->load->database();
 		// Query de Validacion.
 		$diasSemana = $this->getDiasPorFrecuenciaTipo($obj["FrecuenciaTipo"]); 		

 		$this->db->select("count(BloqueID) as Choques, NombreDia, HoraInicio, HoraFin");
 		$this->db->where_in("DiaSemana", $diasSemana); 
 		$this->db->where("ProgramacionID", $obj["ProgramacionID"]); 
 		$this->db->where("Estado", 1);
 		$this->db->where_not_in("BloqueID", $obj["BloqueID"]); 
 		$this->db->where("( ('{$obj["HoraInicio"]}' between `HoraInicio` and HoraFin) or ('{$obj["HoraFin"]}' between `HoraInicio` and HoraFin) or  (`HoraInicio` between '{$obj["HoraInicio"]}' and '{$obj["HoraFin"]}') or (`HoraFin` between '{$obj["HoraInicio"]}' and '{$obj["HoraFin"]}') )");
 		
 		$this->db->group_by(array("NombreDia", "HoraInicio", "HoraFin") );  		
 		$result = $this->db->get("vw_programacion_bloque_semana"); 


		if ($result->num_rows() == 0)
		{
			return array('res' => true , 'msg'=> ""); 
		}

		$arrRes = array('res'=> false, 'msg'=> "Existen choques de hora: ");

		foreach ($result->result() as $key => $value) {
			$arrRes["msg"] .= " (" . $value->NombreDia . " de ". $value->HoraInicio. "-". $value->HoraFin. " ); "; 
		}
		return $arrRes; 
 	}


 	public function obtenerListaBloqueActivos($ProgramacionID){
 		$this->load->database();

 		$sql = "select concat('B #', b.BloqueID) as Label,  b.BloqueID, b.ProgramacionID, b.FrecuenciaTipo, (select Descripcion from vw_frecuencia_desc where Id = b.FrecuenciaTipo limit 1) FrecuenciaTipoDesc,
 b.HoraInicio, b.HoraFin, concat(TIME_FORMAT(b.HoraInicio, '%H:%i'), '-', TIME_FORMAT(b.HoraFin, '%H:%i') ) as Horario, b.Estado from bloques as b
 where b.Estado = 1 and b.ProgramacionID = {$ProgramacionID} order by b.horaInicio;"; 

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
		$query = $this->db->get("bloques");	
			
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
		$this->load->database();
		$this->db->where("BloqueID", $id); 
		$result = $this->db->get("bloques");		

		if ($result->num_rows() == 0)
		{
			return null; 
		}
		return current($result->result()); 
	}

	public function ObtenerBloqueContenidoPorIdProgramacion($id){

		$sql = "select bc.BloqueContenidoID, bc.BloqueID, bc.ContenidoID, bc.GrupoID, bc.Orden,  bc.Estado,
g.Descripcion as GrupoDesc, c.Descripcion as ContenidoDesc, c.Nombre,
c.Duracion, 
sec_to_time(TIME_TO_SEC(b.HoraFin)-TIME_TO_SEC(b.HoraInicio)) as BloqueDuracion
 from bloque_contenido as bc
inner join grupo as g on g.GrupoID = bc.GrupoID
and g.Estado = 1 and bc.Estado = 1
inner join contenido as c on c.ContenidoID = bc.ContenidoID
and c.Estado = 1
inner join bloques as b on b.BloqueID = bc.BloqueID
and b.Estado = 1
	where b.ProgramacionID = {$id}"; 

		$query = $this->db->query($sql);
		$listaBloqueContenido = $query->result(); 

		return $listaBloqueContenido;


	}


	public function ObtenerDetallePorBloquePorIDProgramacion($idBloque, $idProgramacion ){
		$this->load->database();

		$sql = "select bc.BloqueContenidoID, bc.BloqueID, bc.ContenidoID, bc.GrupoID, bc.Orden,  bc.Estado,
		g.Descripcion as GrupoDesc, c.Descripcion as ContenidoDesc, c.Nombre,
		c.Duracion, 
		sec_to_time(TIME_TO_SEC(b.HoraFin)-TIME_TO_SEC(b.HoraInicio)) as BloqueDuracion
		 from bloque_contenido as bc
		inner join grupo as g on g.GrupoID = bc.GrupoID
		and g.Estado = 1 and bc.Estado = 1
		inner join contenido as c on c.ContenidoID = bc.ContenidoID
		and c.Estado = 1
		inner join bloques as b on b.BloqueID = bc.BloqueID
		and b.Estado = 1
		where b.ProgramacionID = {$idProgramacion} and b.BloqueID = {$idBloque}
		order by Orden"; 

		$query = $this->db->query($sql);
		$listaBloqueContenido = $query->result(); 

		$array = array();

		$arrGrupos = array();

		foreach ($listaBloqueContenido as $key => $value) {
			if(!array_key_exists($value->GrupoID, $array)){
				$array[$value->GrupoID] = array();
			}
			$array[$value->GrupoID][] = $value;
		}
		return $array;
	}

	public function ObtenerResumenBloqueContenido($idBloque, $idProgramacion){
		// Resumen del Bloque contenido.
		$this->load->database();
		$sql = "select b.BloqueID, b.ProgramacionID, b.HoraInicio, b.HoraFin, bc.GrupoID, g.Descripcion,
			sec_to_time(TIME_TO_SEC(b.HoraFin)-TIME_TO_SEC(b.HoraInicio)) as BloqueDuracion,
			sec_to_time(SUM(TIME_TO_SEC(c.Duracion)))  as TiempoOcupado, 
			sec_to_time( (TIME_TO_SEC(b.HoraFin)-TIME_TO_SEC(b.HoraInicio)) - SUM(TIME_TO_SEC(c.Duracion)) ) as TiempoDisponible,
			SUM(TIME_TO_SEC(c.Duracion)) as DuracionSec,
			(TIME_TO_SEC(b.HoraFin)-TIME_TO_SEC(b.HoraInicio)) - SUM(TIME_TO_SEC(c.Duracion))  DisponibleSec
			 from bloque_contenido as bc
			inner join grupo as g on g.GrupoID = bc.GrupoID
			and g.Estado = 1 and bc.Estado = 1
			inner join contenido as c on c.ContenidoID = bc.ContenidoID
			and c.Estado = 1
			inner join bloques as b on b.BloqueID = bc.BloqueID
			and b.Estado = 1
			where b.ProgramacionID = {$idProgramacion} and b.BloqueID = {$idBloque}
			group by b.BloqueID, b.ProgramacionID, b.HoraInicio, b.HoraFin, bc.GrupoID, g.Descripcion
			order by b.HoraInicio";

			$query = $this->db->query($sql);
			$listaBloqueContenido = $query->result();
			return $listaBloqueContenido;

	}	
	
 }
 ?>