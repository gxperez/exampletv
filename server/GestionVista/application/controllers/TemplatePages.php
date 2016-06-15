<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class TemplatePages extends MY_Controller {

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
		$this->load->view("web/sm_template_pages", $data); 
	}


	public function Obtener(){
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		$idRol = 0; 
		$this->load->model("TemplatePages_Model", "mTemplatePages");
		$pagConf =  $this->config->item("client_pagination");

		if($this->input->get("vNumPage")){
			$pagina = $this->input->get("vNumPage");

			$listaTemplatePages = $this->mTemplatePages->obtenerTemplatePagesPaginado($pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );

		} else {
			$listaTemplatePages = $this->mTemplatePages->obtenerTemplatePagesPaginado($pagConf["RowsPerPages"], 0);
		}
		
		$totalResult = 0; 
		if(count($listaTemplatePages)> 0){
			$first = current($listaTemplatePages); 	
			$totalResult = $first->CountRow;
		}
		
		$first = current($listaTemplatePages); 
		echo json_encode(array("data" => $listaTemplatePages, "totalResult"=> $totalResult, "count"=> count($listaTemplatePages), "IsOk"=> true, "rowsPerPages"=> $pagConf["RowsPerPages"], "IsSession" => true)); 
	}

	public function Crear(){
	if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}
		$this->load->model("TemplatePages_Model", "mTemplatePages");
		// Auto Validacion del Formulario.

	$this->validation->set_rules("objeto[SliderMaestroID]", "SliderMaestroID", "required|integer"); 
$this->validation->set_rules("objeto[EsquemaTipo]", "EsquemaTipo", "required|integer"); 
$this->validation->set_rules("objeto[MostrarHeader]", "MostrarHeader", "required"); 
$this->validation->set_rules("objeto[Duracion]", "Duracion", "required"); 
$this->validation->set_rules("objeto[TransicionTipoIni]", "TransicionTipoIni", "required|integer"); 
$this->validation->set_rules("objeto[TransicionTipoFin]", "TransicionTipoFin", "required|integer"); 
$this->validation->set_rules("objeto[Estado]", "Estado", "required|integer"); 
$this->validation->set_rules("objeto[UsuarioModificaID]", "UsuarioModificaID", "required|integer"); 
$this->validation->set_rules("objeto[FechaModificacion]", "FechaModificacion", "required"); 


	if ($this->validation->run() == FALSE)
         {
            echo json_encode(array("IsOk"=> false, "Msg"=> validation_errors(), "IsSession" => true, "csrf" =>array(
        "name" => $this->security->get_csrf_token_name(),
        "hash" => $this->security->get_csrf_hash()
        ) )  );
           	return false;
    }

    if($this->input->post("objeto")){
		$template_pagesObj = $this->security->xss_clean($this->input->post("objeto"));

		$template_pagesEnt = array('SliderMaestroID'=> $template_pagesObj['SliderMaestroID'] 
, 'EsquemaTipo'=> $template_pagesObj['EsquemaTipo'] 
, 'MostrarHeader'=> $template_pagesObj['MostrarHeader'] 
, 'Duracion'=> $template_pagesObj['Duracion'] 
, 'TransicionTipoIni'=> $template_pagesObj['TransicionTipoIni'] 
, 'TransicionTipoFin'=> $template_pagesObj['TransicionTipoFin'] 
, 'Estado'=> $template_pagesObj['Estado'] 
, 'UsuarioModificaID'=> $template_pagesObj['UsuarioModificaID'] 
, 'FechaModificacion'=> date('Y-m-d H:i:s') 
);

			$id = $this->mTemplatePages->insertar( $template_pagesEnt ); 
			$template_pagesEnt["TemplatePagesID"] = $id; 

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
		$this->load->model("TemplatePages_Model", "mTemplatePages");
	$this->validation->set_rules("objeto[SliderMaestroID]", "SliderMaestroID", "required|integer"); 
$this->validation->set_rules("objeto[EsquemaTipo]", "EsquemaTipo", "required|integer"); 
$this->validation->set_rules("objeto[MostrarHeader]", "MostrarHeader", "required"); 
$this->validation->set_rules("objeto[Duracion]", "Duracion", "required"); 
$this->validation->set_rules("objeto[TransicionTipoIni]", "TransicionTipoIni", "required|integer"); 
$this->validation->set_rules("objeto[TransicionTipoFin]", "TransicionTipoFin", "required|integer"); 
$this->validation->set_rules("objeto[Estado]", "Estado", "required|integer"); 
$this->validation->set_rules("objeto[UsuarioModificaID]", "UsuarioModificaID", "required|integer"); 
$this->validation->set_rules("objeto[FechaModificacion]", "FechaModificacion", "required"); 

	if ($this->validation->run() == FALSE)
         {
            echo json_encode(array("IsOk"=> false, "Msg"=> validation_errors(), "IsSession" => true, "csrf" =>array(
        "name" => $this->security->get_csrf_token_name(),
        "hash" => $this->security->get_csrf_hash()
        ) )  );
           	return false;
    }

     if($this->input->post("objeto")){
		$template_pagesObj = $this->security->xss_clean($this->input->post("objeto"));
		$template_pagesEnt = $this->mTemplatePages->actualizar( $template_pagesObj );

				if( ! $template_pagesEnt ){
				echo json_encode(array("IsOk"=> false, "Msg"=> "Error DB al actualizar". print_r($template_pagesEnt, true), "csrf" =>array(
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

		$this->load->model("TemplatePages_Model", "mTemplatePages");

		$pagConf =  $this->config->item("client_pagination");
		if($this->input->get("vNumPage")){
			$pagina = $this->input->get("vNumPage"); 
			$listaTemplatePages = $this->mTemplatePages->obtenerTemplatePagesPorCampo("Descripcion", $str, $pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );
		} else {
			$listaTemplatePages = $this->mDispositivo->obtenerDispositivoPorCampo("Descripcion", $str, $pagConf["RowsPerPages"], 0);
		}
		$totalResult = 0; 
		if(count($listaTemplatePages)> 0){
			$first = current($listaTemplatePages); 	
			$totalResult = $first->CountRow;
		}
		echo json_encode(array("data" => $listaTemplatePages, "totalResult"=> $totalResult, "count"=> count($listaTemplatePages), "IsOk"=> true, "rowsPerPages" => $pagConf["RowsPerPages"], "IsSession" => true)); 
	}

	public function Eliminar(){
		// Eliminar a travez del post y del Get y validar la Session.
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		if($this->input->post("objeto")){			
			$this->load->model("TemplatePages_Model", "mTemplatePages");
			
			$template_pagesObj = $this->security->xss_clean($this->input->post("objeto"));  
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