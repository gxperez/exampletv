<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class GrupoTv extends MY_Controller {

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
		$this->load->view("web/sm_grupo_tv", $data); 
	}


	public function Obtener(){
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		$idRol = 0; 
		$this->load->model("GrupoTv_Model", "mGrupoTv");
		$pagConf =  $this->config->item("client_pagination");

		if($this->input->get("vNumPage")){
			$pagina = $this->input->get("vNumPage");

			$listaGrupoTv = $this->mGrupoTv->obtenerGrupoTvPaginado($pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );

		} else {
			$listaGrupoTv = $this->mGrupoTv->obtenerGrupoTvPaginado($pagConf["RowsPerPages"], 0);
		}
		
		$totalResult = 0; 
		if(count($listaGrupoTv)> 0){
			$first = current($listaGrupoTv); 	
			$totalResult = $first->CountRow;
		}
		
		$first = current($listaGrupoTv); 
		echo json_encode(array("data" => $listaGrupoTv, "totalResult"=> $totalResult, "count"=> count($listaGrupoTv), "IsOk"=> true, "rowsPerPages"=> $pagConf["RowsPerPages"], "IsSession" => true)); 
	}

	public function Crear(){
	if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}
		$this->load->model("GrupoTv_Model", "mGrupoTv");
		// Auto Validacion del Formulario.

	$this->validation->set_rules("objeto[GrupoID]", "GrupoID", "integer"); 
$this->validation->set_rules("objeto[DispositivoID]", "DispositivoID", "integer"); 
$this->validation->set_rules("objeto[Estado]", "Estado", "integer"); 
$this->validation->set_rules("objeto[UsuarioModificaID]", "UsuarioModificaID", "integer"); 
$this->validation->set_rules("objeto[FechaModifica]", "FechaModifica", ""); 


	if ($this->validation->run() == FALSE)
         {
            echo json_encode(array("IsOk"=> false, "Msg"=> validation_errors(), "IsSession" => true, "csrf" =>array(
        "name" => $this->security->get_csrf_token_name(),
        "hash" => $this->security->get_csrf_hash()
        ) )  );
           	return false;
    }

    if($this->input->post("objeto")){
		$grupo_tvObj = $this->security->xss_clean($this->input->post("objeto"));

		$grupo_tvEnt = array('GrupoID'=> $grupo_tvObj['GrupoID'] 
, 'DispositivoID'=> $grupo_tvObj['DispositivoID'] 
, 'Estado'=> $grupo_tvObj['Estado'] 
, 'UsuarioModificaID'=> $grupo_tvObj['UsuarioModificaID'] 
, 'FechaModifica'=> date('Y-m-d H:i:s') 
);

			$id = $this->mGrupoTv->insertar( $grupo_tvEnt ); 
			$grupo_tvEnt["GrupoTvID"] = $id; 

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
		$this->load->model("GrupoTv_Model", "mGrupoTv");
	$this->validation->set_rules("objeto[GrupoID]", "GrupoID", "integer"); 
$this->validation->set_rules("objeto[DispositivoID]", "DispositivoID", "integer"); 
$this->validation->set_rules("objeto[Estado]", "Estado", "integer"); 
$this->validation->set_rules("objeto[UsuarioModificaID]", "UsuarioModificaID", "integer"); 
$this->validation->set_rules("objeto[FechaModifica]", "FechaModifica", ""); 

	if ($this->validation->run() == FALSE)
         {
            echo json_encode(array("IsOk"=> false, "Msg"=> validation_errors(), "IsSession" => true, "csrf" =>array(
        "name" => $this->security->get_csrf_token_name(),
        "hash" => $this->security->get_csrf_hash()
        ) )  );
           	return false;
    }

     if($this->input->post("objeto")){
		$grupo_tvObj = $this->security->xss_clean($this->input->post("objeto"));
		$grupo_tvEnt = $this->mGrupoTv->actualizar( $grupo_tvObj );

				if( ! $grupo_tvEnt ){
				echo json_encode(array("IsOk"=> false, "Msg"=> "Error DB al actualizar". print_r($grupo_tvEnt, true), "csrf" =>array(
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

		$this->load->model("GrupoTv_Model", "mGrupoTv");

		$pagConf =  $this->config->item("client_pagination");
		if($this->input->get("vNumPage")){
			$pagina = $this->input->get("vNumPage"); 
			$listaGrupoTv = $this->mGrupoTv->obtenerGrupoTvPorCampo("Descripcion", $str, $pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );
		} else {
			$listaGrupoTv = $this->mDispositivo->obtenerDispositivoPorCampo("Descripcion", $str, $pagConf["RowsPerPages"], 0);
		}
		$totalResult = 0; 
		if(count($listaGrupoTv)> 0){
			$first = current($listaGrupoTv); 	
			$totalResult = $first->CountRow;
		}
		echo json_encode(array("data" => $listaGrupoTv, "totalResult"=> $totalResult, "count"=> count($listaGrupoTv), "IsOk"=> true, "rowsPerPages" => $pagConf["RowsPerPages"], "IsSession" => true)); 
	}

	public function Eliminar(){
		// Eliminar a travez del post y del Get y validar la Session.
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		if($this->input->post("objeto")){			
			$this->load->model("GrupoTv_Model", "mGrupoTv");
			
			$grupo_tvObj = $this->security->xss_clean($this->input->post("objeto"));  
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
	