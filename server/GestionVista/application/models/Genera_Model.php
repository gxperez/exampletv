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
		$query = $this->db->query("SELECT * FROM '.$entidadTemp.'");
			
		$usuario = array();
		foreach ($query->result() as $row)
		{
			$lista'.$entidadIns.'[] = '.$this->getInstianciaEntidad($tabla, $listaCampos).' 
		}
			return $lista'.$entidadIns.';
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

		$this->db->insert("' . $tabla. '", $obj); 			

	}

	public function actualizar($obj){

		$this->db->where("'. $primary_key .'", $obj["'. $primary_key .'"]); 
		$result = $this->db->get("'.$entidadTemp.'");
		if ($result->num_rows() == 1)
		{
			$'.$entidadIns.' =  current($result->result()); 
			$this->db->where("'.$primary_key .'", $'.$entidadIns.'->'.$primary_key .');
			$rs = $this->db->update("'.$tabla.'", $'.$entidadIns.'); 
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