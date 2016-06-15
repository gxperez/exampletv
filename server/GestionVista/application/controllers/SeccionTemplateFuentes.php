<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class SeccionTemplateFuentes extends MY_Controller {

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
		$this->load->view("web/sm_seccion_template_fuentes", $data); 
	}


	public function Obtener(){
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		$idRol = 0; 
		$this->load->model("SeccionTemplateFuentes_Model", "mSeccionTemplateFuentes");
		$pagConf =  $this->config->item("client_pagination");

		if($this->input->get("vNumPage")){
			$pagina = $this->input->get("vNumPage");

			$listaSeccionTemplateFuentes = $this->mSeccionTemplateFuentes->obtenerSeccionTemplateFuentesPaginado($pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );

		} else {
			$listaSeccionTemplateFuentes = $this->mSeccionTemplateFuentes->obtenerSeccionTemplateFuentesPaginado($pagConf["RowsPerPages"], 0);
		}
		
		$totalResult = 0; 
		if(count($listaSeccionTemplateFuentes)> 0){
			$first = current($listaSeccionTemplateFuentes); 	
			$totalResult = $first->CountRow;
		}
		
		$first = current($listaSeccionTemplateFuentes); 
		echo json_encode(array("data" => $listaSeccionTemplateFuentes, "totalResult"=> $totalResult, "count"=> count($listaSeccionTemplateFuentes), "IsOk"=> true, "rowsPerPages"=> $pagConf["RowsPerPages"], "IsSession" => true)); 
	}

	public function Crear(){
	if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}
		$this->load->model("SeccionTemplateFuentes_Model", "mSeccionTemplateFuentes");
		// Auto Validacion del Formulario.

	$this->validation->set_rules("objeto[SeccionTemplateID]", "SeccionTemplateID", "required|integer"); 
$this->validation->set_rules("objeto[FuenteID]", "FuenteID", "required|integer"); 
$this->validation->set_rules("objeto[Secuencia]", "Secuencia", "required|integer"); 
$this->validation->set_rules("objeto[ Tiempo]", " Tiempo", "required"); 
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
		$seccion_template_fuentesObj = $this->security->xss_clean($this->input->post("objeto"));

		$seccion_template_fuentesEnt = array('SeccionTemplateID'=> $seccion_template_fuentesObj['SeccionTemplateID'] 
, 'FuenteID'=> $seccion_template_fuentesObj['FuenteID'] 
, 'Secuencia'=> $seccion_template_fuentesObj['Secuencia'] 
, ' Tiempo'=> $seccion_template_fuentesObj[' Tiempo'] 
, 'Estado'=> $seccion_template_fuentesObj['Estado'] 
, 'UsuarioModificaID'=> $seccion_template_fuentesObj['UsuarioModificaID'] 
, 'FechaModificacion'=> date('Y-m-d H:i:s') 
);

			$id = $this->mSeccionTemplateFuentes->insertar( $seccion_template_fuentesEnt ); 
			$seccion_template_fuentesEnt["SeccionTemplateFuentesID"] = $id; 

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
		$this->load->model("SeccionTemplateFuentes_Model", "mSeccionTemplateFuentes");
	$this->validation->set_rules("objeto[SeccionTemplateID]", "SeccionTemplateID", "required|integer"); 
$this->validation->set_rules("objeto[FuenteID]", "FuenteID", "required|integer"); 
$this->validation->set_rules("objeto[Secuencia]", "Secuencia", "required|integer"); 
$this->validation->set_rules("objeto[ Tiempo]", " Tiempo", "required"); 
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
		$seccion_template_fuentesObj = $this->security->xss_clean($this->input->post("objeto"));
		$seccion_template_fuentesEnt = $this->mSeccionTemplateFuentes->actualizar( $seccion_template_fuentesObj );

				if( ! $seccion_template_fuentesEnt ){
				echo json_encode(array("IsOk"=> false, "Msg"=> "Error DB al actualizar". print_r($seccion_template_fuentesEnt, true), "csrf" =>array(
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

		$this->load->model("SeccionTemplateFuentes_Model", "mSeccionTemplateFuentes");

		$pagConf =  $this->config->item("client_pagination");
		if($this->input->get("vNumPage")){
			$pagina = $this->input->get("vNumPage"); 
			$listaSeccionTemplateFuentes = $this->mSeccionTemplateFuentes->obtenerSeccionTemplateFuentesPorCampo("Descripcion", $str, $pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );
		} else {
			$listaSeccionTemplateFuentes = $this->mDispositivo->obtenerDispositivoPorCampo("Descripcion", $str, $pagConf["RowsPerPages"], 0);
		}
		$totalResult = 0; 
		if(count($listaSeccionTemplateFuentes)> 0){
			$first = current($listaSeccionTemplateFuentes); 	
			$totalResult = $first->CountRow;
		}
		echo json_encode(array("data" => $listaSeccionTemplateFuentes, "totalResult"=> $totalResult, "count"=> count($listaSeccionTemplateFuentes), "IsOk"=> true, "rowsPerPages" => $pagConf["RowsPerPages"], "IsSession" => true)); 
	}

	public function Eliminar(){
		// Eliminar a travez del post y del Get y validar la Session.
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		if($this->input->post("objeto")){			
			$this->load->model("SeccionTemplateFuentes_Model", "mSeccionTemplateFuentes");
			
			$seccion_template_fuentesObj = $this->security->xss_clean($this->input->post("objeto"));  
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