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
				$resultado[] = array("name"=>$field->name, "type"=>$field->type, "max_length"=>$field->max_length, "primary_key"=>$field->primary_key, "foraneas"=> null );
			
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
	public function insertar($obj){
		$this->load->database();
		$this->db->insert("' . $tabla. '", $obj);
	}

	public function actualizar($obj){

		$this->load->database();
		$this->db->where("'. $primary_key .'", $obj["'. $primary_key .'"]); 
		$result = $this->db->get("'.$tabla.'");
		if ($result->num_rows() == 1)
		{
			$'.$entidadIns.' =  current($result->result()); 
			foreach ($'.$entidadIns.' as $key => $value) {
				if($key == "'. $primary_key .'"){ continue; }							

				if( array_key_exists($key, $obj)){					
					$'.$entidadIns.'->$key = $obj[$key];
				}
			}
			
			$this->db->where("BloqueContenidoID", $BloqueContenido->BloqueContenidoID);
			$rs = $this->db->update("bloque_contenido", $BloqueContenido); 			
			return $rs; 
		} else {
			return false;
		}
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


public function generarRules($sufijo, $tabla, $listaCampos){

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
			$attr .= "is_unique[{$tabla}.{$value->Nombre}]"; 
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

$text .= '$this->form_validation->set_rules("'. $sufijo. '['. $value->Nombre. ']", "' . $value->Nombre . '", "'.    $attr . '"); '. "\n";

		}

		
	}

	 return htmlentities( $text); 

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
}

}
?>