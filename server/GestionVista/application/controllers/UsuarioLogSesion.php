<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class UsuarioLogSesion extends MY_Controller {

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
		$this->load->view("web/sm_usuario_log_sesion", $data); 
	}


	public function Obtener(){
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		$idRol = 0; 
		$this->load->model("UsuarioLogSesion_Model", "mUsuarioLogSesion");
		$pagConf =  $this->config->item("client_pagination");

		if($this->input->get("vNumPage")){
			$pagina = $this->input->get("vNumPage");

			$listaUsuarioLogSesion = $this->mUsuarioLogSesion->obtenerUsuarioLogSesionPaginado($pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );

		} else {
			$listaUsuarioLogSesion = $this->mUsuarioLogSesion->obtenerUsuarioLogSesionPaginado($pagConf["RowsPerPages"], 0);
		}
		
		$totalResult = 0; 
		if(count($listaUsuarioLogSesion)> 0){
			$first = current($listaUsuarioLogSesion); 	
			$totalResult = $first->CountRow;
		}
		
		$first = current($listaUsuarioLogSesion); 
		echo json_encode(array("data" => $listaUsuarioLogSesion, "totalResult"=> $totalResult, "count"=> count($listaUsuarioLogSesion), "IsOk"=> true, "rowsPerPages"=> $pagConf["RowsPerPages"], "IsSession" => true)); 
	}

	public function Crear(){
	if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}
		$this->load->model("UsuarioLogSesion_Model", "mUsuarioLogSesion");
		// Auto Validacion del Formulario.

	$this->validation->set_rules("objeto[nombreUsuario]", "nombreUsuario", "required|max_length[50]"); 
$this->validation->set_rules("objeto[email]", "email", "max_length[255]"); 
$this->validation->set_rules("objeto[clave]", "clave", "required|max_length[45]"); 
$this->validation->set_rules("objeto[fechaCrea]", "fechaCrea", ""); 
$this->validation->set_rules("objeto[ultimaSesion]", "ultimaSesion", ""); 
$this->validation->set_rules("objeto[estatus]", "estatus", "max_length[45]"); 
$this->validation->set_rules("objeto[GUID]", "GUID", "max_length[50]"); 
$this->validation->set_rules("objeto[ipUser]", "ipUser", "max_length[25]"); 


	if ($this->validation->run() == FALSE)
         {
            echo json_encode(array("IsOk"=> false, "Msg"=> validation_errors(), "IsSession" => true, "csrf" =>array(
        "name" => $this->security->get_csrf_token_name(),
        "hash" => $this->security->get_csrf_hash()
        ) )  );
           	return false;
    }

    if($this->input->post("objeto")){
		$usuario_log_sesionObj = $this->security->xss_clean($this->input->post("objeto"));

		$usuario_log_sesionEnt = array('nombreUsuario'=> $usuario_log_sesionObj['nombreUsuario'] 
, 'email'=> $usuario_log_sesionObj['email'] 
, 'clave'=> $usuario_log_sesionObj['clave'] 
, 'ultimaSesion'=> date('Y-m-d H:i:s') 
, 'estatus'=> $usuario_log_sesionObj['estatus'] 
, 'GUID'=> $usuario_log_sesionObj['GUID'] 
, 'ipUser'=> $usuario_log_sesionObj['ipUser'] 
);

			$id = $this->mUsuarioLogSesion->insertar( $usuario_log_sesionEnt ); 
			$usuario_log_sesionEnt["usuario_log_sesionID"] = $id; 

			echo json_encode(array("data" => $usuario_log_sesionEnt, "IsOk"=> true, "Msg"=>"Success", "IsSession" => true, "csrf" =>array(
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
		$this->load->model("UsuarioLogSesion_Model", "mUsuarioLogSesion");
	$this->validation->set_rules("objeto[nombreUsuario]", "nombreUsuario", "required|max_length[50]"); 
$this->validation->set_rules("objeto[email]", "email", "max_length[255]"); 
$this->validation->set_rules("objeto[clave]", "clave", "required|max_length[45]"); 
$this->validation->set_rules("objeto[fechaCrea]", "fechaCrea", ""); 
$this->validation->set_rules("objeto[ultimaSesion]", "ultimaSesion", ""); 
$this->validation->set_rules("objeto[estatus]", "estatus", "max_length[45]"); 
$this->validation->set_rules("objeto[GUID]", "GUID", "max_length[50]"); 
$this->validation->set_rules("objeto[ipUser]", "ipUser", "max_length[25]"); 

	if ($this->validation->run() == FALSE)
         {
            echo json_encode(array("IsOk"=> false, "Msg"=> validation_errors(), "IsSession" => true, "csrf" =>array(
        "name" => $this->security->get_csrf_token_name(),
        "hash" => $this->security->get_csrf_hash()
        ) )  );
           	return false;
    }

     if($this->input->post("objeto")){
		$usuario_log_sesionObj = $this->security->xss_clean($this->input->post("objeto"));
		$usuario_log_sesionEnt = $this->mUsuarioLogSesion->actualizar( $usuario_log_sesionObj );

				if( ! $usuario_log_sesionEnt ){
				echo json_encode(array("IsOk"=> false, "Msg"=> "Error DB al actualizar". print_r($usuario_log_sesionEnt, true), "csrf" =>array(
		        "name" => $this->security->get_csrf_token_name(),
		        "hash" => $this->security->get_csrf_hash()
		        ), "IsSession" => true )  );
		        return false; 
			}

			echo json_encode(array("data" => $usuario_log_sesionObj, "IsOk"=> true, "Msg"=>"Success", "IsSession" => true, "csrf" =>array(
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

		$this->load->model("UsuarioLogSesion_Model", "mUsuarioLogSesion");

		$pagConf =  $this->config->item("client_pagination");
		if($this->input->get("vNumPage")){
			$pagina = $this->input->get("vNumPage"); 
			$listaUsuarioLogSesion = $this->mUsuarioLogSesion->obtenerUsuarioLogSesionPorCampo("Descripcion", $str, $pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );
		} else {
			$listaUsuarioLogSesion = $this->mUsuarioLogSesion->obtenerUsuarioLogSesionPorCampo("Descripcion", $str, $pagConf["RowsPerPages"], 0);
		}
		$totalResult = 0; 
		if(count($listaUsuarioLogSesion)> 0){
			$first = current($listaUsuarioLogSesion); 	
			$totalResult = $first->CountRow;
		}
		echo json_encode(array("data" => $listaUsuarioLogSesion, "totalResult"=> $totalResult, "count"=> count($listaUsuarioLogSesion), "IsOk"=> true, "rowsPerPages" => $pagConf["RowsPerPages"], "IsSession" => true)); 
	}

	public function Eliminar(){
		// Eliminar a travez del post y del Get y validar la Session.
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		if($this->input->post("objeto")){			
			$this->load->model("UsuarioLogSesion_Model", "mUsuarioLogSesion");
			
			$usuario_log_sesionObj = $this->security->xss_clean($this->input->post("objeto"));  
			$result = $this->mUsuarioLogSesion->cambiarEstado($usuario_log_sesionObj, -1); 

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
	