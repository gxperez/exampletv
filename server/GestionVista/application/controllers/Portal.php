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
		if (!$this->session->userdata('sUsuario')){
			redirect('/portal/login', 'refresh');			
			return false; 
		} 

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
		$myServices = $this->config->item("web_services_key"); 
			

		$data = array('registro' => "Registro", "mensaje"=> "", "services" => $myServices["auth"]);

		if (!$this->session->userdata('sUsuario')){

			// if($pos)  // Sui existen la variables post
			if ( isset($_POST["login"]) ) {

				$credenciales = $_POST["login"]; 
				$this->load->model("usuario_model", "modeloUsuario");

				$nombreUsuario = trim($credenciales["userName"]);
				$clave = trim($credenciales["userPw"]);
				$keyS = trim($credenciales["Data"]);
				$dispositivo = trim($credenciales["dispositivo"]);


				$resultadoUsuario = $this->modeloUsuario->validarUsuario($nombreUsuario, $clave, $keyS, $dispositivo ); 

				


			if($resultadoUsuario["resultado"]){
				
					$this->session->set_userdata('sUsuario', array("IDusuario" => $resultadoUsuario["registro"]->usuario_log_sesionID,
					"nombre_usuario" => $resultadoUsuario["registro"]->nombreUsuario,					
					"GUID" =>$resultadoUsuario["registro"]->GUID
					 ));

//					redirect('/portal/index', 'refresh');
					echo json_encode(array("data"=> true)); 
					exit(); 

			} else {
				echo json_encode(array("data"=> false));
				// $data["mensaje"] = '<div style="color: black;  font-weight: bold; background-color: pink;"> Nombre de usuario o clave incorrecta</div>';
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


	public function GenerarFiles($modo){

		$this->load->model("genera_model", "Gen");

		switch ($modo) {
			case 'modelo':

			$vv = $this->Gen->obtenerTablas("bis_gestionvista"); 
			echo "<pre>"; 

			$htmlT = ""; 
			foreach ($vv as $key => $value) {
				$htmlT .= $this->Gen->formarModel("", $key, $value); 

			//	echo $htmlT;

			//	exit(); 				

			}

			print_r($vv); 
				
				break;

			case 'Controlador':
				
				break;

			
			default:
			// Vista
				
				break;
		}

	}

}
