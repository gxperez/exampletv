<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class PlanConfig extends MY_Controller {

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

        $data['listOrganigramaTipo'] = $this->mEnum->getEnum("organigramatipo"); 

        

		// Carga de planilla web en general.
		$this->load->view("web/sm_plan_config", $data); 
	}


	public function Obtener(){
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		$idRol = 0; 
		$this->load->model("PlanConfig_Model", "mPlanConfig");
		$pagConf =  $this->config->item("client_pagination");

		if($this->input->get("vNumPage")){
			$pagina = $this->input->get("vNumPage");

			$listaPlanConfig = $this->mPlanConfig->obtenerPlanConfigPaginado($pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );

		} else {
			$listaPlanConfig = $this->mPlanConfig->obtenerPlanConfigPaginado($pagConf["RowsPerPages"], 0);
		}
		
		$totalResult = 0; 
		if(count($listaPlanConfig)> 0){
			$first = current($listaPlanConfig); 	
			$totalResult = $first->CountRow;
		}
		
		$first = current($listaPlanConfig); 
		echo json_encode(array("data" => $listaPlanConfig, "totalResult"=> $totalResult, "count"=> count($listaPlanConfig), "IsOk"=> true, "rowsPerPages"=> $pagConf["RowsPerPages"], "IsSession" => true)); 
	}

	public function Crear(){
	if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}
		$this->load->model("PlanConfig_Model", "mPlanConfig");
		// Auto Validacion del Formulario.

	$this->validation->set_rules("objeto[CalendarioID]", "CalendarioID", "integer"); 
$this->validation->set_rules("objeto[Titulo]", "Titulo", "max_length[500]"); 
$this->validation->set_rules("objeto[Vision]", "Vision", ""); 
$this->validation->set_rules("objeto[Mision]", "Mision", ""); 
$this->validation->set_rules("objeto[Estado]", "Estado", "integer"); 
$this->validation->set_rules("objeto[UsuarioCreaID]", "UsuarioCreaID", "integer"); 
$this->validation->set_rules("objeto[FechaCrea]", "FechaCrea", ""); 


	if ($this->validation->run() == FALSE)
         {
            echo json_encode(array("IsOk"=> false, "Msg"=> validation_errors(), "IsSession" => true, "csrf" =>array(
        "name" => $this->security->get_csrf_token_name(),
        "hash" => $this->security->get_csrf_hash()
        ) )  );
           	return false;
    }

    if($this->input->post("objeto")){
		$plan_configObj = $this->security->xss_clean($this->input->post("objeto"));

		$plan_configEnt = array('CalendarioID'=> $plan_configObj['CalendarioID'] 
, 'Titulo'=> $plan_configObj['Titulo'] 
, 'Vision'=> $plan_configObj['Vision'] 
, 'Mision'=> $plan_configObj['Mision'] 
, 'Estado'=> $plan_configObj['Estado'] 
, 'UsuarioCreaID'=> $plan_configObj['UsuarioCreaID'] 
, 'FechaCrea'=> date('Y-m-d H:i:s') 
);

			$id = $this->mPlanConfig->insertar( $plan_configEnt ); 
			$plan_configEnt["PlanConfigID"] = $id; 

			echo json_encode(array("data" => $plan_configEnt, "IsOk"=> true, "Msg"=>"Success", "IsSession" => true, "csrf" =>array(
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
		$this->load->model("PlanConfig_Model", "mPlanConfig");
	$this->validation->set_rules("objeto[CalendarioID]", "CalendarioID", "integer"); 
$this->validation->set_rules("objeto[Titulo]", "Titulo", "max_length[500]"); 
$this->validation->set_rules("objeto[Vision]", "Vision", ""); 
$this->validation->set_rules("objeto[Mision]", "Mision", ""); 
$this->validation->set_rules("objeto[Estado]", "Estado", "integer"); 
$this->validation->set_rules("objeto[UsuarioCreaID]", "UsuarioCreaID", "integer"); 
$this->validation->set_rules("objeto[FechaCrea]", "FechaCrea", ""); 

	if ($this->validation->run() == FALSE)
         {
            echo json_encode(array("IsOk"=> false, "Msg"=> validation_errors(), "IsSession" => true, "csrf" =>array(
        "name" => $this->security->get_csrf_token_name(),
        "hash" => $this->security->get_csrf_hash()
        ) )  );
           	return false;
    }

     if($this->input->post("objeto")){
		$plan_configObj = $this->security->xss_clean($this->input->post("objeto"));
		$plan_configEnt = $this->mPlanConfig->actualizar( $plan_configObj );

				if( ! $plan_configEnt ){
				echo json_encode(array("IsOk"=> false, "Msg"=> "Error DB al actualizar". print_r($plan_configEnt, true), "csrf" =>array(
		        "name" => $this->security->get_csrf_token_name(),
		        "hash" => $this->security->get_csrf_hash()
		        ), "IsSession" => true )  );
		        return false; 
			}

			echo json_encode(array("data" => $plan_configObj, "IsOk"=> true, "Msg"=>"Success", "IsSession" => true, "csrf" =>array(
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

		$this->load->model("PlanConfig_Model", "mPlanConfig");

		$pagConf =  $this->config->item("client_pagination");
		if($this->input->get("vNumPage")){
			$pagina = $this->input->get("vNumPage"); 
			$listaPlanConfig = $this->mPlanConfig->obtenerPlanConfigPorCampo("Descripcion", $str, $pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );
		} else {
			$listaPlanConfig = $this->mPlanConfig->obtenerPlanConfigPorCampo("Descripcion", $str, $pagConf["RowsPerPages"], 0);
		}
		$totalResult = 0; 
		if(count($listaPlanConfig)> 0){
			$first = current($listaPlanConfig); 	
			$totalResult = $first->CountRow;
		}
		echo json_encode(array("data" => $listaPlanConfig, "totalResult"=> $totalResult, "count"=> count($listaPlanConfig), "IsOk"=> true, "rowsPerPages" => $pagConf["RowsPerPages"], "IsSession" => true)); 
	}

	public function Eliminar(){
		// Eliminar a travez del post y del Get y validar la Session.
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		if($this->input->post("objeto")){			
			$this->load->model("PlanConfig_Model", "mPlanConfig");
			
			$plan_configObj = $this->security->xss_clean($this->input->post("objeto"));  
			$result = $this->mPlanConfig->cambiarEstado($plan_configObj, -1); 

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