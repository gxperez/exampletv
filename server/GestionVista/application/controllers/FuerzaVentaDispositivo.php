<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class FuerzaVentaDispositivo extends MY_Controller {

	/**
	 * Index Page for this controller.
	 	@autor:	Grequis Xavier Perez Fortuna.
	 */
	public function index()
	{	
	}

	public function master(){
		// Carga del Template Especial.
		// AGrupado en JS

		$data = array("csrf" =>array(
        "name" => $this->security->get_csrf_token_name(),
        "hash" => $this->security->get_csrf_hash()
        ) );

        $this->load->model('EmunsViews_model', 'mEnum');        	       	
        $data['nivelTipos'] = $this->mEnum->getEnum("niveltipo");

        $this->load->model("FuerzaVentaDispositivo_Model", "mFuerzaVentaDispositivo");
        $this->load->model("SessionDispositivoLog_Model", "mSessionDisp");

		$listaFuerzaVentaDispositivo = $this->mFuerzaVentaDispositivo->obtenerDispositivoRelacion(); 
		$listaFuerzaVenta = $this->mFuerzaVentaDispositivo->formatNivelObject($this->mFuerzaVentaDispositivo->obtenerFuerzaVentaRelacion() ); 
		
		$arregloResult = array();

		foreach ($this->mSessionDisp->obtenerSessionDispositivoLog() as $key => $value) {
			$arregloResult[$value->Mac] = $value; 
		 } 

		// Darle formato de Nodos. Siempre Ordenados.
		$data['dispositivosData'] = json_encode($listaFuerzaVentaDispositivo); 		
		$data['fuerzaVentaData'] = json_encode($listaFuerzaVenta); 
		$data['resentDispositivo'] = json_encode($arregloResult); 

		$this->load->view("web/sm_fuerza_venta_dispositivo", $data); 
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
		$this->load->view("web/sm_fuerza_venta_dispositivo", $data); 
	}

	public function registraRelacion(){
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}
		// Metodos Get para el Regitro.
		$this->load->model("FuerzaVentaDispositivo_Model", "mFuerzaVentaDispositivo");


		$dispositivoID = $this->input->get("dispositivoID");
		$FV = $this->input->get("GUID_FV");			

		if($dispositivoID !== null && $FV !== null){
			// Vamos a Registrar el Objeto.
			$fuerza_venta_dispositivoEnt = array('DispositivoID'=> $dispositivoID
			, 'GUID_FV'=> $FV 
			, 'UsuarioCreaID'=> 1
			, 'FechaCrea'=> date('Y-m-d H:i:s') 
			, 'Estado'=> 1 
);


			if($this->mFuerzaVentaDispositivo->validarExistencia($fuerza_venta_dispositivoEnt)){
				// Ya existe no h
				$id = -1; 

			} else {
				$id = $this->mFuerzaVentaDispositivo->insertar( $fuerza_venta_dispositivoEnt ); 				
			}


			
			echo json_encode(array("data" => 1, "IsOk"=> true, "id"=> $id, "Msg"=>"Success", "IsSession" => true ) );
			return true; 
		}


echo json_encode(array("IsOk"=> false, "Msg"=> validation_errors(), "IsSession" => true  ) );
           	return false;

	}

	public function eliminarRelacion(){

		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}
		// Metodos Get para el Regitro.
			$dispositivoID = $this->input->get("dispositivoID");
			$FV = $this->input->get("GUID_FV");

			if($dispositivoID !== null && $FV !== null){
			// Vamos a Registrar el Objeto.
			$fuerza_venta_dispositivoEnt = array('DispositivoID'=> $dispositivoID
			, 'GUID_FV'=> $FV 
			, 'UsuarioCreaID'=> 1
			, 'FechaCrea'=> date('Y-m-d H:i:s') 
			, 'Estado'=> 1 
);

			$id = 0; 

			if($this->mFuerzaVentaDispositivo->validarExistencia($fuerza_venta_dispositivoEnt)){
				
				$this->mFuerzaVentaDispositivo->eliminar($fuerza_venta_dispositivoEnt); 
				$id = -1; 
			} 


			
			echo json_encode(array("data" => 1, "IsOk"=> true, "id"=> $id, "Msg"=>"Success", "IsSession" => true ) );
			return true; 
		}


	}


	public function Obtener(){
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		$idRol = 0; 
		$this->load->model("FuerzaVentaDispositivo_Model", "mFuerzaVentaDispositivo");
		$pagConf =  $this->config->item("client_pagination");

		if($this->input->get("vNumPage")){
			$pagina = $this->input->get("vNumPage");

			$listaFuerzaVentaDispositivo = $this->mFuerzaVentaDispositivo->obtenerFuerzaVentaDispositivoPaginado($pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );

		} else {
			$listaFuerzaVentaDispositivo = $this->mFuerzaVentaDispositivo->obtenerFuerzaVentaDispositivoPaginado($pagConf["RowsPerPages"], 0);
		}
		
		$totalResult = 0; 
		if(count($listaFuerzaVentaDispositivo)> 0){
			$first = current($listaFuerzaVentaDispositivo); 	
			$totalResult = $first->CountRow;
		}
		
		$first = current($listaFuerzaVentaDispositivo); 
		echo json_encode(array("data" => $listaFuerzaVentaDispositivo, "totalResult"=> $totalResult, "count"=> count($listaFuerzaVentaDispositivo), "IsOk"=> true, "rowsPerPages"=> $pagConf["RowsPerPages"], "IsSession" => true)); 
	}

	public function Crear(){
	if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}
		$this->load->model("FuerzaVentaDispositivo_Model", "mFuerzaVentaDispositivo");
		// Auto Validacion del Formulario.

	$this->validation->set_rules("objeto[DispositivoID]", "DispositivoID", "required|integer"); 
$this->validation->set_rules("objeto[GUID_FV]", "GUID_FV", "required|max_length[45]"); 
$this->validation->set_rules("objeto[UsuarioCreaID]", "UsuarioCreaID", "integer"); 
$this->validation->set_rules("objeto[FechaCrea]", "FechaCrea", ""); 
$this->validation->set_rules("objeto[Estatus]", "Estatus", "integer"); 


	if ($this->validation->run() == FALSE)
         {
            echo json_encode(array("IsOk"=> false, "Msg"=> validation_errors(), "IsSession" => true, "csrf" =>array(
        "name" => $this->security->get_csrf_token_name(),
        "hash" => $this->security->get_csrf_hash()
        ) )  );
           	return false;
    }

    if($this->input->post("objeto")){
		$fuerza_venta_dispositivoObj = $this->security->xss_clean($this->input->post("objeto"));

		$fuerza_venta_dispositivoEnt = array('DispositivoID'=> $fuerza_venta_dispositivoObj['DispositivoID'] 
, 'GUID_FV'=> $fuerza_venta_dispositivoObj['GUID_FV'] 
, 'UsuarioCreaID'=> $fuerza_venta_dispositivoObj['UsuarioCreaID'] 
, 'FechaCrea'=> date('Y-m-d H:i:s') 
, 'Estatus'=> $fuerza_venta_dispositivoObj['Estatus'] 
);

			$id = $this->mFuerzaVentaDispositivo->insertar( $fuerza_venta_dispositivoEnt ); 
			$fuerza_venta_dispositivoEnt["FuerzaVentaDispositivoID"] = $id; 

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
		$this->load->model("FuerzaVentaDispositivo_Model", "mFuerzaVentaDispositivo");
	$this->validation->set_rules("objeto[DispositivoID]", "DispositivoID", "required|integer"); 
$this->validation->set_rules("objeto[GUID_FV]", "GUID_FV", "required|max_length[45]"); 
$this->validation->set_rules("objeto[UsuarioCreaID]", "UsuarioCreaID", "integer"); 
$this->validation->set_rules("objeto[FechaCrea]", "FechaCrea", ""); 
$this->validation->set_rules("objeto[Estatus]", "Estatus", "integer"); 

	if ($this->validation->run() == FALSE)
         {
            echo json_encode(array("IsOk"=> false, "Msg"=> validation_errors(), "IsSession" => true, "csrf" =>array(
        "name" => $this->security->get_csrf_token_name(),
        "hash" => $this->security->get_csrf_hash()
        ) )  );
           	return false;
    }

     if($this->input->post("objeto")){
		$fuerza_venta_dispositivoObj = $this->security->xss_clean($this->input->post("objeto"));
		$fuerza_venta_dispositivoEnt = $this->mFuerzaVentaDispositivo->actualizar( $fuerza_venta_dispositivoObj );

				if( ! $fuerza_venta_dispositivoEnt ){
				echo json_encode(array("IsOk"=> false, "Msg"=> "Error DB al actualizar". print_r($fuerza_venta_dispositivoEnt, true), "csrf" =>array(
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

		$this->load->model("FuerzaVentaDispositivo_Model", "mFuerzaVentaDispositivo");

		$pagConf =  $this->config->item("client_pagination");
		if($this->input->get("vNumPage")){
			$pagina = $this->input->get("vNumPage"); 
			$listaFuerzaVentaDispositivo = $this->mFuerzaVentaDispositivo->obtenerFuerzaVentaDispositivoPorCampo("Descripcion", $str, $pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );
		} else {
			$listaFuerzaVentaDispositivo = $this->mDispositivo->obtenerDispositivoPorCampo("Descripcion", $str, $pagConf["RowsPerPages"], 0);
		}
		$totalResult = 0; 
		if(count($listaFuerzaVentaDispositivo)> 0){
			$first = current($listaFuerzaVentaDispositivo); 	
			$totalResult = $first->CountRow;
		}
		echo json_encode(array("data" => $listaFuerzaVentaDispositivo, "totalResult"=> $totalResult, "count"=> count($listaFuerzaVentaDispositivo), "IsOk"=> true, "rowsPerPages" => $pagConf["RowsPerPages"], "IsSession" => true)); 
	}

	public function Eliminar(){
		// Eliminar a travez del post y del Get y validar la Session.
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		if($this->input->post("objeto")){			
			$this->load->model("FuerzaVentaDispositivo_Model", "mFuerzaVentaDispositivo");
			
			$fuerza_venta_dispositivoObj = $this->security->xss_clean($this->input->post("objeto"));  
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
}
	 