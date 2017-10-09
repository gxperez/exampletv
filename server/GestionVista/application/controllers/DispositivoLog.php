<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class DispositivoLog extends MY_Controller {

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

		// Carga de planilla web en general.
		$this->load->view("web/sm_dispositivo_log", $data); 
	}


	public function Obtener(){
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		$idRol = 0; 
		$this->load->model("DispositivoLog_Model", "mDispositivoLog");
		$pagConf =  $this->config->item("client_pagination");

		if($this->input->get("vNumPage")){
			$pagina = $this->input->get("vNumPage");

			$listaDispositivoLog = $this->mDispositivoLog->obtenerDispositivoLogPaginado($pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );

		} else {
			$listaDispositivoLog = $this->mDispositivoLog->obtenerDispositivoLogPaginado($pagConf["RowsPerPages"], 0);
		}
		
		$totalResult = 0; 
		if(count($listaDispositivoLog)> 0){
			$first = current($listaDispositivoLog); 	
			$totalResult = $first->CountRow;
		}
		
		$first = current($listaDispositivoLog); 
		echo json_encode(array("data" => $listaDispositivoLog, "totalResult"=> $totalResult, "count"=> count($listaDispositivoLog), "IsOk"=> true, "rowsPerPages"=> $pagConf["RowsPerPages"], "IsSession" => true)); 
	}

	public function Crear(){
	if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}
		$this->load->model("DispositivoLog_Model", "mDispositivoLog");
		// Auto Validacion del Formulario.

	$this->validation->set_rules("objeto[DispositivoID]", "DispositivoID", "max_length[45]"); 
$this->validation->set_rules("objeto[Estatus]", "Estatus", "max_length[45]"); 
$this->validation->set_rules("objeto[FechaHoraInicio]", "FechaHoraInicio", "max_length[45]"); 
$this->validation->set_rules("objeto[FechaHoraFin]", "FechaHoraFin", "max_length[45]"); 
$this->validation->set_rules("objeto[FechaCrea]", "FechaCrea", ""); 


	if ($this->validation->run() == FALSE)
         {
            echo json_encode(array("IsOk"=> false, "Msg"=> validation_errors(), "IsSession" => true, "csrf" =>array(
        "name" => $this->security->get_csrf_token_name(),
        "hash" => $this->security->get_csrf_hash()
        ) )  );
           	return false;
    }

    if($this->input->post("objeto")){
		$dispositivo_logObj = $this->security->xss_clean($this->input->post("objeto"));

		$dispositivo_logEnt = array('DispositivoID'=> $dispositivo_logObj['DispositivoID'] 
, 'Estatus'=> $dispositivo_logObj['Estatus'] 
, 'FechaHoraInicio'=> $dispositivo_logObj['FechaHoraInicio'] 
, 'FechaHoraFin'=> $dispositivo_logObj['FechaHoraFin'] 
);

			$id = $this->mDispositivoLog->insertar( $dispositivo_logEnt ); 
			$dispositivo_logEnt["Dispositivo_log_ID"] = $id; 

			echo json_encode(array("data" => $dispositivoEnt, "IsOk"=> true, "Msg"=>"Success", "IsSession" => true, "csrf" =>array(
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
		$this->load->model("DispositivoLog_Model", "mDispositivoLog");
	$this->validation->set_rules("objeto[DispositivoID]", "DispositivoID", "max_length[45]"); 
$this->validation->set_rules("objeto[Estatus]", "Estatus", "max_length[45]"); 
$this->validation->set_rules("objeto[FechaHoraInicio]", "FechaHoraInicio", "max_length[45]"); 
$this->validation->set_rules("objeto[FechaHoraFin]", "FechaHoraFin", "max_length[45]"); 
$this->validation->set_rules("objeto[FechaCrea]", "FechaCrea", ""); 

	if ($this->validation->run() == FALSE)
         {
            echo json_encode(array("IsOk"=> false, "Msg"=> validation_errors(), "IsSession" => true, "csrf" =>array(
        "name" => $this->security->get_csrf_token_name(),
        "hash" => $this->security->get_csrf_hash()
        ) )  );
           	return false;
    }

     if($this->input->post("objeto")){
		$dispositivo_logObj = $this->security->xss_clean($this->input->post("objeto"));
		$dispositivo_logEnt = $this->mDispositivoLog->actualizar( $dispositivo_logObj );

				if( ! $dispositivo_logEnt ){
				echo json_encode(array("IsOk"=> false, "Msg"=> "Error DB al actualizar". print_r($dispositivo_logEnt, true), "csrf" =>array(
		        "name" => $this->security->get_csrf_token_name(),
		        "hash" => $this->security->get_csrf_hash()
		        ), "IsSession" => true )  );
		        return false; 
			}

			echo json_encode(array("data" => $dispositivoObj, "IsOk"=> true, "Msg"=>"Success", "IsSession" => true, "csrf" =>array(
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

		$this->load->model("DispositivoLog_Model", "mDispositivoLog");

		$pagConf =  $this->config->item("client_pagination");
		if($this->input->get("vNumPage")){
			$pagina = $this->input->get("vNumPage"); 
			$listaDispositivoLog = $this->mDispositivoLog->obtenerDispositivoLogPorCampo("Descripcion", $str, $pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );
		} else {
			$listaDispositivoLog = $this->mDispositivo->obtenerDispositivoPorCampo("Descripcion", $str, $pagConf["RowsPerPages"], 0);
		}
		$totalResult = 0; 
		if(count($listaDispositivoLog)> 0){
			$first = current($listaDispositivoLog); 	
			$totalResult = $first->CountRow;
		}
		echo json_encode(array("data" => $listaDispositivoLog, "totalResult"=> $totalResult, "count"=> count($listaDispositivoLog), "IsOk"=> true, "rowsPerPages" => $pagConf["RowsPerPages"], "IsSession" => true)); 
	}

	public function Eliminar(){
		// Eliminar a travez del post y del Get y validar la Session.
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		if($this->input->post("objeto")){			
			$this->load->model("DispositivoLog_Model", "mDispositivoLog");
			
			$dispositivo_logObj = $this->security->xss_clean($this->input->post("objeto"));  
			$result = $this->mDispositivo->cambiarEstado($dispositivoObj, -1); 

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

	public function online(){
		if (!$this->session->userdata("sUsuario")){
			redirect("/portal/login", "refresh");			
			return false; 
		}
		
		$myServices = $this->config->item("web_services_key");



		$data = array("csrf" =>array(
        "name" => $this->security->get_csrf_token_name(),
        "hash" => $this->security->get_csrf_hash()
        ) );

        $data["url_ws_conected"]= $myServices["ws_ip"].":". $myServices["ws_port"]; 

        $this->load->model("GrupoTv_Model", "mGrupoTv");
        $this->load->model("FuerzaVentaDispositivo_Model", "mFuerzaVentaDispositivo");


        $data['listaGrupoTv'] = $this->mGrupoTv->obtenerListaGrupoTvPorGrupoID(); 
		$data['fuerzaVentaData'] = json_encode($this->mFuerzaVentaDispositivo->formatNivelObject($this->mFuerzaVentaDispositivo->obtenerFuerzaVentaRelacion() ) ); 

		// Carga de planilla web en general.
		$this->load->view("web/vw_websocket_dispositivo", $data); 

	}
}