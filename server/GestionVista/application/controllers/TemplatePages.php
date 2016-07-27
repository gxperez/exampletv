<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class TemplatePages extends MY_Controller {

	/**
	 * Index Page for this controller.
	 	@autor:	Grequis Xavier Perez Fortuna.
	 */
	public function index()
	{	
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

		// Carga de planilla web en general.
		$this->load->view("web/sm_template_pages", $data); 
	}

	public function ObtenerPorIDSliderMaestro(){
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		if($this->input->get("id")){

			$this->load->model("TemplatePages_Model", "mTemplatePages");
			$id = $this->input->get("id"); 
			$templatePages = $this->mTemplatePages->ObtenerPorIDSliderMaestro($id);
			echo json_encode(array("data" => $templatePages, "IsOk"=> true, "IsSession" => true)); 
		} else {

		}

	}


	public function Obtener(){
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		$idRol = 0; 
		$this->load->model("TemplatePages_Model", "mTemplatePages");
		$pagConf =  $this->config->item("client_pagination");

		if($this->input->get("vNumPage")){
			$pagina = $this->input->get("vNumPage");

			$listaTemplatePages = $this->mTemplatePages->obtenerTemplatePagesPaginado($pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );

		} else {
			$listaTemplatePages = $this->mTemplatePages->obtenerTemplatePagesPaginado($pagConf["RowsPerPages"], 0);
		}
		
		$totalResult = 0; 
		if(count($listaTemplatePages)> 0){
			$first = current($listaTemplatePages); 	
			$totalResult = $first->CountRow;
		}
		
		$first = current($listaTemplatePages); 
		echo json_encode(array("data" => $listaTemplatePages, "totalResult"=> $totalResult, "count"=> count($listaTemplatePages), "IsOk"=> true, "rowsPerPages"=> $pagConf["RowsPerPages"], "IsSession" => true)); 
	}

	public function Crear(){
	if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}
		$this->load->model("TemplatePages_Model", "mTemplatePages");
		// Auto Validacion del Formulario.

	$this->validation->set_rules("objeto[SliderMaestroID]", "SliderMaestroID", "required|integer"); 
$this->validation->set_rules("objeto[EsquemaTipo]", "EsquemaTipo", "required|integer"); 
$this->validation->set_rules("objeto[Duracion]", "Duracion", "required"); 
$this->validation->set_rules("objeto[TransicionTipoIni]", "TransicionTipoIni", "required|integer"); 
$this->validation->set_rules("objeto[TransicionTipoFin]", "TransicionTipoFin", "required|integer"); 
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

		$template_pagesObj = $this->security->xss_clean($this->input->post("objeto"));
		$template_pagesEnt = array('SliderMaestroID'=> $template_pagesObj['SliderMaestroID'] 
			, 'EsquemaTipo'=> $template_pagesObj['EsquemaTipo'] 
			, 'MostrarHeader'=> isset($template_pagesObj['MostrarHeader'])?$template_pagesObj['MostrarHeader']: 0
			, 'Duracion'=> $template_pagesObj['Duracion'] 
			, 'TransicionTipoIni'=> $template_pagesObj['TransicionTipoIni'] 
			, 'TransicionTipoFin'=> $template_pagesObj['TransicionTipoFin'] 
			, 'Estado'=> $template_pagesObj['Estado'] 
			, 'UsuarioModificaID'=> $this->session->userdata("sUsuario")["IDusuario"]
			, 'FechaModificacion'=> date('Y-m-d H:i:s') 
			);

			$id = $this->mTemplatePages->insertar( $template_pagesEnt ); 
			$template_pagesEnt["TemplatePagesID"] = $id; 

			echo json_encode(array("data" => $template_pagesEnt, "IsOk"=> true, "Msg"=>"Success", "IsSession" => true, "csrf" =>array(
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
		$this->load->model("TemplatePages_Model", "mTemplatePages");
		$this->validation->set_rules("objeto[SliderMaestroID]", "SliderMaestroID", "required|integer"); 
		$this->validation->set_rules("objeto[EsquemaTipo]", "EsquemaTipo", "required|integer"); 
		$this->validation->set_rules("objeto[MostrarHeader]", "MostrarHeader", "required"); 
		$this->validation->set_rules("objeto[Duracion]", "Duracion", "required"); 
		$this->validation->set_rules("objeto[TransicionTipoIni]", "TransicionTipoIni", "required|integer"); 
		$this->validation->set_rules("objeto[TransicionTipoFin]", "TransicionTipoFin", "required|integer"); 
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
		$template_pagesObj = $this->security->xss_clean($this->input->post("objeto"));

		$template_pagesEnt = $this->mTemplatePages->actualizar( $template_pagesObj );
				if( ! $template_pagesEnt ){
				echo json_encode(array("IsOk"=> false, "Msg"=> "Error DB al actualizar". print_r($template_pagesEnt, true), "csrf" =>array(
		        "name" => $this->security->get_csrf_token_name(),
		        "hash" => $this->security->get_csrf_hash()
		        ), "IsSession" => true )  );
		        return false; 
			}

			echo json_encode(array("data" => $template_pagesEnt, "IsOk"=> true, "Msg"=>"Success", "IsSession" => true, "csrf" =>array(
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

		$this->load->model("TemplatePages_Model", "mTemplatePages");

		$pagConf =  $this->config->item("client_pagination");
		if($this->input->get("vNumPage")){
			$pagina = $this->input->get("vNumPage"); 
			$listaTemplatePages = $this->mTemplatePages->obtenerTemplatePagesPorCampo("Descripcion", $str, $pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );
		} else {
			$listaTemplatePages = $this->mDispositivo->obtenerDispositivoPorCampo("Descripcion", $str, $pagConf["RowsPerPages"], 0);
		}
		$totalResult = 0; 
		if(count($listaTemplatePages)> 0){
			$first = current($listaTemplatePages); 	
			$totalResult = $first->CountRow;
		}
		echo json_encode(array("data" => $listaTemplatePages, "totalResult"=> $totalResult, "count"=> count($listaTemplatePages), "IsOk"=> true, "rowsPerPages" => $pagConf["RowsPerPages"], "IsSession" => true)); 
	}

	public function Eliminar(){
		// Eliminar a travez del post y del Get y validar la Session.
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		if($this->input->post("objeto")){			
			$this->load->model("TemplatePages_Model", "mTemplatePages");
			
			$template_pagesObj = $this->security->xss_clean($this->input->post("objeto"));  
			$result = $this->mDispositivo->cambiarEstado($dispositivoObj, -1); 

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

	// El esquema Envado por Get.
	public function obtenerTablaEsquemaID(){

		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}		

// Los bloques del Espero. 

		$listBlok = array();

		$listBlok["Full"] = '<div id="Full">
			<div class="Full" ng-click="seccionTemp.agregar(1, seccionTemp.listSeccion.pos_1 )"> <div id="pos_1"></div> </div>
			</div>'; 
		$listBlok["DxD"] = '<div id="DxD">
	<table class="tbltv">
		<tr>
			<td ng-click="seccionTemp.agregar(1, seccionTemp.listSeccion.pos_1 )"><div id="pos_1"></div> </td> <td ng-click="seccionTemp.agregar(2, seccionTemp.listSeccion.pos_2 )"> <div id="pos_2"></div> </td>
		</tr>
		<tr>
			<td ng-click="seccionTemp.agregar(3, seccionTemp.listSeccion.pos_3 )"><div id="pos_3"></div> </td> <td ng-click="seccionTemp.agregar(4, seccionTemp.listSeccion.pos_4 )"> <div id="pos_4"></div></td>
		</tr>
	</table>
</div>'; 

		$listBlok["TresxTres"] = '<div id="TresxTres">
	<table class="tbltv">
		<tr>
			<td ng-click="seccionTemp.agregar(1, seccionTemp.listSeccion.pos_1 )"><div id="pos_1"></div></td> <td ng-click="seccionTemp.agregar(2, seccionTemp.listSeccion.pos_2 )" ><div id="pos_2"></div> </td>  <td ng-click="seccionTemp.agregar(3, seccionTemp.listSeccion.pos_3 )"><div id="pos_3"></div></td>
		</tr>
		<tr>
			<td ng-click="seccionTemp.agregar(4, seccionTemp.listSeccion.pos_4 )"><div id="pos_4"></div></td> <td ng-click="seccionTemp.agregar(5, seccionTemp.listSeccion.pos_5 )" ><div id="pos_5"></div> </td>  <td ng-click="seccionTemp.agregar(6, seccionTemp.listSeccion.pos_6 )"><div id="pos_6"></div></td>
		</tr>
	</table>
</div>';


		$listBlok["Ux2_V"] = '<div id="Ux2_V">
	<table class="tbltv">
		<tbody><tr>
			<td ng-click="seccionTemp.agregar(1, seccionTemp.listSeccion.pos_1 )" colspan="2">   	<div id="pos_1">  </div>  	 </td>
		</tr>
		<tr>
			<td ng-click="seccionTemp.agregar(2, seccionTemp.listSeccion.pos_2 )" ><div id="pos_2"> </div></td> <td><div id="pos_3"> </div> </td> 
		</tr>
	</tbody></table>
</div>';

$listBlok["Ux3_V"] = '<div id="Ux3_V">
	<table class="tbltv">
		<tbody><tr>
			<td ng-click="seccionTemp.agregar(1, seccionTemp.listSeccion.pos_1 )" colspan="3">   	<div id="pos_1"> </div>  	 </td>
		</tr>
		<tr>
			<td ng-click="seccionTemp.agregar(2, seccionTemp.listSeccion.pos_2 )" ><div id="pos_2"> </div></td>  <td ng-click="seccionTemp.agregar(3, seccionTemp.listSeccion.pos_3 )"><div id="pos_3"> </div> </td>  <td ng-click="seccionTemp.agregar(4, seccionTemp.listSeccion.pos_4 )"><div id="pos_4"></div> </td> 
		</tr>
	</tbody></table>
</div>'; 

$listBlok["UX1_V"] = '<div id="UX1_V">
	<table class="tbltv">
		<tr>
			<td ng-click="seccionTemp.agregar(1, seccionTemp.listSeccion.pos_1 )">   	<div id="pos_1"></div>  	 </td>
		</tr>

		<tr>
			<td ng-click="seccionTemp.agregar(2, seccionTemp.listSeccion.pos_2 )" ><div id="pos_2"></div></td> 
		</tr>
	</table>
</div>'; 

$listBlok["Ux2_H"] = '<div id="Ux2_H">
	<table class="tbltv">
		<tbody>
		<tr>
			<td ng-click="seccionTemp.agregar(1, seccionTemp.listSeccion.pos_1 )" rowspan="2">   	<div id="pos_1"> </div> </td>   <td ng-click="seccionTemp.agregar(2, seccionTemp.listSeccion.pos_2 )"><div id="pos_2"> </div></td> 
		</tr>
		<tr>		
			<td ng-click="seccionTemp.agregar(3, seccionTemp.listSeccion.pos_3 )"><div id="pos_3">  </div></td> 
		</tr>
		</tbody>
	</table>
</div>'; 

	$listBlok["Ux3_H"] = '<div id="Ux3_H">
	<table class="tbltv">
		<tbody>
		<tr>
			<td ng-click="seccionTemp.agregar(1, seccionTemp.listSeccion.pos_1 )" rowspan="3">   	<div id="pos_1"> </div> </td>   <td ng-click="seccionTemp.agregar(2, seccionTemp.listSeccion.pos_2 )"><div id="pos_2"> </div></td> 
		</tr>

		<tr>		
			<td ng-click="seccionTemp.agregar(3, seccionTemp.listSeccion.pos_3 )" ><div id="pos_3">  </div></td> 
		</tr>

		<tr>		
			<td ng-click="seccionTemp.agregar(4, seccionTemp.listSeccion.pos_4 )" ><div id="pos_4">  </div></td> 
		</tr>
		</tbody>
	</table>
</div>'; 

	$listBlok["Ux1_H"] = '<div id="Ux1_H">
	<table class="tbltv">
		<tbody>
		<tr>
			<td ng-click="seccionTemp.agregar(1, seccionTemp.listSeccion.pos_1 )">   	<div id="pos_1"> </div> </td>   <td ng-click="seccionTemp.agregar(2, seccionTemp.listSeccion.pos_2 )" ><div id="pos_2"> </div></td> 
		</tr>
		</tbody>
	</table>
</div>'; 


if($this->input->get("EsquemaTipo")){	

	$keyGet = $this->input->get("EsquemaTipo"); 

	if(array_key_exists($keyGet, $listBlok)){
		echo $listBlok[$keyGet]; 
		return true; 
	}

} 



	}


}