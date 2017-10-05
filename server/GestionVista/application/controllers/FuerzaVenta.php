<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class FuerzaVenta extends MY_Controller {

	/**
	 * Index Page for this controller.
	 	@autor:	Grequis Xavier Perez Fortuna.
	 */
	public function index()
	{
		// Consumo del Web Services. WebServices Link; 
		if (!$this->session->userdata("sUsuario")){
			redirect("/portal/login", "refresh");			
			return false; 
		}
		$this->load->model("FuerzaVenta_Model", "mFuerzaVenta");
		// Consume la Fuerza De Venta Por Bloque.		
		// $json_file = file_get_contents('http://cnddosdobis:8090/WebServices/api.asmx/ObtenerFuerzaVenta');
		$json_file = file_get_contents('http://localhost:52999/api.asmx/ObtenerFuerzaVentaImagen');

		$jFV = json_decode($json_file);
		 $rest = $this->mFuerzaVenta->tabularNodos($jFV, 1, date("y-m-d h:i:s")); 

		 print_r($rest); 
		 exit(); 
		$this->mFuerzaVenta->activarNuevaFuerzaVenta($jFV, 1); 
		exit();
	}

	public function ObtenerMaestro() {

		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}				
		
		$this->load->model("FuerzaVenta_Model", "mFuerzaVenta");
		$listaFuerzaVenta = $this->mFuerzaVenta->obtenerFuerzaVentaResumenActivo();
		echo json_encode(array("data" => $listaFuerzaVenta, "IsOk"=> true, "IsSession" => true)); 

	}

		public function SincronizarWebServices(){
			if (!$this->session->userdata("sUsuario")){
					echo json_encode(array("IsSession" => false)); 
					return false; 
			}
		// Sincronizar.
		if($this->input->post("sincronizar")){			
			// Obtencion del JSON.			
//			$json_file = file_get_contents('http://cnddosdobis:8090/WebServices/api.asmx/ObtenerFuerzaVentaImagen');
			$json_file = file_get_contents('http://10.234.51.69:8090/WebServices/api.asmx/ObtenerFuerzaVentaImagen');
			$jFV = json_decode($json_file);
			$this->load->model("FuerzaVenta_Model", "mFuerzaVenta");
			// Actualizar La Base de datos.. Eliminar todas las Fuerzas de Ventas.
			if ($this->mFuerzaVenta->desactivarFuerzaVenta()){				
				$usuario = $this->session->userdata("sUsuario");				
				if($this->mFuerzaVenta->activarNuevaFuerzaVenta($jFV, $usuario['IDusuario']))
				{
					
				echo json_encode(array("data"=> $this->mFuerzaVenta->obtenerFuerzaVentaResumenActivo(), "IsOk"=> true, "IsSession" => true, "csrf" =>array(
	        "name" => $this->security->get_csrf_token_name(),
	        "hash" => $this->security->get_csrf_hash()
        	)
         )); 
				return true; 

				} else {

								echo json_encode(array("IsOk"=> false, "IsSession" => true, "csrf" =>array(
			        "name" => $this->security->get_csrf_token_name(),
			        "hash" => $this->security->get_csrf_hash()
			        )
			         )); 

								return flase; 

				}

				// Maxima cantidad de String  Validar la Maxima Cantidad de String.

			}






		}



	}


	public function master(){

		if (!$this->session->userdata("sUsuario")){
			redirect("/portal/login", "refresh");			
			return false; 
		}

		$data = array("csrf" =>array(
        "name" => $this->security->get_csrf_token_name(),
        "hash" => $this->security->get_csrf_hash()
        ) );

		$data["pantalla"] = ""; 
		$this->load->view("web/view_fuerzaventa", $data); 
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
        	

		// Carga de planilla web en general.
		$this->load->view("web/sm_fuerza_venta", $data); 
	}


	public function Obtener(){
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		$idRol = 0; 
		$this->load->model("FuerzaVenta_Model", "mFuerzaVenta");
		$pagConf =  $this->config->item("client_pagination");

		if($this->input->get("vNumPage")){
			$pagina = $this->input->get("vNumPage");

			$listaFuerzaVenta = $this->mFuerzaVenta->obtenerFuerzaVentaPaginado($pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );

		} else {
			$listaFuerzaVenta = $this->mFuerzaVenta->obtenerFuerzaVentaPaginado($pagConf["RowsPerPages"], 0);
		}
		
		$totalResult = 0; 
		if(count($listaFuerzaVenta)> 0){
			$first = current($listaFuerzaVenta); 	
			$totalResult = $first->CountRow;
		}
		
		$first = current($listaFuerzaVenta); 
		echo json_encode(array("data" => $listaFuerzaVenta, "totalResult"=> $totalResult, "count"=> count($listaFuerzaVenta), "IsOk"=> true, "rowsPerPages"=> $pagConf["RowsPerPages"], "IsSession" => true)); 
	}

	public function Crear(){
	if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}
		$this->load->model("FuerzaVenta_Model", "mFuerzaVenta");
		// Auto Validacion del Formulario.

		$this->validation->set_rules("objeto[GUIDDependencia]", "GUIDDependencia", "max_length[50]"); 
		$this->validation->set_rules("objeto[Nombre]", "Nombre", "required|max_length[50]"); 
		$this->validation->set_rules("objeto[Descripcion]", "Descripcion", "max_length[100]"); 
		$this->validation->set_rules("objeto[Nivel]", "Nivel", "integer"); 
		$this->validation->set_rules("objeto[Estado]", "Estado", "integer"); 
		$this->validation->set_rules("objeto[FechaCrea]", "FechaCrea", ""); 
		$this->validation->set_rules("objeto[FechaFin]", "FechaFin", ""); 


	if ($this->validation->run() == FALSE)
         {
            echo json_encode(array("IsOk"=> false, "Msg"=> validation_errors(), "IsSession" => true, "csrf" =>array(
        "name" => $this->security->get_csrf_token_name(),
        "hash" => $this->security->get_csrf_hash()
        ) )  );
           	return false;
    }

    if($this->input->post("objeto")){
		$fuerza_ventaObj = $this->security->xss_clean($this->input->post("objeto"));

		$fuerza_ventaEnt = array('GUIDDependencia'=> $fuerza_ventaObj['GUIDDependencia'] 
, 'Nombre'=> $fuerza_ventaObj['Nombre'] 
, 'Descripcion'=> $fuerza_ventaObj['Descripcion'] 
, 'Nivel'=> $fuerza_ventaObj['Nivel'] 
, 'Estado'=> $fuerza_ventaObj['Estado'] 
, 'FechaFin'=> date('Y-m-d H:i:s') 
);

			$id = $this->mFuerzaVenta->insertar( $fuerza_ventaEnt ); 
			$fuerza_ventaEnt["GUID_FV"] = $id; 

			echo json_encode(array("data" => $fuerza_ventaEnt, "IsOk"=> true, "Msg"=>"Success", "IsSession" => true, "csrf" =>array(
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
		$this->load->model("FuerzaVenta_Model", "mFuerzaVenta");
	$this->validation->set_rules("objeto[GUIDDependencia]", "GUIDDependencia", "max_length[50]"); 
$this->validation->set_rules("objeto[Nombre]", "Nombre", "required|max_length[50]"); 
$this->validation->set_rules("objeto[Descripcion]", "Descripcion", "max_length[100]"); 
$this->validation->set_rules("objeto[Nivel]", "Nivel", "integer"); 
$this->validation->set_rules("objeto[Estado]", "Estado", "integer"); 
$this->validation->set_rules("objeto[FechaCrea]", "FechaCrea", ""); 
$this->validation->set_rules("objeto[FechaFin]", "FechaFin", ""); 

	if ($this->validation->run() == FALSE)
         {
            echo json_encode(array("IsOk"=> false, "Msg"=> validation_errors(), "IsSession" => true, "csrf" =>array(
        "name" => $this->security->get_csrf_token_name(),
        "hash" => $this->security->get_csrf_hash()
        ) )  );
           	return false;
    }

     if($this->input->post("objeto")){
		$fuerza_ventaObj = $this->security->xss_clean($this->input->post("objeto"));
		$fuerza_ventaEnt = $this->mFuerzaVenta->actualizar( $fuerza_ventaObj );

				if( ! $fuerza_ventaEnt ){
				echo json_encode(array("IsOk"=> false, "Msg"=> "Error DB al actualizar". print_r($fuerza_ventaEnt, true), "csrf" =>array(
		        "name" => $this->security->get_csrf_token_name(),
		        "hash" => $this->security->get_csrf_hash()
		        ), "IsSession" => true )  );
		        return false; 
			}

			echo json_encode(array("data" => $fuerza_ventaObj, "IsOk"=> true, "Msg"=>"Success", "IsSession" => true, "csrf" =>array(
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

		$this->load->model("FuerzaVenta_Model", "mFuerzaVenta");

		$pagConf =  $this->config->item("client_pagination");
		if($this->input->get("vNumPage")){
			$pagina = $this->input->get("vNumPage"); 
			$listaFuerzaVenta = $this->mFuerzaVenta->obtenerFuerzaVentaPorCampo("Descripcion", $str, $pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );
		} else {
			$listaFuerzaVenta = $this->mFuerzaVenta->obtenerFuerzaVentaPorCampo("Descripcion", $str, $pagConf["RowsPerPages"], 0);
		}
		$totalResult = 0; 
		if(count($listaFuerzaVenta)> 0){
			$first = current($listaFuerzaVenta); 	
			$totalResult = $first->CountRow;
		}
		echo json_encode(array("data" => $listaFuerzaVenta, "totalResult"=> $totalResult, "count"=> count($listaFuerzaVenta), "IsOk"=> true, "rowsPerPages" => $pagConf["RowsPerPages"], "IsSession" => true)); 
	}

	public function Eliminar(){
		// Eliminar a travez del post y del Get y validar la Session.
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		if($this->input->post("objeto")){			
			$this->load->model("FuerzaVenta_Model", "mFuerzaVenta");
			
			$fuerza_ventaObj = $this->security->xss_clean($this->input->post("objeto"));  
			$result = $this->mFuerzaVenta->cambiarEstado($fuerza_ventaObj, -1); 

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
