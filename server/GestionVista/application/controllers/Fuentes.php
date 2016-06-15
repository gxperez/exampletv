<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Fuentes extends MY_Controller {

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
        	$data['listRepresentacionTipo'] =  $this->mEnum->getEnum("representaciontipo");
        	$data['listFuenteTipo'] =  $this->mEnum->getEnum("fuentetipo");        	

		// Carga de planilla web en general.
		$this->load->view("web/sm_fuentes", $data); 
	}


	public function Obtener(){
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		$idRol = 0; 
		$this->load->model("Fuentes_Model", "mFuentes");
		$pagConf =  $this->config->item("client_pagination");

		if($this->input->get("vNumPage")){
			$pagina = $this->input->get("vNumPage");

			$listaFuentes = $this->mFuentes->obtenerFuentesPaginado($pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );

		} else {
			$listaFuentes = $this->mFuentes->obtenerFuentesPaginado($pagConf["RowsPerPages"], 0);
		}
		
		$totalResult = 0; 
		if(count($listaFuentes)> 0){
			$first = current($listaFuentes); 	
			$totalResult = $first->CountRow;
		}
		
		$first = current($listaFuentes); 
		echo json_encode(array("data" => $listaFuentes, "totalResult"=> $totalResult, "count"=> count($listaFuentes), "IsOk"=> true, "rowsPerPages"=> $pagConf["RowsPerPages"], "IsSession" => true)); 
	}

	public function Crear(){
	if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}
		$this->load->model("Fuentes_Model", "mFuentes");
		// Auto Validacion del Formulario.

	$this->validation->set_rules("objeto[FuenteTipo]", "FuenteTipo", "required|integer"); 
$this->validation->set_rules("objeto[FuenteTipoID]", "FuenteTipoID", "required|integer"); 
$this->validation->set_rules("objeto[RepresentacionTipo]", "RepresentacionTipo", "required|integer"); 
$this->validation->set_rules("objeto[Descripcion]", "Descripcion", "max_length[45]"); 
$this->validation->set_rules("objeto[Url]", "Url", "required|max_length[500]"); 
$this->validation->set_rules("objeto[GuidRelacionalJson]", "GuidRelacionalJson", "required|max_length[50]"); 
$this->validation->set_rules("objeto[ContentByID]", "ContentByID", "required|max_length[100]"); 
$this->validation->set_rules("objeto[ContenidoTexto]", "ContenidoTexto", "required"); 
$this->validation->set_rules("objeto[EsManual]", "EsManual", "required"); 
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
		$fuentesObj = $this->security->xss_clean($this->input->post("objeto"));

		$fuentesEnt = array('FuenteTipo'=> $fuentesObj['FuenteTipo'] 
, 'FuenteTipoID'=> $fuentesObj['FuenteTipoID'] 
, 'RepresentacionTipo'=> $fuentesObj['RepresentacionTipo'] 
, 'Descripcion'=> $fuentesObj['Descripcion'] 
, 'Url'=> $fuentesObj['Url'] 
, 'GuidRelacionalJson'=> $fuentesObj['GuidRelacionalJson'] 
, 'ContentByID'=> $fuentesObj['ContentByID'] 
, 'ContenidoTexto'=> $fuentesObj['ContenidoTexto'] 
, 'EsManual'=> $fuentesObj['EsManual'] 
, 'Estado'=> $fuentesObj['Estado'] 
, 'UsuarioModificaID'=> $fuentesObj['UsuarioModificaID'] 
, 'FechaModifica'=> date('Y-m-d H:i:s') 
);

			$id = $this->mFuentes->insertar( $fuentesEnt ); 
			$fuentesEnt["FuenteID"] = $id; 

			echo json_encode(array("data" => $fuentesEnt, "IsOk"=> true, "Msg"=>"Success", "IsSession" => true, "csrf" =>array(
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
		$this->load->model("Fuentes_Model", "mFuentes");
	$this->validation->set_rules("objeto[FuenteTipo]", "FuenteTipo", "required|integer"); 
$this->validation->set_rules("objeto[FuenteTipoID]", "FuenteTipoID", "required|integer"); 
$this->validation->set_rules("objeto[RepresentacionTipo]", "RepresentacionTipo", "required|integer"); 
$this->validation->set_rules("objeto[Descripcion]", "Descripcion", "max_length[45]"); 
$this->validation->set_rules("objeto[Url]", "Url", "required|max_length[500]"); 
$this->validation->set_rules("objeto[GuidRelacionalJson]", "GuidRelacionalJson", "required|max_length[50]"); 
$this->validation->set_rules("objeto[ContentByID]", "ContentByID", "required|max_length[100]"); 
$this->validation->set_rules("objeto[ContenidoTexto]", "ContenidoTexto", "required"); 
$this->validation->set_rules("objeto[EsManual]", "EsManual", "required"); 
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
		$fuentesObj = $this->security->xss_clean($this->input->post("objeto"));
		$fuentesEnt = $this->mFuentes->actualizar( $fuentesObj );

				if( ! $fuentesEnt ){
				echo json_encode(array("IsOk"=> false, "Msg"=> "Error DB al actualizar". print_r($fuentesEnt, true), "csrf" =>array(
		        "name" => $this->security->get_csrf_token_name(),
		        "hash" => $this->security->get_csrf_hash()
		        ), "IsSession" => true )  );
		        return false; 
			}

			echo json_encode(array("data" => $fuentesObj, "IsOk"=> true, "Msg"=>"Success", "IsSession" => true, "csrf" =>array(
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

		$this->load->model("Fuentes_Model", "mFuentes");

		$pagConf =  $this->config->item("client_pagination");
		if($this->input->get("vNumPage")){
			$pagina = $this->input->get("vNumPage"); 
			$listaFuentes = $this->mFuentes->obtenerFuentesPorCampo("Descripcion", $str, $pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );
		} else {
			$listaFuentes = $this->mFuentes->obtenerFuentesPorCampo("Descripcion", $str, $pagConf["RowsPerPages"], 0);
		}
		$totalResult = 0; 
		if(count($listaFuentes)> 0){
			$first = current($listaFuentes); 	
			$totalResult = $first->CountRow;
		}
		echo json_encode(array("data" => $listaFuentes, "totalResult"=> $totalResult, "count"=> count($listaFuentes), "IsOk"=> true, "rowsPerPages" => $pagConf["RowsPerPages"], "IsSession" => true)); 
	}

	public function Eliminar(){
		// Eliminar a travez del post y del Get y validar la Session.
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		if($this->input->post("objeto")){			
			$this->load->model("Fuentes_Model", "mFuentes");
			
			$fuentesObj = $this->security->xss_clean($this->input->post("objeto"));  
			$result = $this->mFuentes->cambiarEstado($fuentesObj, -1); 

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