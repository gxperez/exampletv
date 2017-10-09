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
         $data['menus'] = $this->renderMenu( 1 ); //  $this->Portal_Model->generarMenu($idRol);

         $data['page'] = $this->Portal_model->getPages();
		$this->load->view('template/master', $data);
	}

	public function HomePages(){
		if (!$this->session->userdata('sUsuario')){
			redirect('/portal/login', 'refresh');			
			return false; 
		} 

		$data = array();

//	 $this->load->model('Calendario_Model', 'mCal');
// 	 $this->load->model('Reporte_Model', "mReport");
//   $this->load->model("DirectivaOrganigrama_Model", "mDOr");
// 	 $data['calendario'] = $this->mCal->ObtenerActivo();      
		$data['esZona'] = false; 
		$data['esACD'] = false; 										
		$data['esClub'] = true; 
		// El portal del Home Pages.

		// echo "Este es el Home de DashBoar de Gestion a la Vista, para entregar."; 
		
		$this->load->view('web/sm_home_dashboard', $data);



	}

	public function login() {		
		$myServices = $this->config->item("web_services_key"); 
		$data = array('registro' => "Registro", "mensaje"=> "", "services" => $myServices["auth"],
			"csrf" =>array(
        			'name' => $this->security->get_csrf_token_name(),
        			'hash' => $this->security->get_csrf_hash()));

		// Redireccion al Sistema del Bis.
		$data["isIntegracionLog"] = true;

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


	public function GenerarFiles($modo, $fal = false){
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
			case 'controlador':

			$htmlT = " <pre> "; 
			foreach ($vv as $key => $value) {
					echo "<hr> <pre> "; 
					$htmlT = $this->Gen->formarController("", $key, $value);

					echo $htmlT;
					echo "</pre> <hr> <br>"; 					

				}
				break;
			case 'Entidad':
			$htmlT= ""; 
				foreach ($vv as $key => $value) {
					$htmlT .= "<hr> <pre> ". $this->Gen->generarEntidad("", $key, $value); 
					echo $htmlT;
					exit();
				}
				break;
			case 'Rule':
			$htmlT= ""; 		
				foreach ($vv as $key => $value) {
					$htmlT = "<br> $key <hr>  <pre> ". $this->Gen->generarRules("objeto", $key, $value); 
					echo $htmlT;					
				}
				break;/*
				$this->form_validation->set_rules('username', 'Username', 'callback_username_check');
                */
              case 'asignateArray':
			$htmlT= ""; 		
				foreach ($vv as $key => $value) {
					$htmlT = "<br><hr>  <pre> ". $this->Gen->generarAsignate("objeto", $key, $value, $fal); 
					echo $htmlT;					
				}
				break;

			case 'vista':

			$htmlT = " <pre> "; 
			foreach ($vv as $key => $value) {
					echo "<hr> <pre> "; 
					$htmlT = $this->Gen->formarVista("", $key, $value);
					
					echo $htmlT;
					echo "</pre> <hr> <br>"; 					
				}			
				break;
				case "js":
				$htmlT = " <pre> "; 
			foreach ($vv as $key => $value) {
					echo "<hr> <pre> "; 
					$htmlT = $this->Gen->formarJSController("", $key, $value);					
					echo $htmlT;
					echo "</pre> <hr> <br>"; 					
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

	public function express(){		
		$this->load->model("bloqueContenido_Model", "BloquesCont");
		$row = array();
		$row['BloqueContenidoID'] = 4;
		$row['BloqueID'] = 2;
		$row['ContenidoID'] = 1;
		$row['Estado'] = 0;
		$row['UsuarioModificaID'] = 1;
		$row['FechaModifica'] = date("Y-m-d H:i:s"); 
		 print_r( $this->BloquesCont->actualizar($row) );
	}


	public function renderMenu($idRol){
		$listaMenu = array();
		// Seguridad 
		$listaMenu[] = array("Padre"=>"Seguridad", "icon"=> "fa fa-cogs",  "Nombre"=>"Usuarios", "attr"=>"ng-click=\"SetMain('usuariologsesion/sm')\"",  "Rol"=> array( 1 ) );


		$listaMenu[] = array("Padre"=>"Administrar Información", "icon"=> "fa fa-tasks",  "Nombre"=>"Slide de Contenidos", "attr"=>"ng-click=\"SetMain('contenido/sm')\"",  "Rol"=> array( 1 ) );
		

		$listaMenu[] = array("Padre"=>"Administrar Información", "icon"=> "fa fa-tasks",  "Nombre"=>"Fuentes", "attr"=>"ng-click=\"SetMain('fuentes/sm')\"",  "Rol"=> array( 1, 8 ) );

		$listaMenu[] = array("Padre"=>"Administrar Información", "icon"=> "fa fa-tasks",  "Nombre"=>"Calendario Bloques", "attr"=>"ng-click=\"SetMain('bloques/master')\"",  "Rol"=> array( 1, 8 ) );
		
		$listaMenu[] = array("Padre"=>"Administrar Información", "icon"=> "fa fa-tasks",  "Nombre"=>"Programación", "attr"=>"ng-click=\"SetMain('programacion/sm')\"",  "Rol"=> array( 1, 8 ) );

		$listaMenu[] = array("Padre"=>"Administrar Información", "icon"=> "fa fa-tasks",  "Nombre"=>"Asginar Grupos", "attr"=>"ng-click=\"SetMain('grupotv/sm')\"",  "Rol"=> array( 1, 8 ) );

		$listaMenu[] = array("Padre"=>"Estructuras Externas", "icon"=> "fa fa-tasks",  "Nombre"=>"Fuerza de Venta TV", "attr"=>"ng-click=\"SetMain('fuerzaventa/master')\"",  "Rol"=> array( 1, 8 ) );		

		$listaMenu[] = array("Padre"=>"Estructuras Externas", "icon"=> "fa fa-tasks",  "Nombre"=>"Dipositivo TV- FV", "attr"=>"ng-click=\"SetMain('fuerzaventadispositivo/master')\"",  "Rol"=> array( 1, 8 ) );
		

		$listaMenu[] = array("Padre"=>"Reportes", "icon"=> "fa fa-bar-chart-o",  "Nombre"=>"Chartjs", "attr"=>"href=\"#\"",  "Rol"=> array( 1 ));		

		$listaMenu[] = array("Padre"=>"Reportes", "icon"=> "fa fa-bar-chart-o",  "Nombre"=>"Desempeño Planificación.", "attr"=>"ng-click=\"SetMain('ReporteController/sm/DesempenoPlanificacion')\"",  "Rol"=> array( 1, 10, 11) );
				

		$listaMenu[] = array("Padre"=>"Configuración", "icon"=> "fa fa-tasks",  "Nombre"=>"Grupo", "attr"=>"ng-click=\"SetMain('grupo/sm')\"",  "Rol"=> array( 1, 8 ) );

		$listaMenu[] = array("Padre"=>"Configuración", "icon"=> "fa fa-cogs",  "Nombre"=>"Mantenimiento F.V.", "attr"=>"ng-click=\"SetMain('fuerzaventa/sm')\"",  "Rol"=> array( 1, 8 ) );

		$listaMenu[] = array("Padre"=>"Configuración", "icon"=> "fa fa-tasks",  "Nombre"=>"Bloque", "attr"=>"ng-click=\"SetMain('bloques/sm')\"",  "Rol"=> array( 1, 8 ) );

		$listaMenu[] = array("Padre"=>"Configuración", "icon"=> "fa fa-tasks",  "Nombre"=>"Dispositivo", "attr"=>"ng-click=\"SetMain('dispositivo/sm')\"",  "Rol"=> array( 1, 8 ) );		

		$listaMenu[] = array("Padre"=>"Configuración", "icon"=> "fa fa-tasks",  "Nombre"=>"En Linea", "attr"=>"ng-click=\"SetMain('dispositivolog/online')\"",  "Rol"=> array( 1, 8 ) );				

		$listaMenu[] = array("Padre"=>"BroadCast", "icon"=> "fa fa-rss",  "Nombre"=>"TV Conected.", "attr"=>"ng-click=\"SetMain('dispositivolog/online')\"",  "Rol"=> array( 1, 8 ) );				
				
		
		$renderHtml =  array();
		foreach ($listaMenu as $key => $value) {
			if(in_array($idRol, $value["Rol"]) ){
				if(!array_key_exists($value["Padre"], $renderHtml)){
					$renderHtml[$value["Padre"]] = array(
						'header' => '<a href="javascript:;" ><i class="'. $value["icon"] . '"></i><span>'. $value["Padre"] . '</span></a>',
					 "content" => ""); //  $value
				}
				$renderHtml[$value["Padre"]]["content"] .= "<li><a {$value["attr"]} >{$value["Nombre"]}</a></li>";	
			} 
		}
		// Recorrido Final para devolver
		$ab = "";
		foreach ($renderHtml as $key => $html) {
			$ab .= "<li class=\"sub-menu\">
                      {$html['header']}                    
                      <ul class=\"sub\">
						{$html['content']}
                      </ul>
                  </li>"; 		
		}
		return $ab; 
	}

}
