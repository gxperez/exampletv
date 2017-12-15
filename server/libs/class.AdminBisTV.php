<?php 


class AdminBisTV
{

	public $dtRefresh = false; 
	private $db = false; 

	public function __construct( $db ){
		$this->db = $db; 		
	}

	public function setHasRefresh(){

		if(!$this->dtRefresh){
			// Refrescando la Pantalla. 
			$listaPorograma = $this->obtenerProgramaGlobal(); 

			return $listaPorograma; 			
		}

		$date = date("Y-m-d"); 
		if(!array_key_exists($date, $this->dtRefresh)) {
			// Ajustes e General.			
		}
		// Para la programacion en General.
	}

	public function getFuerzaVenta($macAd) {
		// MacAdrdrees.				

	}


	public function ObtenerListaCOnfiguracionMensajes(){
		$result = $this->db->ejecutar("select * from configurcion_fuente_mensajes where Estado = 1");
		$retorno = array();
		while($rows =$this->db->obtener_fila($result, 0) ) {
			$retorno[] = $rows; 
		}		
		return $retorno; 
	}

	public function obtenerProgramaGlobal(){
		$result = $this->db->ejecutar("select * from vw_rep_programacion_contenido_hoy");

		$retorno = array();

		while($rows =$this->db->obtener_fila($result, 0) ) {

			if(!array_key_exists($rows["GrupoID"], $retorno)){
				$retorno[$rows["GrupoID"]] = array();
			}			
			$retorno[$rows["GrupoID"]][] = $rows; 
		}		
		return $retorno; 
	}

	public function ObtenerListaTVDelGrupoPorMacLider($mc){
		$result = $this->db->ejecutar("select dis.Mac, dis.Nombre, dis.DispositivoID, gt.GrupoID from grupo_tv as gt
 inner join dispositivo as dis on dis.DispositivoID = gt.DispositivoID
 and dis.Estado = 1 and gt.Estado = 1
 where gt.GrupoID = (select gt.GrupoID from grupo_tv as gt
 inner join dispositivo as dis on dis.DispositivoID = gt.DispositivoID
 and dis.Estado = 1 and gt.Estado = 1
 where dis.Mac = '$mc' and gt.EsLider = 1)");

		$retorno = array();

		while($rows =$this->db->obtener_fila($result, 0) ) {
			if($rows["Mac"] !== $mc){
				$retorno[$rows["Mac"]] = $rows; 
			}			
		}		
		return $retorno; 
	}

}


?>