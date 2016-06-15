<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Contenido extends MY_Controller {

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
		$this->load->view("web/sm_contenido", $data); 
	}


	public function Obtener(){
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		$idRol = 0; 
		$this->load->model("Contenido_Model", "mContenido");
		$pagConf =  $this->config->item("client_pagination");

		if($this->input->get("vNumPage")){
			$pagina = $this->input->get("vNumPage");

			$listaContenido = $this->mContenido->obtenerContenidoPaginado($pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );

		} else {
			$listaContenido = $this->mContenido->obtenerContenidoPaginado($pagConf["RowsPerPages"], 0);
		}
		
		$totalResult = 0; 
		if(count($listaContenido)> 0){
			$first = current($listaContenido); 	
			$totalResult = $first->CountRow;
		}
		
		$first = current($listaContenido); 
		echo json_encode(array("data" => $listaContenido, "totalResult"=> $totalResult, "count"=> count($listaContenido), "IsOk"=> true, "rowsPerPages"=> $pagConf["RowsPerPages"], "IsSession" => true)); 
	}

	public function Crear(){
	if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}
		$this->load->model("Contenido_Model", "mContenido");
		// Auto Validacion del Formulario.

	$this->validation->set_rules("objeto[Nombre]", "Nombre", "required|max_length[100]"); 
$this->validation->set_rules("objeto[Descripcion]", "Descripcion", "required|max_length[100]"); 
$this->validation->set_rules("objeto[SliderMaestroID]", "SliderMaestroID", "required|integer"); 
$this->validation->set_rules("objeto[Duracion]", "Duracion", "required"); 
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
		$contenidoObj = $this->security->xss_clean($this->input->post("objeto"));

		$contenidoEnt = array('Nombre'=> $contenidoObj['Nombre'] 
, 'Descripcion'=> $contenidoObj['Descripcion'] 
, 'SliderMaestroID'=> $contenidoObj['SliderMaestroID'] 
, 'Duracion'=> $contenidoObj['Duracion'] 
, 'Estado'=> $contenidoObj['Estado'] 
, 'Guid'=> $contenidoObj['Guid'] 
, 'UsuarioModificaID'=> $contenidoObj['UsuarioModificaID'] 
, 'FechaModifica'=> date('Y-m-d H:i:s') 
);

			$id = $this->mContenido->insertar( $contenidoEnt ); 
			$contenidoEnt["ContenidoID"] = $id; 

			echo json_encode(array("data" => $contenidoEnt, "IsOk"=> true, "Msg"=>"Success", "IsSession" => true, "csrf" =>array(
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
		$this->load->model("Contenido_Model", "mContenido");
	$this->validation->set_rules("objeto[Nombre]", "Nombre", "required|max_length[100]"); 
$this->validation->set_rules("objeto[Descripcion]", "Descripcion", "required|max_length[100]"); 
$this->validation->set_rules("objeto[SliderMaestroID]", "SliderMaestroID", "required|integer"); 
$this->validation->set_rules("objeto[Duracion]", "Duracion", "required"); 
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
		$contenidoObj = $this->security->xss_clean($this->input->post("objeto"));
		$contenidoEnt = $this->mContenido->actualizar( $contenidoObj );

				if( ! $contenidoEnt ){
				echo json_encode(array("IsOk"=> false, "Msg"=> "Error DB al actualizar". print_r($contenidoEnt, true), "csrf" =>array(
		        "name" => $this->security->get_csrf_token_name(),
		        "hash" => $this->security->get_csrf_hash()
		        ), "IsSession" => true )  );
		        return false; 
			}

			echo json_encode(array("data" => $contenidoObj, "IsOk"=> true, "Msg"=>"Success", "IsSession" => true, "csrf" =>array(
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

		$this->load->model("Contenido_Model", "mContenido");

		$pagConf =  $this->config->item("client_pagination");
		if($this->input->get("vNumPage")){
			$pagina = $this->input->get("vNumPage"); 
			$listaContenido = $this->mContenido->obtenerContenidoPorCampo("Descripcion", $str, $pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );
		} else {
			$listaContenido = $this->mContenido->obtenerContenidoPorCampo("Descripcion", $str, $pagConf["RowsPerPages"], 0);
		}
		$totalResult = 0; 
		if(count($listaContenido)> 0){
			$first = current($listaContenido); 	
			$totalResult = $first->CountRow;
		}
		echo json_encode(array("data" => $listaContenido, "totalResult"=> $totalResult, "count"=> count($listaContenido), "IsOk"=> true, "rowsPerPages" => $pagConf["RowsPerPages"], "IsSession" => true)); 
	}

	public function Eliminar(){
		// Eliminar a travez del post y del Get y validar la Session.
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		if($this->input->post("objeto")){			
			$this->load->model("Contenido_Model", "mContenido");
			
			$contenidoObj = $this->security->xss_clean($this->input->post("objeto"));  
			$result = $this->mContenido->cambiarEstado($contenidoObj, -1); 

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