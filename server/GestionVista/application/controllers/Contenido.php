<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Contenido extends MY_Controller {

	/**
	 * Index Page for this controller.
	 	@autor:	Grequis Xavier Perez Fortuna.
	 */
	public function index()
	{			
	
	echo $this->generateGUID(); 
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
			$this->load->model('Fuentes_model', 'mFuentes');        	
        	$data['listEstadoForm'] = $this->mEnum->getEnumsEstado();

        	$data['listEsquemaTipo'] = $this->mEnum->getEnum("esquematipo"); 
        	$data['listTransicionTipoIni'] = $this->mEnum->getEnum("transiciontipo");
        	$data['listTransicionTipoFin'] = $data['listTransicionTipoIni'];

        	$data['listFuentesActivas'] = $this->mFuentes->obtenerFuentesActivas(); 

        	
		// Carga de planilla web en general.
		$this->load->view("web/sm_contenido", $data); 
	}


	public function Obtener(){
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		$idRol = 0; 
		$this->load->model("Contenido_Model", "mContenido");
		$pagConf =  $this->config->item("client_pagination");

		if($this->input->get("vNumPage")){
			$pagina = $this->input->get("vNumPage");

			$listaContenido = $this->mContenido->obtenerContenidoPaginado($pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );

		} else {
			$listaContenido = $this->mContenido->obtenerContenidoPaginado($pagConf["RowsPerPages"], 0);
		}
		
		$totalResult = 0; 
		if(count($listaContenido)> 0){
			$first = current($listaContenido); 	
			$totalResult = $first->CountRow;
		}
		
		$first = current($listaContenido); 
		echo json_encode(array("data" => $listaContenido, "totalResult"=> $totalResult, "count"=> count($listaContenido), "IsOk"=> true, "rowsPerPages"=> $pagConf["RowsPerPages"], "IsSession" => true)); 
	}

	public function Crear(){
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}
		$this->load->model("Contenido_Model", "mContenido");
		$this->load->model("SliderMaestro_Model", "mSlider");
		
		// Auto Validacion del Formulario.

		$this->validation->set_rules("objeto[Nombre]", "Nombre", "required|max_length[100]"); 
		$this->validation->set_rules("objeto[Descripcion]", "Descripcion", "required|max_length[100]"); 
		$this->validation->set_rules("objeto[Estado]", "Estado", "required|integer"); 

		if ($this->validation->run() == FALSE)
         {
	            echo json_encode(array("IsOk"=> false, "Msg"=> validation_errors(), "IsSession" => true, "csrf" =>array(
	        "name" => $this->security->get_csrf_token_name(),
	        "hash" => $this->security->get_csrf_hash()
	        ) )  );
           		return false;
    	}

    if($this->input->post("objeto")){
		$contenidoObj = $this->security->xss_clean($this->input->post("objeto"));

		$sliderObj = $this->mSlider->autoInsertar(); 
		$contenidoEnt = array('Nombre'=> $contenidoObj['Nombre'] 
		, 'Descripcion'=> $contenidoObj['Descripcion'] 
		, 'SliderMaestroID'=> $sliderObj->SliderMaestroID
		, 'Duracion'=> "00:00:00" 
		, 'Estado'=> 1
		, 'Guid'=> $this->generateGUID()
		, 'UsuarioModificaID'=> $this->session->userdata("sUsuario")["IDusuario"] 
		, 'FechaModifica'=> date('Y-m-d H:i:s') 
		);

		// Si se puede 

			$id = $this->mContenido->insertar( $contenidoEnt ); 
			$contenidoEnt["ContenidoID"] = $id; 

			echo json_encode(array("data" => $contenidoEnt, "IsOk"=> true, "Msg"=>"Success", "IsSession" => true, "csrf" =>array(
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

		$this->load->model("Contenido_Model", "mContenido");
		$this->validation->set_rules("objeto[Nombre]", "Nombre", "required|max_length[100]"); 
		$this->validation->set_rules("objeto[Descripcion]", "Descripcion", "required|max_length[100]"); 
		$this->validation->set_rules("objeto[Estado]", "Estado", "required|integer");  

	if ($this->validation->run() == FALSE)
         {
            echo json_encode(array("IsOk"=> false, "Msg"=> validation_errors(), "IsSession" => true, "csrf" =>array(
        "name" => $this->security->get_csrf_token_name(),
        "hash" => $this->security->get_csrf_hash()
        ) )  );
           	return false;
    }

     if($this->input->post("objeto")){
		$contenidoObj = $this->security->xss_clean($this->input->post("objeto"));
		$contenidoEnt = $this->mContenido->actualizar( $contenidoObj );

				if( ! $contenidoEnt ){
				echo json_encode(array("IsOk"=> false, "Msg"=> "Error DB al actualizar". print_r($contenidoEnt, true), "csrf" =>array(
		        "name" => $this->security->get_csrf_token_name(),
		        "hash" => $this->security->get_csrf_hash()
		        ), "IsSession" => true )  );
		        return false; 
			}

			echo json_encode(array("data" => $contenidoObj, "IsOk"=> true, "Msg"=>"Success", "IsSession" => true, "csrf" =>array(
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

		$this->load->model("Contenido_Model", "mContenido");

		$pagConf =  $this->config->item("client_pagination");
		if($this->input->get("vNumPage")){
			$pagina = $this->input->get("vNumPage"); 
			$listaContenido = $this->mContenido->obtenerContenidoPorCampo("Descripcion", $str, $pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );
		} else {
			$listaContenido = $this->mContenido->obtenerContenidoPorCampo("Descripcion", $str, $pagConf["RowsPerPages"], 0);
		}
		$totalResult = 0; 
		if(count($listaContenido)> 0){
			$first = current($listaContenido); 	
			$totalResult = $first->CountRow;
		}
		echo json_encode(array("data" => $listaContenido, "totalResult"=> $totalResult, "count"=> count($listaContenido), "IsOk"=> true, "rowsPerPages" => $pagConf["RowsPerPages"], "IsSession" => true)); 
	}

	public function Eliminar(){
		// Eliminar a travez del post y del Get y validar la Session.
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		if($this->input->post("objeto")){			
			$this->load->model("Contenido_Model", "mContenido");
			
			$contenidoObj = $this->security->xss_clean($this->input->post("objeto"));  
			$result = $this->mContenido->cambiarEstado($contenidoObj, -1); 

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

	public function httpObtenerIDBloqueNow(){
		if( $this->input->get("Mac")){
			$this->load->model("Contenido_Model", "mContenido");
			$this->load->model("GrupoTv_Model", "mGrupoTv");

			$defualGrupoID = 1; 

			// Ajustes por la Ips que esta configurando.
			$Mac = $this->input->get("Mac"); 
				$grupoTv = $this->mGrupoTv->obtenerGrupoPorMacTv($Mac); 	
				$filtroGuid = $this->mGrupoTv->GenerarFiltroPorMac($Mac);

				if( $filtroGuid["Existe"] === false){
					// NO Tiene Fuerza de venta Asignada.
					echo json_encode(array("IsOk"=> false, "data"=> array(), "Msg"=>"Este Dispositivo no esta vinculado a un Grupo o a una clasificacion en la fuerza de Venta." ));
					return false; 
				}


				if(count($grupoTv) == 0 ){	
					$idGrupo = $defualGrupoID;
				} else {
					$idGrupo = current($grupoTv)->GrupoID;
				}

				$currentBloque = $this->mContenido->ObtenerBloqueCorrespondienteHoraPorGrupoId($idGrupo); 


			echo json_encode(array("IsOk"=> true, "data"=> $currentBloque, "Msg"=>"Trasfiriendo Bloque" ));



		}	
	}

	// Metodo que Devualve la Imagen En Gestion a la Vista.
	public function httpObtenerImagenFVNow(){
		if( $this->input->get("Mac")){
			$this->load->model("FuerzaVenta_Model", "mFv");
			$Mac = $this->input->get("Mac"); 		

			// Ajustes por la Ips que esta configurando.					
				$stdRes = $this->mFv->ObtenerFotoPorMac($Mac);
				if($stdRes){
			 //		imagejpeg($stdRes->Foto, 'textosimple.jpg');      
    //   list($name, $type, $size, $content) = mysql_fetch_array($result);
      
    //  header("Content-type: image/jpeg");
      /* read data (binary) */     	
		//  echo  $outputfile; 
// echo 'data:image/jpeg;base64,' . base64_encode($stdRes->Foto);
	//				echo '<img src="data:image/jpeg;base64,' . $stdRes->Foto . '" width="290" height="290">'; 

			$image = $this->base64_to_jpeg( $stdRes->Foto, 'tmp.jpg' );

			echo $image; 



				} else {

					echo "nop"; 

				}
exit(); 

			echo json_encode(array("IsOk"=> true, "data"=> $currentBloque, "Msg"=>"Trasfiriendo Bloque" ));
		}	
	}

	public function httpQuitsObtenerPrograma(){

		// ?sckt_hash=F736E021-AAE6-FFBD-CEBE-A64294FC34B1
		$defualGrupoID = 1; 
		$idGrupo = 0; 

		if( $this->input->get("sckt_hash")){
			$hash = $this->input->get("sckt_hash"); 
			if($hash == 'F736E021-AAE6-FFBD-CEBE-A64294FC34B1'){				
				$this->load->model("Contenido_Model", "mContenido");
				$this->load->model("GrupoTv_Model", "mGrupoTv");

				if($this->input->get("Mac")){
					// Este es el FIltro.

				$Mac = $this->input->get("Mac"); 
				$grupoTv = $this->mGrupoTv->obtenerGrupoPorMacTv($Mac); 	
				$filtroGuid = $this->mGrupoTv->GenerarFiltroPorMac($Mac);

				if( $filtroGuid["Existe"] === false){
					// NO Tiene Fuerza de venta Asignada.
					echo json_encode(array("IsOk"=> false, "programa"=> array(), "Msg"=>"Este Dispositivo no esta vinculado a un Grupo o a una clasificacion en la fuerza de Venta." ));

					return false; 
				}


				if(count($grupoTv) == 0 ){	
					$idGrupo = $defualGrupoID;
				} else {
					$idGrupo = current($grupoTv)->GrupoID;
				}


				$contenidoHoy = $this->mContenido->obtenerContenidoHoyPorGrupoPorGrupoID($idGrupo, $filtroGuid["getString"]);

			
				 echo json_encode(array("IsOk"=> true, "programa"=> $contenidoHoy, "FuerzaVenta"=> $filtroGuid["FuerzaVenta"]) );
				 return 0; 

				}				

				return true; 
			}
		}

		echo "Solicitud no encontrada"; 
		return false; 
	}


	public function GenerarBisChartPorFuerzaVenta(){

			$fuente = $this->mContenido->ObtenerContenidoHoyFuentes();

	}

	public function base64_to_jpeg( $base64_string, $output_file ) {
    $ifp = fopen( $output_file, "wb" ); 
    fwrite( $ifp, base64_decode( $base64_string) ); 
    fclose( $ifp ); 
    return( $output_file ); 
}


}