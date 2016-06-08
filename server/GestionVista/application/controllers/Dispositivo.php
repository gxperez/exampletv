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
		echo json_encode(array('data' => $listaDispositivo, 'totalResult'=> $first->CountRow, "count"=> count($listaDispositivo), 'IsOk'=> true, 'rowsPerPages'=> $pagConf["RowsPerPages"])); 
	}

	public function Crear(){		
		$this->load->model('Dispositivo_model', 'mDispositivo');
		// Auto Validacion del Formulario.		

		$this->validation->set_rules("objeto[Nombre]", "Nombre", "required|max_length[50]"); 
		$this->validation->set_rules("objeto[Descripcion]", "Descripcion", "max_length[50]"); 
		$this->validation->set_rules("objeto[DispositivoTipo]", "DispositivoTipo", "integer"); 
		$this->validation->set_rules("objeto[Marca]", "Marca", "max_length[45]"); 
		$this->validation->set_rules("objeto[Estatus]", "Estatus", "integer"); 
		$this->validation->set_rules("objeto[Mac]", "Mac", "required|max_length[45]"); 
		$this->validation->set_rules("objeto[IP]", "IP", "max_length[45]"); 
		

		    if ($this->validation->run() == FALSE)
                {
                	echo "Hay Error en el Formulario"; 
                	echo validation_errors();

                } else {
                	echo "TOdo Ok YESS"; 
                }

exit(); 

		if($this->input->post("objeto")){

			 

			$dispositivoObj = $this->input->post("objeto");
			$dispositivoObj = $this->security->xss_clean($dispositivoObj);



// Validar los campos que viajan y limpiar los que no son necesarios.


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