<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Bloques extends MY_Controller {

	/**
	 * Index Page for this controller.
	 	@autor:	Grequis Xavier Perez Fortuna.
	 */
	public function index()
	{	
	}


	public function master(){

		if (!$this->session->userdata("sUsuario")){
			redirect("/portal/login", "refresh");			
			return false; 
		}


		$data = array("csrf" =>array(
        "name" => $this->security->get_csrf_token_name(),
        "hash" => $this->security->get_csrf_hash()
        ) );

        $this->load->model('EmunsViews_model', 'mEnum');        
        $this->load->model('Grupo_model', 'mGrupo');        		
        $this->load->model('Contenido_model', 'mContenido');        		
        $this->load->model("Programacion_Model", "mProgramacion");

        $listaGrupos = array();

        foreach ($this->mGrupo->obtenerGruposActivos() as  $value) {
        	$listaGrupos[$value->GrupoID] = $value; 
        }

        $data["contenidos"] = $this->mContenido->obtenerContenidoActivos();
		$data["listaGrupos"] = $listaGrupos; 
		$data['listaProgramacion'] = $this->mProgramacion->obtenerProgramacionActivas(); 
        $data['listEstadoForm'] = $this->mEnum->getEnumsEstado();   
        $data['listFrecuenciaTipo'] = $this->mEnum->getEnum("frecuenciatipo");      	

		// Carga de planilla web en general.
		$this->load->view("web/view_bloques", $data); 
	}
	
	public function sm()
	{
		if (!$this->session->userdata("sUsuario")){
			redirect("/portal/login", "refresh");			
			return false; 
		}

		$data = array("csrf" =>array(
        "name" => $this->security->get_csrf_token_name(),
        "hash" => $this->security->get_csrf_hash()
        ) );

        $this->load->model('EmunsViews_model', 'mEnum');        	
        	$data['listEstadoForm'] = $this->mEnum->getEnumsEstado();
        	$data['listFrecuenciaTipo'] = $this->mEnum->getEnum("frecuenciatipo"); 

		// Carga de planilla web en general.
		$this->load->view("web/sm_bloques", $data); 
	}

	public function validarChoqueBloque(){
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}


		if($this->input->get("ProgramacionID") && $this->input->get("HoraInicio") && $this->input->get("HoraFin") && $this->input->get("FrecuenciaTipo") ){

			$objeto = array('ProgramacionID' => $this->input->get("ProgramacionID"), "FrecuenciaTipo"=>$this->input->get("FrecuenciaTipo"), 'HoraInicio'=> $this->input->get("HoraInicio"), "HoraFin"=> $this->input->get("HoraFin"));

			// Validar los Bloques de las base de datos
			$this->load->model('Bloques_Model', 'mBloque');			
			$res = $this->mBloque->validarHoraBloque($objeto); 			

		echo json_encode(array("IsOk"=> true, "IsSession" => true, 'data'=> $res["res"], "msg"=> $res["msg"])); 		

				return true; 
		}

		echo json_encode(array("IsOk"=> false, "IsSession" => true)); 
		return false; 
	}



	public function cmp($a, $b)
	{
    	return strcmp($a->HoraInicio, $b->HoraInicio);
	}


	public function ObtenerBloquesGenerados(){

		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		// Cada dia por ellos.
		if($this->input->get("ProgramacionID")){
			$programacionID = $this->input->get("ProgramacionID");

			$this->load->model('Bloques_Model', 'mBloque');

		 $listaBloques = $this->mBloque->obtenerListaBloqueActivos($programacionID); 
		 $listaBloquesSemana = $this->mBloque->generarBloques($programacionID); 
		 $semanal = array("1"=> array(), "2"=> array(), "3"=> array(), "4"=> array(), "5"=> array(), "6"=> array(), "7"=> array() );

		 foreach ($listaBloquesSemana as $value) {
		 	if(!array_key_exists($value->DiaSemana, $semanal)){
		 		$semanal[$value->DiaSemana] = array();
		 	}
		 	$semanal[$value->DiaSemana][] = $value; 		 	
		 }

		 // Formatear las variables semanal.
		 foreach ($semanal as $key => $val) {		  	
		  	usort($semanal[$key], array($this, "cmp"));
		  } 


		echo json_encode(array("data"=> $semanal, "bloques"=> $listaBloques,  "IsOk"=> true, "IsSession" => true));
		return true; 

		}


		echo json_encode(array("IsOk"=> false, "IsSession" => true)); 
		return false; 


	}

	public function Obtener(){
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		$idRol = 0; 
		$this->load->model("Bloques_Model", "mBloques");
		$pagConf =  $this->config->item("client_pagination");

		if($this->input->get("vNumPage")){
			$pagina = $this->input->get("vNumPage");

			$listaBloques = $this->mBloques->obtenerBloquesPaginado($pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );

		} else {
			$listaBloques = $this->mBloques->obtenerBloquesPaginado($pagConf["RowsPerPages"], 0);
		}
		
		$totalResult = 0; 
		if(count($listaBloques)> 0){
			$first = current($listaBloques); 	
			$totalResult = $first->CountRow;
		}
		
		$first = current($listaBloques); 
		echo json_encode(array("data" => $listaBloques, "totalResult"=> $totalResult, "count"=> count($listaBloques), "IsOk"=> true, "rowsPerPages"=> $pagConf["RowsPerPages"], "IsSession" => true)); 
	}

	public function Crear(){
	if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}
		$this->load->model("Bloques_Model", "mBloques");
		// Auto Validacion del Formulario.

		$this->validation->set_rules("objeto[ProgramacionID]", "ProgramacionID", "required|integer"); 
		$this->validation->set_rules("objeto[FrecuenciaTipo]", "FrecuenciaTipo", "required|integer"); 		
		$this->validation->set_rules("objeto[Estado]", "Estado", "required|integer"); 
		$this->validation->set_rules("objeto[HoraInicio]", "HoraInicio", "required"); 
		$this->validation->set_rules("objeto[HoraFin]", "HoraFin", "required"); 



	if ($this->validation->run() == FALSE)
         {
            echo json_encode(array("IsOk"=> false, "Msg"=> validation_errors(), "IsSession" => true, "csrf" =>array(
        "name" => $this->security->get_csrf_token_name(),
        "hash" => $this->security->get_csrf_hash()
        ) )  );
           	return false;
    }

    if($this->input->post("objeto")){
		$bloquesObj = $this->security->xss_clean($this->input->post("objeto"));

		$bloquesEnt = array('ProgramacionID'=> $bloquesObj['ProgramacionID'] 
, 'FrecuenciaTipo'=> $bloquesObj['FrecuenciaTipo'] 
, 'FrecuenciaNumero'=> 1 
, 'Estado'=> $bloquesObj['Estado'] 
, 'HoraInicio'=> $bloquesObj['HoraInicio'] 
, 'HoraFin'=> $bloquesObj['HoraFin'] 
, 'UsuarioModificaID'=>  1
, 'FechaModificacion'=> date("Y-m-d H:i:s") 
);
		

			$id = $this->mBloques->insertar( $bloquesEnt ); 
			$bloquesEnt["BloqueID"] = $id; 

			echo json_encode(array("data" => $bloquesEnt, "IsOk"=> true, "Msg"=>"Success", "IsSession" => true, "csrf" =>array(
        "name" => $this->security->get_csrf_token_name(),
        "hash" => $this->security->get_csrf_hash()
        ) ));

		} else {
			echo json_encode(array("IsOk"=> false, "IsSession" => true));
			return false;
		}
	}

	public function ActualizarValidar(){
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		$this->load->model("Bloques_Model", "mBloques");

		$this->validation->set_rules("objeto[ProgramacionID]", "ProgramacionID", "required|integer"); 
		$this->validation->set_rules("objeto[FrecuenciaTipo]", "FrecuenciaTipo", "required|integer"); 		
		$this->validation->set_rules("objeto[Estado]", "Estado", "required|integer"); 
		$this->validation->set_rules("objeto[HoraInicio]", "HoraInicio", "required"); 
		$this->validation->set_rules("objeto[HoraFin]", "HoraFin", "required"); 			

		if ($this->validation->run() == FALSE)
         {
         	echo json_encode(array("IsOk"=> false, "Msg"=> validation_errors(), "IsSession" => true, "csrf" =>array(
         		"name" => $this->security->get_csrf_token_name(),
         		"hash" => $this->security->get_csrf_hash()
         		))
         	);
           	return false;
         }

         if($this->input->post("objeto")){

			$bloquesObj = $this->security->xss_clean($this->input->post("objeto"));
			$bloquesObj["FrecuenciaNumero"] = 1; 
         // Validar Choque.
         $res = $this->mBloques->validarHoraBloqueUpdate($bloquesObj);
         if(!$res["res"]){
         	echo json_encode(array("IsOk"=> false, "IsSession" => true, 'data'=> $res["res"], "Msg"=> $res["msg"],"csrf" =>array(
        "name" => $this->security->get_csrf_token_name(),
        "hash" => $this->security->get_csrf_hash()
        )));
         	return false;
         }

         // Actualizamos si todo esta OK.          
		$bloquesEnt = $this->mBloques->actualizar( $bloquesObj );

				if( ! $bloquesEnt ){
				echo json_encode(array("IsOk"=> false, "Msg"=> "Error DB al actualizar". print_r($bloquesEnt, true), "csrf" =>array(
		        "name" => $this->security->get_csrf_token_name(),
		        "hash" => $this->security->get_csrf_hash()
		        ), "IsSession" => true )  );
		        return false; 
			}

			echo json_encode(array("data" => $bloquesObj, "IsOk"=> true, "Msg"=>"Success", "IsSession" => true, "csrf" =>array(
	        "name" => $this->security->get_csrf_token_name(),
	        "hash" => $this->security->get_csrf_hash()
	        ) )); 
		}
	}


	public function obtenerBloquesContenidoPorIDs(){
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		if($this->input->get("BloqueID") && $this->input->get("ProgramacionID")){
			$idBloque = $this->input->get("BloqueID"); 
			$idProgramcion = $this->input->get("ProgramacionID"); 

			$this->load->model("Bloques_Model", "mBloques");		
			$estables = $this->mBloques->ObtenerDetallePorBloquePorIDProgramacion($idBloque, $idProgramcion);
			$resumen = $this->mBloques->ObtenerResumenBloqueContenido($idBloque, $idProgramcion);

			echo json_encode(array("IsOk"=> true, "IsSession"=> true, 'Msg' => "", "data"=> $estables, "resumen"=> $resumen)); 

			return true;
		} else {
			echo json_encode(array("IsOk"=> false, "IsSession"=> true, "Msg"=> "NO se ha Enviado las variables. GET" ));
			return false;
		}
	}



public function Actualizar(){
	if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}
		$this->load->model("Bloques_Model", "mBloques");
	$this->validation->set_rules("objeto[ProgramacionID]", "ProgramacionID", "required|integer"); 
	$this->validation->set_rules("objeto[FrecuenciaTipo]", "FrecuenciaTipo", "required|integer"); 
	$this->validation->set_rules("objeto[FrecuenciaNumero]", "FrecuenciaNumero", "required"); 
	$this->validation->set_rules("objeto[Estado]", "Estado", "required|integer"); 
	$this->validation->set_rules("objeto[HoraInicio]", "HoraInicio", "required"); 
	$this->validation->set_rules("objeto[HoraFin]", "HoraFin", "required"); 	

	if ($this->validation->run() == FALSE)
         {
            echo json_encode(array("IsOk"=> false, "Msg"=> validation_errors(), "IsSession" => true, "csrf" =>array(
        "name" => $this->security->get_csrf_token_name(),
        "hash" => $this->security->get_csrf_hash()
        ) )  );
           	return false;
    }

     if($this->input->post("objeto")){
		$bloquesObj = $this->security->xss_clean($this->input->post("objeto"));
		$bloquesEnt = $this->mBloques->actualizar( $bloquesObj );

				if( ! $bloquesEnt ){
				echo json_encode(array("IsOk"=> false, "Msg"=> "Error DB al actualizar". print_r($bloquesEnt, true), "csrf" =>array(
		        "name" => $this->security->get_csrf_token_name(),
		        "hash" => $this->security->get_csrf_hash()
		        ), "IsSession" => true )  );
		        return false; 
			}

			echo json_encode(array("data" => $bloquesObj, "IsOk"=> true, "Msg"=>"Success", "IsSession" => true, "csrf" =>array(
	        "name" => $this->security->get_csrf_token_name(),
	        "hash" => $this->security->get_csrf_hash()
	        ) )); 
		}
	}

	public function Buscar($str = null){
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		if(!isset($str)) {		
			return false; 
		}

		$this->load->model("Bloques_Model", "mBloques");

		$pagConf =  $this->config->item("client_pagination");
		if($this->input->get("vNumPage")){
			$pagina = $this->input->get("vNumPage"); 
			$listaBloques = $this->mBloques->obtenerBloquesPorCampo("Descripcion", $str, $pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );
		} else {
			$listaBloques = $this->mBloques->obtenerBloquesPorCampo("Descripcion", $str, $pagConf["RowsPerPages"], 0);
		}
		$totalResult = 0; 
		if(count($listaBloques)> 0){
			$first = current($listaBloques); 	
			$totalResult = $first->CountRow;
		}
		echo json_encode(array("data" => $listaBloques, "totalResult"=> $totalResult, "count"=> count($listaBloques), "IsOk"=> true, "rowsPerPages" => $pagConf["RowsPerPages"], "IsSession" => true)); 
	}

	public function Eliminar(){
		// Eliminar a travez del post y del Get y validar la Session.
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		if($this->input->post("objeto")){			
			$this->load->model("Bloques_Model", "mBloques");
			
			$bloquesObj = $this->security->xss_clean($this->input->post("objeto"));  
			$result = $this->mBloques->cambiarEstado($bloquesObj, -1); 

			if(! $result ){
				echo json_encode(array("IsOk"=> false, "Msg"=> "Error al Tratar de Eliminar", "csrf" =>array(
		        "name" => $this->security->get_csrf_token_name(),
		        "hash" => $this->security->get_csrf_hash()
		        ), "IsSession" => true )  );		        		        
		        return false; 
			} 

			echo json_encode(array( "IsOk"=> true, "Msg"=>"Success", "IsSession" => true, "csrf" =>array(
	        "name" => $this->security->get_csrf_token_name(),
	        "hash" => $this->security->get_csrf_hash()
	        ) ));
	        return true; 
		} 
	}


	public function EliminarBloqueContenido(){
		// Eliminar a travez del post y del Get y validar la Session.
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		// Por GET
		if($this->input->get("BloqueContenidoID") && $this->input->get("BloqueID") && $this->input->get("ProgramacionID")){			
			$this->load->model("BloqueContenido_Model", "mBloqueContenido");
			$this->load->model("Bloques_Model", "mBloques");

			$id = $this->security->xss_clean($this->input->get("BloqueContenidoID"));  
			$idBloque = $this->security->xss_clean($this->input->get("BloqueID"));  
			$idProgramcion = $this->security->xss_clean($this->input->get("ProgramacionID"));
			$rest = $this->mBloqueContenido->cambiarEstado($id, 0);			

			if(!$rest){
				echo json_encode(array("IsOk"=> false, "Msg"=> "Error al Tratar de Eliminar", "IsSession" => true )  );
		        return false; 
			}

			$resumen = $this->mBloques->ObtenerResumenBloqueContenido($idBloque, $idProgramcion);
			echo json_encode(array( "IsOk"=> true, "Msg"=>"Success", "IsSession" => true,  "data"=> $resumen ));

			return true; 
		} else {
			echo json_encode(array("IsOk"=> false, "Msg"=> "Error al Tratar de Eliminar", "IsSession" => true )  );	
		}

		return true;

		
	}

}
	