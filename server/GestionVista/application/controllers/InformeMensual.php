<?php 
defined('BASEPATH') OR exit('No direct script access allowed');


class InformeMensual extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *	@autor:	Grequis Xavier Perez Fortuna.
	 */

	public function index()
	{
	// Hacer el informe Mensual.	

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

        // Informaciones Generales.
        $FechaCreacion = date("m");
        $fechaJS = array('Anio' => date("Y") , "Mes"=>date("m"), "Dia"=>date("j")); 
        switch ((int)date("d") ){
        	case 27: case  28: case  29: case 30: case 31: //case :
        	$FechaCreacion = date("m");

        	break; 
        	case 1: case 2: case 3: case 4: case  5: case 6: case 7: case 8: case 9: case 10:         	
			$nuevafecha = strtotime ( '-1 month' , strtotime ( date('Y-m-d') ) ) ;
			$FechaCreacion = date ( 'm' , $nuevafecha );
			$fechaJS["Anio"] = date ( 'Y' , $nuevafecha );
			$fechaJS["Mes"] = date ( 'm' , $nuevafecha );
			$fechaJS["Dia"] = date ( 'd' , $nuevafecha );
        	break;
        }        

        $mesInt = $FechaCreacion; 
        $mes = ""; 
        switch ((int)$mesInt) {
        	case 1:
        	$mes = "Enero";         	
        		break;
        	case 2:
        	$mes = "Febrero";         	
        		break;        	
        	case 3:
        	$mes = "Marzo";         	
        		break;
        	case 4:
        	$mes = "Abril";
        		break;
        		       	case 5:
        	$mes = "Mayo";
        		break;
        	case 6:
        	$mes = "Junio";
        		break;
        	case 7:
        	$mes = "Julio";
        		break;
        		case 8:
        	$mes = "Agosto";
        		break;

        	case 9:
        	$mes = "Septiembre";
        		break;
        	case 10:
        	$mes = "Octubre";
        		break;
        	case 11:
        	$mes = "Noviembre";
        		break;
        	case 11:
        	$mes = "Diciembre";
        		break;
        	default:        		
        		break;
        }        
        
        $data["mes"] = $mes;
        $data["FechaCal"] = $fechaJS; 

        // Informaciones del Club local                        
        $this->load->model('Calendario_Model', 'mCal');  
        $this->load->model("DirectivaOrganigrama_Model", "mDOr");  
        $this->load->model("Club_Model", "mClub");

        // Validar si tiene Club Asignado Para continuar.
        $data['esClub'] = false; 
        $data['calendario'] = $this->mCal->ObtenerActivo();
        $listOrganigramaUsuario = $this->mDOr->ObtenerOrganigramaPorUsuario($this->session->userdata('sUsuario')["IDusuario"], $data['calendario']->CalendarioID);
        $clubLocal = array();    

        $datosResumen =  array();

		if($listOrganigramaUsuario == null){
			$data['estaOrganigrama']  = false;
			$data["Messages"] = "Usteded no tiene asignado un club en para acceder a esta acción"; 
        $this->load->view("web/sm_default_parking", $data); 
			return true;
		} else {
			foreach ($listOrganigramaUsuario as $key => $value) {				
				if($value->OrganigramaTipo == 7){
					$data['esClub'] = true; 										
					$clubLocal[] = $value;

					$datosResumen[$value->OrganigramaID] =$this->mDOr->GetInfoClubResumen($value->OrganigramaID); 
				}			
			}
		}

		$this->load->model("PlanClubObjetivo_Model", "mPlanClubObjetivo");
		$actividades = $this->mPlanClubObjetivo->ObtenerListaActividades();
		$listActividad=  array( );
		foreach ($actividades as $key => $value) {
			if(!array_key_exists($value->Categoria, $listActividad)){
				$listActividad[$value->Categoria] = array();
			} //   $listActividad)
				
				$listActividad[$value->Categoria][] = $value; 
			}

			$data["listaActividad"] = $listActividad; 
			$data["resumenClub"] = $datosResumen; 

		$data["listOrganigramaUsuario"] = $clubLocal;
		// $this->load->model('EmunsViews_Model', 'mEnum'); 
      //   $data['listEstadoForm'] = $this->mEnum->getEnumsEstado();        
		// Carga de planilla web en general.
		$this->load->view("web/sm_informe_wizard", $data); 
	}


	public function Obtener(){
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		$idRol = 0; 
		$this->load->model("InscripcionClub_Model", "mInscripcionClub");
		$pagConf =  $this->config->item("client_pagination");

		if($this->input->get("vNumPage")){
			$pagina = $this->input->get("vNumPage");

			$listaInscripcionClub = $this->mInscripcionClub->obtenerInscripcionClubPaginado($pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );

		} else {
			$listaInscripcionClub = $this->mInscripcionClub->obtenerInscripcionClubPaginado($pagConf["RowsPerPages"], 0);
		}
		
		$totalResult = 0; 
		if(count($listaInscripcionClub)> 0){
			$first = current($listaInscripcionClub); 	
			$totalResult = $first->CountRow;
		}
		
		$first = current($listaInscripcionClub); 
		echo json_encode(array("data" => $listaInscripcionClub, "totalResult"=> $totalResult, "count"=> count($listaInscripcionClub), "IsOk"=> true, "rowsPerPages"=> $pagConf["RowsPerPages"], "IsSession" => true)); 
	}

	public function Crear(){
	if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}
		$this->load->model("InscripcionClub_Model", "mInscripcionClub");
		// Auto Validacion del Formulario.

	

	if ($this->validation->run() == FALSE)
         {
            echo json_encode(array("IsOk"=> false, "Msg"=> validation_errors(), "IsSession" => true, "csrf" =>array(
        "name" => $this->security->get_csrf_token_name(),
        "hash" => $this->security->get_csrf_hash()
        ) )  );
           	return false;
    }

    if($this->input->post("objeto")){
		$inscripcion_clubObj = $this->security->xss_clean($this->input->post("objeto"));

		$inscripcion_clubEnt = array('CalendarioID'=> $inscripcion_clubObj['CalendarioID'] 
, 'ClubID'=> $inscripcion_clubObj['ClubID'] 
, 'NombreClub'=> $inscripcion_clubObj['NombreClub'] 
, 'PersonaID'=> $inscripcion_clubObj['PersonaID'] 
, ' FrecuenciaReunion'=> $inscripcion_clubObj[' FrecuenciaReunion'] 
, 'CantidadHoras'=> $inscripcion_clubObj['CantidadHoras'] 
, 'Anio'=> $inscripcion_clubObj['Anio'] 
, 'GUID'=> $inscripcion_clubObj['GUID'] 
, 'Estado'=> $inscripcion_clubObj['Estado'] 
, 'UsuarioModificaID'=> $inscripcion_clubObj['UsuarioModificaID'] 
, 'FechaCreacion'=> date('Y-m-d H:i:s') 
, 'FechaModificacion'=> date('Y-m-d H:i:s') 
);

			$id = $this->mInscripcionClub->insertar( $inscripcion_clubEnt ); 
			$inscripcion_clubEnt["InscripcionID"] = $id; 

			echo json_encode(array("data" => $inscripcion_clubEnt, "IsOk"=> true, "Msg"=>"Success", "IsSession" => true, "csrf" =>array(
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
		$this->load->model("InscripcionClub_Model", "mInscripcionClub");
	
	if ($this->validation->run() == FALSE)
         {
            echo json_encode(array("IsOk"=> false, "Msg"=> validation_errors(), "IsSession" => true, "csrf" =>array(
        "name" => $this->security->get_csrf_token_name(),
        "hash" => $this->security->get_csrf_hash()
        ) )  );
           	return false;
    }

     if($this->input->post("objeto")){
		$inscripcion_clubObj = $this->security->xss_clean($this->input->post("objeto"));
		$inscripcion_clubEnt = $this->mInscripcionClub->actualizar( $inscripcion_clubObj );

				if( ! $inscripcion_clubEnt ){
				echo json_encode(array("IsOk"=> false, "Msg"=> "Error DB al actualizar". print_r($inscripcion_clubEnt, true), "csrf" =>array(
		        "name" => $this->security->get_csrf_token_name(),
		        "hash" => $this->security->get_csrf_hash()
		        ), "IsSession" => true )  );
		        return false; 
			}

			echo json_encode(array("data" => $inscripcion_clubObj, "IsOk"=> true, "Msg"=>"Success", "IsSession" => true, "csrf" =>array(
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

		$this->load->model("InscripcionClub_Model", "mInscripcionClub");

		$pagConf =  $this->config->item("client_pagination");
		if($this->input->get("vNumPage")){
			$pagina = $this->input->get("vNumPage"); 
			$listaInscripcionClub = $this->mInscripcionClub->obtenerInscripcionClubPorCampo("Descripcion", $str, $pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );
		} else {
			$listaInscripcionClub = $this->mInscripcionClub->obtenerInscripcionClubPorCampo("Descripcion", $str, $pagConf["RowsPerPages"], 0);
		}
		$totalResult = 0; 
		if(count($listaInscripcionClub)> 0){
			$first = current($listaInscripcionClub); 	
			$totalResult = $first->CountRow;
		}
		echo json_encode(array("data" => $listaInscripcionClub, "totalResult"=> $totalResult, "count"=> count($listaInscripcionClub), "IsOk"=> true, "rowsPerPages" => $pagConf["RowsPerPages"], "IsSession" => true)); 
	}

	public function Eliminar(){
		// Eliminar a travez del post y del Get y validar la Session.
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		if($this->input->post("objeto")){			
			$this->load->model("InscripcionClub_Model", "mInscripcionClub");
			
			$inscripcion_clubObj = $this->security->xss_clean($this->input->post("objeto"));  
			$result = $this->mInscripcionClub->cambiarEstado($inscripcion_clubObj, -1); 

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