<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Programacion extends MY_Controller {

	/**
	 * Index Page for this controller.
	 	@autor:	Grequis Xavier Perez Fortuna.
	 */
	public function index()
	{	
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
        	
		// Carga de planilla web en general.
		$this->load->view("web/sm_programacion", $data); 
	}


	public function Obtener(){
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		$idRol = 0; 
		$this->load->model("Programacion_Model", "mProgramacion");
		$pagConf =  $this->config->item("client_pagination");

		if($this->input->get("vNumPage")){
			$pagina = $this->input->get("vNumPage");

			$listaProgramacion = $this->mProgramacion->obtenerProgramacionPaginado($pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );

		} else {
			$listaProgramacion = $this->mProgramacion->obtenerProgramacionPaginado($pagConf["RowsPerPages"], 0);
		}
		
		$totalResult = 0; 
		if(count($listaProgramacion)> 0){
			$first = current($listaProgramacion); 	
			$totalResult = $first->CountRow;
		}
		
		$first = current($listaProgramacion); 
		echo json_encode(array("data" => $listaProgramacion, "totalResult"=> $totalResult, "count"=> count($listaProgramacion), "IsOk"=> true, "rowsPerPages"=> $pagConf["RowsPerPages"], "IsSession" => true)); 
	}

	public function Crear(){
	if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}
		$this->load->model("Programacion_Model", "mProgramacion");
		// Auto Validacion del Formulario.

		$this->validation->set_rules("objeto[Descripcion]", "Descripcion", "required|max_length[50]"); 
		$this->validation->set_rules("objeto[EsRegular]", "EsRegular", "required"); 
		$this->validation->set_rules("objeto[FechaEjecutaInicio]", "FechaEjecutaInicio", "required"); 
		$this->validation->set_rules("objeto[FechaEjecutaFin]", "FechaEjecutaFin", "required"); 
		$this->validation->set_rules("objeto[Estado]", "Estado", "required|integer"); 
		$this->validation->set_rules("objeto[Guid]", "Guid", "required|max_length[50]"); 
		$this->validation->set_rules("objeto[UsuarioModificaID]", "UsuarioModificaID", "required|integer"); 
		$this->validation->set_rules("objeto[FechaModifica]", "FechaModifica", "required"); 


	if ($this->validation->run() == FALSE)
         {
            echo json_encode(array("IsOk"=> false, "Msg"=> validation_errors(), "IsSession" => true, "csrf" =>array(
        "name" => $this->security->get_csrf_token_name(),
        "hash" => $this->security->get_csrf_hash()
        ) )  );
           	return false;
    }

    if($this->input->post("objeto")){
		$programacionObj = $this->security->xss_clean($this->input->post("objeto"));

		$programacionEnt = array('Descripcion'=> $programacionObj['Descripcion'] 
, 'EsRegular'=> $programacionObj['EsRegular'] 
, 'FechaEjecutaInicio'=> date('Y-m-d H:i:s') 
, 'FechaEjecutaFin'=> date('Y-m-d H:i:s') 
, 'Estado'=> $programacionObj['Estado'] 
, 'Guid'=> $programacionObj['Guid'] 
, 'UsuarioModificaID'=> $programacionObj['UsuarioModificaID'] 
, 'FechaModifica'=> date('Y-m-d H:i:s') 
);

			$id = $this->mProgramacion->insertar( $programacionEnt ); 
			$programacionEnt["ProgramacionID"] = $id; 

			echo json_encode(array("data" => $programacionEnt, "IsOk"=> true, "Msg"=>"Success", "IsSession" => true, "csrf" =>array(
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
		$this->load->model("Programacion_Model", "mProgramacion");
	$this->validation->set_rules("objeto[Descripcion]", "Descripcion", "required|max_length[50]"); 
$this->validation->set_rules("objeto[EsRegular]", "EsRegular", "required"); 
$this->validation->set_rules("objeto[FechaEjecutaInicio]", "FechaEjecutaInicio", "required"); 
$this->validation->set_rules("objeto[FechaEjecutaFin]", "FechaEjecutaFin", "required"); 
$this->validation->set_rules("objeto[Estado]", "Estado", "required|integer"); 
$this->validation->set_rules("objeto[Guid]", "Guid", "required|max_length[50]"); 
$this->validation->set_rules("objeto[UsuarioModificaID]", "UsuarioModificaID", "required|integer"); 
$this->validation->set_rules("objeto[FechaModifica]", "FechaModifica", "required"); 

	if ($this->validation->run() == FALSE)
         {
            echo json_encode(array("IsOk"=> false, "Msg"=> validation_errors(), "IsSession" => true, "csrf" =>array(
        "name" => $this->security->get_csrf_token_name(),
        "hash" => $this->security->get_csrf_hash()
        ) )  );
           	return false;
    }

     if($this->input->post("objeto")){
		$programacionObj = $this->security->xss_clean($this->input->post("objeto"));

		

		if($programacionObj["EsRegular"] == "true" || $programacionObj["EsRegular"] == 1){
			$programacionObj["EsRegular"] = 1; 
		} else {

			$programacionObj["EsRegular"] = 0; 
		}

		$programacionEnt = $this->mProgramacion->actualizar( $programacionObj );

				if( ! $programacionEnt ){
				echo json_encode(array("IsOk"=> false, "Msg"=> "Error DB al actualizar". print_r($programacionEnt, true), "csrf" =>array(
		        "name" => $this->security->get_csrf_token_name(),
		        "hash" => $this->security->get_csrf_hash()
		        ), "IsSession" => true )  );
		        return false; 
			}

			echo json_encode(array("data" => $programacionObj, "IsOk"=> true, "Msg"=>"Success", "IsSession" => true, "csrf" =>array(
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

		$this->load->model("Programacion_Model", "mProgramacion");

		$pagConf =  $this->config->item("client_pagination");
		if($this->input->get("vNumPage")){
			$pagina = $this->input->get("vNumPage"); 
			$listaProgramacion = $this->mProgramacion->obtenerProgramacionPorCampo("Descripcion", $str, $pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );
		} else {
			$listaProgramacion = $this->mProgramacion->obtenerProgramacionPorCampo("Descripcion", $str, $pagConf["RowsPerPages"], 0);
		}
		$totalResult = 0; 
		if(count($listaProgramacion)> 0){
			$first = current($listaProgramacion); 	
			$totalResult = $first->CountRow;
		}
		echo json_encode(array("data" => $listaProgramacion, "totalResult"=> $totalResult, "count"=> count($listaProgramacion), "IsOk"=> true, "rowsPerPages" => $pagConf["RowsPerPages"], "IsSession" => true)); 
	}

	public function Eliminar(){
		// Eliminar a travez del post y del Get y validar la Session.
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		if($this->input->post("objeto")){			
			$this->load->model("Programacion_Model", "mProgramacion");
			
			$programacionObj = $this->security->xss_clean($this->input->post("objeto"));  
			$result = $this->mProgramacion->cambiarEstado($programacionObj, -1); 

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