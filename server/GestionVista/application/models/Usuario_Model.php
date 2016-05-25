<?php 

class Usuario_Model extends CI_Model {

function __construct()
	{
		// Llamando al contructor del Modelo
		parent::__construct();		
		
		
	}
	
	public function validarUsaurio($usuario, $clave){

		$clave = md5($clave); 

		$this->load->database();
		$query = $this->db->query("select * from usuarios where nombreUsuario like '{$usuario}' and Clave = '{$clave}'");

		if ($query->num_rows() == 1)
		{
		
			foreach ($query->result() as $row)
			{
			$usuario = $row; 
			}
			
			return array("resultado"=> true, "registro"=>$usuario);
		} else {
			return array("resultado"=> false, "registro"=>array()); 
		}

	 
	}

	
	
	public function obtenerUsuario(){
		$this->load->database();
		$query = $this->db->query("SELECT u.*, r.Descripcion, r.`IDrol` FROM Usuarios AS u
 LEFT JOIN 
roles_usuario AS ru ON u.IDusuario = ru.IDusuario
LEFT JOIN role AS r ON r.IDrol = ru.IDrol");		
		$usuario = array();
			foreach ($query->result() as $row)
			{
			$usuario[] = $row; 
			}		
			return $usuario; 
	}
	
	public function setPermisos(){
	$this->load->database();
	
	}


}
?>