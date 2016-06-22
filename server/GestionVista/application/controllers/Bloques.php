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
        $this->load->model("Programacion_Model", "mProgramacion");

		
		$data['listaProgramacion'] = $this->mProgramacion->obtenerProgramacionActivas(); 
        $data['listEstadoForm'] = $this->mEnum->getEnumsEstado();
        	
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
$this->validation->set_rules("objeto[FrecuenciaNumero]", "FrecuenciaNumero", "required"); 
$this->validation->set_rules("objeto[Estado]", "Estado", "required|integer"); 
$this->validation->set_rules("objeto[HoraInicio]", "HoraInicio", "required"); 
$this->validation->set_rules("objeto[HoraFin]", "HoraFin", "required"); 
$this->validation->set_rules("objeto[UsuarioModificaID]", "UsuarioModificaID", "integer"); 
$this->validation->set_rules("objeto[FechaModificacion]", "FechaModificacion", ""); 


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
, 'FrecuenciaNumero'=> $bloquesObj['FrecuenciaNumero'] 
, 'Estado'=> $bloquesObj['Estado'] 
, 'HoraInicio'=> $bloquesObj['HoraInicio'] 
, 'HoraFin'=> $bloquesObj['HoraFin'] 
, 'UsuarioModificaID'=> $bloquesObj['UsuarioModificaID'] 
, 'FechaModificacion'=> $bloquesObj['FechaModificacion'] 
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
$this->validation->set_rules("objeto[UsuarioModificaID]", "UsuarioModificaID", "integer"); 
$this->validation->set_rules("objeto[FechaModificacion]", "FechaModificacion", ""); 

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
}
	