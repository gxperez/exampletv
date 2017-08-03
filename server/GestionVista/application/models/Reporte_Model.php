<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

 class Reporte_Model extends CI_Model {
 
 
  function __construct()
	{
		parent::__construct();
	}


	public function get_DataSetResumenDispositivosOnline(){
		$this->load->database();
		$select = "Select Descripcion, valReal, valTotal, (valReal/valTotal) as Alcance   from (
select 
'Online' as Descripcion, 
(select COUNT(*) from session_dispositivo_log where Estado = 1) as valReal, 
(select Count(*) from dispositivo where Estado = 1) as valTotal
) as td"; 

		$query = $this->db->query($select);
		$listaResultado = $query->result();
		$keyRes = array();		
 		return $listaResultado;
	}
	
 }
 ?>