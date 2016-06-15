<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class SliderMaestro extends MY_Controller {

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
		$this->load->view("web/sm_slider_maestro", $data); 
	}


	public function Obtener(){
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		$idRol = 0; 
		$this->load->model("SliderMaestro_Model", "mSliderMaestro");
		$pagConf =  $this->config->item("client_pagination");

		if($this->input->get("vNumPage")){
			$pagina = $this->input->get("vNumPage");

			$listaSliderMaestro = $this->mSliderMaestro->obtenerSliderMaestroPaginado($pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );

		} else {
			$listaSliderMaestro = $this->mSliderMaestro->obtenerSliderMaestroPaginado($pagConf["RowsPerPages"], 0);
		}
		
		$totalResult = 0; 
		if(count($listaSliderMaestro)> 0){
			$first = current($listaSliderMaestro); 	
			$totalResult = $first->CountRow;
		}
		
		$first = current($listaSliderMaestro); 
		echo json_encode(array("data" => $listaSliderMaestro, "totalResult"=> $totalResult, "count"=> count($listaSliderMaestro), "IsOk"=> true, "rowsPerPages"=> $pagConf["RowsPerPages"], "IsSession" => true)); 
	}

	public function Crear(){
	if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}
		$this->load->model("SliderMaestro_Model", "mSliderMaestro");
		// Auto Validacion del Formulario.

	$this->validation->set_rules("objeto[Duracion]", "Duracion", "required"); 
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
		$slider_maestroObj = $this->security->xss_clean($this->input->post("objeto"));

		$slider_maestroEnt = array('Duracion'=> $slider_maestroObj['Duracion'] 
, 'Estado'=> $slider_maestroObj['Estado'] 
, 'UsuarioModificaID'=> $slider_maestroObj['UsuarioModificaID'] 
, 'FechaModifica'=> date('Y-m-d H:i:s') 
);

			$id = $this->mSliderMaestro->insertar( $slider_maestroEnt ); 
			$slider_maestroEnt["SliderMaestroID"] = $id; 

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
		$this->load->model("SliderMaestro_Model", "mSliderMaestro");
	$this->validation->set_rules("objeto[Duracion]", "Duracion", "required"); 
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
		$slider_maestroObj = $this->security->xss_clean($this->input->post("objeto"));
		$slider_maestroEnt = $this->mSliderMaestro->actualizar( $slider_maestroObj );

				if( ! $slider_maestroEnt ){
				echo json_encode(array("IsOk"=> false, "Msg"=> "Error DB al actualizar". print_r($slider_maestroEnt, true), "csrf" =>array(
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

		$this->load->model("SliderMaestro_Model", "mSliderMaestro");

		$pagConf =  $this->config->item("client_pagination");
		if($this->input->get("vNumPage")){
			$pagina = $this->input->get("vNumPage"); 
			$listaSliderMaestro = $this->mSliderMaestro->obtenerSliderMaestroPorCampo("Descripcion", $str, $pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );
		} else {
			$listaSliderMaestro = $this->mDispositivo->obtenerDispositivoPorCampo("Descripcion", $str, $pagConf["RowsPerPages"], 0);
		}
		$totalResult = 0; 
		if(count($listaSliderMaestro)> 0){
			$first = current($listaSliderMaestro); 	
			$totalResult = $first->CountRow;
		}
		echo json_encode(array("data" => $listaSliderMaestro, "totalResult"=> $totalResult, "count"=> count($listaSliderMaestro), "IsOk"=> true, "rowsPerPages" => $pagConf["RowsPerPages"], "IsSession" => true)); 
	}

	public function Eliminar(){
		// Eliminar a travez del post y del Get y validar la Session.
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		if($this->input->post("objeto")){			
			$this->load->model("SliderMaestro_Model", "mSliderMaestro");
			
			$slider_maestroObj = $this->security->xss_clean($this->input->post("objeto"));  
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