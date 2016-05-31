<?php 

class Usuario_Model extends CI_Model {

function __construct()
	{
		// Llamando al contructor del Modelo
		parent::__construct();		
		
		
	}
	
	public function validarUsuario($usuario, $clave, $keyS, $dispositivo){

		$clave = md5($clave); 

		$this->load->database();
		$query = $this->db->query("select * from usuario_log_sesion where nombreUsuario like '{$usuario}'");


		if ($query->num_rows() == 1)
		{
			// Existe el Usuario.

			foreach ($query->result() as $row)
			{
			$usuario = $row; 
			}
			// Actualizamos el Token y los secciones
			$usuario->ultimaSesion = date("Y-m-d H:i:s");
			$usuario->estatus = 1;
			$usuario->GUID = $keyS;			
			$this->db->where('usuario_log_sesionID', $usuario->usuario_log_sesionID);
			$this->db->update('usuario_log_sesion', $usuario); 
			 return array("resultado"=> true, "registro"=>$usuario);
		} else {
			// Crear Usuario Nuevo.
			$registro = array("nombreUsuario"=> $usuario, "clave"=> $clave, "ultimaSesion"=> date("Y-m-d H:i:s"), "estatus"=> 1, "GUID"=> $keyS); 

			$this->db->insert("usuario_log_sesion", $registro); 
			return array("resultado"=> true, "registro"=>$registro); 
		}

	 
	}

	
	
	public function obtenerUsuario(){
		$this->load->database();
		$query = $this->db->query("SELECT u.*, r.Descripcion, r.`IDrol` FROM usuario_log_sesion AS u
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