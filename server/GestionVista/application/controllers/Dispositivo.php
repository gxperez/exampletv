<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dispositivo extends MY_Controller {



	/**
	 * Index Page for this controller.
	 	@autor:	Grequis Xavier Perez Fortuna.
	 */
	public function index()
	{
		if (!$this->session->userdata('sUsuario')){
			redirect('/portal/login', 'refresh');			
			return false; 
		}
		$this->load->view("web/sm_dispositivo", $data); 
	}


	public function sm()
	{
		if (!$this->session->userdata('sUsuario')){
			redirect('/portal/login', 'refresh');			
			return false; 
		}

		$data = array("csrf" =>array(
        'name' => $this->security->get_csrf_token_name(),
        'hash' => $this->security->get_csrf_hash()
        ) );

		// Carga de planilla web en general.		

		$this->load->view("web/sm_dispositivo", $data); 

	}



	public function Obtener(){
		if (!$this->session->userdata('sUsuario')){
			echo json_encode(array('IsSession' => false)); 
			return false; 
		}

		$idRol = 0; 
		$this->load->model('Dispositivo_model', 'mDispositivo');

		$pagConf =  $this->config->item("client_pagination");

		if($this->input->get('vNumPage')){
			$pagina = $this->input->get('vNumPage'); 

			$listaDispositivo = $this->mDispositivo->obtenerDispositivoPaginado($pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );

		} else {
			$listaDispositivo = $this->mDispositivo->obtenerDispositivoPaginado($pagConf["RowsPerPages"], 0);
		}

		
		$first = current($listaDispositivo); 
		echo json_encode(array('data' => $listaDispositivo, 'totalResult'=> $first->CountRow, "count"=> count($listaDispositivo), 'IsOk'=> true, 'rowsPerPages'=> $pagConf["RowsPerPages"], 'IsSession' => true)); 
	}

	public function Crear(){

	if (!$this->session->userdata('sUsuario')){
			echo json_encode(array('IsSession' => false)); 
			return false; 
		}		



		$this->load->model('Dispositivo_model', 'mDispositivo');
		// Auto Validacion del Formulario.						

	 	$this->validation->set_rules("objeto[Nombre]", "Nombre", "required|max_length[50]"); 
		$this->validation->set_rules("objeto[Descripcion]", "Descripcion", "max_length[50]"); 
		$this->validation->set_rules("objeto[DispositivoTipo]", "DispositivoTipo", "integer"); 
		$this->validation->set_rules("objeto[Marca]", "Marca", "max_length[45]"); 
		$this->validation->set_rules("objeto[Estatus]", "Estatus", "integer"); 
		$this->validation->set_rules("objeto[Mac]", "Mac", "required|trim|callback_validarMac|max_length[45]"); 
		$this->validation->set_rules("objeto[IP]", "IP", "max_length[45]");



		if ($this->validation->run() == FALSE)
         {
            echo json_encode(array("IsOk"=> false, "Msg"=> validation_errors(), 'IsSession' => true, "csrf" =>array(
        'name' => $this->security->get_csrf_token_name(),
        'hash' => $this->security->get_csrf_hash()
        ) )  );
           	return false;
         }


		if($this->input->post("objeto")){

		$dispositivoObj = $this->security->xss_clean($this->input->post("objeto"));

		
		 $dispositivoEnt = array('Nombre'=> $dispositivoObj['Nombre'] 
								, 'Descripcion'=> $dispositivoObj['Descripcion'] 
								, 'DispositivoTipo'=> $dispositivoObj['DispositivoTipo'] 
								, 'Marca'=> $dispositivoObj['Marca'] 
								, 'Estatus'=> $dispositivoObj['Estatus'] 
								, 'Mac'=> $dispositivoObj['Mac'] 								
								);

			$id = $this->mDispositivo->insertar( $dispositivoEnt ); 
			$dispositivoEnt['DispositivoID'] = $id; 

			echo json_encode(array('data' => $dispositivoEnt, 'IsOk'=> true, "Msg"=>"Success", 'IsSession' => true, "csrf" =>array(
        'name' => $this->security->get_csrf_token_name(),
        'hash' => $this->security->get_csrf_hash()
        ) )); 

		} else {
			echo  json_encode(array("IsOk"=> false, 'IsSession' => true) );
			return false;
		}
	}

	public function Actualizar(){

		if (!$this->session->userdata('sUsuario')){
			echo json_encode(array('IsSession' => false)); 
			return false; 
		}		


		$this->load->model('Dispositivo_model', 'mDispositivo');
		$this->validation->set_rules("objeto[Nombre]", "Nombre", "required|max_length[50]"); 
		$this->validation->set_rules("objeto[Descripcion]", "Descripcion", "max_length[50]"); 
		$this->validation->set_rules("objeto[DispositivoTipo]", "DispositivoTipo", "integer"); 
		$this->validation->set_rules("objeto[Marca]", "Marca", "max_length[45]"); 
		$this->validation->set_rules("objeto[Estatus]", "Estatus", "integer"); 

			if ($this->validation->run() == FALSE)
         {
            echo json_encode(array("IsOk"=> false, "Msg"=> validation_errors(), 'IsSession' => true, "csrf" =>array(
		        'name' => $this->security->get_csrf_token_name(),
		        'hash' => $this->security->get_csrf_hash()
		        ) )  );
           	return false;
         }

        if($this->input->post("objeto")){
        	$dispositivoObj = $this->security->xss_clean($this->input->post("objeto"));        	
			$dispositivoEnt = $this->mDispositivo->actualizar( $dispositivoObj);

			if( ! $dispositivoEnt ){
				echo json_encode(array("IsOk"=> false, "Msg"=> "Error DB al actualizar". print_r($dispositivoEnt, true), "csrf" =>array(
		        'name' => $this->security->get_csrf_token_name(),
		        'hash' => $this->security->get_csrf_hash()
		        ), 'IsSession' => true )  );
		        return false; 
			} 

			echo json_encode(array('data' => $dispositivoObj, 'IsOk'=> true, "Msg"=>"Success", 'IsSession' => true, "csrf" =>array(
	        'name' => $this->security->get_csrf_token_name(),
	        'hash' => $this->security->get_csrf_hash()
	        ) )); 
		}
	}


	public function validarMac($str){
		$this->load->model('Dispositivo_model', 'mDispositivo');
		$this->form_validation->set_message('validarMac', 'La {field} ya existe en el sistema.');
		return $this->mDispositivo->existeValorCampo("dispositivo.Mac", $str);		                     
	}

	public function Buscar($str = null){

		if (!$this->session->userdata('sUsuario')){
			echo json_encode(array('IsSession' => false)); 
			return false; 
		}		

		if(!isset($str)) {		
			return false; 
		}


		$this->load->model('Dispositivo_model', 'mDispositivo');
		$pagConf =  $this->config->item("client_pagination");
		if($this->input->get('vNumPage')){
			$pagina = $this->input->get('vNumPage'); 
			$listaDispositivo = $this->mDispositivo->obtenerDispositivoPorCampo("Descripcion", $str, $pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );
		} else {
			$listaDispositivo = $this->mDispositivo->obtenerDispositivoPorCampo("Descripcion", $str, $pagConf["RowsPerPages"], 0);
		}
		$totalResult = 0; 
		if(count($listaDispositivo)> 0){
			$first = current($listaDispositivo); 	
			$totalResult = $first->CountRow;
		}
		echo json_encode(array('data' => $listaDispositivo, 'totalResult'=> $totalResult, "count"=> count($listaDispositivo), 'IsOk'=> true, 'rowsPerPages'=> $pagConf["RowsPerPages"])); 
	}

	public function Eliminar(){
		// Eliminar a travez del post y del Get y validar la Session.
		if (!$this->session->userdata('sUsuario')){
			echo json_encode(array('IsSession' => false)); 
			
			return false; 
		}		




	}
}