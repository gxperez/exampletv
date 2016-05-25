<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Portal extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$idRol = 0; 
		 $this->load->model('Portal_model');

         $data['menus'] = $this->Portal_model->generarMenu($idRol);
         $data['usuario'] = array('UsuarioID' => 1, 'Usuario'=> 'ClubAdmin', 'Nombre'=> 'Administrador', 'NombreCompleto'=>'Admin Admin' ,
         	'rolDefault' => "Admin" );
         $data['listaRol'] =  array('1' => "Admin" , 2=> "SuperAdmin");
         $data['page'] = $this->Portal_model->getPages();
		$this->load->view('template/master', $data);

	}

	public function login() {		

		$data = array('registro' => "Registro", "mensaje"=> "" );

		if (!$this->session->userdata('sUsuario')){

			// if($pos)  // Sui existen la variables post
			if ( isset($_POST["login"]) ) {

				$credenciales = $_POST["login"]; 
				$this->load->model("usuario_model", "modeloUsuario");

				$nombreUsuario = trim($credenciales["usuario"]);
				$clave = trim($credenciales["clave"]);
				$resultadoUsuario = $this->modeloUsuario->validarUsaurio($nombreUsuario, $clave); 


			if($resultadoUsuario["resultado"]){					

				

				
				
					$this->session->set_userdata('sUsuario', array("IDusuario" => $resultadoUsuario["registro"]->UsuarioID,
					"nombre_usuario" => $resultadoUsuario["registro"]->NombreUsuario,
					"clave" => $resultadoUsuario["registro"]->Clave,
					"nombre" => $resultadoUsuario["registro"]->Nombre,
					"apellido" => $resultadoUsuario["registro"]->Apellido,
					'correo' => $resultadoUsuario["registro"]->Correo
					 ));

					redirect('/portal/index', 'refresh');
					exit(); 

			} else {
				$data["mensaje"] = '<div style="color: black;  font-weight: bold; background-color: pink;"> Nombre de usuario o clave incorrecta</div>';


			}


			}


			
			$this->load->view('template/login', $data);
			return false;
		} else {
			redirect('/portal/index', 'refresh');
		}


	}

	public function cerrarSession(){
					// Destroy
	$this->session->sess_destroy();					
	redirect('/portal/login/', 'refresh');
	}


	public function confirmacionService()
	{	

		if ($this->session->userdata('sUsuario')){

			echo "SESSION"; 

		} else {

			echo "SIN SESSION"; 

		}
		$this->load->model("usuario_model", "modeloUsuario");





		echo "COntroler";

	}

}
