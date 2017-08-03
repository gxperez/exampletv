<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class MatriculaC_Model extends CI_Model {	 
 
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

	

	public function ObtenerResumenPorClub(){
		// Resumen del Bloque contenido.
		$this->load->database();


		$sql = "SELECT c.ClubID, i.Descripcion Iglesia, c.Nombre as NombreClub, c.ClubTipo, COUNT(distinct ma.MatriculaActividadID) as CantidadMiembros FROM matricula_actividad as ma
inner join club as c  
	on ma.OrganigramaID = c.ClubID and ma.Estado = 1
inner join iglesia as i
	on i.IglesiaID = c.IglesiaID 
and c.Estado = 1
group by c.ClubID, i.Descripcion, c.Nombre, c.ClubTipo";

			$query = $this->db->query($sql);
			$listaBloqueContenido = $query->result();

				$arrGrupos = array();

			foreach ($listaBloqueContenido as $key => $value) {
				
				$arrGrupos[$value->GrupoID] = $value;

			}

			return $arrGrupos;
	}	
	
 }
 ?>