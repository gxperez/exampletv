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

	public function Prueba(){
		$this->load->model("bloqueContenido_Model", "BloquesCont");
		$row = array();

		$row['BloqueContenidoID'] = 4;
		$row['BloqueID'] = 2;
		$row['ContenidoID'] = 1;
		$row['Estado'] = 0;
		$row['UsuarioModificaID'] = 1;
		$row['FechaModifica'] = date("Y-m-d H:i:s"); 

	//	 $this->BloquesCont->insertar($row); 
 // echo $this->BloquesCont->obtenerBloqueContenidoJson();
		 print_r( $this->BloquesCont->actualizar($row) );


	}


	public function GenerarFiles($modo){

		$this->load->model("genera_model", "Gen");
		$vv = $this->Gen->obtenerTablas("bis_gestionvista"); 

		switch ($modo) {
			case 'modelo':
			
			$htmlT = ""; 
			foreach ($vv as $key => $value) {
				$htmlT .= "<hr> // Siguiente..  <br>". $this->Gen->formarModel("", $key, $value); 
			}

			echo "<hr> <pre> "; 
				echo $htmlT;
			echo "</pre> <hr> <br>"; 
				break;

			case 'Controlador':				
				break;

			case 'Entidad':

			$htmlT= ""; 

				foreach ($vv as $key => $value) {
					$htmlT .= "<hr> <pre> ". $this->Gen->generarEntidad("", $key, $value); 

					echo $htmlT;

					exit();
				}
				
				break;

			
			default:
			// Vista
				
				break;
		}

	}

	public function CrearQuerys(){
		for ($i=161; $i < 310 ; $i++) { 


		$query = "INSERT INTO `bis_gestionvista`.`dispositivo` (`Nombre`, `Descripcion`, `DispositivoTipo`, `Marca`, `Estatus`, `Mac`, `IP`, `FechaCrea`, `UltimaSesion`) VALUES ('TV-SONY-smart-gen{$i}', 'smartTV00-{$i}', '1', 'SONY', '1', '52:camre:25:tu:85no:am:{$i}', '22.35.0.{$i}', '2016-06-05', '2016-06-05');"; 

		echo "<br> -- tt <br> ". $query; 

		}



	}

}
