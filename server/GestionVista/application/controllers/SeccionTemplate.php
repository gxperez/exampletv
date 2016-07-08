<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class SeccionTemplate extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *	@autor:	Grequis Xavier Perez Fortuna.
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
        	$data['listContenidoTipo'] =  $this->mEnum->getEnum("contenidotipo");

        

		// Carga de planilla web en general.
		$this->load->view("web/sm_seccion_template", $data); 
	}

	public function obtenerSecionTempatePorTempateID(){

		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		/// 
		if($this->input->get("TemplatePagesID")){
			$this->load->model("SeccionTemplate_Model", "mSeccionTemplate");

			$listSeccion_templateEnt = $this->mSeccionTemplate->obtenerSeccionPorTemplatePagesID($this->input->get("TemplatePagesID") );
			echo json_encode(array("data" => $listSeccion_templateEnt, "IsOk"=> true,  "IsSession" => true) ); 		
			return true; 

		}


	}


	public function Obtener(){
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		$idRol = 0; 
		$this->load->model("SeccionTemplate_Model", "mSeccionTemplate");
		$pagConf =  $this->config->item("client_pagination");

		if($this->input->get("vNumPage")){
			$pagina = $this->input->get("vNumPage");

			$listaSeccionTemplate = $this->mSeccionTemplate->obtenerSeccionTemplatePaginado($pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );

		} else {
			$listaSeccionTemplate = $this->mSeccionTemplate->obtenerSeccionTemplatePaginado($pagConf["RowsPerPages"], 0);
		}
		
		$totalResult = 0; 
		if(count($listaSeccionTemplate)> 0){
			$first = current($listaSeccionTemplate); 	
			$totalResult = $first->CountRow;
		}
		
		$first = current($listaSeccionTemplate); 
		echo json_encode(array("data" => $listaSeccionTemplate, "totalResult"=> $totalResult, "count"=> count($listaSeccionTemplate), "IsOk"=> true, "rowsPerPages"=> $pagConf["RowsPerPages"], "IsSession" => true)); 
	}

	public function Crear(){
	if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}
		$this->load->model("SeccionTemplate_Model", "mSeccionTemplate");
		// Auto Validacion del Formulario.

	$this->validation->set_rules("objeto[TemplatePagesID]", "TemplatePagesID", "required|integer"); 
$this->validation->set_rules("objeto[ContenidoTipo]", "ContenidoTipo", "required|integer"); 
$this->validation->set_rules("objeto[Posicion]", "Posicion", "required|integer"); 
$this->validation->set_rules("objeto[Encabezado]", "Encabezado", "required|max_length[100]"); 
$this->validation->set_rules("objeto[FuenteID]", "FuenteID", "required|integer"); 
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
		$seccion_templateObj = $this->security->xss_clean($this->input->post("objeto"));

		$seccion_templateEnt = array('TemplatePagesID'=> $seccion_templateObj['TemplatePagesID'] 
, 'ContenidoTipo'=> $seccion_templateObj['ContenidoTipo'] 
, 'Posicion'=> $seccion_templateObj['Posicion'] 
, 'Encabezado'=> $seccion_templateObj['Encabezado'] 
, 'FuenteID'=> $seccion_templateObj['FuenteID'] 
, 'Estado'=> $seccion_templateObj['Estado'] 
, 'UsuarioModificaID'=> $seccion_templateObj['UsuarioModificaID'] 
, 'FechaModificacion'=> date('Y-m-d H:i:s') 
);

			$id = $this->mSeccionTemplate->insertar( $seccion_templateEnt ); 
			$seccion_templateEnt["SeccionTemplateID"] = $id; 

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
		$this->load->model("SeccionTemplate_Model", "mSeccionTemplate");
	$this->validation->set_rules("objeto[TemplatePagesID]", "TemplatePagesID", "required|integer"); 
$this->validation->set_rules("objeto[ContenidoTipo]", "ContenidoTipo", "required|integer"); 
$this->validation->set_rules("objeto[Posicion]", "Posicion", "required|integer"); 
$this->validation->set_rules("objeto[Encabezado]", "Encabezado", "required|max_length[100]"); 
$this->validation->set_rules("objeto[FuenteID]", "FuenteID", "required|integer"); 
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
		$seccion_templateObj = $this->security->xss_clean($this->input->post("objeto"));
		$seccion_templateEnt = $this->mSeccionTemplate->actualizar( $seccion_templateObj );

				if( ! $seccion_templateEnt ){
				echo json_encode(array("IsOk"=> false, "Msg"=> "Error DB al actualizar". print_r($seccion_templateEnt, true), "csrf" =>array(
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

		$this->load->model("SeccionTemplate_Model", "mSeccionTemplate");

		$pagConf =  $this->config->item("client_pagination");
		if($this->input->get("vNumPage")){
			$pagina = $this->input->get("vNumPage"); 
			$listaSeccionTemplate = $this->mSeccionTemplate->obtenerSeccionTemplatePorCampo("Descripcion", $str, $pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );
		} else {
			$listaSeccionTemplate = $this->mDispositivo->obtenerDispositivoPorCampo("Descripcion", $str, $pagConf["RowsPerPages"], 0);
		}
		$totalResult = 0; 
		if(count($listaSeccionTemplate)> 0){
			$first = current($listaSeccionTemplate); 	
			$totalResult = $first->CountRow;
		}
		echo json_encode(array("data" => $listaSeccionTemplate, "totalResult"=> $totalResult, "count"=> count($listaSeccionTemplate), "IsOk"=> true, "rowsPerPages" => $pagConf["RowsPerPages"], "IsSession" => true)); 
	}

	public function Eliminar(){
		// Eliminar a travez del post y del Get y validar la Session.
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		if($this->input->post("objeto")){			
			$this->load->model("SeccionTemplate_Model", "mSeccionTemplate");
			
			$seccion_templateObj = $this->security->xss_clean($this->input->post("objeto"));  
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