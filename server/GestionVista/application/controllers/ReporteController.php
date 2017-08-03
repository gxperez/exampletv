<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReporteController extends MY_Controller {
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
	public function index(){

	}

	public function sm($report = "default"){

	if (!$this->session->userdata("sUsuario")){
		redirect("/portal/login", "refresh");			
		return false; 
	}

		$data["isACD"] =  array('res' => false); 


// print_r($this->session->userdata("sUsuario"));

         $this->load->model('Calendario_Model', 'mCal');
		 $this->load->model('Reporte_Model', "mReport");
		 $this->load->model("DirectivaOrganigrama_Model", "mDOr");
		 $data['calendario'] = $this->mCal->ObtenerActivo();      
		$data['esZona'] = false; 
		$zonaLocal = null; 

		
		switch ($report) {

			case "ResumenPreMatriculaCamporeeACD":
			$zonaLocal = null; 
			$listOrganigramaUsuario = $this->mDOr->ObtenerOrganigramaPorUsuario($this->session->userdata('sUsuario')["IDusuario"], $data['calendario']->CalendarioID); 		
		
		if($listOrganigramaUsuario){
			foreach ($listOrganigramaUsuario as $key => $value) {				
				if($value->OrganigramaTipo == 4){
					$data['esZona'] = true;
					$zonaLocal = $value;
				}
			}
		}

		$data["isACD"] =  array('res' => true); 

		 $dataReporte = $this->mReport->MatriculaCamporeeACD(); 
		 $filtro =  array('Zona' => array(), 'Club'=> array());
		 $data = array('lista' => $dataReporte,  'filtro'=> $filtro, "isACD"=> array('res' => true) );
		$this->load->view("web/rp_matriculados_camporee", $data);
				
				break;				

			case 'ClubesMapas':


			$this->load->view("web/rp_mapas", $data);

			return 0; 

			break;

			case 'ClubesInscritos':


		$listOrganigramaUsuario = $this->mDOr->ObtenerOrganigramaPorUsuario($this->session->userdata('sUsuario')["IDusuario"], $data['calendario']->CalendarioID); 		
		
		if($listOrganigramaUsuario == null){
			echo json_encode(array("IsOk"=> false, "Msg"=> "Error al Tratar de Cargar el reporte.", "IsSession" => true ) ); 			
		exit();
		} else {

			foreach ($listOrganigramaUsuario as $key => $value) {				
				if($value->OrganigramaTipo == 4){
					$data['esZona'] = true; 					
					
					$zonaLocal = $value; 

				}			
			}
		}
		if($zonaLocal != null){
			$ress = $this->mReport->get_DataSetClubesInscritos($zonaLocal->OrganigramaID, $data['calendario']->CalendarioID); 
			$data = array('lista' => $ress );	
		} 

		// Carga de planilla web en general.
		$this->load->view("web/rp_clubes_inscritos", $data); 				
				break;
				case 'DesempenoPlanificacion':

				// Determinar la Zona
				 if(in_array($this->session->userdata('sUsuario')["RolDB"],  array(1, 2, 3, 4, 11 ))  ){
				 	$ress = $this->mReport->get_DatasetDesempenoPlanificacion();
					$data = array('lista' => $ress );	
				//	print_r($data ); 
					$this->load->view("web/rp_desempeno_planificacion", $data);
					return 0;
				 }

				$zonaLocal = null; 
				$listOrganigramaUsuario = $this->mDOr->ObtenerOrganigramaPorUsuario($this->session->userdata('sUsuario')["IDusuario"], $data['calendario']->CalendarioID); 		
		
		
		if($listOrganigramaUsuario == null){
			echo json_encode(array("IsOk"=> false, "Msg"=> "Error al Tratar de Cargar el reporte.", "IsSession" => true ) ); 			
		exit();
		} else {

			foreach ($listOrganigramaUsuario as $key => $value) {				
				if($value->OrganigramaTipo == 4){
					$data['esZona'] = true; 
					$zonaLocal = $value; 

					$this->load->model('Zona_Model', "mZone");					
					$zonaDt = $this->mZone->ObtenerPorID($zonaLocal->OrganigramaID); 
					$zonaDt->Descripcion;
					$ress = $this->mReport->get_DatasetDesempenoPlanificacion($zonaDt->Descripcion);

					$data = array('lista' => $ress );					
					$this->load->view("web/rp_desempeno_planificacion", $data);
					return 0;
				}
			}
		}
		break;

		case "ResumenPreMatriculaCamporee":
			$zonaLocal = null; 
			$listOrganigramaUsuario = $this->mDOr->ObtenerOrganigramaPorUsuario($this->session->userdata('sUsuario')["IDusuario"], $data['calendario']->CalendarioID); 		
		
		if($listOrganigramaUsuario){
			foreach ($listOrganigramaUsuario as $key => $value) {				
				if($value->OrganigramaTipo == 4){
					$data['esZona'] = true;
					$zonaLocal = $value;
				}
			}
		}

		$data["isACD"] =  array('res' => false); 

		 $dataReporte = $this->mReport->get_Matriculacion($zonaLocal); 
		 $filtro =  array('Zona' => array(), 'Club'=> array());
		 $data = array('lista' => $dataReporte,  'filtro'=> $filtro, "isACD"=> array('res' => false) );

		$this->load->view("web/rp_matriculados_camporee", $data);
		// print_r($dataReporte); 

				
				break;
			
			default:
				break;
		}
	}

	public function reporteDispositivosOnline(){		
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsOk"=> false, "Msg"=> "Error al Tratar de Cargar el reporte.", "IsSession" => true ) );
			return false;
		}		

		$this->load->model('Reporte_Model', "mReport");

		$dataReporte = $this->mReport->get_DataSetResumenDispositivosOnline(); 
		echo json_encode(array("Data"=>$dataReporte, "lastUpdate" => date("Y, M d h:i") ) ); 

	}


	public function eventosCalendario(){

		$this->load->model('Calendario_Model', 'mCal');
		$this->load->model('Reporte_Model', "mReport");
		$this->load->model("DirectivaOrganigrama_Model", "mDOr");
		 
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsOk"=> false, "Msg"=> "Error al Tratar de Cargar el reporte.", "IsSession" => true ) );
			return false;
		}		
		// Determinar el Rol.
		  $zonaLocal = null;
		  $clubLocal = array();

		// Buscamos el calendario Activo. 
		  $data['calendario'] = $this->mCal->ObtenerActivo();   
		  $listOrganigramaUsuario = $this->mDOr->ObtenerOrganigramaPorUsuario($this->session->userdata('sUsuario')["IDusuario"], $data['calendario']->CalendarioID);

		if($listOrganigramaUsuario){
			foreach ($listOrganigramaUsuario as $key => $value) {				
			 if($value->OrganigramaTipo == 4){
				// $data['esZona'] = true;
				$zonaLocal = $value;
			 }

			 if($value->OrganigramaTipo == 7){
			 	$clubLocal[] = $value;
			 }
			}
		}


$whereCondicion = ""; 
	
		if($zonaLocal != null ){
			// echo "Nuestro Mundo Zonal"; 			
			// return 0;
			$whereCondicion = " and z.ZonaID = {$zonaLocal->OrganigramaID}  and pco.CalendarioID =  {$data['calendario']->CalendarioID} ";
		}

		if(count($clubLocal) > 0){
			$ins = ""; 
			foreach ($clubLocal as $k => $val) {
				$ins .= "{$val->OrganigramaID}, "; 
			}

			$whereCondicion = " and  cl.ClubID in( {$ins} 0 ) and  pco.CalendarioID =  {$data['calendario']->CalendarioID} "; 
			
		//	return 0;
		}

	//	echo $whereCondicion; 

	// 	exit(); 
	

// 		 $dataReporte = $this->mReport->get_Matriculacion($zonaLocal); 
		if (!empty($_GET['year']) && !empty($_GET['month'])) {
    $year = intval($_GET['year']);
    $month = intval($_GET['month']);
    $lastday = intval(strftime('%d', mktime(0, 0, 0, ($month == 12 ? 1 : $month + 1), 0, ($month == 12 ? $year + 1 : $year))));
    $dates = array();

    $dataReporte = $this->mReport->get_DataSetCalendarioEvento($year, $month, 7, $whereCondicion, $data['calendario']->CalendarioID ); 
	// get_DataSetCalendarioEventoResumen
    $dtaRunner = array(); 

    foreach ($dataReporte as $key => $value) {

    	$tClub= ""; 
    	switch ($value->ClubTipo) {
    		case 1:
    			$tClub="(Castor)";
    			break;    		
    		case 2:
    			$tClub="(Aventurero)";
    			break;    		
    		case 3:
    			$tClub="(Conquistador)";
    			break;    		
    		case 4:
    			$tClub="(Guia)";
    			break;    		
    		default:
    			$tClub="Conquistador";
    			break;
    	}

    	$descTipoFRC = $value->FrecueciaDesc; 
    	if( $value->FrecuenciaTipo == 2 ){ 
    		$descTipoFRC = $value->FrecueciaDesc . 'Del ' . date_format(date_create($value->FechaAccion),"d"). " al " . date_format(date_create($value->FechaFin),"d/m"); 

    	}
    	// Listar Tabla de Contenido por Fehas Pendientes En el Calendario.
    	if(! array_key_exists($value->FechaAccion, $dtaRunner) ){
    		$dtaRunner[$value->FechaAccion] = array('head' => "" , "body"=> "" );   		
    	}
    	// ""; 
    	$dtaRunner[$value->FechaAccion]["body"] .=  '<tr> <td>'. $descTipoFRC. '</td> <td>'. $value->Iglesia. '</td> <td> '. $tClub . " " . $value->NombreClub .'</td>  <td><strong> '. $value->Titulo .': </strong> <p> '. $value->Descripcion .' </p> </td> <td><stron>'. $value->Lugar .' </strong>  '. $value->Direccion .' </td>  <td> '. $value->Responsables .' </td>  </tr>'; 
    }

  //   var_dump($dtaRunner); 

 //   exit(); 
$i = 0;
    foreach ($dtaRunner as $keyV => $value2) {    	 

    	$date = date_format(date_create($keyV),"Y-m-d");
    	
    	  $dates[$i] = array(
            'date' => $date,
            'badge' => true, //($i & 1) ? true : false,
            'title' => 'Example for ' . $date,
            'body' => '<p class="lead">Information for this date</p><p>You can add <strong>html</strong> in this block</p>',
            'footer' => 'Extra information',
        );

    	  if (!empty($_REQUEST['grade'])) {
            $dates[$i]['badge'] = false;
            $dates[$i]['classname'] = 'grade-' . rand(1, 4);
        }

          if (!empty($_GET['action'])) {
            $dates[$i]['title'] = 'Eventos ' . $date;
            $dates[$i]['body'] = '<div style="height: 320px; overflow-y: scroll;"><table class="table" > <tr> <th> FRC </th> <th> Iglesia </th> <th> Club </th> <th>Actividad</th> <th>Lugar</th> <th>Responsables</th>  </tr>'; 
            $dates[$i]['body'] .=  $value2["body"]; 
              $dates[$i]['body'] .= '</table> </div>';
            
            $dates[$i]['footer'] = '
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
            // <button type="button" class="btn btn-primary" onclick="dateId = $(this).closest(\'.modal\').attr(\'dateId\'); myDateFunction(dateId, true);">Go ahead!</button>            ';

        }

    	$i++; 
    // 	echo $i . "  ; "; 
    } 
    
    echo json_encode($dates);
} else {
    echo json_encode(array());
}


	}

	public function ObtenerAtividadesParaInformes($ClubID){

		$this->load->model('Calendario_Model', 'mCal');
		$this->load->model('Reporte_Model', "mReport");

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

        $data = array();

        $data['calendario'] = $this->mCal->ObtenerActivo();	

        $whereCondicion = " and  cl.ClubID = {$ClubID}  and  pco.CalendarioID =  {$data['calendario']->CalendarioID} "; 

        // Aqui Consultamos la fuente para validar si existen todos los casos.
			$rss = $this->mReport->HaEnviadoInforme($ClubID, $fechaJS["Anio"], $fechaJS["Mes"]); 
       	 

			
		$dataReporte = $this->mReport->get_DataSetCalendarioEventoResumen($fechaJS["Anio"] , $fechaJS["Mes"], 7, $whereCondicion, $data['calendario']->CalendarioID ); 
		echo json_encode(array("data"=>$dataReporte, "rs"=> $rss) ); 

	}


public function claves($nuv){
	echo md5($nuv);	
}

public function Manager(){

		if (!$this->session->userdata("sUsuario")){
			redirect("/portal/login", "refresh");			
			return false; 
	}
}

public function base(){


// `dataclub_dataclubesSTG`.`z`
$zonas = array(
  array('ZonaID' => '5','ZonaDesc' =>'ZONA V'),
  array('ZonaID' => '3','ZonaDesc' =>'ZONA III'),
  array('ZonaID' => '4','ZonaDesc' =>'ZONA IV'),
  array('ZonaID' => '1','ZonaDesc' =>'ZONA I'),
  array('ZonaID' => '2','ZonaDesc' =>'ZONA II'),
  array('ZonaID' => '7','ZonaDesc' =>'ZONA 7'),
  array('ZonaID' => '6','ZonaDesc' =>'ZONA VI')
);

$zonaDetalle = array(
  array('ZonaID' => '5','ZonaDesc' => 'ZONA V','DistritoID' => '37','DistritoDesc' => 'QUISQUEYA I','IglesiaID' => '1','Iglesia' => 'CENTRAL QUISQUEYA','Lng' => '-69.9509','Lat' => '18.458','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Central Quisqueya'),
  array('ZonaID' => '5','ZonaDesc' => 'ZONA V','DistritoID' => '37','DistritoDesc' => 'QUISQUEYA I','IglesiaID' => '1','Iglesia' => 'CENTRAL QUISQUEYA','Lng' => '-69.9509','Lat' => '18.458','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Central Quisqueya'),
  array('ZonaID' => '5','ZonaDesc' => 'ZONA V','DistritoID' => '38','DistritoDesc' => 'QUISQUEYA II','IglesiaID' => '2','Iglesia' => 'QUISQUEYA II','Lng' => '-69.9497','Lat' => '18.4641','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Quisqueya II'),
  array('ZonaID' => '5','ZonaDesc' => 'ZONA V','DistritoID' => '38','DistritoDesc' => 'QUISQUEYA II','IglesiaID' => '2','Iglesia' => 'QUISQUEYA II','Lng' => '-69.9497','Lat' => '18.4641','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Élite'),
  array('ZonaID' => '5','ZonaDesc' => 'ZONA V','DistritoID' => '38','DistritoDesc' => 'QUISQUEYA II','IglesiaID' => '2','Iglesia' => 'QUISQUEYA II','Lng' => '-69.9497','Lat' => '18.4641','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Quisqueya II'),
  array('ZonaID' => '5','ZonaDesc' => 'ZONA V','DistritoID' => '39','DistritoDesc' => 'LOS JARDINES','IglesiaID' => '3','Iglesia' => 'LOS JARDINES','Lng' => '18.4856','Lat' => '-69.9623','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Los Jardines'),
  array('ZonaID' => '5','ZonaDesc' => 'ZONA V','DistritoID' => '39','DistritoDesc' => 'LOS JARDINES','IglesiaID' => '3','Iglesia' => 'LOS JARDINES','Lng' => '18.4856','Lat' => '-69.9623','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Los Jardines'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '24','DistritoDesc' => 'NUEVO EDÉN','IglesiaID' => '4','Iglesia' => 'Central Nuevo Eden','Lng' => '-69.9203','Lat' => '18.4706','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Central Nuevo Eden'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '24','DistritoDesc' => 'NUEVO EDÉN','IglesiaID' => '4','Iglesia' => 'Central Nuevo Eden','Lng' => '-69.9203','Lat' => '18.4706','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Central Nuevo eden'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '24','DistritoDesc' => 'NUEVO EDÉN','IglesiaID' => '4','Iglesia' => 'Central Nuevo Eden','Lng' => '-69.9203','Lat' => '18.4706','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Central Nuevo eden'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '24','DistritoDesc' => 'NUEVO EDÉN','IglesiaID' => '4','Iglesia' => 'Central Nuevo Eden','Lng' => '-69.9203','Lat' => '18.4706','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Central Nuevo eden'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '24','DistritoDesc' => 'NUEVO EDÉN','IglesiaID' => '4','Iglesia' => 'Central Nuevo Eden','Lng' => '-69.9203','Lat' => '18.4706','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Central Nuevo eden'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '24','DistritoDesc' => 'NUEVO EDÉN','IglesiaID' => '4','Iglesia' => 'Central Nuevo Eden','Lng' => '-69.9203','Lat' => '18.4706','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Central Nuevo eden'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '24','DistritoDesc' => 'NUEVO EDÉN','IglesiaID' => '4','Iglesia' => 'Central Nuevo Eden','Lng' => '-69.9203','Lat' => '18.4706','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Central Nuevo eden'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '24','DistritoDesc' => 'NUEVO EDÉN','IglesiaID' => '4','Iglesia' => 'Central Nuevo Eden','Lng' => '-69.9203','Lat' => '18.4706','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Central Nuevo eden'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '24','DistritoDesc' => 'NUEVO EDÉN','IglesiaID' => '4','Iglesia' => 'Central Nuevo Eden','Lng' => '-69.9203','Lat' => '18.4706','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Central Nuevo eden'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '27','DistritoDesc' => 'APOCALIPSIS (ALCARRIZOS I)','IglesiaID' => '5','Iglesia' => 'Central Apocalipsis','Lng' => '-69.8977','Lat' => '18.4765','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Apocalipsis'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '27','DistritoDesc' => 'APOCALIPSIS (ALCARRIZOS I)','IglesiaID' => '5','Iglesia' => 'Central Apocalipsis','Lng' => '-69.8977','Lat' => '18.4765','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'savica'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '27','DistritoDesc' => 'APOCALIPSIS (ALCARRIZOS I)','IglesiaID' => '5','Iglesia' => 'Central Apocalipsis','Lng' => '-69.8977','Lat' => '18.4765','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'las mercedes'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '27','DistritoDesc' => 'APOCALIPSIS (ALCARRIZOS I)','IglesiaID' => '5','Iglesia' => 'Central Apocalipsis','Lng' => '-69.8977','Lat' => '18.4765','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Valle de Canaam'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '27','DistritoDesc' => 'APOCALIPSIS (ALCARRIZOS I)','IglesiaID' => '5','Iglesia' => 'Central Apocalipsis','Lng' => '-69.8977','Lat' => '18.4765','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Apocalipsis 2'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '27','DistritoDesc' => 'APOCALIPSIS (ALCARRIZOS I)','IglesiaID' => '5','Iglesia' => 'Central Apocalipsis','Lng' => '-69.8977','Lat' => '18.4765','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'luz en la 41'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '27','DistritoDesc' => 'APOCALIPSIS (ALCARRIZOS I)','IglesiaID' => '5','Iglesia' => 'Central Apocalipsis','Lng' => '-69.8977','Lat' => '18.4765','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'SAVICA'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '27','DistritoDesc' => 'APOCALIPSIS (ALCARRIZOS I)','IglesiaID' => '5','Iglesia' => 'Central Apocalipsis','Lng' => '-69.8977','Lat' => '18.4765','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'central apocalipsis'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '27','DistritoDesc' => 'APOCALIPSIS (ALCARRIZOS I)','IglesiaID' => '5','Iglesia' => 'Central Apocalipsis','Lng' => '-69.8977','Lat' => '18.4765','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Valle angelical'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '27','DistritoDesc' => 'APOCALIPSIS (ALCARRIZOS I)','IglesiaID' => '5','Iglesia' => 'Central Apocalipsis','Lng' => '-69.8977','Lat' => '18.4765','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Rayitos de Amor'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '27','DistritoDesc' => 'APOCALIPSIS (ALCARRIZOS I)','IglesiaID' => '5','Iglesia' => 'Central Apocalipsis','Lng' => '-69.8977','Lat' => '18.4765','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'APOCALIPSIS 2'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '27','DistritoDesc' => 'APOCALIPSIS (ALCARRIZOS I)','IglesiaID' => '5','Iglesia' => 'Central Apocalipsis','Lng' => '-69.8977','Lat' => '18.4765','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Canaam celestial'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '19','DistritoDesc' => 'BETHEL','IglesiaID' => '6','Iglesia' => 'Bethel Central','Lng' => '-69.9827','Lat' => '18.4709','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Bethel Central'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '19','DistritoDesc' => 'BETHEL','IglesiaID' => '7','Iglesia' => 'Bethel Central','Lng' => '-69.9827','Lat' => '18.4709','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '29','DistritoDesc' => 'EL REMANENTE','IglesiaID' => '8','Iglesia' => 'Iglesia Adventista del 7mo día Colinas del Norte','Lng' => '-70.0076','Lat' => '18.518','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Revolución 04'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '29','DistritoDesc' => 'EL REMANENTE','IglesiaID' => '8','Iglesia' => 'Iglesia Adventista del 7mo día Colinas del Norte','Lng' => '-70.0076','Lat' => '18.518','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'MISION JUNIOR'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '24','DistritoDesc' => 'NUEVO EDÉN','IglesiaID' => '9','Iglesia' => 'Villa Nazareth','Lng' => '-70.0063','Lat' => '18.4646','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Villa Nazareth'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '24','DistritoDesc' => 'NUEVO EDÉN','IglesiaID' => '9','Iglesia' => 'Villa Nazareth','Lng' => '-70.0063','Lat' => '18.4646','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Villa Nazareth'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '19','DistritoDesc' => 'BETHEL','IglesiaID' => '10','Iglesia' => 'BETHEL CENTRAL','Lng' => '-69.9755','Lat' => '18.4693','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '1','ZonaDesc' => 'ZONA I','DistritoID' => '5','DistritoDesc' => 'AVANZADA EL CINCO','IglesiaID' => '11','Iglesia' => 'CENTRAL SHALOM KM.5','Lng' => '-70.1422','Lat' => '18.4057','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Jaguares de Jesus'),
  array('ZonaID' => '1','ZonaDesc' => 'ZONA I','DistritoID' => '5','DistritoDesc' => 'AVANZADA EL CINCO','IglesiaID' => '11','Iglesia' => 'CENTRAL SHALOM KM.5','Lng' => '-70.1422','Lat' => '18.4057','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Jaguares Maxium'),
  array('ZonaID' => '1','ZonaDesc' => 'ZONA I','DistritoID' => '5','DistritoDesc' => 'AVANZADA EL CINCO','IglesiaID' => '11','Iglesia' => 'CENTRAL SHALOM KM.5','Lng' => '-70.1422','Lat' => '18.4057','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Jaguares Junior'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '49','DistritoDesc' => 'Central Remanente','IglesiaID' => '12','Iglesia' => 'CENTRAL REMANENTE','Lng' => '-70.0032','Lat' => '18.5073','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Remanente'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '49','DistritoDesc' => 'Central Remanente','IglesiaID' => '12','Iglesia' => 'CENTRAL REMANENTE','Lng' => '-70.0032','Lat' => '18.5073','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Colinas'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '49','DistritoDesc' => 'Central Remanente','IglesiaID' => '12','Iglesia' => 'CENTRAL REMANENTE','Lng' => '-70.0032','Lat' => '18.5073','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'el 14'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '49','DistritoDesc' => 'Central Remanente','IglesiaID' => '12','Iglesia' => 'CENTRAL REMANENTE','Lng' => '-70.0032','Lat' => '18.5073','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'pantoja'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '49','DistritoDesc' => 'Central Remanente','IglesiaID' => '12','Iglesia' => 'CENTRAL REMANENTE','Lng' => '-70.0032','Lat' => '18.5073','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Renacer'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '49','DistritoDesc' => 'Central Remanente','IglesiaID' => '12','Iglesia' => 'CENTRAL REMANENTE','Lng' => '-70.0032','Lat' => '18.5073','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Carmen Renata 3'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '49','DistritoDesc' => 'Central Remanente','IglesiaID' => '12','Iglesia' => 'CENTRAL REMANENTE','Lng' => '-70.0032','Lat' => '18.5073','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Los Humildes'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '49','DistritoDesc' => 'Central Remanente','IglesiaID' => '12','Iglesia' => 'CENTRAL REMANENTE','Lng' => '-70.0032','Lat' => '18.5073','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Pablo Mella'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '49','DistritoDesc' => 'Central Remanente','IglesiaID' => '12','Iglesia' => 'CENTRAL REMANENTE','Lng' => '-70.0032','Lat' => '18.5073','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'REMANENTE'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '49','DistritoDesc' => 'Central Remanente','IglesiaID' => '12','Iglesia' => 'CENTRAL REMANENTE','Lng' => '-70.0032','Lat' => '18.5073','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Uriel-CCR-III'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '49','DistritoDesc' => 'Central Remanente','IglesiaID' => '12','Iglesia' => 'CENTRAL REMANENTE','Lng' => '-70.0032','Lat' => '18.5073','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Los Humildes'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '28','DistritoDesc' => 'MONTE DE SIÓN (ALCARRIZOS II)','IglesiaID' => '13','Iglesia' => 'Central Monte de Sión','Lng' => '-70.0378','Lat' => '18.5225','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Fuentes de Aguas Vivas'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '28','DistritoDesc' => 'MONTE DE SIÓN (ALCARRIZOS II)','IglesiaID' => '13','Iglesia' => 'Central Monte de Sión','Lng' => '-70.0378','Lat' => '18.5225','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Monte de Sion'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '28','DistritoDesc' => 'MONTE DE SIÓN (ALCARRIZOS II)','IglesiaID' => '13','Iglesia' => 'Central Monte de Sión','Lng' => '-70.0378','Lat' => '18.5225','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Luz de Sion'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '28','DistritoDesc' => 'MONTE DE SIÓN (ALCARRIZOS II)','IglesiaID' => '13','Iglesia' => 'Central Monte de Sión','Lng' => '-70.0378','Lat' => '18.5225','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Roca de Sion'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '28','DistritoDesc' => 'MONTE DE SIÓN (ALCARRIZOS II)','IglesiaID' => '13','Iglesia' => 'Central Monte de Sión','Lng' => '-70.0378','Lat' => '18.5225','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Vislumbre de Sion'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '28','DistritoDesc' => 'MONTE DE SIÓN (ALCARRIZOS II)','IglesiaID' => '13','Iglesia' => 'Central Monte de Sión','Lng' => '-70.0378','Lat' => '18.5225','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Central Monte de Sion'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '28','DistritoDesc' => 'MONTE DE SIÓN (ALCARRIZOS II)','IglesiaID' => '13','Iglesia' => 'Central Monte de Sión','Lng' => '-70.0378','Lat' => '18.5225','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Roca Junior'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '28','DistritoDesc' => 'MONTE DE SIÓN (ALCARRIZOS II)','IglesiaID' => '13','Iglesia' => 'Central Monte de Sión','Lng' => '-70.0378','Lat' => '18.5225','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Peniel'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '28','DistritoDesc' => 'MONTE DE SIÓN (ALCARRIZOS II)','IglesiaID' => '13','Iglesia' => 'Central Monte de Sión','Lng' => '-70.0378','Lat' => '18.5225','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Roca de Sion'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '30','DistritoDesc' => 'HERALDOS DE SIÓN','IglesiaID' => '14','Iglesia' => 'Fuente de las Aguas','Lng' => '-70.0493','Lat' => '18.515','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'AGUA DE VIDA'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '30','DistritoDesc' => 'HERALDOS DE SIÓN','IglesiaID' => '15','Iglesia' => 'Heraldos de Sión','Lng' => '-70.0354','Lat' => '18.5288','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Centinelas'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '30','DistritoDesc' => 'HERALDOS DE SIÓN','IglesiaID' => '15','Iglesia' => 'Heraldos de Sión','Lng' => '-70.0354','Lat' => '18.5288','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Fe de Sion'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '30','DistritoDesc' => 'HERALDOS DE SIÓN','IglesiaID' => '15','Iglesia' => 'Heraldos de Sión','Lng' => '-70.0354','Lat' => '18.5288','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Fuentes de las aguas'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '30','DistritoDesc' => 'HERALDOS DE SIÓN','IglesiaID' => '15','Iglesia' => 'Heraldos de Sión','Lng' => '-70.0354','Lat' => '18.5288','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Heraldos de Sion'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '30','DistritoDesc' => 'HERALDOS DE SIÓN','IglesiaID' => '15','Iglesia' => 'Heraldos de Sión','Lng' => '-70.0354','Lat' => '18.5288','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Leones Rugientes'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '30','DistritoDesc' => 'HERALDOS DE SIÓN','IglesiaID' => '15','Iglesia' => 'Heraldos de Sión','Lng' => '-70.0354','Lat' => '18.5288','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'leones rugientes'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '30','DistritoDesc' => 'HERALDOS DE SIÓN','IglesiaID' => '15','Iglesia' => 'Heraldos de Sión','Lng' => '-70.0354','Lat' => '18.5288','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Soldaditos de fe'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '30','DistritoDesc' => 'HERALDOS DE SIÓN','IglesiaID' => '15','Iglesia' => 'Heraldos de Sión','Lng' => '-70.0354','Lat' => '18.5288','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Mahanain'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '30','DistritoDesc' => 'HERALDOS DE SIÓN','IglesiaID' => '15','Iglesia' => 'Heraldos de Sión','Lng' => '-70.0354','Lat' => '18.5288','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Hijos del Rey'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '28','DistritoDesc' => 'MONTE DE SIÓN (ALCARRIZOS II)','IglesiaID' => '16','Iglesia' => 'Vislumbre de Sion','Lng' => '-70.0334','Lat' => '18.5192','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '29','DistritoDesc' => 'EL REMANENTE','IglesiaID' => '17','Iglesia' => 'Pantoja','Lng' => '-69.9902','Lat' => '18.5345','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Pantoja'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '29','DistritoDesc' => 'EL REMANENTE','IglesiaID' => '17','Iglesia' => 'Pantoja','Lng' => '-69.9902','Lat' => '18.5345','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'LAS COLINAS'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '29','DistritoDesc' => 'EL REMANENTE','IglesiaID' => '17','Iglesia' => 'Pantoja','Lng' => '-69.9902','Lat' => '18.5345','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Soldaditos de Jesus'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '29','DistritoDesc' => 'EL REMANENTE','IglesiaID' => '17','Iglesia' => 'Pantoja','Lng' => '-69.9902','Lat' => '18.5345','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'pantoja'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '30','DistritoDesc' => 'HERALDOS DE SIÓN','IglesiaID' => '18','Iglesia' => 'Fe de Sion','Lng' => '-70.0397','Lat' => '18.5252','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Fe de Sion'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '30','DistritoDesc' => 'HERALDOS DE SIÓN','IglesiaID' => '18','Iglesia' => 'Fe de Sion','Lng' => '-70.0397','Lat' => '18.5252','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Mahanain'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '30','DistritoDesc' => 'HERALDOS DE SIÓN','IglesiaID' => '19','Iglesia' => 'APOCALIPSIS 2','Lng' => '-70.0039','Lat' => '18.504','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '29','DistritoDesc' => 'EL REMANENTE','IglesiaID' => '20','Iglesia' => 'CARMEN RENATA III','Lng' => '-70.0018','Lat' => '18.5027','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Carmen Renata III'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '51','DistritoDesc' => 'PANTOJA','IglesiaID' => '21','Iglesia' => 'LOS HUMILDES','Lng' => '-70.0392','Lat' => '18.532','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '31','DistritoDesc' => 'MARANATHA','IglesiaID' => '22','Iglesia' => 'CENTRAL MARANATHA','Lng' => '-70.0032','Lat' => '18.504','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Angeles de Luz'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '31','DistritoDesc' => 'MARANATHA','IglesiaID' => '22','Iglesia' => 'CENTRAL MARANATHA','Lng' => '-70.0032','Lat' => '18.504','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Los Angeles'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '31','DistritoDesc' => 'MARANATHA','IglesiaID' => '22','Iglesia' => 'CENTRAL MARANATHA','Lng' => '-70.0032','Lat' => '18.504','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Maranatha'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '31','DistritoDesc' => 'MARANATHA','IglesiaID' => '22','Iglesia' => 'CENTRAL MARANATHA','Lng' => '-70.0032','Lat' => '18.504','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Ciudad Real 2'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '31','DistritoDesc' => 'MARANATHA','IglesiaID' => '22','Iglesia' => 'CENTRAL MARANATHA','Lng' => '-70.0032','Lat' => '18.504','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Central Maratha'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '31','DistritoDesc' => 'MARANATHA','IglesiaID' => '22','Iglesia' => 'CENTRAL MARANATHA','Lng' => '-70.0032','Lat' => '18.504','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Los Angeles'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '31','DistritoDesc' => 'MARANATHA','IglesiaID' => '22','Iglesia' => 'CENTRAL MARANATHA','Lng' => '-70.0032','Lat' => '18.504','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'CENTRAL MARANATHA'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '31','DistritoDesc' => 'MARANATHA','IglesiaID' => '22','Iglesia' => 'CENTRAL MARANATHA','Lng' => '-70.0032','Lat' => '18.504','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Los Angeles'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '28','DistritoDesc' => 'MONTE DE SIÓN (ALCARRIZOS II)','IglesiaID' => '23','Iglesia' => 'CENTRAL MONTE DE SION','Lng' => '-70.0021','Lat' => '18.505','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '27','DistritoDesc' => 'APOCALIPSIS (ALCARRIZOS I)','IglesiaID' => '24','Iglesia' => 'VALLE DE CANAAN','Lng' => '-70.0035','Lat' => '18.504','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '27','DistritoDesc' => 'APOCALIPSIS (ALCARRIZOS I)','IglesiaID' => '25','Iglesia' => 'LUZ EN LA 41','Lng' => '-70.0042','Lat' => '18.5044','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '32','DistritoDesc' => 'Central Redencion','IglesiaID' => '26','Iglesia' => 'PABLO MELLA MORALES','Lng' => '-70.0035','Lat' => '18.5066','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '27','DistritoDesc' => 'APOCALIPSIS (ALCARRIZOS I)','IglesiaID' => '27','Iglesia' => 'SAVICA','Lng' => '-70.0039','Lat' => '18.5047','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '29','DistritoDesc' => 'EL REMANENTE','IglesiaID' => '28','Iglesia' => 'RENACER','Lng' => '-70.0028','Lat' => '18.5047','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '28','DistritoDesc' => 'MONTE DE SIÓN (ALCARRIZOS II)','IglesiaID' => '29','Iglesia' => 'LUZ DE SION','Lng' => '-70.0021','Lat' => '18.506','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '27','DistritoDesc' => 'APOCALIPSIS (ALCARRIZOS I)','IglesiaID' => '30','Iglesia' => 'LAS MERCEDES','Lng' => '-70.0032','Lat' => '18.506','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '31','DistritoDesc' => 'MARANATHA','IglesiaID' => '31','Iglesia' => 'ANGELES DE LUZ','Lng' => '-70.0018','Lat' => '18.5066','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '27','DistritoDesc' => 'APOCALIPSIS (ALCARRIZOS I)','IglesiaID' => '32','Iglesia' => 'VISLUMBRE CELESTIAL','Lng' => '-70.0014','Lat' => '18.5057','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '30','DistritoDesc' => 'HERALDOS DE SIÓN','IglesiaID' => '33','Iglesia' => 'VOZ DE SALVACION 1','Lng' => '-70.0032','Lat' => '18.5063','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Pequenos Leones'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '30','DistritoDesc' => 'HERALDOS DE SIÓN','IglesiaID' => '34','Iglesia' => 'VOZ DE SALVACION 2','Lng' => '-70.0035','Lat' => '18.505','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '30','DistritoDesc' => 'HERALDOS DE SIÓN','IglesiaID' => '35','Iglesia' => 'SALVACION EN EL PROGRESO','Lng' => '-70.0035','Lat' => '18.5053','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '33','DistritoDesc' => 'AVANZADA VILLA ALTAGRACIA','IglesiaID' => '36','Iglesia' => 'VILLA ALTAGRACIA','Lng' => '-70.0039','Lat' => '18.5044','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Orion'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '33','DistritoDesc' => 'AVANZADA VILLA ALTAGRACIA','IglesiaID' => '36','Iglesia' => 'VILLA ALTAGRACIA','Lng' => '-70.0039','Lat' => '18.5044','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Villa Altagracia'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '33','DistritoDesc' => 'AVANZADA VILLA ALTAGRACIA','IglesiaID' => '36','Iglesia' => 'VILLA ALTAGRACIA','Lng' => '-70.0039','Lat' => '18.5044','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Orion'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '27','DistritoDesc' => 'APOCALIPSIS (ALCARRIZOS I)','IglesiaID' => '37','Iglesia' => 'CENTRAL LUZ DE APOCALIPSIS','Lng' => '-70.0035','Lat' => '18.505','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Esperanza Gloriosa'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '27','DistritoDesc' => 'APOCALIPSIS (ALCARRIZOS I)','IglesiaID' => '37','Iglesia' => 'CENTRAL LUZ DE APOCALIPSIS','Lng' => '-70.0035','Lat' => '18.505','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Esperanza Gloriosa'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '27','DistritoDesc' => 'APOCALIPSIS (ALCARRIZOS I)','IglesiaID' => '37','Iglesia' => 'CENTRAL LUZ DE APOCALIPSIS','Lng' => '-70.0035','Lat' => '18.505','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Primicias de Jesus'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '27','DistritoDesc' => 'APOCALIPSIS (ALCARRIZOS I)','IglesiaID' => '37','Iglesia' => 'CENTRAL LUZ DE APOCALIPSIS','Lng' => '-70.0035','Lat' => '18.505','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Encuentro de Paz'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '31','DistritoDesc' => 'MARANATHA','IglesiaID' => '38','Iglesia' => 'LOS ANGELES','Lng' => '-70.0056','Lat' => '18.5053','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '35','DistritoDesc' => 'GIRASOLES','IglesiaID' => '39','Iglesia' => 'CENTRAL GIRASOLES','Lng' => '-70.0028','Lat' => '18.506','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Los Girasoles'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '35','DistritoDesc' => 'GIRASOLES','IglesiaID' => '39','Iglesia' => 'CENTRAL GIRASOLES','Lng' => '-70.0028','Lat' => '18.506','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Girasoles 2'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '35','DistritoDesc' => 'GIRASOLES','IglesiaID' => '39','Iglesia' => 'CENTRAL GIRASOLES','Lng' => '-70.0028','Lat' => '18.506','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Roca Eterna'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '35','DistritoDesc' => 'GIRASOLES','IglesiaID' => '39','Iglesia' => 'CENTRAL GIRASOLES','Lng' => '-70.0028','Lat' => '18.506','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Girasoles 3'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '35','DistritoDesc' => 'GIRASOLES','IglesiaID' => '39','Iglesia' => 'CENTRAL GIRASOLES','Lng' => '-70.0028','Lat' => '18.506','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Samaria'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '35','DistritoDesc' => 'GIRASOLES','IglesiaID' => '39','Iglesia' => 'CENTRAL GIRASOLES','Lng' => '-70.0028','Lat' => '18.506','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Fuente de Salvacion'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '35','DistritoDesc' => 'GIRASOLES','IglesiaID' => '39','Iglesia' => 'CENTRAL GIRASOLES','Lng' => '-70.0028','Lat' => '18.506','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Fuerte Pregon'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '35','DistritoDesc' => 'GIRASOLES','IglesiaID' => '39','Iglesia' => 'CENTRAL GIRASOLES','Lng' => '-70.0028','Lat' => '18.506','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Girasoles hacia el Cielo'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '35','DistritoDesc' => 'GIRASOLES','IglesiaID' => '39','Iglesia' => 'CENTRAL GIRASOLES','Lng' => '-70.0028','Lat' => '18.506','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Girasoles 3'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '35','DistritoDesc' => 'GIRASOLES','IglesiaID' => '39','Iglesia' => 'CENTRAL GIRASOLES','Lng' => '-70.0028','Lat' => '18.506','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Girasoles 3'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '35','DistritoDesc' => 'GIRASOLES','IglesiaID' => '39','Iglesia' => 'CENTRAL GIRASOLES','Lng' => '-70.0028','Lat' => '18.506','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Fuentes de Salvacion'),
  array('ZonaID' => '1','ZonaDesc' => 'ZONA I','DistritoID' => '5','DistritoDesc' => 'AVANZADA EL CINCO','IglesiaID' => '40','Iglesia' => 'cambita uribe calle 1ra','Lng' => '-70.1522','Lat' => '18.4055','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '1','ZonaDesc' => 'ZONA I','DistritoID' => '3','DistritoDesc' => 'EL PUEBLECITO','IglesiaID' => '41','Iglesia' => 'EL PUEBLECITO CALLE PRIMERA','Lng' => '-70.1755','Lat' => '18.4542','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'JOSE WOLFF'),
  array('ZonaID' => '1','ZonaDesc' => 'ZONA I','DistritoID' => '3','DistritoDesc' => 'EL PUEBLECITO','IglesiaID' => '41','Iglesia' => 'EL PUEBLECITO CALLE PRIMERA','Lng' => '-70.1755','Lat' => '18.4542','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Luces de Esperanza'),
  array('ZonaID' => '1','ZonaDesc' => 'ZONA I','DistritoID' => '5','DistritoDesc' => 'AVANZADA EL CINCO','IglesiaID' => '42','Iglesia' => 'FARO DE ESPERANZA','Lng' => '-70.1526','Lat' => '18.4052','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'ZURISADAI'),
  array('ZonaID' => '1','ZonaDesc' => 'ZONA I','DistritoID' => '5','DistritoDesc' => 'AVANZADA EL CINCO','IglesiaID' => '42','Iglesia' => 'FARO DE ESPERANZA','Lng' => '-70.1526','Lat' => '18.4052','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'ZURIZADAI'),
  array('ZonaID' => '1','ZonaDesc' => 'ZONA I','DistritoID' => '1','DistritoDesc' => 'CAMBITA I','IglesiaID' => '43','Iglesia' => 'CENTRAL CAMBITA','Lng' => '-70.1999','Lat' => '18.4549','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'CENTRAL CAMBITA'),
  array('ZonaID' => '1','ZonaDesc' => 'ZONA I','DistritoID' => '1','DistritoDesc' => 'CAMBITA I','IglesiaID' => '43','Iglesia' => 'CENTRAL CAMBITA','Lng' => '-70.1999','Lat' => '18.4549','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Central cambita'),
  array('ZonaID' => '1','ZonaDesc' => 'ZONA I','DistritoID' => '1','DistritoDesc' => 'CAMBITA I','IglesiaID' => '43','Iglesia' => 'CENTRAL CAMBITA','Lng' => '-70.1999','Lat' => '18.4549','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'CENTRAL CAMBITA'),
  array('ZonaID' => '1','ZonaDesc' => 'ZONA I','DistritoID' => '4','DistritoDesc' => 'BUENAS NUEVAS','IglesiaID' => '44','Iglesia' => 'CENTRAL BUENAS NUEBAS','Lng' => '-70.1919','Lat' => '18.4531','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'CENTRAL BUENAS NUEVAS'),
  array('ZonaID' => '1','ZonaDesc' => 'ZONA I','DistritoID' => '4','DistritoDesc' => 'BUENAS NUEVAS','IglesiaID' => '44','Iglesia' => 'CENTRAL BUENAS NUEBAS','Lng' => '-70.1919','Lat' => '18.4531','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'CENTRAL BUENAS NUEVAS'),
  array('ZonaID' => '1','ZonaDesc' => 'ZONA I','DistritoID' => '4','DistritoDesc' => 'BUENAS NUEVAS','IglesiaID' => '44','Iglesia' => 'CENTRAL BUENAS NUEBAS','Lng' => '-70.1919','Lat' => '18.4531','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'CENTRAL BUENAS NUEVAS'),
  array('ZonaID' => '1','ZonaDesc' => 'ZONA I','DistritoID' => '4','DistritoDesc' => 'BUENAS NUEVAS','IglesiaID' => '45','Iglesia' => 'ESPERANZA DE VIDA','Lng' => '-70.0984','Lat' => '18.4404','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'ESCUADRON DE JUDA'),
  array('ZonaID' => '1','ZonaDesc' => 'ZONA I','DistritoID' => '4','DistritoDesc' => 'BUENAS NUEVAS','IglesiaID' => '45','Iglesia' => 'ESPERANZA DE VIDA','Lng' => '-70.0984','Lat' => '18.4404','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Herederos de Sion'),
  array('ZonaID' => '1','ZonaDesc' => 'ZONA I','DistritoID' => '5','DistritoDesc' => 'AVANZADA EL CINCO','IglesiaID' => '46','Iglesia' => 'LOS TOROS','Lng' => '-70.0808','Lat' => '18.4298','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Los Toros'),
  array('ZonaID' => '1','ZonaDesc' => 'ZONA I','DistritoID' => '5','DistritoDesc' => 'AVANZADA EL CINCO','IglesiaID' => '46','Iglesia' => 'LOS TOROS','Lng' => '-70.0808','Lat' => '18.4298','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Los Toros'),
  array('ZonaID' => '1','ZonaDesc' => 'ZONA I','DistritoID' => '4','DistritoDesc' => 'BUENAS NUEVAS','IglesiaID' => '47','Iglesia' => 'LA TOMA','Lng' => '-70.0775','Lat' => '18.4335','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'JAAC'),
  array('ZonaID' => '1','ZonaDesc' => 'ZONA I','DistritoID' => '4','DistritoDesc' => 'BUENAS NUEVAS','IglesiaID' => '47','Iglesia' => 'LA TOMA','Lng' => '-70.0775','Lat' => '18.4335','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'la toma'),
  array('ZonaID' => '1','ZonaDesc' => 'ZONA I','DistritoID' => '4','DistritoDesc' => 'BUENAS NUEVAS','IglesiaID' => '47','Iglesia' => 'LA TOMA','Lng' => '-70.0775','Lat' => '18.4335','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'la toma'),
  array('ZonaID' => '1','ZonaDesc' => 'ZONA I','DistritoID' => '4','DistritoDesc' => 'BUENAS NUEVAS','IglesiaID' => '48','Iglesia' => 'Esperanza de vida','Lng' => '-70.2023','Lat' => '18.4578','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '1','ZonaDesc' => 'ZONA I','DistritoID' => '3','DistritoDesc' => 'EL PUEBLECITO','IglesiaID' => '49','Iglesia' => 'IGLESIA DE PUEBLO NUEVO DE CAMBITA','Lng' => '-70.1747','Lat' => '18.4433','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '1','ZonaDesc' => 'ZONA I','DistritoID' => '4','DistritoDesc' => 'BUENAS NUEVAS','IglesiaID' => '50','Iglesia' => 'Iglesia de Mucha Agua','Lng' => '-70.243','Lat' => '18.4302','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Leones de Juda'),
  array('ZonaID' => '1','ZonaDesc' => 'ZONA I','DistritoID' => '3','DistritoDesc' => 'EL PUEBLECITO','IglesiaID' => '51','Iglesia' => 'Pueblo Nuevo','Lng' => '-70.1752','Lat' => '18.4435','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'John Wicleff'),
  array('ZonaID' => '1','ZonaDesc' => 'ZONA I','DistritoID' => '3','DistritoDesc' => 'EL PUEBLECITO','IglesiaID' => '51','Iglesia' => 'Pueblo Nuevo','Lng' => '-70.1752','Lat' => '18.4435','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'John Wicleff Yunior'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '52','DistritoDesc' => 'Sendero de Luz','IglesiaID' => '52','Iglesia' => 'Sendero de Luz','Lng' => '-70.0938','Lat' => '18.4164','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Orion'),
  array('ZonaID' => '5','ZonaDesc' => 'ZONA V','DistritoID' => '40','DistritoDesc' => 'NUEVA VISTA','IglesiaID' => '53','Iglesia' => 'LUZ DE BELLA VISTA','Lng' => '-69.9448','Lat' => '18.456','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Luz de Bella Vista'),
  array('ZonaID' => '5','ZonaDesc' => 'ZONA V','DistritoID' => '40','DistritoDesc' => 'NUEVA VISTA','IglesiaID' => '53','Iglesia' => 'LUZ DE BELLA VISTA','Lng' => '-69.9448','Lat' => '18.456','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Luz de Bella vista'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '31','DistritoDesc' => 'MARANATHA','IglesiaID' => '54','Iglesia' => 'Angeles de Luz','Lng' => '-70.0021','Lat' => '18.5053','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '32','DistritoDesc' => 'Central Redencion','IglesiaID' => '55','Iglesia' => 'Ciudad Satelite','Lng' => '-70.0008','Lat' => '18.5066','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '21','DistritoDesc' => 'MADRE VIEJA NORTE','IglesiaID' => '56','Iglesia' => 'Iglesia Amisadai','Lng' => '-70.0983','Lat' => '18.4145','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Amisaday'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '21','DistritoDesc' => 'MADRE VIEJA NORTE','IglesiaID' => '56','Iglesia' => 'Iglesia Amisadai','Lng' => '-70.0983','Lat' => '18.4145','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Amisadai'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '52','DistritoDesc' => 'Sendero de Luz','IglesiaID' => '57','Iglesia' => 'Hato Damas','Lng' => '-70.1244','Lat' => '18.4324','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Avencris'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '12','DistritoDesc' => 'PUEBLO NUEVO','IglesiaID' => '58','Iglesia' => 'El Cerro','Lng' => '-70.1246','Lat' => '18.401','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Aventureros del Rey'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '12','DistritoDesc' => 'PUEBLO NUEVO','IglesiaID' => '58','Iglesia' => 'El Cerro','Lng' => '-70.1246','Lat' => '18.401','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'El Cerro'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '12','DistritoDesc' => 'PUEBLO NUEVO','IglesiaID' => '58','Iglesia' => 'El Cerro','Lng' => '-70.1246','Lat' => '18.401','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Nueva Antorcha'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '7','DistritoDesc' => 'SAN CRISTÓBAL CENTRAL','IglesiaID' => '59','Iglesia' => 'Iglesia Adventista Central San Cristobal','Lng' => '-70.1103','Lat' => '18.4145','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Jetai Junior'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '7','DistritoDesc' => 'SAN CRISTÓBAL CENTRAL','IglesiaID' => '59','Iglesia' => 'Iglesia Adventista Central San Cristobal','Lng' => '-70.1103','Lat' => '18.4145','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Jetai'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '7','DistritoDesc' => 'SAN CRISTÓBAL CENTRAL','IglesiaID' => '59','Iglesia' => 'Iglesia Adventista Central San Cristobal','Lng' => '-70.1103','Lat' => '18.4145','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'JETAI EGM'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '7','DistritoDesc' => 'SAN CRISTÓBAL CENTRAL','IglesiaID' => '60','Iglesia' => 'Iglesia Adventista Central San Cristobal','Lng' => '-70.1103','Lat' => '18.4145','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '7','DistritoDesc' => 'SAN CRISTÓBAL CENTRAL','IglesiaID' => '61','Iglesia' => 'Iglesia Adventista Central San Cristobal','Lng' => '-70.1103','Lat' => '18.4145','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '7','DistritoDesc' => 'SAN CRISTÓBAL CENTRAL','IglesiaID' => '62','Iglesia' => 'Iglesia Adventista Central San Cristobal','Lng' => '-70.1103','Lat' => '18.4145','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '7','DistritoDesc' => 'SAN CRISTÓBAL CENTRAL','IglesiaID' => '63','Iglesia' => 'Iglesia Adventista Central San Cristobal','Lng' => '-70.1104','Lat' => '18.4145','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '7','DistritoDesc' => 'SAN CRISTÓBAL CENTRAL','IglesiaID' => '64','Iglesia' => 'Iglesia Adventista Central San Cristobal','Lng' => '-70.1103','Lat' => '18.4145','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '7','DistritoDesc' => 'SAN CRISTÓBAL CENTRAL','IglesiaID' => '65','Iglesia' => 'Iglesia Adventista Central San Cristobal','Lng' => '-70.1104','Lat' => '18.4145','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '52','DistritoDesc' => 'Sendero de Luz','IglesiaID' => '66','Iglesia' => 'Nuevo Amanecer','Lng' => '-70.0799','Lat' => '18.4239','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Bello Amanecer'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '52','DistritoDesc' => 'Sendero de Luz','IglesiaID' => '67','Iglesia' => 'Nuevo Amanecer','Lng' => '-70.0799','Lat' => '18.4239','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Nuevo Amanecer'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '15','DistritoDesc' => 'LAS FLORES','IglesiaID' => '68','Iglesia' => 'Rayo de Luz','Lng' => '-70.1149','Lat' => '18.4271','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Comando de Ángeles Junior'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '15','DistritoDesc' => 'LAS FLORES','IglesiaID' => '68','Iglesia' => 'Rayo de Luz','Lng' => '-70.1149','Lat' => '18.4271','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Coman2 de Angeles'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '10','DistritoDesc' => 'MADRE VIEJA SUR','IglesiaID' => '69','Iglesia' => 'Central Madre Vieja Sur','Lng' => '-70.0991','Lat' => '18.4182','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Edvers Junior'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '10','DistritoDesc' => 'MADRE VIEJA SUR','IglesiaID' => '69','Iglesia' => 'Central Madre Vieja Sur','Lng' => '-70.0991','Lat' => '18.4182','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Edvers'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '10','DistritoDesc' => 'MADRE VIEJA SUR','IglesiaID' => '69','Iglesia' => 'Central Madre Vieja Sur','Lng' => '-70.0991','Lat' => '18.4182','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Edvers Maxium'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '9','DistritoDesc' => 'CANASTICA','IglesiaID' => '70','Iglesia' => 'Emanuel','Lng' => '-70.1274','Lat' => '18.411','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Emanuel Junior'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '9','DistritoDesc' => 'CANASTICA','IglesiaID' => '70','Iglesia' => 'Emanuel','Lng' => '-70.1274','Lat' => '18.411','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Emanuel'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '9','DistritoDesc' => 'CANASTICA','IglesiaID' => '70','Iglesia' => 'Emanuel','Lng' => '-70.1274','Lat' => '18.411','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Emanuel Maxium'),
  array('ZonaID' => '5','ZonaDesc' => 'ZONA V','DistritoID' => '39','DistritoDesc' => 'LOS JARDINES','IglesiaID' => '71','Iglesia' => 'ARROYO HONDO','Lng' => '-69.9518','Lat' => '18.5029','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Arroyo Hondo'),
  array('ZonaID' => '5','ZonaDesc' => 'ZONA V','DistritoID' => '39','DistritoDesc' => 'LOS JARDINES','IglesiaID' => '71','Iglesia' => 'ARROYO HONDO','Lng' => '-69.9518','Lat' => '18.5029','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Arroyo Hondo'),
  array('ZonaID' => '5','ZonaDesc' => 'ZONA V','DistritoID' => '39','DistritoDesc' => 'LOS JARDINES','IglesiaID' => '71','Iglesia' => 'ARROYO HONDO','Lng' => '-69.9518','Lat' => '18.5029','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Renacer'),
  array('ZonaID' => '5','ZonaDesc' => 'ZONA V','DistritoID' => '62','DistritoDesc' => '30 de Mayo','IglesiaID' => '72','Iglesia' => 'HUMILDAD 7ma','Lng' => '-69.98','Lat' => '18.4295','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Fusion'),
  array('ZonaID' => '5','ZonaDesc' => 'ZONA V','DistritoID' => '62','DistritoDesc' => '30 de Mayo','IglesiaID' => '72','Iglesia' => 'HUMILDAD 7ma','Lng' => '-69.98','Lat' => '18.4295','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Humildad 7ma'),
  array('ZonaID' => '5','ZonaDesc' => 'ZONA V','DistritoID' => '20','DistritoDesc' => '30 DE MAYO','IglesiaID' => '73','Iglesia' => 'humildad 7ma','Lng' => '-69.9799','Lat' => '18.4294','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '9','DistritoDesc' => 'CANASTICA','IglesiaID' => '74','Iglesia' => 'Faro de Amor','Lng' => '-70.1404','Lat' => '18.401','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Faro de Amor Aventureros'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '9','DistritoDesc' => 'CANASTICA','IglesiaID' => '74','Iglesia' => 'Faro de Amor','Lng' => '-70.1404','Lat' => '18.401','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'SABANETA'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '9','DistritoDesc' => 'CANASTICA','IglesiaID' => '74','Iglesia' => 'Faro de Amor','Lng' => '-70.1404','Lat' => '18.401','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Angeles de Paz'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '9','DistritoDesc' => 'CANASTICA','IglesiaID' => '74','Iglesia' => 'Faro de Amor','Lng' => '-70.1404','Lat' => '18.401','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Faro de Amor'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '9','DistritoDesc' => 'CANASTICA','IglesiaID' => '74','Iglesia' => 'Faro de Amor','Lng' => '-70.1404','Lat' => '18.401','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Angeles de Paz'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '9','DistritoDesc' => 'CANASTICA','IglesiaID' => '75','Iglesia' => 'Canasticca II','Lng' => '-70.1246','Lat' => '18.4089','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Gireyi Junior'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '9','DistritoDesc' => 'CANASTICA','IglesiaID' => '75','Iglesia' => 'Canasticca II','Lng' => '-70.1246','Lat' => '18.4089','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Gireyi Maxium'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '9','DistritoDesc' => 'CANASTICA','IglesiaID' => '75','Iglesia' => 'Canasticca II','Lng' => '-70.1246','Lat' => '18.4089','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Gireyi'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '15','DistritoDesc' => 'LAS FLORES','IglesiaID' => '76','Iglesia' => 'Central Las Flores','Lng' => '-70.1137','Lat' => '18.4214','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Jengi Junior'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '15','DistritoDesc' => 'LAS FLORES','IglesiaID' => '76','Iglesia' => 'Central Las Flores','Lng' => '-70.1137','Lat' => '18.4214','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Jengi'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '6','DistritoDesc' => 'VILLA ESPERANZA','IglesiaID' => '77','Iglesia' => 'Arca de Salvacion','Lng' => '-70.1046','Lat' => '18.3976','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Lucec'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '6','DistritoDesc' => 'VILLA ESPERANZA','IglesiaID' => '77','Iglesia' => 'Arca de Salvacion','Lng' => '-70.1046','Lat' => '18.3976','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Lucec'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '6','DistritoDesc' => 'VILLA ESPERANZA','IglesiaID' => '77','Iglesia' => 'Arca de Salvacion','Lng' => '-70.1046','Lat' => '18.3976','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Los Angeles'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '13','DistritoDesc' => 'YAGUATE','IglesiaID' => '78','Iglesia' => 'Luz Divina I','Lng' => '-70.1531','Lat' => '18.3816','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Luz Divina Junior'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '13','DistritoDesc' => 'YAGUATE','IglesiaID' => '79','Iglesia' => 'Luz Divina II','Lng' => '-70.1784','Lat' => '18.3351','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Ovejitas del Rey'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '13','DistritoDesc' => 'YAGUATE','IglesiaID' => '79','Iglesia' => 'Luz Divina II','Lng' => '-70.1784','Lat' => '18.3351','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Luz Diniva II'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '13','DistritoDesc' => 'YAGUATE','IglesiaID' => '79','Iglesia' => 'Luz Divina II','Lng' => '-70.1784','Lat' => '18.3351','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Nishi'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '21','DistritoDesc' => 'MADRE VIEJA NORTE','IglesiaID' => '80','Iglesia' => 'Central Manantial de Luz','Lng' => '-70.107','Lat' => '18.425','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Shalom Junior'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '21','DistritoDesc' => 'MADRE VIEJA NORTE','IglesiaID' => '80','Iglesia' => 'Central Manantial de Luz','Lng' => '-70.107','Lat' => '18.425','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Manantial de Luz'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '15','DistritoDesc' => 'LAS FLORES','IglesiaID' => '81','Iglesia' => 'El Amor','Lng' => '-70.1181','Lat' => '18.4206','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Mensajeros de Amor Junior'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '15','DistritoDesc' => 'LAS FLORES','IglesiaID' => '81','Iglesia' => 'El Amor','Lng' => '-70.1181','Lat' => '18.4206','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Mensajeros de Amor'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '12','DistritoDesc' => 'PUEBLO NUEVO','IglesiaID' => '82','Iglesia' => 'Central Pueblo nuevo','Lng' => '-70.1116','Lat' => '18.4056','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Paraiso Infantil'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '12','DistritoDesc' => 'PUEBLO NUEVO','IglesiaID' => '82','Iglesia' => 'Central Pueblo nuevo','Lng' => '-70.1116','Lat' => '18.4056','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'La compañia'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '12','DistritoDesc' => 'PUEBLO NUEVO','IglesiaID' => '82','Iglesia' => 'Central Pueblo nuevo','Lng' => '-70.1116','Lat' => '18.4056','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'La Compañia Maxium'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '21','DistritoDesc' => 'MADRE VIEJA NORTE','IglesiaID' => '83','Iglesia' => 'Central Madre Vieja Norte','Lng' => '-70.1026','Lat' => '18.4239','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Perfección Norte Junior'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '21','DistritoDesc' => 'MADRE VIEJA NORTE','IglesiaID' => '83','Iglesia' => 'Central Madre Vieja Norte','Lng' => '-70.1026','Lat' => '18.4239','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Perfección Norte'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '21','DistritoDesc' => 'MADRE VIEJA NORTE','IglesiaID' => '83','Iglesia' => 'Central Madre Vieja Norte','Lng' => '-70.1026','Lat' => '18.4239','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Perfección Maxium'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '21','DistritoDesc' => 'MADRE VIEJA NORTE','IglesiaID' => '83','Iglesia' => 'Central Madre Vieja Norte','Lng' => '-70.1026','Lat' => '18.4239','ClubTipo' => '1','TipoClubDesc' => 'Castor','NombreClub' => 'Mi Buen Pastor'),
  array('ZonaID' => '7','ZonaDesc' => 'ZONA 7','DistritoID' => '23','DistritoDesc' => 'VILLA LISA (HAINA II)','IglesiaID' => '84','Iglesia' => 'VILLA LISA','Lng' => '-70.0307','Lat' => '18.4203','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '5','ZonaDesc' => 'ZONA V','DistritoID' => '40','DistritoDesc' => 'NUEVA VISTA','IglesiaID' => '85','Iglesia' => 'MIRADOR','Lng' => '-69.9584','Lat' => '18.4461','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Mirador'),
  array('ZonaID' => '5','ZonaDesc' => 'ZONA V','DistritoID' => '40','DistritoDesc' => 'NUEVA VISTA','IglesiaID' => '85','Iglesia' => 'MIRADOR','Lng' => '-69.9584','Lat' => '18.4461','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Mirador'),
  array('ZonaID' => '5','ZonaDesc' => 'ZONA V','DistritoID' => '62','DistritoDesc' => '30 de Mayo','IglesiaID' => '86','Iglesia' => 'HUMILDAD 3ra','Lng' => '-69.9767','Lat' => '18.4308','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Humildad 3era'),
  array('ZonaID' => '5','ZonaDesc' => 'ZONA V','DistritoID' => '62','DistritoDesc' => '30 de Mayo','IglesiaID' => '86','Iglesia' => 'HUMILDAD 3ra','Lng' => '-69.9767','Lat' => '18.4308','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Humildad 3era'),
  array('ZonaID' => '5','ZonaDesc' => 'ZONA V','DistritoID' => '66','DistritoDesc' => 'Pergamo II','IglesiaID' => '87','Iglesia' => 'HOREB 1','Lng' => '-69.9521','Lat' => '18.468','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Horeb 1'),
  array('ZonaID' => '5','ZonaDesc' => 'ZONA V','DistritoID' => '38','DistritoDesc' => 'QUISQUEYA II','IglesiaID' => '88','Iglesia' => 'MANGANAGUA','Lng' => '-69.965','Lat' => '18.4529','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Abriendo Caminos'),
  array('ZonaID' => '5','ZonaDesc' => 'ZONA V','DistritoID' => '62','DistritoDesc' => '30 de Mayo','IglesiaID' => '89','Iglesia' => 'HUMILDAD 4ta','Lng' => '-70.0093','Lat' => '18.4273','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Humildad 4ta'),
  array('ZonaID' => '5','ZonaDesc' => 'ZONA V','DistritoID' => '39','DistritoDesc' => 'LOS JARDINES','IglesiaID' => '90','Iglesia' => 'RIOS DE ESPERANZA','Lng' => '-69.9613','Lat' => '18.4979','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Rios de Esperanza'),
  array('ZonaID' => '5','ZonaDesc' => 'ZONA V','DistritoID' => '39','DistritoDesc' => 'LOS JARDINES','IglesiaID' => '90','Iglesia' => 'RIOS DE ESPERANZA','Lng' => '-69.9613','Lat' => '18.4979','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Rios de Esperanza'),
  array('ZonaID' => '5','ZonaDesc' => 'ZONA V','DistritoID' => '40','DistritoDesc' => 'NUEVA VISTA','IglesiaID' => '91','Iglesia' => 'IGLESIA NUEVA','Lng' => '-69.9258','Lat' => '18.4564','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Iglesia Nueva'),
  array('ZonaID' => '5','ZonaDesc' => 'ZONA V','DistritoID' => '40','DistritoDesc' => 'NUEVA VISTA','IglesiaID' => '91','Iglesia' => 'IGLESIA NUEVA','Lng' => '-69.9258','Lat' => '18.4564','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Iglesia Nueva'),
  array('ZonaID' => '5','ZonaDesc' => 'ZONA V','DistritoID' => '40','DistritoDesc' => 'NUEVA VISTA','IglesiaID' => '92','Iglesia' => 'BELLA VISTA','Lng' => '-69.9309','Lat' => '18.4578','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Bella Vista'),
  array('ZonaID' => '5','ZonaDesc' => 'ZONA V','DistritoID' => '40','DistritoDesc' => 'NUEVA VISTA','IglesiaID' => '92','Iglesia' => 'BELLA VISTA','Lng' => '-69.9309','Lat' => '18.4578','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Bella Vista'),
  array('ZonaID' => '5','ZonaDesc' => 'ZONA V','DistritoID' => '62','DistritoDesc' => '30 de Mayo','IglesiaID' => '93','Iglesia' => 'HUMILDAD 2da','Lng' => '-69.9631','Lat' => '18.4377','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'La Formula 1'),
  array('ZonaID' => '5','ZonaDesc' => 'ZONA V','DistritoID' => '62','DistritoDesc' => '30 de Mayo','IglesiaID' => '93','Iglesia' => 'HUMILDAD 2da','Lng' => '-69.9631','Lat' => '18.4377','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'La Formula 1'),
  array('ZonaID' => '5','ZonaDesc' => 'ZONA V','DistritoID' => '62','DistritoDesc' => '30 de Mayo','IglesiaID' => '94','Iglesia' => 'HUMILDAD 4ta','Lng' => '-69.9987','Lat' => '18.4294','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Humildad 4ta'),
  array('ZonaID' => '7','ZonaDesc' => 'ZONA 7','DistritoID' => '63','DistritoDesc' => 'Avanzada Sabaneta','IglesiaID' => '95','Iglesia' => 'SABANETA','Lng' => '-70.033','Lat' => '18.4383','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'SABANETA'),
  array('ZonaID' => '7','ZonaDesc' => 'ZONA 7','DistritoID' => '63','DistritoDesc' => 'Avanzada Sabaneta','IglesiaID' => '95','Iglesia' => 'SABANETA','Lng' => '-70.033','Lat' => '18.4383','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'SABANETA'),
  array('ZonaID' => '7','ZonaDesc' => 'ZONA 7','DistritoID' => '63','DistritoDesc' => 'Avanzada Sabaneta','IglesiaID' => '96','Iglesia' => 'LA FELICIANA','Lng' => '-70.044','Lat' => '18.4656','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'LA FELICIANA'),
  array('ZonaID' => '7','ZonaDesc' => 'ZONA 7','DistritoID' => '23','DistritoDesc' => 'VILLA LISA (HAINA II)','IglesiaID' => '97','Iglesia' => 'BARSEQUILLO','Lng' => '-70.0394','Lat' => '18.4254','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'BARSEQUILLO'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '7','DistritoDesc' => 'SAN CRISTÓBAL CENTRAL','IglesiaID' => '98','Iglesia' => 'Nueva Esperanza','Lng' => '-70.1234','Lat' => '18.4107','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'SDC Junior'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '7','DistritoDesc' => 'SAN CRISTÓBAL CENTRAL','IglesiaID' => '98','Iglesia' => 'Nueva Esperanza','Lng' => '-70.1234','Lat' => '18.4107','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Soldados de Cristo'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '7','DistritoDesc' => 'SAN CRISTÓBAL CENTRAL','IglesiaID' => '98','Iglesia' => 'Nueva Esperanza','Lng' => '-70.1234','Lat' => '18.4107','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'SDC EGM'),
  array('ZonaID' => '7','ZonaDesc' => 'ZONA 7','DistritoID' => '11','DistritoDesc' => 'HAINA I','IglesiaID' => '99','Iglesia' => 'HAINA I','Lng' => '-70.0343','Lat' => '18.4144','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'HAINA I'),
  array('ZonaID' => '7','ZonaDesc' => 'ZONA 7','DistritoID' => '11','DistritoDesc' => 'HAINA I','IglesiaID' => '99','Iglesia' => 'HAINA I','Lng' => '-70.0343','Lat' => '18.4144','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'HAINA I'),
  array('ZonaID' => '7','ZonaDesc' => 'ZONA 7','DistritoID' => '11','DistritoDesc' => 'HAINA I','IglesiaID' => '99','Iglesia' => 'HAINA I','Lng' => '-70.0343','Lat' => '18.4144','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'HAINA I'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '14','DistritoDesc' => 'PALENQUE','IglesiaID' => '100','Iglesia' => 'Central Palenque','Lng' => '-70.1479','Lat' => '18.2613','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Pequeños de Adriel'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '14','DistritoDesc' => 'PALENQUE','IglesiaID' => '100','Iglesia' => 'Central Palenque','Lng' => '-70.1479','Lat' => '18.2613','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Adriel'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '14','DistritoDesc' => 'PALENQUE','IglesiaID' => '100','Iglesia' => 'Central Palenque','Lng' => '-70.1479','Lat' => '18.2613','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Adriel Maxter'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '6','DistritoDesc' => 'VILLA ESPERANZA','IglesiaID' => '101','Iglesia' => 'Cañada Honda','Lng' => '-70.1199','Lat' => '18.3987','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Pequeños Halcones'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '6','DistritoDesc' => 'VILLA ESPERANZA','IglesiaID' => '101','Iglesia' => 'Cañada Honda','Lng' => '-70.1199','Lat' => '18.3987','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Halcones Dorados'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '6','DistritoDesc' => 'VILLA ESPERANZA','IglesiaID' => '101','Iglesia' => 'Cañada Honda','Lng' => '-70.1199','Lat' => '18.3987','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Halcones Dorados Maxium'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '14','DistritoDesc' => 'PALENQUE','IglesiaID' => '102','Iglesia' => 'Sabana Palenque','Lng' => '-70.1639','Lat' => '18.2496','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Pescadores Junior'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '14','DistritoDesc' => 'PALENQUE','IglesiaID' => '102','Iglesia' => 'Sabana Palenque','Lng' => '-70.1639','Lat' => '18.2496','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Pescadores'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '14','DistritoDesc' => 'PALENQUE','IglesiaID' => '102','Iglesia' => 'Sabana Palenque','Lng' => '-70.1639','Lat' => '18.2496','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Pescadores Maxium'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '12','DistritoDesc' => 'PUEBLO NUEVO','IglesiaID' => '103','Iglesia' => 'Nueva Vida','Lng' => '-70.1197','Lat' => '18.4097','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Pléyades Junior'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '12','DistritoDesc' => 'PUEBLO NUEVO','IglesiaID' => '103','Iglesia' => 'Nueva Vida','Lng' => '-70.1197','Lat' => '18.4097','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Pléyades de Jesús'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '12','DistritoDesc' => 'PUEBLO NUEVO','IglesiaID' => '103','Iglesia' => 'Nueva Vida','Lng' => '-70.1197','Lat' => '18.4097','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Pléyades Maxium'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '9','DistritoDesc' => 'CANASTICA','IglesiaID' => '104','Iglesia' => 'Canastica Central','Lng' => '-70.1331','Lat' => '18.4034','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Pricex Junior'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '9','DistritoDesc' => 'CANASTICA','IglesiaID' => '104','Iglesia' => 'Canastica Central','Lng' => '-70.1331','Lat' => '18.4034','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Pricex'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '9','DistritoDesc' => 'CANASTICA','IglesiaID' => '104','Iglesia' => 'Canastica Central','Lng' => '-70.1331','Lat' => '18.4034','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Pricex Maxium'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '9','DistritoDesc' => 'CANASTICA','IglesiaID' => '104','Iglesia' => 'Canastica Central','Lng' => '-70.1331','Lat' => '18.4034','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Pricex Junior'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '6','DistritoDesc' => 'VILLA ESPERANZA','IglesiaID' => '105','Iglesia' => 'Central Villa Esperanza','Lng' => '-70.1074','Lat' => '18.4038','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Villa Esperanza'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '6','DistritoDesc' => 'VILLA ESPERANZA','IglesiaID' => '105','Iglesia' => 'Central Villa Esperanza','Lng' => '-70.1074','Lat' => '18.4038','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Villa Esperanza'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '6','DistritoDesc' => 'VILLA ESPERANZA','IglesiaID' => '105','Iglesia' => 'Central Villa Esperanza','Lng' => '-70.1074','Lat' => '18.4038','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Villa Esperanza EGM'),
  array('ZonaID' => '7','ZonaDesc' => 'ZONA 7','DistritoID' => '23','DistritoDesc' => 'VILLA LISA (HAINA II)','IglesiaID' => '106','Iglesia' => 'CENTRAL VILLA LISA','Lng' => '-70.0307','Lat' => '18.4203','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'VILLA LISA'),
  array('ZonaID' => '7','ZonaDesc' => 'ZONA 7','DistritoID' => '23','DistritoDesc' => 'VILLA LISA (HAINA II)','IglesiaID' => '106','Iglesia' => 'CENTRAL VILLA LISA','Lng' => '-70.0307','Lat' => '18.4203','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'VILLA LISA'),
  array('ZonaID' => '7','ZonaDesc' => 'ZONA 7','DistritoID' => '23','DistritoDesc' => 'VILLA LISA (HAINA II)','IglesiaID' => '106','Iglesia' => 'CENTRAL VILLA LISA','Lng' => '-70.0307','Lat' => '18.4203','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'VILLA LISA'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '9','DistritoDesc' => 'CANASTICA','IglesiaID' => '107','Iglesia' => 'Faro de Amor','Lng' => '-70.1429','Lat' => '18.4016','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '7','ZonaDesc' => 'ZONA 7','DistritoID' => '11','DistritoDesc' => 'HAINA I','IglesiaID' => '108','Iglesia' => 'CRISTO FUENTE DE VIDA','Lng' => '-70.0362','Lat' => '18.3964','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'LOS CAZADORES JR'),
  array('ZonaID' => '7','ZonaDesc' => 'ZONA 7','DistritoID' => '11','DistritoDesc' => 'HAINA I','IglesiaID' => '108','Iglesia' => 'CRISTO FUENTE DE VIDA','Lng' => '-70.0362','Lat' => '18.3964','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'LOS CAZADORES'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '6','DistritoDesc' => 'VILLA ESPERANZA','IglesiaID' => '109','Iglesia' => 'Sainagua','Lng' => '-70.1133','Lat' => '18.3887','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'DANS'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '13','DistritoDesc' => 'YAGUATE','IglesiaID' => '110','Iglesia' => 'Mana de Yaguate','Lng' => '-70.1785','Lat' => '18.334','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Elacrim'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '13','DistritoDesc' => 'YAGUATE','IglesiaID' => '110','Iglesia' => 'Mana de Yaguate','Lng' => '-70.1785','Lat' => '18.334','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'ElaCrim'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '52','DistritoDesc' => 'Sendero de Luz','IglesiaID' => '111','Iglesia' => 'Villa Fundación','Lng' => '-70.1289','Lat' => '18.4284','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Gedeon'),
  array('ZonaID' => '7','ZonaDesc' => 'ZONA 7','DistritoID' => '63','DistritoDesc' => 'Avanzada Sabaneta','IglesiaID' => '112','Iglesia' => 'EL CAJUILITO','Lng' => '-70.0528','Lat' => '18.4331','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'SOL SALIENTE'),
  array('ZonaID' => '7','ZonaDesc' => 'ZONA 7','DistritoID' => '63','DistritoDesc' => 'Avanzada Sabaneta','IglesiaID' => '112','Iglesia' => 'EL CAJUILITO','Lng' => '-70.0528','Lat' => '18.4331','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'HIDEKEL'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '52','DistritoDesc' => 'Sendero de Luz','IglesiaID' => '113','Iglesia' => 'Sendero de Luz II','Lng' => '-70.1193','Lat' => '18.4337','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Jeyi'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '13','DistritoDesc' => 'YAGUATE','IglesiaID' => '114','Iglesia' => 'Santana','Lng' => '-70.3344','Lat' => '18.2795','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Los 3 Angeles'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '13','DistritoDesc' => 'YAGUATE','IglesiaID' => '114','Iglesia' => 'Santana','Lng' => '-70.3344','Lat' => '18.2795','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Arcoiris'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '14','DistritoDesc' => 'PALENQUE','IglesiaID' => '115','Iglesia' => 'Igleisia Najayo','Lng' => '-70.1068','Lat' => '18.3018','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Nave'),
  array('ZonaID' => '7','ZonaDesc' => 'ZONA 7','DistritoID' => '23','DistritoDesc' => 'VILLA LISA (HAINA II)','IglesiaID' => '116','Iglesia' => 'VISTA DEL VALLE','Lng' => '-70.0164','Lat' => '18.4323','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'VISTA DEL VALLE'),
  array('ZonaID' => '7','ZonaDesc' => 'ZONA 7','DistritoID' => '23','DistritoDesc' => 'VILLA LISA (HAINA II)','IglesiaID' => '117','Iglesia' => 'VILLA MARIA','Lng' => '-70.0329','Lat' => '18.4327','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'VILLA MARIA'),
  array('ZonaID' => '1','ZonaDesc' => 'ZONA I','DistritoID' => '4','DistritoDesc' => 'BUENAS NUEVAS','IglesiaID' => '118','Iglesia' => 'la toma 1','Lng' => '-70.2071','Lat' => '18.4503','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '52','DistritoDesc' => 'Sendero de Luz','IglesiaID' => '119','Iglesia' => 'Nuevo Amanecer','Lng' => '-70.0817','Lat' => '18.4246','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '10','DistritoDesc' => 'MADRE VIEJA SUR','IglesiaID' => '120','Iglesia' => 'Fuente de Paz','Lng' => '-70.092','Lat' => '18.4142','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Serafines'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '21','DistritoDesc' => 'MADRE VIEJA NORTE','IglesiaID' => '121','Iglesia' => 'Manantial de Luz II','Lng' => '-70.1056','Lat' => '18.4214','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Shekinah'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '6','DistritoDesc' => 'VILLA ESPERANZA','IglesiaID' => '122','Iglesia' => 'Villa Esperanza','Lng' => '-70.1074','Lat' => '18.4038','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '1','ZonaDesc' => 'ZONA I','DistritoID' => '5','DistritoDesc' => 'AVANZADA EL CINCO','IglesiaID' => '123','Iglesia' => 'Faro de Luz','Lng' => '-70.1487','Lat' => '18.4158','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'estrellitas de jesus'),
  array('ZonaID' => '6','ZonaDesc' => 'ZONA VI','DistritoID' => '43','DistritoDesc' => 'GAZCUE','IglesiaID' => '124','Iglesia' => 'Gazcue','Lng' => '-69.91','Lat' => '18.4642','ClubTipo' => '1','TipoClubDesc' => 'Castor','NombreClub' => 'Castores Gazcue'),
  array('ZonaID' => '6','ZonaDesc' => 'ZONA VI','DistritoID' => '43','DistritoDesc' => 'GAZCUE','IglesiaID' => '124','Iglesia' => 'Gazcue','Lng' => '-69.91','Lat' => '18.4642','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Gazcue - Aventureros'),
  array('ZonaID' => '6','ZonaDesc' => 'ZONA VI','DistritoID' => '43','DistritoDesc' => 'GAZCUE','IglesiaID' => '124','Iglesia' => 'Gazcue','Lng' => '-69.91','Lat' => '18.4642','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Gazcue - Conquistador'),
  array('ZonaID' => '6','ZonaDesc' => 'ZONA VI','DistritoID' => '43','DistritoDesc' => 'GAZCUE','IglesiaID' => '124','Iglesia' => 'Gazcue','Lng' => '-69.91','Lat' => '18.4642','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Gazcue - Guias Mayores'),
  array('ZonaID' => '6','ZonaDesc' => 'ZONA VI','DistritoID' => '46','DistritoDesc' => 'MISIONERA DE PAZ','IglesiaID' => '125','Iglesia' => 'Misionera de Paz','Lng' => '-69.9','Lat' => '18.4854','ClubTipo' => '1','TipoClubDesc' => 'Castor','NombreClub' => 'Misionera de Paz - Castores'),
  array('ZonaID' => '6','ZonaDesc' => 'ZONA VI','DistritoID' => '46','DistritoDesc' => 'MISIONERA DE PAZ','IglesiaID' => '125','Iglesia' => 'Misionera de Paz','Lng' => '-69.9','Lat' => '18.4854','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Misionera de Paz - Aventureros'),
  array('ZonaID' => '6','ZonaDesc' => 'ZONA VI','DistritoID' => '46','DistritoDesc' => 'MISIONERA DE PAZ','IglesiaID' => '125','Iglesia' => 'Misionera de Paz','Lng' => '-69.9','Lat' => '18.4854','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Misionera de Paz - Conquistadores'),
  array('ZonaID' => '6','ZonaDesc' => 'ZONA VI','DistritoID' => '46','DistritoDesc' => 'MISIONERA DE PAZ','IglesiaID' => '125','Iglesia' => 'Misionera de Paz','Lng' => '-69.9','Lat' => '18.4854','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Misionera de Paz - Guias'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '10','DistritoDesc' => 'MADRE VIEJA SUR','IglesiaID' => '126','Iglesia' => 'Villa Mercedes','Lng' => '-70.1014','Lat' => '18.41','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Eledem Junior'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '10','DistritoDesc' => 'MADRE VIEJA SUR','IglesiaID' => '126','Iglesia' => 'Villa Mercedes','Lng' => '-70.1014','Lat' => '18.41','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Eledem'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '10','DistritoDesc' => 'MADRE VIEJA SUR','IglesiaID' => '126','Iglesia' => 'Villa Mercedes','Lng' => '-70.1014','Lat' => '18.41','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Eledem Maxium'),
  array('ZonaID' => '6','ZonaDesc' => 'ZONA VI','DistritoID' => '44','DistritoDesc' => 'MELLA','IglesiaID' => '127','Iglesia' => 'Central Mella','Lng' => '-69.8894','Lat' => '18.4761','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Central Mella - Aventureros'),
  array('ZonaID' => '6','ZonaDesc' => 'ZONA VI','DistritoID' => '44','DistritoDesc' => 'MELLA','IglesiaID' => '127','Iglesia' => 'Central Mella','Lng' => '-69.8894','Lat' => '18.4761','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Central Mella - Conquistadores'),
  array('ZonaID' => '6','ZonaDesc' => 'ZONA VI','DistritoID' => '45','DistritoDesc' => 'LA PAZ','IglesiaID' => '128','Iglesia' => 'Iglesia Adventista La Paz','Lng' => '-69.9084','Lat' => '18.4862','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '19','DistritoDesc' => 'BETHEL','IglesiaID' => '129','Iglesia' => 'La Fidelidad','Lng' => '-69.9818','Lat' => '18.4644','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Fidelidad'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '19','DistritoDesc' => 'BETHEL','IglesiaID' => '129','Iglesia' => 'La Fidelidad','Lng' => '-69.9818','Lat' => '18.4644','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Fidelidad'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '24','DistritoDesc' => 'NUEVO EDÉN','IglesiaID' => '130','Iglesia' => 'Central Nuevo Eden','Lng' => '-69.9993','Lat' => '18.4649','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'El Nuevo Eden'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '18','DistritoDesc' => 'ESMIRNA','IglesiaID' => '131','Iglesia' => 'Esmirna 2','Lng' => '-69.9357','Lat' => '18.4895','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'New Power JR'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '18','DistritoDesc' => 'ESMIRNA','IglesiaID' => '131','Iglesia' => 'Esmirna 2','Lng' => '-69.9357','Lat' => '18.4895','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'New power'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '18','DistritoDesc' => 'ESMIRNA','IglesiaID' => '131','Iglesia' => 'Esmirna 2','Lng' => '-69.9357','Lat' => '18.4895','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'NEW POWER'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '18','DistritoDesc' => 'ESMIRNA','IglesiaID' => '131','Iglesia' => 'Esmirna 2','Lng' => '-69.9357','Lat' => '18.4895','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'New Power Children'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '16','DistritoDesc' => 'BETHANIA','IglesiaID' => '132','Iglesia' => 'Central Bethania','Lng' => '-69.9753','Lat' => '18.4754','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Central Bethania'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '16','DistritoDesc' => 'BETHANIA','IglesiaID' => '132','Iglesia' => 'Central Bethania','Lng' => '-69.9753','Lat' => '18.4754','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Central Bethania'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '16','DistritoDesc' => 'BETHANIA','IglesiaID' => '132','Iglesia' => 'Central Bethania','Lng' => '-69.9753','Lat' => '18.4754','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Central Bethania'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '74','DistritoDesc' => 'Bethania','IglesiaID' => '133','Iglesia' => 'Central Bethania','Lng' => '-69.9753','Lat' => '18.4754','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '18','DistritoDesc' => 'ESMIRNA','IglesiaID' => '134','Iglesia' => 'Esmirna Central','Lng' => '-69.9846','Lat' => '18.4464','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Chiqui Firmes'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '18','DistritoDesc' => 'ESMIRNA','IglesiaID' => '134','Iglesia' => 'Esmirna Central','Lng' => '-69.9846','Lat' => '18.4464','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Esmirna Central'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '18','DistritoDesc' => 'ESMIRNA','IglesiaID' => '135','Iglesia' => 'El Milagro','Lng' => '-69.9969','Lat' => '18.4369','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Mahanaim'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '24','DistritoDesc' => 'NUEVO EDÉN','IglesiaID' => '136','Iglesia' => 'Filadelfia','Lng' => '-70.0038','Lat' => '18.4694','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Filadelfia'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '24','DistritoDesc' => 'NUEVO EDÉN','IglesiaID' => '137','Iglesia' => 'El Amor Manoguayabo','Lng' => '-70.0064','Lat' => '18.4801','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Escuadrón de Ángeles Junior'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '24','DistritoDesc' => 'NUEVO EDÉN','IglesiaID' => '137','Iglesia' => 'El Amor Manoguayabo','Lng' => '-70.0064','Lat' => '18.4801','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Escuadron de Angeles Master'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '24','DistritoDesc' => 'NUEVO EDÉN','IglesiaID' => '137','Iglesia' => 'El Amor Manoguayabo','Lng' => '-70.0064','Lat' => '18.4801','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Escuadrón de Angeles'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '75','DistritoDesc' => 'Jerusalem','IglesiaID' => '138','Iglesia' => 'Jerusalem','Lng' => '-69.9786','Lat' => '18.4808','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Jerusalem'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '75','DistritoDesc' => 'Jerusalem','IglesiaID' => '138','Iglesia' => 'Jerusalem','Lng' => '-69.9786','Lat' => '18.4808','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Jerusalem'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '75','DistritoDesc' => 'Jerusalem','IglesiaID' => '138','Iglesia' => 'Jerusalem','Lng' => '-69.9786','Lat' => '18.4808','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Jerusalen'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '76','DistritoDesc' => 'MANOGUAYABO','IglesiaID' => '139','Iglesia' => 'Sinai I','Lng' => '-70.0438','Lat' => '18.4822','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Monte Sinai'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '18','DistritoDesc' => 'ESMIRNA','IglesiaID' => '140','Iglesia' => 'Heraldos Del Abanico','Lng' => '-69.9858','Lat' => '18.4573','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Herald Baby'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '18','DistritoDesc' => 'ESMIRNA','IglesiaID' => '140','Iglesia' => 'Heraldos Del Abanico','Lng' => '-69.9858','Lat' => '18.4573','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Herald Free'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '18','DistritoDesc' => 'ESMIRNA','IglesiaID' => '140','Iglesia' => 'Heraldos Del Abanico','Lng' => '-69.9858','Lat' => '18.4573','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Herald Master'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '18','DistritoDesc' => 'ESMIRNA','IglesiaID' => '140','Iglesia' => 'Heraldos Del Abanico','Lng' => '-69.9858','Lat' => '18.4573','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Heral Baby'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '75','DistritoDesc' => 'Jerusalem','IglesiaID' => '141','Iglesia' => 'Rio Jordan','Lng' => '-70.0047','Lat' => '18.4949','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'NISSI'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '73','DistritoDesc' => 'Nuevo Eden','IglesiaID' => '142','Iglesia' => 'Filadelfia','Lng' => '-70.0038','Lat' => '18.4695','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Filadelfia'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '73','DistritoDesc' => 'Nuevo Eden','IglesiaID' => '142','Iglesia' => 'Filadelfia','Lng' => '-70.0038','Lat' => '18.4695','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Filadelfia'),
  array('ZonaID' => '6','ZonaDesc' => 'ZONA VI','DistritoID' => '78','DistritoDesc' => 'La Paz','IglesiaID' => '143','Iglesia' => 'La Paz','Lng' => '-69.9084','Lat' => '18.4863','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'La Paz - Aventureros'),
  array('ZonaID' => '6','ZonaDesc' => 'ZONA VI','DistritoID' => '78','DistritoDesc' => 'La Paz','IglesiaID' => '143','Iglesia' => 'La Paz','Lng' => '-69.9084','Lat' => '18.4863','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'La Paz - Conquistadores'),
  array('ZonaID' => '6','ZonaDesc' => 'ZONA VI','DistritoID' => '78','DistritoDesc' => 'La Paz','IglesiaID' => '144','Iglesia' => 'Jireh','Lng' => '-69.9157','Lat' => '18.487','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Jireh - Conquistadores'),
  array('ZonaID' => '6','ZonaDesc' => 'ZONA VI','DistritoID' => '67','DistritoDesc' => 'Misionera de Paz','IglesiaID' => '145','Iglesia' => 'Misionera de Paz','Lng' => '-69.9','Lat' => '18.4854','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '6','ZonaDesc' => 'ZONA VI','DistritoID' => '46','DistritoDesc' => 'MISIONERA DE PAZ','IglesiaID' => '146','Iglesia' => 'Ciudad Nueva','Lng' => '-69.8915','Lat' => '18.469','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '6','ZonaDesc' => 'ZONA VI','DistritoID' => '47','DistritoDesc' => 'NAZARETH','IglesiaID' => '147','Iglesia' => 'Nazareth (Los Kakis)','Lng' => '-69.9255','Lat' => '18.5011','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Nazareth (Los Kakis) - Aventureros'),
  array('ZonaID' => '6','ZonaDesc' => 'ZONA VI','DistritoID' => '47','DistritoDesc' => 'NAZARETH','IglesiaID' => '147','Iglesia' => 'Nazareth (Los Kakis)','Lng' => '-69.9255','Lat' => '18.5011','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Nazareth (Los Kakis) - Conquistadores'),
  array('ZonaID' => '6','ZonaDesc' => 'ZONA VI','DistritoID' => '47','DistritoDesc' => 'NAZARETH','IglesiaID' => '148','Iglesia' => 'La Agustina','Lng' => '-69.9293','Lat' => '18.4931','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'La Agustina - Aventureros'),
  array('ZonaID' => '6','ZonaDesc' => 'ZONA VI','DistritoID' => '47','DistritoDesc' => 'NAZARETH','IglesiaID' => '148','Iglesia' => 'La Agustina','Lng' => '-69.9293','Lat' => '18.4931','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'La Agustina - Conquistador'),
  array('ZonaID' => '6','ZonaDesc' => 'ZONA VI','DistritoID' => '46','DistritoDesc' => 'MISIONERA DE PAZ','IglesiaID' => '149','Iglesia' => 'La Felicidad','Lng' => '-69.8943','Lat' => '18.4763','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'La Felicidad - Conquistadores'),
  array('ZonaID' => '6','ZonaDesc' => 'ZONA VI','DistritoID' => '47','DistritoDesc' => 'NAZARETH','IglesiaID' => '150','Iglesia' => 'Voz de Esperanza','Lng' => '-69.9217','Lat' => '18.5042','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Voz de Esperanza - Guias'),
  array('ZonaID' => '6','ZonaDesc' => 'ZONA VI','DistritoID' => '47','DistritoDesc' => 'NAZARETH','IglesiaID' => '150','Iglesia' => 'Voz de Esperanza','Lng' => '-69.9217','Lat' => '18.5042','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Voz de Esperanza - Conquistador'),
  array('ZonaID' => '6','ZonaDesc' => 'ZONA VI','DistritoID' => '47','DistritoDesc' => 'NAZARETH','IglesiaID' => '150','Iglesia' => 'Voz de Esperanza','Lng' => '-69.9217','Lat' => '18.5042','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Voz de Esperanza - Aventureros'),
  array('ZonaID' => '6','ZonaDesc' => 'ZONA VI','DistritoID' => '47','DistritoDesc' => 'NAZARETH','IglesiaID' => '150','Iglesia' => 'Voz de Esperanza','Lng' => '-69.9217','Lat' => '18.5042','ClubTipo' => '1','TipoClubDesc' => 'Castor','NombreClub' => 'Voz de Esperanza - Castores'),
  array('ZonaID' => '6','ZonaDesc' => 'ZONA VI','DistritoID' => '48','DistritoDesc' => 'PIANTINI','IglesiaID' => '151','Iglesia' => 'Piantini','Lng' => '-69.9366','Lat' => '18.4757','ClubTipo' => '1','TipoClubDesc' => 'Castor','NombreClub' => 'Piantini - Castores'),
  array('ZonaID' => '6','ZonaDesc' => 'ZONA VI','DistritoID' => '48','DistritoDesc' => 'PIANTINI','IglesiaID' => '151','Iglesia' => 'Piantini','Lng' => '-69.9366','Lat' => '18.4757','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Piantini - Conquistadores'),
  array('ZonaID' => '6','ZonaDesc' => 'ZONA VI','DistritoID' => '48','DistritoDesc' => 'PIANTINI','IglesiaID' => '151','Iglesia' => 'Piantini','Lng' => '-69.9366','Lat' => '18.4757','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Piantini - Aventureros'),
  array('ZonaID' => '6','ZonaDesc' => 'ZONA VI','DistritoID' => '48','DistritoDesc' => 'PIANTINI','IglesiaID' => '151','Iglesia' => 'Piantini','Lng' => '-69.9366','Lat' => '18.4757','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Piantini - Guias Mayores'),
  array('ZonaID' => '6','ZonaDesc' => 'ZONA VI','DistritoID' => '48','DistritoDesc' => 'PIANTINI','IglesiaID' => '152','Iglesia' => 'Naco','Lng' => '-69.9265','Lat' => '18.4788','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Naco - Aventureros'),
  array('ZonaID' => '6','ZonaDesc' => 'ZONA VI','DistritoID' => '48','DistritoDesc' => 'PIANTINI','IglesiaID' => '152','Iglesia' => 'Naco','Lng' => '-69.9265','Lat' => '18.4788','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Naco - Conquistadores'),
  array('ZonaID' => '6','ZonaDesc' => 'ZONA VI','DistritoID' => '47','DistritoDesc' => 'NAZARETH','IglesiaID' => '153','Iglesia' => 'Luz de Nazareth','Lng' => '-69.9288','Lat' => '18.5011','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Luz de Nazareth - Aventureros'),
  array('ZonaID' => '6','ZonaDesc' => 'ZONA VI','DistritoID' => '47','DistritoDesc' => 'NAZARETH','IglesiaID' => '153','Iglesia' => 'Luz de Nazareth','Lng' => '-69.9288','Lat' => '18.5011','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Luz de Nazareth - Conquistadores'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '17','DistritoDesc' => 'LAS CAOBAS','IglesiaID' => '154','Iglesia' => 'Las Caobas','Lng' => '-69.9953','Lat' => '18.4785','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Embajadores del Rey'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '17','DistritoDesc' => 'LAS CAOBAS','IglesiaID' => '154','Iglesia' => 'Las Caobas','Lng' => '-69.9953','Lat' => '18.4785','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Embajadores del Rey'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '16','DistritoDesc' => 'BETHANIA','IglesiaID' => '155','Iglesia' => 'Pregoneros de Esperanza','Lng' => '-69.9824','Lat' => '18.4718','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Centinelas de Esperanza'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '16','DistritoDesc' => 'BETHANIA','IglesiaID' => '156','Iglesia' => 'Palmas 2','Lng' => '-69.9799','Lat' => '18.4732','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Eliam Baby'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '52','DistritoDesc' => 'Sendero de Luz','IglesiaID' => '157','Iglesia' => 'Manantial de Amor','Lng' => '-70.1251','Lat' => '18.4324','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Rayito de Luz'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '52','DistritoDesc' => 'Sendero de Luz','IglesiaID' => '157','Iglesia' => 'Manantial de Amor','Lng' => '-70.1251','Lat' => '18.4324','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Consanjes'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '79','DistritoDesc' => 'Central San Cristóbal','IglesiaID' => '158','Iglesia' => 'Santo Cabral','Lng' => '-70.106','Lat' => '18.4093','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '17','DistritoDesc' => 'LAS CAOBAS','IglesiaID' => '159','Iglesia' => 'Las Caobas','Lng' => '-69.9953','Lat' => '18.4785','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Embajadores del Rey'),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '17','DistritoDesc' => 'LAS CAOBAS','IglesiaID' => '160','Iglesia' => 'Central Las Caobas','Lng' => '-69.9953','Lat' => '18.4785','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '3','ZonaDesc' => 'ZONA III','DistritoID' => '16','DistritoDesc' => 'BETHANIA','IglesiaID' => '161','Iglesia' => 'Reparto Rosa','Lng' => '-69.9893','Lat' => '18.4684','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Depovic'),
  array('ZonaID' => '1','ZonaDesc' => 'ZONA I','DistritoID' => '4','DistritoDesc' => 'BUENAS NUEVAS','IglesiaID' => '162','Iglesia' => 'Mucha Agua','Lng' => '-70.2082','Lat' => '18.4564','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Primicias Para Cristo'),
  array('ZonaID' => '1','ZonaDesc' => 'ZONA I','DistritoID' => '3','DistritoDesc' => 'EL PUEBLECITO','IglesiaID' => '163','Iglesia' => 'Pueblecito 2','Lng' => '-70.1788','Lat' => '18.4527','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Mensajero de Cristo'),
  array('ZonaID' => '1','ZonaDesc' => 'ZONA I','DistritoID' => '3','DistritoDesc' => 'EL PUEBLECITO','IglesiaID' => '164','Iglesia' => 'Central Pueblecito','Lng' => '-70.1768','Lat' => '18.4481','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Luces De Esperanza'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '21','DistritoDesc' => 'MADRE VIEJA NORTE','IglesiaID' => '165','Iglesia' => 'Fuente de Esperanza','Lng' => '-70.0956','Lat' => '18.426','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Edejec'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '21','DistritoDesc' => 'MADRE VIEJA NORTE','IglesiaID' => '165','Iglesia' => 'Fuente de Esperanza','Lng' => '-70.0956','Lat' => '18.426','ClubTipo' => '4','TipoClubDesc' => 'Guia','NombreClub' => 'Atalaya'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '30','DistritoDesc' => 'HERALDOS DE SIÓN','IglesiaID' => '166','Iglesia' => 'Heraldos','Lng' => '-70.0028','Lat' => '18.5053','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Sol Rosmery Samantha'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '30','DistritoDesc' => 'HERALDOS DE SIÓN','IglesiaID' => '166','Iglesia' => 'Heraldos','Lng' => '-70.0028','Lat' => '18.5053','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Heraldos Aventureros'),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '30','DistritoDesc' => 'HERALDOS DE SIÓN','IglesiaID' => '166','Iglesia' => 'Heraldos','Lng' => '-70.0028','Lat' => '18.5053','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'centinelas del Rey'),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '9','DistritoDesc' => 'CANASTICA','IglesiaID' => '167','Iglesia' => 'Canastica Central','Lng' => '-70.1276','Lat' => '18.4071','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '2','ZonaDesc' => 'ZONA II','DistritoID' => '13','DistritoDesc' => 'YAGUATE','IglesiaID' => '168','Iglesia' => 'Mana de Yaguate','Lng' => '-70.1805','Lat' => '18.3347','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '4','ZonaDesc' => 'ZONA IV','DistritoID' => '27','DistritoDesc' => 'APOCALIPSIS (ALCARRIZOS I)','IglesiaID' => '169','Iglesia' => 'APOCALIPSIS 2','Lng' => '-70.0014','Lat' => '18.5047','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'APOCALISIS 2'),
  array('ZonaID' => '1','ZonaDesc' => 'ZONA I','DistritoID' => '2','DistritoDesc' => 'AVANZADA EL CACAO','IglesiaID' => '170','Iglesia' => 'Central Los Cacaos','Lng' => '-69.9524','Lat' => '18.5411','ClubTipo' => NULL,'TipoClubDesc' => 'N/A','NombreClub' => NULL),
  array('ZonaID' => '1','ZonaDesc' => 'ZONA I','DistritoID' => '3','DistritoDesc' => 'EL PUEBLECITO','IglesiaID' => '171','Iglesia' => 'PUEBLECITO 2','Lng' => '-69.9192','Lat' => '18.4681','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'MENSAJEROS DE CRISTO'),
  array('ZonaID' => '1','ZonaDesc' => 'ZONA I','DistritoID' => '2','DistritoDesc' => 'AVANZADA EL CACAO','IglesiaID' => '172','Iglesia' => 'CENTRAL EL CACAO','Lng' => '-69.8799','Lat' => '18.4405','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'BARSADAI'),
  array('ZonaID' => '1','ZonaDesc' => 'ZONA I','DistritoID' => '5','DistritoDesc' => 'AVANZADA EL CINCO','IglesiaID' => '173','Iglesia' => 'Shalom km5','Lng' => '-70.1459','Lat' => '18.4051','ClubTipo' => '3','TipoClubDesc' => 'Conquistador','NombreClub' => 'Jaguares de Jesus'),
  array('ZonaID' => '1','ZonaDesc' => 'ZONA I','DistritoID' => '5','DistritoDesc' => 'AVANZADA EL CINCO','IglesiaID' => '174','Iglesia' => 'Cambita Sterling','Lng' => '-70.1603','Lat' => '18.4273','ClubTipo' => '2','TipoClubDesc' => 'Aventurero','NombreClub' => 'Alfa y Omega')
);

// header('Content-Type: application/json');
echo  json_encode(array('Zonas' => $zonas ,'ZonasDet' => $zonaDetalle ));
}

}