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
		$idRol = 0; 
		 $this->load->model('Dispositivo_model');		

	}

	public function Obtener(){
		$idRol = 0; 
		$this->load->model('Dispositivo_model', 'mDispositivo');

		$listaDispositivo = $this->mDispositivo->obtenerDispositivo(); 
		echo json_encode(array('data' => $listaDispositivo, 'IsOk'=> true)); 
	}

	public function Crear(){
		$idRol = 0; 
		$this->load->model('Dispositivo_model', 'mDispositivo');
		if($this->input->post("objeto")){

			$dispositivoObj = $this->input->post("objeto"); 
			$id = $this->mDispositivo->insertar($dispositivoObj); 
			$dispositivoObj['DispositivoID'] = $id; 

			echo json_encode(array('data' => $dispositivoObj, 'IsOk'=> true)); 


		} else {
			return json_encode(array("IsOk"=> false) );
		}

	}

	public function Actualizar(){
		$idRol = 0; 
		$this->load->model('Dispositivo_model', 'mDispositivo');

		$listaDispositivo = $this->mDispositivo->obtenerDispositivo(); 
		echo json_encode(array('data' => $listaDispositivo, 'IsOk'=> true)); 
	}

	public function Eliminar(){

	}
}