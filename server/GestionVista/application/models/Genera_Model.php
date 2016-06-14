<?php 

class Genera_Model extends CI_Model {

	function __construct()
	{
		// Llamando al contructor del Modelo
		parent::__construct();
	}


	public function obtenerTablas($baseDatos){
	
		$this->load->database();
		$tablas = $this->db->list_tables();
		
		$listaForaneas = array();
		$resultadoTablas = array(); 
		foreach($tablas as $tb){
			$resultadoTablas[$tb] = $this->obtenerForaneas($tb, $baseDatos); 
		}	
		return $resultadoTablas; 
	
	}
	
	
	public function obtenerForaneas($table_name, $database){
	$this->load->database();
	
	 $listaCampos = $this->db->field_data($table_name);
		$query = $this->db->query("SELECT CONSTRAINT_SCHEMA, table_name, column_name, 
    CONCAT(table_name, '.', column_name) AS 'foreign_key',  
    CONCAT(referenced_table_name, '.', referenced_column_name) AS 'references'
FROM
    information_schema.key_column_usage
WHERE
    referenced_table_name IS NOT NULL and table_name = '$table_name' and  CONSTRAINT_SCHEMA = '$database' ");
	
	$foraneas = array(); 
		foreach ($query->result() as $row)
			{
			$foraneas[$row->column_name][] = array(  "SCHEMA"=>$row->CONSTRAINT_SCHEMA, "table_name"=>$row->table_name, "column_name"=>$row->column_name, "foreign_key"=> $row->foreign_key, "references"=>$row->references);  
			}
			
		$resultado = array(); 	
		foreach ($listaCampos as $field){
			if (array_key_exists($field->name, $foraneas)) {
				$resultado[] = array("name"=>$field->name, "type"=>$field->type, "max_length"=>$field->max_length, "primary_key"=>$field->primary_key, "foraneas"=> $foraneas[$field->name]);
			} else {
				$resultado[] = array("name"=>$field->name, "type"=>$field->type, "max_length"=>$field->max_length, "primary_key"=>$field->primary_key, "foraneas"=> null, "default"=> $field->default );
			
			}
		}	
		
			return $resultado; 
	}

	function obtenerDetalleTabla( $table_name, $database){

		$this->load->database();
		$query = $this->db->query("SELECT COLUMN_NAME as 'Nombre', IS_NULLABLE as 'IsNullable', DATA_TYPE as 'Type',  A.COLUMN_KEY as 'Key' , 
A.CHARACTER_MAXIMUM_LENGTH as max_length

FROM
    information_schema.COLUMNS as A
WHERE 
TABLE_SCHEMA = '{$database}' and table_name = '{$table_name}'"); 

		return $query->result(); 
	}

	public function obtenerDetalleTablaCampo($campo,  $table_name, $database){

		$this->load->database();
		$query = $this->db->query("SELECT COLUMN_NAME as 'Nombre', IS_NULLABLE as 'IsNullaBle', DATA_TYPE as 'Type',  A.COLUMN_KEY as 'Key' , 
A.CHARACTER_MAXIMUM_LENGTH as max_length

FROM
    information_schema.COLUMNS as A
WHERE 
TABLE_SCHEMA ='{$database}' and table_name = '{$table_name}' and COLUMN_NAME = '{$campo}'"); 

		return $query->result(); 

 }



	function limpiarCampo($name){
		$entidadTemp = strtolower($name); 
		$entidadIns = "";	
		$entidadTemp = explode("_", $entidadTemp);
	
		if (is_array($entidadTemp)) {
			foreach($entidadTemp as $val){
				$entidadIns .= ucfirst(trim($val));
			}
		} else {
		$entidadIns .= ucfirst(trim($entidadTemp));
		} 
		return $entidadIns;
	}

	public function formarController($sufijo, $tabla, $listaCampos, $login = false, $campos = null){
	
	$entidadTemp = strtolower($tabla);
	$entidadIns = $this->limpiarCampo(trim($entidadTemp));
	$claseNombre = $this->limpiarCampo($sufijo). $entidadIns;
	$primary_key = ""; 
	foreach ($listaCampos as $key => $value) {
		if($value['primary_key']){
					$primary_key = $value["name"]; 
				}		
	}	


		$encabezado = "
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class {$entidadIns} extends MY_Controller {

	/**
	 * Index Page for this controller.
	 	@autor:	Grequis Xavier Perez Fortuna.
	 */
	public function index()
	{	
	}
	"; 

	$encabezado .= '
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
		$this->load->view("web/sm_'.$tabla.'", $data); 
	}


	public function Obtener(){
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		$idRol = 0; 
		$this->load->model("'.$claseNombre .'_Model", "m'.$claseNombre .'");
		$pagConf =  $this->config->item("client_pagination");

		if($this->input->get("vNumPage")){
			$pagina = $this->input->get("vNumPage");

			$lista'.$claseNombre .' = $this->m'.$claseNombre .'->obtener'.$claseNombre .'Paginado($pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );

		} else {
			$lista'.$claseNombre .' = $this->m'.$claseNombre .'->obtener'.$claseNombre .'Paginado($pagConf["RowsPerPages"], 0);
		}
		
		$totalResult = 0; 
		if(count($lista'.$claseNombre .')> 0){
			$first = current($lista'.$claseNombre .'); 	
			$totalResult = $first->CountRow;
		}
		
		$first = current($lista'.$claseNombre .'); 
		echo json_encode(array("data" => $lista'.$claseNombre .', "totalResult"=> $totalResult, "count"=> count($lista'.$claseNombre .'), "IsOk"=> true, "rowsPerPages"=> $pagConf["RowsPerPages"], "IsSession" => true)); 
	}

	public function Crear(){
	if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}
		$this->load->model("'.$claseNombre .'_Model", "m'.$claseNombre .'");
		// Auto Validacion del Formulario.

	' . $this->generarRules("objeto", $tabla, $listaCampos, false). '

	if ($this->validation->run() == FALSE)
         {
            echo json_encode(array("IsOk"=> false, "Msg"=> validation_errors(), "IsSession" => true, "csrf" =>array(
        "name" => $this->security->get_csrf_token_name(),
        "hash" => $this->security->get_csrf_hash()
        ) )  );
           	return false;
    }

    if($this->input->post("objeto")){
		$'.$tabla .'Obj = $this->security->xss_clean($this->input->post("objeto"));

		'.$this->generarAsignate("objeto", $tabla, $listaCampos, false, false) . '

			$id = $this->m'.$claseNombre.'->insertar( $'.$tabla.'Ent ); 
			$'. $tabla .'Ent["'. $primary_key. '"] = $id; 

			echo json_encode(array("data" => $dispositivoEnt, "IsOk"=> true, "Msg"=>"Success", "IsSession" => true, "csrf" =>array(
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
		$this->load->model("'.$claseNombre .'_Model", "m'.$claseNombre .'");
	' . $this->generarRules("objeto", $tabla, $listaCampos, false).'
	if ($this->validation->run() == FALSE)
         {
            echo json_encode(array("IsOk"=> false, "Msg"=> validation_errors(), "IsSession" => true, "csrf" =>array(
        "name" => $this->security->get_csrf_token_name(),
        "hash" => $this->security->get_csrf_hash()
        ) )  );
           	return false;
    }

     if($this->input->post("objeto")){
		$'.$tabla .'Obj = $this->security->xss_clean($this->input->post("objeto"));
		$'.$tabla.'Ent = $this->m'.$claseNombre.'->actualizar( $'.$tabla.'Obj );

				if( ! $'.$tabla.'Ent ){
				echo json_encode(array("IsOk"=> false, "Msg"=> "Error DB al actualizar". print_r($'.$tabla.'Ent, true), "csrf" =>array(
		        "name" => $this->security->get_csrf_token_name(),
		        "hash" => $this->security->get_csrf_hash()
		        ), "IsSession" => true )  );
		        return false; 
			}

			echo json_encode(array("data" => $dispositivoObj, "IsOk"=> true, "Msg"=>"Success", "IsSession" => true, "csrf" =>array(
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

		$this->load->model("'.$claseNombre .'_Model", "m'.$claseNombre .'");

		$pagConf =  $this->config->item("client_pagination");
		if($this->input->get("vNumPage")){
			$pagina = $this->input->get("vNumPage"); 
			$lista'.$claseNombre .' = $this->m'.$claseNombre .'->obtener'.$claseNombre .'PorCampo("Descripcion", $str, $pagConf["RowsPerPages"], Text::calcularOffset($pagConf["RowsPerPages"],  $pagina) );
		} else {
			$lista'.$claseNombre .' = $this->mDispositivo->obtenerDispositivoPorCampo("Descripcion", $str, $pagConf["RowsPerPages"], 0);
		}
		$totalResult = 0; 
		if(count($lista'.$claseNombre .')> 0){
			$first = current($lista'.$claseNombre .'); 	
			$totalResult = $first->CountRow;
		}
		echo json_encode(array("data" => $lista'.$claseNombre .', "totalResult"=> $totalResult, "count"=> count($lista'.$claseNombre .'), "IsOk"=> true, "rowsPerPages" => $pagConf["RowsPerPages"], "IsSession" => true)); 
	}

	public function Eliminar(){
		// Eliminar a travez del post y del Get y validar la Session.
		if (!$this->session->userdata("sUsuario")){
			echo json_encode(array("IsSession" => false)); 
			return false; 
		}

		if($this->input->post("objeto")){			
			$this->load->model("'.$claseNombre .'_Model", "m'.$claseNombre .'");
			
			$'.$tabla.'Obj = $this->security->xss_clean($this->input->post("objeto"));  
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
}
	';
echo htmlentities($encabezado); 
	}	

	 public function formarVista($sufijo, $tabla, $listaCampos, $login = false, $campos = null){
	
	$entidadTemp = strtolower($tabla);
	$entidadIns = $this->limpiarCampo(trim($entidadTemp));
	$claseNombre = $this->limpiarCampo($sufijo). $entidadIns;
	$primary_key = ""; 
	foreach ($listaCampos as $key => $value) {
		if($value['primary_key']){
					$primary_key = $value["name"]; 
				}		
	}

	 	// "web/sm_nombre.php";	 	
	 	$htmlEncabezado = '<div  ng-controller="'.$claseNombre.'Controller" ng-init= "initt(); vCrud.setHash('. "'". '<?=$csrf["name"];?>'."', '". '<?=$csrf["hash"];?>'. "' ". ');">
  <style type="text/css"> </style>'; 


  $htmlEncabezado .= '
<div class="styleCrud">
<div id="ListMantenimiento">
              <div class="row mt mb">

    <div id="header-crudTools" class="crudTools col-md-12">     
                  <div>
                    <div class="btn-group">                      

                    <button id="btnGuardar" type="submit" class="btn btn-default" ng-click="vCrud.Editar(0)"><span class="fa fa-plus"></span> Agregar </button>
                  </div>
                  </div>
                  </div> 

     <div class="col-md-12">
                      <section class="task-panel tasks-widget">

                    <div class="panel-heading">


            <div class="pull-right">                                  

                            <input type="text" ng-model="buscarLista"  ng-keypress="Buscar($event)" class="round-form" >                            
                            <button type="button" class="btn btn-round btn-default" ng-click="Buscar($event)"><i class="fa fa-search"></i>  </button>
                      </div>
                          
                    

                          <div class="pull-left"> 
                            <h5><i class="fa fa-tasks"></i> {{Pantalla.nombre}}</h5>
                           </div>
                          <br>
                          
                    </div>
                          <div class="panel-body">
                              <div class="task-content">
                                  <ul id="sortable" class="task-list">
                                      <li ng-repeat="item in listaDispositivo|filter:buscarLista:strict" class="list-primary">
                                          <i class=" fa fa-ellipsis-v"></i>
                                          <div class="task-checkbox">
                                              <input type="checkbox" class="list-child" value=""  />
                                          </div>
                                          <div class="task-title">
                                              <span class="task-title-sp">{{item.Descripcion}}</span>
                                              <div class="pull-right hidden-phone">                                                  
                                            <button class="btn btn-primary btn-xs fa fa-pencil" ng-click="Llenar(item, $index ); vCrud.Editar(1);"></button>
                                                  <button class="btn btn-danger btn-xs fa fa-trash-o" ng-click="Eliminar(item, $index)"></button>
                                              </div>
                                          </div>
                                      </li>

                                  </ul>
                              </div>
                              <div class=" add-task-row">   
                               <div id="page-selection-APP"></div> 

                                  <a class="btn btn-default btn-sm pull-right" ng-click="ListAll()">Ver Todo</a>
                              </div>
                          </div>
                      </section>
                  </div><!--/col-md-12 -->'; 
                  echo htmlentities($htmlEncabezado);
	 }

	public function formarJSController(){

	}
	
	public function formarModel($sufijo, $tabla, $listaCampos, $login = false, $campos = null){	
	$entidadTemp = strtolower($tabla);
	$entidadIns = $this->limpiarCampo(trim($entidadTemp));
	$claseNombre = $this->limpiarCampo($sufijo). $entidadIns;
	$primary_key = ""; 
	foreach ($listaCampos as $key => $value) {
		if($value['primary_key']){
					$primary_key = $value["name"]; 
				}		
	}

	$encabezado = "<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
\n class {$claseNombre}_Model extends CI_Model {
 \n";
	$constructor = " \n  function __construct()
	{
		parent::__construct();
	}\n \n ";
 
 	$pie = "\n }\n ?>";
	
	$metodosBasicos = '
	public function obtener'.$entidadIns.'(){
		$this->load->database();
		$query = $this->db->get('. $tabla .');			
			
		$lista'.$claseNombre.' = $query->result(); 
		return $lista'.$claseNombre.';
 	}

 	public function obtener'.$entidadIns.'PorCampo($campo, $valor = "", $limit = 0, $page = 20){
		$this->load->database();
		$list = $this->db->field_data("'.$tabla.'");
		$fieldList = array();
		foreach ($list as  $value) {
			$fieldList[] = $value->name;		
		}

		$this->db->select($fieldList,FALSE);
		$this->db->where(" Estado !=" , "-1" );
		$this->db->like($campo, trim($valor));
		$data = $this->db->get_compiled_select("'.$tabla.'", $limit, $page);

		$arrFill = array("vQuery"=> $data, "vLimit" => $limit, "vPage"=> $page);
		$stored_procedure = "call sp_PaginarResultQuery( ?, ?, ?);";
		$query = $this->db->query($stored_procedure, $arrFill);
		$lista'.$entidadIns.' = $query->result();
 		return $listaDispositivo;
 	}

 	public function obtener'.$entidadIns.'Paginado($limit, $row, $condicion = " Estado != -1"){
		$this->load->database();
		$arrFill = array("vLimit" => $limit, "vPage"=> $row, "vCondicion"=> $condicion);
		$stored_procedure = "call sp_PaginarResultTabla("'.$tabla.'", ?, ?, ?);";		
		$query = $this->db->query($stored_procedure, $arrFill);
		$listaDispositivo = $query->result(); 
		return $listaDispositivo;
 	}
	
	public function obtener'.$entidadIns.'Json(){
		$this->load->database();
		$query = $this->db->get('.$tabla.');	
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$lista'.$entidadIns.'[] = $row; 
		}
			return json_encode($lista'.$entidadIns.');
  }	
';


$metodosBasicos .= '
	public function existeValorCampo($tabla_campo, $val){
		$this->load->database();
		$dbStr = explode(".", $tabla_campo); 
		$this->db->where($dbStr[1], trim($val) ); 
		$result = $this->db->get($dbStr[0]);
		if ($result->num_rows() > 0)
		{		
			return false;
		}		
		return true; 
	}


	public function insertar($obj){
		$this->load->database();
		$listaCampos = $this->db->field_data("'.$tabla.'");
		$this->db->insert("'.$tabla.'", $obj);
		return $this->db->insert_id();
	}

	public function actualizar($obj){
		$this->load->database();

		$'.$entidadIns.'Ent = $this->ObtenerPorID($obj["'. $primary_key .'"]);
        	if($'.$entidadIns.'Ent == null){ 
		        return false; 
        	}
        	$update = array();
        	foreach ($'.$entidadIns.'Ent as $key => $value) {
        		if($key != "'.$primary_key.'"){
	        		if(array_key_exists($key, $obj) && $value != $obj[$key]){
	        			$update[$key] = $obj[$key];        			
	        		}           			
        		}    		
        	}
        	$update["FechaModifica"] = date("Y-m-d H:i:s");
        	$this->db->where("'. $primary_key .'", $obj["'. $primary_key .'"]);
			$rs = $this->db->update("' .$tabla. '", $update);
			if($rs){
				return $'.$entidadIns.'Ent; 
			}
			return $rs; 		
	}

	public function cambiarEstado($obj, $estado){
		$this->load->database();
		$'.$tabla.'Ent = $this->ObtenerPorID($obj["' .$tabla.'"]);

		if($dispositivoEnt == null){ 
		        return false; 
        }        
        $update["FechaModifica"] = date("Y-m-d H:i:s");
        $update["Estado"] = $estado; 
        $this->db->where("'. $primary_key .'", $obj["'. $primary_key .'"]);
		$rs = $this->db->update("' .$tabla.'", $update);	

		if($rs){
			return $'.$tabla.'Ent; 
		}
			return $rs; 
	}

	public function ObtenerPorID($id){
		$this->db->where("'.$primary_key .'", $id); 
		$result = $this->db->get("'.$tabla.'");		

		if ($result->num_rows() == 0)
		{
			return null; 
		}
		return current($result->result()); 
	}
	'; 
				
	$string = $encabezado.$constructor. $metodosBasicos. $pie;
				
				return htmlentities( $string );
	}


public function getInstianciaEntidad($tabla, $listaCampos){
	$entidadTemp = strtolower($tabla);
	$entidadIns = $this->limpiarCampo(trim($entidadTemp));
	$constructorParametros = ""; 
	$interator= 0;
	 foreach ($listaCampos as $campos){
					 	
					$atributo = $this->limpiarCampo($campos['name']);
					
					if($interator > count($listaCampos)- 2 ){
					$constructorParametros .= "$"."row['".$campos['name']."']";
					}else{
					$constructorParametros .= "$"."row['".$campos['name']."'], ";				
					}
					
					$interator ++;
				 }
				 
				 $constructorParametros .= "); \n";
				 
				 return "new $entidadIns( ".$constructorParametros;
	}


public function generarRules($sufijo, $tabla, $listaCampos, $entities = true){

	$listado = $this->obtenerDetalleTabla($tabla, 'bis_gestionvista'); 

	$text = ""; 

	foreach ($listado as $key => $value) {
		if($value->Key != "PRI"){

			$attr = "";		

		if ($value->IsNullable == "NO") {
			$attr .= "required"; 
		}

		if($value->Key == "UNI"){
			if($attr != ""){
				$attr .="|"; 
			}
			$attr .= "trim|callback_validar{$value->Nombre}"; 
		}
		if($value->max_length != null && $value->Type == "varchar"){
			if($attr != ""){
				$attr .="|"; 
			}
			$attr .= "max_length[{$value->max_length}]"; 
		}
		if($value->Type == "int"){
			if($attr != ""){
				$attr .="|"; 
			}
			$attr .= "integer"; 
		}		
$text .= '$this->validation->set_rules("'. $sufijo. '['. $value->Nombre. ']", "' . $value->Nombre . '", "'.    $attr . '"); '. "\n";
		}		
	}
	if($entities){		
	 return htmlentities( $text); 
	}
	return  $text; 
}

public function generarAsignate($sufijo, $tabla, $listaCampos, $pk = false, $entities = true){
	$text = '$'.$tabla . 'Ent = array('; 
	$attr = ""; 

	foreach ($listaCampos as $key => $value) {
				


		if($pk){
	// Ajustes que no Añoran conocer a Jesus.

			if( $value["type"] == 'datetime'){
					if(!isset($value["default"]) && $value["default"] == ""){

						if($attr != ""){
							$attr .=", "; 
						} 

						$attr .= " '{$value['name']}'=> date('Y-m-d H:i:s') \n"; 						

					} else {

						if(in_array($value["name"], array('FechaModifica', "UltimaSesion", "FechaModificacion", "fechaModificacion", 'fechaModifica', "ultimaSesion" )) ){

						if($attr != ""){
							$attr .=", "; 
						} 
							$attr .= "'{$value['name']}'=> date('Y-m-d H:i:s') \n"; 						

						}						
					}
			} else {

						if($attr != ""){
							$attr .=", "; 
						} 

							$attr .= "'{$value['name']}'=> $".$tabla."Obj['{$value['name']}'] \n"; 
			}

		} else {	

			if($value['primary_key'] == 0 ){


				if( $value["type"] == 'datetime'){
					if(!isset($value["default"]) && $value["default"] == ""){


						if($attr != ""){
							$attr .=", "; 
						} 

						$attr .= "'{$value['name']}'=> date('Y-m-d H:i:s') \n"; 
					}
				} else {


						if($attr != ""){
							$attr .=", "; 
						} 

					$attr .= "'{$value['name']}'=> $".$tabla."Obj['{$value['name']}'] \n"; 

				}				
			}
		}	
		
	}
	$text .= $attr;
	$text .= ");"; 

	if($entities){		
	 return htmlentities( $text); 
	}

	 return $text; 

}


	// Ajustes de todos los mas grandes.

	public function generarEntidad($sufijo, $tabla, $listaCampos){

		$entidadTemp = strtolower($tabla);
		$entidadIns = $this->limpiarCampo(trim($entidadTemp));
		$claseNombre = $this->limpiarCampo($sufijo). $entidadIns;
		$primary_key = ""; 

		$encabezado = "<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); \n
class E_{$entidadIns} { \n";
		
		$variables = ""; 
		$config = '  var $_config = array( '; 

		$metodos = ""; 

	foreach ($listaCampos as $key => $value) {


		if($value['primary_key']){
			$primary_key = $value["name"]; 
			$variables .= '  var $_primary = "'. $value["name"] .'";'. "\n";
		}	

		$variables .= '  var $'.$value["name"]. "; \n";
		$config .= '"'.$value["name"]. '"=> "'. print_r($value["type"], true).'"'. " \n ";

		$metodos .= ' public function get'.$value["name"]."(){
   return ". '$this->'.$value["name"]."; \n
 } \n \n " .
 ' public function set'.$value["name"].'($'. lcfirst($value["name"]) . "){
   ". ' $this->'.$value["name"]." = ". lcfirst($value["name"]) . "; \n
 } \n \n "; 


		 if( (count($listaCampos)-1) != $key){
		 	$config .= "\t ,";
		 } else {
		 	$config .= "); \n \n";
		 }
	};

		


		$contructor = ' public function __construct($obj = null) {
  if($obj !== null){
 // Recorrer Arreglo.
 	 foreach ($obj as $keys => $val) {
 	  if(array_key_exists($keys, $this->_config)){
 		$this->$keys = $val; 
 	  }
 	 }
  }
 }'. "\n  \n ";

		$general = $encabezado . $variables. $config . $contructor . $metodos. "} ?>";

return htmlentities($general);
		// Recorrido

	}

	
	public function generarModelo($listaTabla){
		// Genrar el Modelo	

	}
	
	public function obtenerUsuario(){	
	}
	
	public function setPermisos(){
	$this->load->database();
	}

}
?>