<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class BloqueContenido extends MY_Controller {

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
		$this->load->view("web/sm_bloque_contenido", $data); 
	}


	public function Obtener(){
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		$idRol = 0; 
		$this->load->model("BloqueContenido_Model", "mBloqueContenido");
		$pagConf =  $this->config->item("client_pagination");

		if($this->input->get("vNumPage")){
			$pagina = $this->input->get("vNumPage");

			$listaBloqueContenido = $this->mBloqueContenido->obtenerBloqueContenidoPaginado($pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );

		} else {
			$listaBloqueContenido = $this->mBloqueContenido->obtenerBloqueContenidoPaginado($pagConf["RowsPerPages"], 0);
		}
		
		$totalResult = 0; 
		if(count($listaBloqueContenido)> 0){
			$first = current($listaBloqueContenido); 	
			$totalResult = $first->CountRow;
		}
		
		$first = current($listaBloqueContenido); 
		echo json_encode(array("data" => $listaBloqueContenido, "totalResult"=> $totalResult, "count"=> count($listaBloqueContenido), "IsOk"=> true, "rowsPerPages"=> $pagConf["RowsPerPages"], "IsSession" => true)); 
	}

	public function Crear(){
	if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}
		$this->load->model("BloqueContenido_Model", "mBloqueContenido");
		// Auto Validacion del Formulario.

	$this->validation->set_rules("objeto[BloqueID]", "BloqueID", "required|integer"); 
$this->validation->set_rules("objeto[ContenidoID]", "ContenidoID", "required|integer"); 
$this->validation->set_rules("objeto[GrupoID]", "GrupoID", "integer"); 
$this->validation->set_rules("objeto[Orden]", "Orden", "integer"); 
$this->validation->set_rules("objeto[Estado]", "Estado", "required|integer"); 
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
		$bloque_contenidoObj = $this->security->xss_clean($this->input->post("objeto"));

		$bloque_contenidoEnt = array('BloqueID'=> $bloque_contenidoObj['BloqueID'] 
, 'ContenidoID'=> $bloque_contenidoObj['ContenidoID'] 
, 'GrupoID'=> $bloque_contenidoObj['GrupoID'] 
, 'Orden'=> $bloque_contenidoObj['Orden'] 
, 'Estado'=> $bloque_contenidoObj['Estado'] 
, 'UsuarioModificaID'=> $bloque_contenidoObj['UsuarioModificaID'] 
, 'FechaModifica'=> date('Y-m-d H:i:s') 
);

			$id = $this->mBloqueContenido->insertar( $bloque_contenidoEnt ); 
			$bloque_contenidoEnt["BloqueContenidoID"] = $id; 

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
		$this->load->model("BloqueContenido_Model", "mBloqueContenido");
	$this->validation->set_rules("objeto[BloqueID]", "BloqueID", "required|integer"); 
$this->validation->set_rules("objeto[ContenidoID]", "ContenidoID", "required|integer"); 
$this->validation->set_rules("objeto[GrupoID]", "GrupoID", "integer"); 
$this->validation->set_rules("objeto[Orden]", "Orden", "integer"); 
$this->validation->set_rules("objeto[Estado]", "Estado", "required|integer"); 
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
		$bloque_contenidoObj = $this->security->xss_clean($this->input->post("objeto"));
		$bloque_contenidoEnt = $this->mBloqueContenido->actualizar( $bloque_contenidoObj );

				if( ! $bloque_contenidoEnt ){
				echo json_encode(array("IsOk"=> false, "Msg"=> "Error DB al actualizar". print_r($bloque_contenidoEnt, true), "csrf" =>array(
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

		$this->load->model("BloqueContenido_Model", "mBloqueContenido");

		$pagConf =  $this->config->item("client_pagination");
		if($this->input->get("vNumPage")){
			$pagina = $this->input->get("vNumPage"); 
			$listaBloqueContenido = $this->mBloqueContenido->obtenerBloqueContenidoPorCampo("Descripcion", $str, $pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );
		} else {
			$listaBloqueContenido = $this->mDispositivo->obtenerDispositivoPorCampo("Descripcion", $str, $pagConf["RowsPerPages"], 0);
		}
		$totalResult = 0; 
		if(count($listaBloqueContenido)> 0){
			$first = current($listaBloqueContenido); 	
			$totalResult = $first->CountRow;
		}
		echo json_encode(array("data" => $listaBloqueContenido, "totalResult"=> $totalResult, "count"=> count($listaBloqueContenido), "IsOk"=> true, "rowsPerPages" => $pagConf["RowsPerPages"], "IsSession" => true)); 
	}

	public function Eliminar(){
		// Eliminar a travez del post y del Get y validar la Session.
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		if($this->input->post("objeto")){			
			$this->load->model("BloqueContenido_Model", "mBloqueContenido");
			
			$bloque_contenidoObj = $this->security->xss_clean($this->input->post("objeto"));  
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
