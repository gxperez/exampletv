<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Grupo extends MY_Controller {

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
		$this->load->view("web/sm_grupo", $data); 
	}


	public function Obtener(){
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		$idRol = 0; 
		$this->load->model("Grupo_Model", "mGrupo");
		$pagConf =  $this->config->item("client_pagination");

		if($this->input->get("vNumPage")){
			$pagina = $this->input->get("vNumPage");

			$listaGrupo = $this->mGrupo->obtenerGrupoPaginado($pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );

		} else {
			$listaGrupo = $this->mGrupo->obtenerGrupoPaginado($pagConf["RowsPerPages"], 0);
		}
		
		$totalResult = 0; 
		if(count($listaGrupo)> 0){
			$first = current($listaGrupo); 	
			$totalResult = $first->CountRow;
		}
		
		$first = current($listaGrupo); 
		echo json_encode(array("data" => $listaGrupo, "totalResult"=> $totalResult, "count"=> count($listaGrupo), "IsOk"=> true, "rowsPerPages"=> $pagConf["RowsPerPages"], "IsSession" => true)); 
	}

	public function Crear(){
	if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}
		$this->load->model("Grupo_Model", "mGrupo");
		// Auto Validacion del Formulario.

	$this->validation->set_rules("objeto[Descripcion]", "Descripcion", "max_length[50]"); 
$this->validation->set_rules("objeto[Estado]", "Estado", "max_length[45]"); 
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
		$grupoObj = $this->security->xss_clean($this->input->post("objeto"));

		$grupoEnt = array('Descripcion'=> $grupoObj['Descripcion'] 
, 'Estado'=> $grupoObj['Estado'] 
, 'UsuarioModificaID'=> $grupoObj['UsuarioModificaID'] 
);

			$id = $this->mGrupo->insertar( $grupoEnt ); 
			$grupoEnt["GrupoID"] = $id; 

			echo json_encode(array("data" => $grupoEnt, "IsOk"=> true, "Msg"=>"Success", "IsSession" => true, "csrf" =>array(
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
		$this->load->model("Grupo_Model", "mGrupo");
	$this->validation->set_rules("objeto[Descripcion]", "Descripcion", "max_length[50]"); 
$this->validation->set_rules("objeto[Estado]", "Estado", "max_length[45]"); 
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
		$grupoObj = $this->security->xss_clean($this->input->post("objeto"));
		$grupoEnt = $this->mGrupo->actualizar( $grupoObj );

				if( ! $grupoEnt ){
				echo json_encode(array("IsOk"=> false, "Msg"=> "Error DB al actualizar". print_r($grupoEnt, true), "csrf" =>array(
		        "name" => $this->security->get_csrf_token_name(),
		        "hash" => $this->security->get_csrf_hash()
		        ), "IsSession" => true )  );
		        return false; 
			}

			echo json_encode(array("data" => $grupoObj, "IsOk"=> true, "Msg"=>"Success", "IsSession" => true, "csrf" =>array(
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

		$this->load->model("Grupo_Model", "mGrupo");

		$pagConf =  $this->config->item("client_pagination");
		if($this->input->get("vNumPage")){
			$pagina = $this->input->get("vNumPage"); 
			$listaGrupo = $this->mGrupo->obtenerGrupoPorCampo("Descripcion", $str, $pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );
		} else {
			$listaGrupo = $this->mGrupo->obtenerGrupoPorCampo("Descripcion", $str, $pagConf["RowsPerPages"], 0);
		}
		$totalResult = 0; 
		if(count($listaGrupo)> 0){
			$first = current($listaGrupo); 	
			$totalResult = $first->CountRow;
		}
		echo json_encode(array("data" => $listaGrupo, "totalResult"=> $totalResult, "count"=> count($listaGrupo), "IsOk"=> true, "rowsPerPages" => $pagConf["RowsPerPages"], "IsSession" => true)); 
	}

	public function Eliminar(){
		// Eliminar a travez del post y del Get y validar la Session.
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		if($this->input->post("objeto")){			
			$this->load->model("Grupo_Model", "mGrupo");
			
			$grupoObj = $this->security->xss_clean($this->input->post("objeto"));  
			$result = $this->mGrupo->cambiarEstado($grupoObj, -1); 

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
	