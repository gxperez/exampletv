<?php 


class AdminBisTV
{

	public $dtRefresh = false; 
	private $db = false; 


	public function __construct($db){
		$this->db = $db; 		
	}


	public function setHasRefresh(){

		if(!$this->dtRefresh){
			// Refrescando la Pantalla. 
			$listaPorograma = $this->obtenerProgramaGlobal(); 
			return $listaPorograma; 
			// Es la Primera Vez para todos
			return true; 
		}

		$date = date("Y-m-d"); 

		if(array_key_exists($date, $this->dtRefresh)) {
			// Ajustes e General.			

		}
		// Para la programacion en General.

	}

	public function obtenerProgramaGlobal(){
		$result = $this->db->ejecutar("select * from vw_rep_programacion_contenido_hoy");

		$retorno = array();

		while($rows =$bd->obtener_fila($result, 0) ) {

			if(!array_key_exists($rows["GrupoID"], $retorno)){
				$retorno[$rows["GrupoID"]] = array();
			}			
			$retorno[$rows["GrupoID"]][] = $rows; 
		}
		
		return $retorno; 

	}

}


?>